# TreeColor - Plateforme de Suivi de Reforestation

## Présentation du Projet

Ce projet est une application web interactive conçue pour l'association TreeColor. Elle permet de cartographier, suivre et analyser l'évolution de projets de reforestation à travers le monde (Panama, Burkina Faso, etc.).

L'outil offre une interface moderne permettant de visualiser des données géospatiales précises et de superposer des images satellites temporelles pour observer la croissance de la végétation au fil des mois.

### ✨ Fonctionnalités Clés
- **Gestion de projets** : Interface pour ajouter et supprimer des zones de reforestation.
- **Dessin interactif** : Outil de délimitation de zones directement sur la carte.
- **Imagerie Satellite (Copernicus)** : Intégration des flux WMS de Sentinel-2 pour visualiser les indices de végétation, les couleurs naturelles et l'infrarouge.
- **Photos de Terrain Géo-taguées** : Possibilité d'ajouter des photos géo-taguées directement sur la carte.
- **Analyse Temporelle** : Sélecteur de date dynamique pour remonter le temps et observer l'évolution d'une parcelle.
- **Cartographie Multi-sources** : Bascule entre Google Satellite, OpenStreetMap et les couches Copernicus.

---

## Tutoriel d'Installation

L'application est entièrement conteneurisée pour faciliter son déploiement sur n'importe quel environnement Linux, macOS ou Windows (via Docker Desktop).

### 📋 Prérequis
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### 🛠 Étapes d'installation

1. **Récupérer les sources**
   ```bash
   git clone <url-du-depot>
   cd treecolor
   ```

2. **Lancer l'infrastructure**
   Depuis la racine du projet, utilisez Docker Compose pour démarrer le serveur Web (PHP 8.2) et la base de données (PostGIS) :
   ```bash
   docker compose -f docker/docker-compose.yml up -d --build
   ```

3. **Accéder à l'interface**
   Une fois les conteneurs démarrés, ouvrez votre navigateur à l'adresse suivante :
   👉 **[http://localhost:8080](http://localhost:8080)**

---

## 📂 Structure du Code
- `/actions` : Logique backend (Scripts de traitement SQL, CRUD).
- `/views` : Templates HTML et interface utilisateur.
- `/styles` : Design system (CSS) et thématique visuelle.
- `/js` : Logique cartographique Leaflet et interactions frontend.
- `/docker` : Configuration des conteneurs et script d'initialisation SQL (`init.sql`).
- `/data` : Stockage des images des projets et des photos de terrain (organisé par ID).

---

## 🎨 Charte Graphique
- **Titres** : Police *Belleza* (Google Fonts).
- **Texte** : Police *Calibri*.
- **Couleurs** : Vert Forêt (`#2b8a3e`), Rose Magentà (`#d63384`), Noir (`#000000`).
- **Formes** : Boutons et composants 100% carrés pour un look "Bold & Clean".
