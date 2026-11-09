/*
Classe qui joue le rôle de  traducteur :
- Spring : "Quelqu'un essaie de se connecter avec le pseudo, donne-moi ses infos."
- UtilisateurRepository cherche yoann dans MySQL.
- Si trouvé, elle transforme l'objet Utilisateur en un objet que Spring Security sait manipuler pour vérifier mdp et perms
*/

package tp1.security;

import java.util.Collections;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import tp1.entity.Utilisateur;
import tp1.repository.UtilisateurRepository;

@Service
public class R505UserDetailsService implements UserDetailsService {

    @Autowired
    private UtilisateurRepository utilisateurRepository;

    @Override
    public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
        // 1. Cherche l'utilisateur dans MySQL via notre repository
        Utilisateur utilisateur = utilisateurRepository.findByUsername(username)
                .orElseThrow(() -> new UsernameNotFoundException("Utilisateur non trouvé avec le pseudo : " + username));

        // 2. Récupère le rôle et le transforme en permission Spring (ex: "ROLE_COACH" ou "ROLE_JOUEUR")
        String role = utilisateur.getRole() != null ? utilisateur.getRole() : "ROLE_USER";
        List<GrantedAuthority> authorities = Collections.singletonList(new SimpleGrantedAuthority(role));

        // 3. Renvoie un objet User officiel de Spring Security contenant : pseudo, mot de passe haché, rôles
        return new User(utilisateur.getUsername(), utilisateur.getPassword(), authorities);
    }
}