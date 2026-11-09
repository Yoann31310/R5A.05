/*
C'est le chef d'orchestre de toute la sécurité Spring Boot :
- Définit l'algorithme de hachage
- Définit quelles URLs sont publiques/protégées
- Branche R505OncePerRequestFilter comme chose à faire en premier
*/

package tp1.security;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.dao.DaoAuthenticationProvider;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.method.configuration.EnableMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.http.SessionCreationPolicy;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.authentication.UsernamePasswordAuthenticationFilter;

@Configuration
@EnableMethodSecurity // Permet d'utiliser plus tard des annotations comme @PreAuthorize sur les méthodes
public class R505WebSecurityConfigurer {

    // 1. Déclare notre filtre JWT en tant que Bean Spring
    @Bean
    public R505OncePerRequestFilter authenticationJwtTokenFilter() {
        return new R505OncePerRequestFilter();
    }

    // 2. Configure la chaîne de filtres de sécurité HTTP
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http, DaoAuthenticationProvider provider, JwtAuthEntryPoint unauthorizedHandler) throws Exception {
        http.cors(cors -> cors.disable())
            .csrf(csrf -> csrf.disable()) // Désactivé car notre API est stateless (pas de cookies de session)
            .exceptionHandling(exception -> exception.authenticationEntryPoint(unauthorizedHandler))
            .sessionManagement(session -> session.sessionCreationPolicy(SessionCreationPolicy.STATELESS))
            .authorizeHttpRequests(auth -> auth
                    // Endpoints publics accessibles SANS être connecté :
                    .requestMatchers("/auth/**", "/bonjour").permitAll()
                    // Tout le reste nécessite d'être authentifié avec un token JWT valide :
                    .anyRequest().authenticated());

        http.authenticationProvider(provider);
        http.addFilterBefore(authenticationJwtTokenFilter(), UsernamePasswordAuthenticationFilter.class);
        return http.build();
    }

    // 3. Fournisseur d'authentification reliant notre UserDetailsService et l'encodeur BCrypt
    @Bean
    public DaoAuthenticationProvider authenticationProvider(R505UserDetailsService userDetailsService) {
        DaoAuthenticationProvider authProvider = new DaoAuthenticationProvider();
        authProvider.setUserDetailsService(userDetailsService);
        authProvider.setPasswordEncoder(passwordEncoder());
        return authProvider;
    }

    // 4. L'algorithme de hachage de mot de passe BCrypt
    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }

    // 5. Le gestionnaire d'authentification central de Spring
    @Bean
    public AuthenticationManager authenticationManager(AuthenticationConfiguration authConfig) throws Exception {
        return authConfig.getAuthenticationManager();
    }
}

