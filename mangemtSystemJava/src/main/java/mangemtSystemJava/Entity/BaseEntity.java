package mangemtSystemJava.Entity;


import jakarta.persistence.*;
import java.util.Date;

@MappedSuperclass
public class BaseEntity {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    Long id;

    @Column
    Date created_at;

    @Column
    Date updated_at;

    @Column
    int version;

    public Long getId() {return id;}

    public void setId(Long id) {this.id = id;}


    public int getVersion() {return version;}

    public void setVersion(int version) {this.version = version;}


    @PrePersist
    void beforeInsert(){
        this.version = 0;
        this.created_at = new Date();
        this.updated_at = new Date();
        //this.user = (User) SecurityContextHolder.getContext().getAuthentication().getPrincipal();
    }

    @PreUpdate
    void beforeUpdate(){
        this.version = this.version + 1;
        this.updated_at = new Date();
    }
}