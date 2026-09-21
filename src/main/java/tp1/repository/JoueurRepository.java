package tp1.repository;

import org.springframework.data.repository.CrudRepository;
import tp1.entity.Joueur;

public interface JoueurRepository extends CrudRepository<Joueur, Integer> {
    
}
