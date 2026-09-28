package tp1.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.bind.annotation.RestController;

import tp1.entity.Joueur;
import tp1.repository.JoueurRepository;

@RestController // This means that this class is a Controller
@RequestMapping(path="/demo") // This means URL's start with /demo (after Application path)
public class JoueurController {
  @Autowired 
  private JoueurRepository joueurRepository;
  
  @GetMapping(path="/all")
  public @ResponseBody Iterable<Joueur> getAllJoueurs() {
    // This returns a JSON or XML with the users
    return joueurRepository.findAll();
  }

  @GetMapping(path="/{id}")
  public Joueur getJoueurById(@PathVariable Integer id) {
    return joueurRepository.findById(id).orElse(null);
  }

  @PostMapping(path="/add") // Map ONLY POST Requests
  public Joueur addJoueur(@RequestBody Joueur joueur) {
    return joueurRepository.save(joueur);
  }

  @PutMapping(path="/{id}")
  public Joueur updateJoueur(@PathVariable Integer id, @RequestBody Joueur joueurDetails) {
    Joueur joueur = joueurRepository.findById(id).orElse(null);
    if (joueur != null) {
      joueur.setNom(joueurDetails.getNom());
      joueur.setPrenom(joueurDetails.getPrenom());
      joueur.setNumeroLicence(joueurDetails.getNumeroLicence());
      joueur.setStatut(joueurDetails.getStatut());
      joueur.setDateNaissance(joueurDetails.getDateNaissance());
      joueur.setTaille(joueurDetails.getTaille());
      joueur.setPoids(joueurDetails.getPoids());
      return joueurRepository.save(joueur);
    }
    return null;
  }

  @DeleteMapping(path="/{id}")
  public String deleteJoueur(@PathVariable Integer id) {
    joueurRepository.deleteById(id);
    return "Joueur supprimé";
  }

}
