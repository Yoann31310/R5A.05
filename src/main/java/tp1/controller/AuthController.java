package tp1.controller;

import java.util.List;
import java.util.stream.Collectors;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import tp1.entity.Utilisateur;
import tp1.repository.UtilisateurRepository;
import tp1.security.JwtDTO;
import tp1.security.TokenGenerator;

@RestController
@RequestMapping("/auth")
public class AuthController {

    @Autowired
    private AuthenticationManager authenticationManager;

    @Autowired
    private UtilisateurRepository utilisateurRepository;

    @Autowired
    private PasswordEncoder passwordEncoder;

    @Autowired
    private TokenGenerator tokenGenerator;

    // Inscription : crée un utilisateur avec mdp haché
    @PostMapping("/inscription")
    public ResponseEntity<?> inscrire(@RequestBody Utilisateur utilisateur) {
        if (utilisateurRepository.existsByUsername(utilisateur.getUsername())) {
            return ResponseEntity.badRequest().body("Erreur : Ce nom d'utilisateur est déjà pris !");
        }

        // Hachage du mot de passe et enregistrement en BD
        utilisateur.setPassword(passwordEncoder.encode(utilisateur.getPassword()));

        if (utilisateur.getRole() == null || utilisateur.getRole().isEmpty()) {
            utilisateur.setRole("ROLE_USER");
        }

        utilisateurRepository.save(utilisateur);
        return ResponseEntity.ok("Utilisateur enregistré avec succès !");
    }

    // Connexion : vérifie login/mdp -> renvoi le token JWT
    @PostMapping("/connexion")
    public ResponseEntity<?> connecter(@RequestBody LoginRequest loginRequest) {
        // Vérifie les identifiants
        Authentication authentication = authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(loginRequest.getUsername(), loginRequest.getPassword()));

        // Enregistre l'authentification
        SecurityContextHolder.getContext().setAuthentication(authentication);

        // Génère le token JWT
        String jwt = tokenGenerator.generateJwtToken(authentication);

        // Récupère l'utilisateur et ses rôles pour fabriquer le JwtDTO
        UserDetails userDetails = (UserDetails) authentication.getPrincipal();
        List<String> roles = userDetails.getAuthorities().stream()
                .map(GrantedAuthority::getAuthority)
                .collect(Collectors.toList());

        return ResponseEntity.ok(new JwtDTO(jwt, userDetails.getUsername(), roles));
    }

    // Petite classe interne pour réceptionner le JSON { "username": "...", "password": "..." }
    public static class LoginRequest {
        private String username;
        private String password;

        public String getUsername() {
            return username;
        }

        public void setUsername(String username) {
            this.username = username;
        }

        public String getPassword() {
            return password;
        }

        public void setPassword(String password) {
            this.password = password;
        }
    }
}