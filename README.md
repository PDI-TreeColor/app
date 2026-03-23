# Projet TreeColor

## 1. Présentation

Application web conçue pour le suivi et la gestion de projets de reforestation de l'association TreeColor. Elle fournit une interface cartographique interactive pour visualiser les zones de projet, superposer des images satellites de différentes périodes et gérer les données des projets.

## 2. Fonctionnalités

*   **Gestion de Projets** : Ajouter, visualiser et supprimer des projets de reforestation.
*   **Carte Interactive** : Utilise Leaflet pour afficher les zones de projet sur une couche de base OpenStreetMap.
*   **Import de Données** : Les utilisateurs peuvent importer les délimitations de leurs projets en utilisant des fichiers KML.
*   **Imagerie Satellite** : S'intègre avec l'écosystème Copernicus pour superposer des couches satellites (par exemple, Couleurs Naturelles, Indice de Végétation).
*   **Analyse Temporelle** : Un sélecteur de date permet de visualiser les données satellites pour différents mois et années.
*   **Fonctionnalités SIG** :
    *   Calcule et affiche la superficie de chaque zone de projet.
    *   Inclut des outils cartographiques pour mesurer manuellement les distances et les surfaces.
*   **Conteneurisé** : L'ensemble de l'application est conteneurisé avec Docker et Docker Compose pour une installation et un déploiement faciles.

## 3. Stack Technique

*   **Backend** : PHP
*   **Frontend** : HTML, CSS, JavaScript
*   **Base de données** : PostgreSQL avec l'extension PostGIS pour les données géospatiales.
*   **Bibliothèque cartographique** : Leaflet.js.
*   **Traitement de données géospatiales** : `geoPHP` pour la conversion de KML en GeoJSON.
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
    # Exemple avec git
    git clone <url-de-votre-depot>
    cd <nom-du-dossier-projet>
    ```

2.  **Construisez et lancez les conteneurs**
    Depuis la racine du projet (là où se trouve le fichier `docker-compose.yml`), exécutez la commande suivante. Elle construira le conteneur PHP, téléchargera l'image PostGIS et démarrera tous les services en arrière-plan.
    ```bash
    docker-compose up -d --build
    ```
    La base de données sera automatiquement initialisée avec le schéma et les données d'exemple de `init.sql`.

3.  **Accédez à l'application**
    *   L'application principale est disponible à l'adresse **`http://localhost:8080`**.
    *   La base de données PostgreSQL est accessible sur le port **`5433`** de votre machine locale si vous avez besoin de vous y connecter avec un client de base de données (comme DBeaver ou pgAdmin).

## 5. Utilisation

### Gestion des Projets (Page d'accueil)

La page d'accueil (`index.php`) liste tous les projets existants et vous permet de les gérer.

*   **Pour ajouter un projet** :
    1.  Cliquez sur le bouton "AJOUTER UN PAYS".
    2.  Remplissez le nom du projet.
    3.  Téléversez un fichier `.kml` définissant la limite géographique du projet.
    4.  Téléversez une image représentative pour le projet.
    5.  Cliquez sur "Valider".

*   **Pour supprimer un projet** :
    *   Cliquez sur le bouton "Supprimer le projet" à côté du projet que vous souhaitez supprimer.

### Visualisation Cartographique

*   **Pour voir un projet** :
    *   Sur la page d'accueil, cliquez sur le bouton "Voir le projet".

*   **Sur la page de la carte (`visualisation.php`)** :
    *   **Couches** : Utilisez le contrôle des couches en haut à droite pour basculer entre les fonds de carte (OpenStreetMap, Copernicus) et pour afficher/masquer la zone du projet.
    *   **Sélection de la date** : Utilisez les menus déroulants en haut à gauche pour sélectionner une année et un mois. Cela mettra à jour les couches satellites Copernicus pour afficher les données de cette période.

## 6. Structure des Fichiers

```
.
├── data/                 # Stocke les images et les GeoJSON générés
├── js/
│   ├── date-selection.js # Logique pour les menus de date
│   └── map.js            # Logique principale de la carte Leaflet
├── php/                  # Contexte de build Docker pour le conteneur PHP
│   └── Dockerfile
├── vendor/               # Dépendances Composer (ex: geoPHP)
├── docker-compose.yml    # Définit les services de l'application (PHP, PostGIS)
├── index.php             # Page principale pour la gestion des projets
├── init.sql              # Script d'initialisation de la base de données
├── read_db.php           # API pour récupérer les données GeoJSON
├── style*.css            # Fichiers de style
└── visualisation.php     # Page qui affiche la carte Leaflet
```
