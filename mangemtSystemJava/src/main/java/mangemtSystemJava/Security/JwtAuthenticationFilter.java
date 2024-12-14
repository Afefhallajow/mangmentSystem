package mangemtSystemJava.Security;


import io.jsonwebtoken.*;
import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.lang.NonNull;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.web.authentication.WebAuthenticationDetailsSource;
import org.springframework.stereotype.Component;
import org.springframework.web.filter.OncePerRequestFilter;

import java.io.IOException;
import java.security.PublicKey;

@Component
public class JwtAuthenticationFilter extends OncePerRequestFilter {

    @Autowired
    private JWTService jwtService;

    @Autowired
    private UserDetailsService userDetailsService;

    protected void doFilterInternal(
            @NonNull HttpServletRequest request,
            @NonNull HttpServletResponse response,
            @NonNull FilterChain filterChain) throws ServletException, IOException {
        final String authHeader = request.getHeader("Authorization");
        if (authHeader == null || !authHeader.startsWith("Bearer ")) {
            filterChain.doFilter(request, response);
            return;
        }

        final String jwt = authHeader.substring(7);
        final String id;
        try {
             id = jwtService.extractUser(jwt);
            System.out.println("id is " + id);
        } catch (ExpiredJwtException e) {
            e.printStackTrace();
            response.setContentType(MediaType.APPLICATION_JSON_VALUE);
            response.setStatus(HttpStatus.UNAUTHORIZED.value());
            response.getWriter().write("{\"error\": \"JWT Token expired\"}");
            return;
        } catch (UnsupportedJwtException e) {
            e.printStackTrace();
            response.setContentType(MediaType.APPLICATION_JSON_VALUE);
            response.setStatus(HttpStatus.UNAUTHORIZED.value());
            response.getWriter().write("Unsupported JWT Token");
            return;
        } catch (MalformedJwtException e) {
            response.setContentType(MediaType.APPLICATION_JSON_VALUE);
            response.setStatus(HttpStatus.UNAUTHORIZED.value());
            response.getWriter().write( "Malformed JWT Token");
            return;
        } catch (JwtException e) {
            response.setContentType(MediaType.APPLICATION_JSON_VALUE);
            response.setStatus(HttpStatus.UNAUTHORIZED.value());
            response.getWriter().write("Invalid JWT Token");
            return;
        }

        Authentication authentication = SecurityContextHolder.getContext().getAuthentication();
        System.out.println("id is " + id);

        if (id != null && authentication == null) {
            UserDetails userDetails = this.userDetailsService.loadUserByUsername(id);
            System.out.println("userDetails is " + userDetails.getUsername());
            System.out.println("userDetails is " + jwtService.isTokenValid(jwt, userDetails));


            if (jwtService.isTokenValid(jwt, userDetails)) {
                UsernamePasswordAuthenticationToken authToken = new UsernamePasswordAuthenticationToken(
                        userDetails,
                        null,
                        userDetails.getAuthorities());
                System.out.println("authToken " + authToken);
                System.out.println("UserName " + authToken.getPrincipal().toString());

                authToken.setDetails(new WebAuthenticationDetailsSource().buildDetails(request));
                System.out.println("authToken " + authToken);

                SecurityContextHolder.getContext().setAuthentication(authToken);
                System.out.println("auth " +  SecurityContextHolder.getContext().getAuthentication());
            }
        }
        filterChain.doFilter(request, response);
    }
}