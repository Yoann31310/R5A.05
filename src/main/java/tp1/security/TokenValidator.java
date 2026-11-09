/*
TokenValidator est le détecteur de faux billets :
Quand un client envoie une requête avec un token, elle vérifie :
- Jeton signé avec clé secrète ?
- a expiré ? 
Si jwt correct, elle extrait le pseudi de l'utilisateur savoir qui parle.
*/

package tp1.security;

import javax.crypto.SecretKey;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;

import io.jsonwebtoken.ExpiredJwtException;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.MalformedJwtException;
import io.jsonwebtoken.UnsupportedJwtException;
import io.jsonwebtoken.io.Decoders;
import io.jsonwebtoken.security.Keys;
import io.jsonwebtoken.security.SignatureException;

@Component
public class TokenValidator {
    
    private static final Logger logger = LoggerFactory.getLogger(TokenValidator.class);
    private final SecretKey key;

    // Le constructeur reçoit la clé secrète et la prépare une fois pour toutes
    public TokenValidator(@Value("${r5a05.app.jwtSecret}") String jwtSecret) {
        this.key = Keys.hmacShaKeyFor(Decoders.BASE64.decode(jwtSecret));
    }

    // Extrait le nom d'utilisateur (subject) stocké à l'intérieur du token
    public String getUserNameFromJwtToken(String token) {
        return Jwts.parser().verifyWith(key).build().parseSignedClaims(token).getPayload().getSubject();
    }

    // Vérifie si le token est intègre et non expiré
    public boolean validateJwtToken(String authToken) {
        try {
            Jwts.parser().verifyWith(key).build().parseSignedClaims(authToken);
            return true;
        } catch (SignatureException e) {
            logger.error("Signature JWT invalide : {}", e.getMessage());
        } catch (MalformedJwtException e) {
            logger.error("Token JWT malformé : {}", e.getMessage());
        } catch (ExpiredJwtException e) {
            logger.error("Le token JWT est expiré : {}", e.getMessage());
        } catch (UnsupportedJwtException e) {
            logger.error("Token JWT non supporté : {}", e.getMessage());
        } catch (IllegalArgumentException e) {
            logger.error("La chaîne de claims JWT est vide : {}", e.getMessage());
        }

        return false;
    }
}