/*
Dès qu'un utilisateur fournit le bon mdp, c'est cette classe qui 
donne son token JWT
 */

package tp1.security;

import java.util.Date;

import javax.crypto.SecretKey;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.stereotype.Component;

import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.io.Decoders;
import io.jsonwebtoken.security.Keys;

@Component
public class TokenGenerator {

    @Value("${r5a05.app.jwtSecret}")
    private String jwtSecret;

    // Récupère la durée de application.properties
    @Value("${r5a05.app.jwtExpirationMs}")
    private long jwtExpirationMs;

    public String generateJwtToken(Authentication authentication) {

        // Récupère les détails de l'utilisateur actuellement authentifié
        UserDetails userPrincipal = (UserDetails) authentication.getPrincipal();

        Date tokenCreationDate = new Date();
        Date tokenExpirationDate = new Date(tokenCreationDate.getTime() + jwtExpirationMs);

        // Décode notre clé secrète Base64 pour signer le jeton
        SecretKey key = Keys.hmacShaKeyFor(Decoders.BASE64.decode(jwtSecret));

        // Construit le token JWT signé
        return Jwts.builder()
                .subject(userPrincipal.getUsername())
                .issuedAt(tokenCreationDate)
                .expiration(tokenExpirationDate)
                .signWith(key)
                .compact();
    }
}