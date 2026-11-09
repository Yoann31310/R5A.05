package tp1.repository;

import java.util.Optional;

import org.springframework.data.repository.CrudRepository;

import tp1.entity.Utilisateur;

public interface UtilisateurRepository extends CrudRepository<Utilisateur, Integer> {

    Optional<Utilisateur> findByUsername(String username);
    Boolean existsByUsername(String username);
}