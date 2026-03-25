# Projet TreeColor

## 1. Présentation

Application web conçue pour le suivi et la gestion de projets de reforestation de l'association TreeColor. Elle fournit une interface cartographique interactive pour visualiser les zones de projet, superposer des images satellites de différentes périodes et gérer les données des projets.

## 2. Fonctionnalités

*   **Gestion de Projets** : Ajouter, visualiser et supprimer des projets de reforestation.
*   **Carte Interactive** : Utilise Leaflet pour afficher les zones de projet sur des couches de base variées (OpenStreetMap, Satellite Google).
*   **Création de Zones (Dessin interactif)** : Les utilisateurs peuvent délimiter la zone de leurs projets en dessinant directement un polygone sur une carte intégrée (Leaflet Draw), remplaçant l'ancien système fastidieux d'import KML.
*   **Imagerie Satellite Temporelle** : S'intègre avec l'écosystème Copernicus pour superposer des couches de surveillance (Couleurs Naturelles, Indice de Végétation, Infrarouge Colorisé).
*   **Analyse Temporelle** : Un sélecteur de date permet de visualiser l'évolution de la zone couverte mois par mois via les API Copernicus.
*   **Fonctionnalités SIG** : Calcule et affiche dynamiquement la superficie de chaque zone de projet en mètres carrés.
*   **Conteneurisé** : L'ensemble de l'application (serveur Web + Base de données spatiale) est conteneurisé avec Docker et Docker Compose pour garantir la portabilité.

## 3. Stack Technique

*   **Backend** : PHP (Architecture MVC, schematisée en actions backend / vues frontend isolées)
*   **Frontend** : HTML, CSS, JavaScript (Vanilla + Bootstrap)
*   **Base de données** : PostgreSQL avec l'extension PostGIS pour la gestion de l'attribut `geom` (géométries des zones).
*   **Bibliothèques Cartographiques** : Leaflet.js et le plugin Leaflet Draw.
*   **Conteneurisation** : Docker, Docker Compose.

## 4. Tutoriel d'Installation

Ce tutoriel vous guidera pour lancer l'application sur votre machine locale.

### Prérequis

*   Docker
*   Docker Compose (généralement inclus avec Docker Desktop)

### Instructions

1.  **Clonez le dépôt**
    Si le projet est sur Git, clonez-le. Sinon, assurez-vous d'avoir tous les fichiers dans un seul dossier.
    ```bash
    git clone <url-de-votre-depot>
    cd <nom-du-dossier-projet>
    ```

2.  **Construisez et lancez les conteneurs**
    L'ensemble de la configuration Docker se trouve désormais dans le dossier `docker/`. Vous devrez vous y déplacer ou spécifier le chemin pour lancer l'application :
    ```bash
    cd docker
    docker-compose up -d --build
    ```
    *(Alternativement, depuis la racine : `docker compose -f docker/docker-compose.yml up -d --build`)*
    La base de données sera automatiquement initialisée avec le schéma et des données d'exemple via `docker/init.sql`.

3.  **Accédez à l'application**
    *   L'application est disponible à l'adresse **`http://localhost:8080`**.
    *   La base de données PostgreSQL est exposée sur le port **`5433`** de votre machine locale (utilisateur: `treecolor` / mot de passe: `treecolor`).

## 5. Utilisation

### Gestion des Projets (Page d'accueil)

La page d'accueil liste tous les projets existants sous forme de cartes de visite paramétrables.

*   **Pour ajouter un projet** :
    1.  Cliquez sur le bouton "AJOUTER UN PROJET".
    2.  Renseignez le nom du projet.
    3.  Une grande carte s'affiche : utilisez l'outil de création de polygone situé sur la gauche de la carte pour **dessiner avec précision la délimitation géographique** du projet (cliquez sur chaque sommet, puis double-cliquez ou cliquez sur le point de départ pour boucler la forme).
    4.  Téléversez (facultativement) une photo représentative de la région.
    5.  Validez le projet.

*   **Pour supprimer un projet** :
    *   Cliquez sur le bouton "Supprimer le projet". La géométrie en base ainsi que l'image associée seront supprimées et nettoyées du disque.

### Visualisation Cartographique détaillée

*   **Pour voir un projet** :
    *   Sur la page d'accueil, cliquez sur "Voir le projet".

*   **Sur la page de la carte** :
    *   **Couches** : Utilisez le sélecteur en haut à droite pour basculer entre vos couches par défaut (Couleurs Naturelles, Indice de Végétation, Google Satellite, OSM).
    *   **Sélecteur temporel** : Le calendrier vous permet de modifier dynamiquement les strates Copernicus affichées. (Note : les menus de dates se grisent automatiquement lorsque vous consultez une couche qui ne dépend pas d'évolution temporelle mensuelle, comme Google Satellite).

## 6. Structure de l'Architecture

L'application est découpée de manière propre et structurée afin de séparer la logique métier de l'interface utilisateur :

```
.
├── actions/              # Scripts backend de traitement (CRUD BDD + requêtes SQL de récupération)
├── config/               # Fichiers de configuration globale (Connexion BDD standardisée)
├── data/                 # Stockage des images d'illustration triées par id_projet ({id}/img.jpg)
├── docker/               # Outils de conteneurisation (docker-compose.yml, init.sql, Dockerfile)
├── js/                   # Scripts Frontend de logique cartographique (map.js)
├── styles/               # Feuilles de styles CSS paramétriques et design system
├── views/                # Templates HTML purs et complets (accueil, carto) injectés par les contrôleurs
├── index.php             # Contrôleur frontal de bootstraping de la page d'accueil
└── visualisation.php     # Contrôleur frontal chargeant le contexte pour l'observation d'un projet
```
