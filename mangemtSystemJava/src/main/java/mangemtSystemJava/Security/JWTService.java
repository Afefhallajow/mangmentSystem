package mangemtSystemJava.Security;

import io.jsonwebtoken.Claims;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.SignatureAlgorithm;
import io.jsonwebtoken.io.Decoders;
import io.jsonwebtoken.security.Keys;
import mangemtSystemJava.Entity.Users;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.stereotype.Service;

import java.security.Key;
import java.security.KeyFactory;
import java.security.PublicKey;
import java.security.interfaces.RSAPublicKey;
import java.security.spec.X509EncodedKeySpec;
import java.util.Base64;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import java.util.function.Function;

@Service
public class JWTService {
    private final String secretKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArF2rDkeJL9v2mCMTln4j" +
            "x+2u/Xc83OURth36KU5S9nLEsrgWx170GUF9A/EeAA06+QPlRsvwycgEkINjVnHx" +
            "Hs46CbaDFfXqqeDPiW33IZca+cHXvfF/zF3CfWZRn8O2ZCBIeCU3Q2Zuq6YYu9Dd" +
            "sCHSz8rdjS9qQWCkpMx+RqbnSQ8nhV5q1vQkmkLjNVnhpfaP52dtrYrAdyh+DkrM" +
            "W+QlmYs/xYtSPg+qoiGym3EL6O2saXvIcMm5cHwasOzzvKksVbHxnWEirMSxb4gN" +
            "HiBhSBR0YnnCuVNk9bpH0wZmT9WKF1Fcvn7kLcaKmmGrI2ZIAgkhY+7D7gljjI0U" +
            "59JCDeueaJvH9Sg/mDRgozjJN2gNxVzLfW+5A1hXYht3xRu7CkiUCXNCuPRdc0ny" +
            "FT0FuY3ZWPw/ohczUzJfWHQtyp7aBBjW3t79oHiS4CWcC0pudWo06wAaqmhNnRXT" +
            "n+uJ001MjtKCn1DUuXidnP5tcCSw8kL0blEBM5p1+bVNeSzhsLingTgQM0I6iXuV" +
            "J3uMRn1D8c9kc6lU9pCynXD15Lht6q/xRFl/dGW9ghC18JpKnPPf4cYCq8/ulzdG" +
            "Nu7LGlGAgG5k/Id6O08BQnnT/LxS5a7r33LB86DySbAxeKDQfW3czzw/l27cJbYP" +
            "d4x31fNVrWIuJMHa6pAv3q0CAwEAAQ==";

    private final long jwtExpiration = 3600000L;

    public String extractUser(String token) {
        return extractClaim(token, Claims::getSubject);
    }

    public <T> T extractClaim(String token, Function<Claims, T> claimsResolver) {
        final Claims claims = extractAllClaims(token);
        System.out.println("cliams " + claims);
        return claimsResolver.apply(claims);
    }

    public String generateToken(UserDetails userDetails) {
        return generateToken(new HashMap<>(), userDetails);
    }

    public String generateToken(Map<String, Object> extraClaims, UserDetails userDetails) {
        return buildToken(extraClaims, userDetails, jwtExpiration);
    }

    public RSAPublicKey getPublicKey() throws Exception {
        byte[] keyBytes = Base64.getDecoder().decode(secretKey);
        X509EncodedKeySpec spec = new X509EncodedKeySpec(keyBytes);
        KeyFactory keyFactory = KeyFactory.getInstance("RSA");
        return (RSAPublicKey)  keyFactory.generatePublic(spec);
    }
    public long getExpirationTime() {return jwtExpiration;}

    private String buildToken(
            Map<String, Object> extraClaims,
            UserDetails userDetails,
            long expiration) {
        return Jwts
                .builder()
                .setClaims(extraClaims)
                .setSubject(userDetails.getUsername())
                .setIssuedAt(new Date(System.currentTimeMillis()))
                .setExpiration(new Date(System.currentTimeMillis() + expiration))
                .signWith(getSignInKey(), SignatureAlgorithm.RS256)
                .compact();
    }

    public boolean isTokenValid(String token, UserDetails userDetails) {
        final String id = extractUser(token);
        System.out.println("is token expired" + isTokenExpired(token));
        return (id.equals(userDetails.getUsername())) && !isTokenExpired(token);
    }

    public boolean isTokenExpired(String token) {
        return extractExpiration(token).before(new Date());
    }

    private Date extractExpiration(String token) {
        return extractClaim(token, Claims::getExpiration);
    }

    private Claims extractAllClaims(String token) {
        RSAPublicKey publicKey;
        try {
            publicKey = getPublicKey();
        } catch (Exception e) {
            throw new RuntimeException(e);
        }
        return Jwts
                .parserBuilder()
                .setSigningKey(publicKey)
                .build()
                .parseClaimsJws(token)
                .getBody();
    }

    private Key getSignInKey() {
        byte[] keyBytes = Decoders.BASE64.decode(secretKey);
        return Keys.hmacShaKeyFor(keyBytes);
    }
}