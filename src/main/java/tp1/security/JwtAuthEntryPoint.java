/*
C'est le "videur de boîte de nuit" quand quelqu'un n'a pas son pass :

Si un utilisateur non connecté tente d'accéder à une route privée (ou si son token est faux ou expiré), Spring Security passe par cette classe.
Elle renvoie au client un code d'erreur HTTP 401 Unauthorized au lieu de laisser passer la requête ou de faire planter l'application.

*/

package tp1.security;

import java.io.IOException;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.web.AuthenticationEntryPoint;
import org.springframework.stereotype.Component;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

@Component
public class JwtAuthEntryPoint implements AuthenticationEntryPoint {

    private static final Logger logger = LoggerFactory.getLogger(JwtAuthEntryPoint.class);

    @Override
    public void commence(HttpServletRequest request, HttpServletResponse response,
            AuthenticationException authException) throws IOException, ServletException {
        // Enregistre l'erreur dans la console pour le débogage
        logger.error("Erreur non autorisée : {}", authException.getMessage());
        
        // Renvoie l'erreur HTTP 401 au client
        response.sendError(HttpServletResponse.SC_UNAUTHORIZED, "Erreur : Non autorisé");
    }
}