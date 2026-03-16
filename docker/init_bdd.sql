-- 1. Activer l'extension PostGIS
CREATE EXTENSION IF NOT EXISTS postgis;

-- 2. Créer une table avec une colonne géométrique
CREATE TABLE sites_interet (
    id SERIAL PRIMARY KEY,
    nom TEXT,
    geom GEOMETRY(Polygon, 4326)
);
