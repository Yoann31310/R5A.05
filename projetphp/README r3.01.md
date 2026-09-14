# Application de Gestion d'Équipe de Handball

## Introduction

Cette application web permet de gérer une équipe de sport, ici de handball. Elle offre des fonctionnalités pour gérer les joueurs, organiser les matchs, composer les feuilles de match et consulter quelques  statistiques précises.

Le projet utilise une architecture MVC (Modèle-Vue-Contrôleur) avec de la programmation orientée objet (POO) pour structurer le code de manière claire et maintenable.

---

## Accès à l'application

**URL de l'application** : https://projetphp.alwaysdata.net/src/Controleurs/ControleurAccueil.php

**Identifiants de connexion** :
- **Identifiant** : `1573357`
- **Mot de passe** : `azertyuiop`

---

## Structure du projet

Le projet est organisé selon l'architecture MVC :

- **Controleurs/** : Contient les contrôleurs qui gèrent les actions utilisateur (ControleurJoueur, ControleurMatch, ControleurStatistiques, etc.)
- **Modeles/** : Contient les modèles organisés en deux sous-dossiers
  - **DAO/** : Classes d'accès aux données (JoueurDAO, MatchDAO, ParticipationDAO, etc.)
  - **Classes/** : Classes métiers avec propriétés et méthodes (Joueur, Match, Participation, Entraineur)
- **Vues/** : Contient les pages HTML/PHP affichées à l'utilisateur (PageListeJoueurs, PageListeMatchs, PageStatistiques, etc.)

Un menu de navigation permet d'accéder aux trois sections principales :
- Gestion des Joueurs
- Gestion des Matchs (Gérer mes matchs)
- Statistiques de l'équipe

---

## Fonctionnalités implémentées

### 1. Gestion des Joueurs

- **Créer un joueur** : Permet d'ajouter un nouveau joueur avec ces informations suivantes (Prénom, nom, poids, statuts, etc)
- **Ajouter un commentaire**
- **Afficher la liste des joueurs**
- **Modifier les attributs d'un joueur**
- **Supprimer un joueur** : Suppression possible uniquement si le joueur n'a jamais participé à un match (il sera pas réellement supprimé).

---

### 2. Gestion des Matchs

- **Créer un match** : Création d'un nouveau match avec choix dans date et lieu, nom de l'équipe adverse, et résultat (renseigné après le match)
- **Afficher la liste des matchs** : Vue tous les matchs programmés et joués.
- **Modifier les informations d'un match** : Modification possible seulement pour les matchs à venir. La date d'un match modifié ne peut pas être dans le passé.
- **Supprimer un match** : Suppression possible uniquement pour les matchs qui n'ont pas encore eu lieu.
- **Saisir le résultat d'un match joué** : Une fois le match passé, possibilité de saisir le résultat (Gagnée, Perdue, Égalité).

---

### 3. Participation des Joueurs et Évaluation
- **Sélectionner les joueurs pour un match à venir** :
- Seuls les joueurs avec le statut "Actif" peuvent être sélectionnés
- Séparation entre titulaires et remplaçants
- Attribution d'un poste pour chaque joueur (Gardien, Pivot, Demi-centre, Arrière gauche, Arrière droit, Ailier gauche, Ailier droit). On peut mettre tous le même rôle si on veut.

- **Modifier la participation d'un joueur** : Modification de la composition d'équipe avant le match.
- **Retirer un joueur d'une feuille de match** : Possibilité de retirer un joueur de la liste des participants.
- **Vérifier les quotas réglementaires** : Le système vérifie automatiquement qu'il y a entre 5 et 7 joueurs titulaires et au maximum 7 remplaçants
- **Empêcher la modification après le match** : Une fois le match joué, la feuille de match ne peut plus être modifiée.

- **Évaluer les joueurs après le match** : Pour chaque joueur ayant participé, possibilité de saisir une note sur 10 et un commentaire sur la performance

---

### 4. Statistiques

**Statistiques globales** comme le nombre total de matchs joués, le nombre de victoires avec pourcentage, etc...
**Tableau détaillé par joueur** avec les statut actuels du joueur, le pourcentage de matchs gagnés parmi ceux auxquels le joueur a participé, etc...

---

## Règles de gestion

### Règles pour les Joueurs

**Validation des données** :
- Le nom et le prénom doivent contenir au minimum 3 caractères
- Seules les lettres (avec accents autorisés) et les tirets sont acceptés
- La taille doit être d'au moins 80 cm
- Le poids doit être d'au moins 20 kg
- Le numéro de licence doit être unique

**Statuts possibles** :
- Actif : Le joueur peut participer aux matchs
- Blessé : Le joueur ne peut pas participer
- Suspendu : Le joueur ne peut pas participer
- Absent : Le joueur ne peut pas participer

**Suppression** : Un joueur ne peut être supprimé que s'il n'a jamais participé à aucun match. En cas de suppression, il est pas réellement supprimé de la BD, mais juste plus affiché

---

### Règles pour les Matchs

**Gestion des dates** :
- La date et l'heure d'un match ne peuvent pas être dans le passé
- Cette règle s'applique lors de la création et de la modification d'un match

**Affichage adaptatif selon la date** :
- Si le match n'a pas encore eu lieu : affichage du formulaire de composition d'équipe
- Si le match est passé : affichage du formulaire de saisie du résultat et d'évaluation des joueurs

**Résultats possibles** : Gagnée / Perdue / Égalité

**Modification et suppression** :
- La modification des informations d'un match n'est possible que si le match n'a pas encore eu lieu
- La suppression d'un match n'est possible que si le match n'a pas encore eu lieu

---

### Règles pour les Feuilles de Match
- Nombre de titulaires : entre 5 et 7 obligatoirement
- Nombre de remplaçants : maximum 7

- Seuls les joueurs ayant le statut "Actif" peuvent être sélectionnés
- Chaque joueur participant doit avoir un poste assigné

- Tout les postes sont disponibles et il n'y aucune restriction dessus, ce qui veut dire que tout le monde peut théoriquement être au même poste.

**Protection des données** :
- Une fois le match joué, la composition de l'équipe ne peut plus être modifiée. Cela garantit que l'historique soit toujours bien intégré.

---

## Côté fonctionnel 
- L'application respecte l'architecture MVC, avec Modèle DAO et classes métiers, Vue avec html/css/php et controleur qui charge la vue avec les données appropriées
- Le projet utilise la POO avec les classes métiers, qui contiennent des méthodes privées, ou static, que tout le monde peut utiliser
- La classe Database utilise le pattern Singleton, pour qu'il n'y ait qu'une seule instance de connexion à la BD
- L'application utilise les sessions PHP, et chaque page renvoit sur la page de connexion si la sesssion est non active
> Pour les matchs, l'application compare la date actuelle avec le match pour savoir s'il est passé ou non. L'interface change donc, pour la feuille de match, ou la sélection des joueurs.
> Cette logique garantit qu'on ne puisse pas modifier un match ou évaluer un joueur participant dans un match passé, ni composer une équipe après la date d'un match passée
- Les mdps sont hachés dans la BD, et on utilise password_verify en php pour l'authentification. 
- Toutes les requêtes SQL utilisent des requêtes préparées, ce qui garantit normalement des problèmes de sécurité 

