# Projet R4.01 - Gestion Équipe de Handball

## Description Globale
Ce projet est une application complète de gestion sportive pour une équipe de handball (cf README R3.01.md). Il est structuré en plusieurs services interconnectés pour assurer une séparation claire entre l'authentification, les données métiers et l'interface utilisateur.

## Description fonctionnelle 
### Liens d'Accès de l'Application (Production)
> - **Application (Frontend)** : `https://projetphp.alwaysdata.net/src/Controleurs/ControleurAccueil.php`
> - **Identifiants de test** : `1573357` / `azertyuiop`


> **Authentification (Dossier : `auth_api`)**
> - URL : `https://alfred.alwaysdata.net/authapi.php`
> - Hôte BD : `mysql-alfred.alwaysdata.net`
> - identifiant BD : `alfred_api_auth`
> - Utilisateur en BD : `alfred`
> - mdp BD : `azertyuiop.@`


> **Gestion Sportive (Dossier : `projetphpgestionjoueurs-matchs`)**
> - API Joueurs : `https://alphonse.alwaysdata.net/apiGestionJoueur.php`
> - API Matchs : `https://alphonse.alwaysdata.net/apiGestionMatch.php`
> - API Stats : `https://alphonse.alwaysdata.net/apiGestionStats.php`
> - Hôte BD : `mysql-alphonse.alwaysdata.net`
> - Base de données : `alphonse_bd_api_gestion`
> - Utilisateur en BD : `alphonse`
> - mdp BD : `azertyuiop.@`

---
### Comment fonctionnent les apppels aux api 
Dans ce projet, tout les modèles DAO ont été déplacés vers les api respectives, afin que l'api authentification gère la connexion de l'entraîneur, et l'api de gestion soit là pour pouvoir gérer toutes les données liées aux joueurs, matchs et statistiques 

Chaque api est indépendante et possède sa propre base de données. 
Plus de détails sont disponibles dans le README de chaque API.
