package tp1.repository;

import org.springframework.data.repository.CrudRepository;
import tp1.entity.Participation;

// This will be AUTO IMPLEMENTED by Spring into a Bean called participationRepository
// CRUD refers Create, Read, Update, Delete

public interface ParticipationRepository extends CrudRepository<Participation, Integer> {

  Iterable<Participation> findByMatchId(Integer matchId);

  Iterable<Participation> findByJoueurId(Integer joueurId);

}
