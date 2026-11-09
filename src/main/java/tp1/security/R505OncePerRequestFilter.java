/*
Once Per Request = s'exécute une fois à chaque requête HTTP. 
Analogie d'un portique de sécurité à l'entrée d'une salle
 */

package tp1.security;

import java.io.IOException;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.web.authentication.WebAuthenticationDetailsSource;
import org.springframework.util.StringUtils;
import org.springframework.web.filter.OncePerRequestFilter;

import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

public class R505OncePerRequestFilter extends OncePerRequestFilter {

    @Autowired
    private TokenValidator tokenValidator;

    @Autowired
    private R505UserDetailsService userDetailsService;

    @Override
    protected void doFilterInternal(HttpServletRequest request, HttpServletResponse response, FilterChain filterChain)
            throws ServletException, IOException {
        try {
            // 1. Extrait le token JWT du header "Authorization: Bearer <token>"
            String jwt = parseJwt(request);

            // 2. Si le token existe et est valide
            if (jwt != null && tokenValidator.validateJwtToken(jwt)) {
                String username = tokenValidator.getUserNameFromJwtToken(jwt);

                // 3. Charge l'utilisateur et ses rôles
                UserDetails userDetails = userDetailsService.loadUserByUsername(username);
                UsernamePasswordAuthenticationToken authentication = new UsernamePasswordAuthenticationToken(
                        userDetails, null, userDetails.getAuthorities());
                authentication.setDetails(new WebAuthenticationDetailsSource().buildDetails(request));

                // 4. Enregistre l'utilisateur comme authentifié dans le contexte de sécurité de Spring
                SecurityContextHolder.getContext().setAuthentication(authentication);
            }
        } catch (Exception e) {
            logger.error("Impossible de définir l'authentification de l'utilisateur : {}", e);
        }

        // 5. Laisse la requête continuer son chemin vers les contrôleurs
        filterChain.doFilter(request, response);
    }

    // Méthode utilitaire qui enlève le mot "Bearer " pour ne garder que le jeton pur
    private String parseJwt(HttpServletRequest request) {
        String headerAuth = request.getHeader("Authorization");

        if (StringUtils.hasText(headerAuth) && headerAuth.startsWith("Bearer ")) {
            return headerAuth.substring(7);
        }

        return null;
    }
}