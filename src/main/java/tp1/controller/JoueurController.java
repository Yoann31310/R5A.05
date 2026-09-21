package tp1.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
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

  @PostMapping(path="/add") // Map ONLY POST Requests
  public Joueur addJoueur(@RequestBody Joueur joueur) {
    return joueurRepository.save(joueur);
  }

}