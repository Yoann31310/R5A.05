/*
DTO = Data Transfer Object (Objet de transfert de données).
Quand quelqu'un réussit à se connecter, on ne veut surtout pas lui renvoyer l'objet Utilisateur complet (qui contient le mot de passe haché !).
Le DTO est un petit objet "paquet cadeau" contenant uniquement les informations utiles pour le client */

package tp1.security;

import java.util.List;

public class JwtDTO {

    private String token;
    private String type = "Bearer";
    private String username;
    private List<String> roles;

    public JwtDTO(String token, String username, List<String> roles) {
        this.token = token;
        this.username = username;
        this.roles = roles;
    }

    public String getToken() {
        return token;
    }

    public String getType() {
        return type;
    }

    public String getUsername() {
        return username;
    }

    public List<String> getRoles() {
        return roles;
    }
}