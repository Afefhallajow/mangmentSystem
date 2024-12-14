package mangemtSystemJava.Repo;

import mangemtSystemJava.Entity.Users;
import org.springframework.stereotype.Repository;

@Repository
public interface UserRepository extends BaseRepository<Users>{
    Users findByEmail(String email);
}
