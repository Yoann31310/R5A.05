package tp1.repository;

import org.springframework.data.repository.CrudRepository;
import tp1.entity.Matchs;

// This will be AUTO IMPLEMENTED by Spring into a Bean called matchsRepository
// CRUD refers Create, Read, Update, Delete

public interface MatchsRepository extends CrudRepository<Matchs, Integer> {

}
