## 1. Contexte et limites de l'approche satellite actuelle

La plateforme de suivi dévelopée pour TreeColor repose actuellement sur l'imagerie satellite Sentinel-2 (Copernicus / ESA) pour le suivi visuel des zones de reforestation. Cette approche présente plusieurs avantages indéniables : gratuité, couverture mondiale, accès historique remonant à 2017. Elle constitue une base solide pour observer l'évolution macro d'un territoire.

Cependant, dans le contexte spécifique des missions de terrain de l'association — des parcelles de quelques dizaines d'hectares maximum au Panama et au Burkina Faso — **l'imagerie satellite présente des limites structurelles importantes** :

| Critère | Satellite (Sentinel-2) | Drone |
|---|---|---|
| **Résolution spatiale** | 10 m/pixel | 1 – 3 cm/pixel |
| **Fréquence de revisite** | 5 jours (si pas de nuages) | À la demande |
| **Couverture nuageuse** | Bloquante (Panama) | Non applicable (vol sous les nuages) |
| **Individualisation des plants** | Impossible | Possible |
| **Coût par mission** | Gratuit (données publiques) | Variable (opérateur + drone) |
| **Délai d'obtention** | ~5 jours | Quelques heures |
| **Détection de mortalité** | Impossible < 1 ha | Pied par pied |

### Le problème de la résolution

Sentinel-2 offre une résolution de **10 mètres par pixel**. Concrètement, même un groupe de plusieurs dizaines d'arbres de 2 mètres de hauteur peut n'occuper qu'un seul pixel. Il est donc **impossible** de distinguer les espèces, d'évaluer le taux de reprise individuel, ou de détecter une mortalité localisée. Pour des associations dont la mission est de planter et d'en rendre compte à des financeurs, cette imprécision est un problème.

### Le problème de la couverture nuageuse

Le Panama est une région à forte pluviométrie et couverture nuageuse quasi permanente plusieurs mois par an. Les indices de végétation comme le NDVI deviennent **inutilisables** lors des saisons des pluies, précisément au moment où la végétation est la plus active. Un drone, opéré à quelques centaines de mètres d'altitude, volera **sous la couche nuageuse** et fournira des données exploitables quelles que soient les conditions météorologiques.

---

## 2. Ce que l'imagerie drone permettrait à TreeColor

Avec des vols réguliers (1 à 2 fois par an), l'association serait en mesure de :

- **Compter et localiser chaque plant individuellement**, et évaluer le taux de reprise réel (non estimé).
- **Quantifier la biomasse** et la hauteur des plants grâce aux modèles 3D (MNS – Modèle Numérique de Surface).
- **Détecter les zones de mortalité** ou de stress hydrique avant qu'elles ne deviennent irreversibles.
- **Produire des rapports de transparence** pour les mécènes, avec des "preuves visuelles" irréfutables à haute résolution.
- **Constituer une archive photographique géocodée**, consultable dans l'outil de cartographie existant, projet par projet.