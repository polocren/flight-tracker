# Flight Tracker - Application de gestion d'avions

## Description

Application web permettant la gestion et le suivi d'une base de données d'avions. Cette application offre des fonctionnalités de météo METAR en direct, un système d'authentification complet, et la possibilité de gérer des logs de vol.

## Fonctionnalités principales

-   Authentification (inscription, connexion, déconnexion)
-   Consultation des données METAR en temps réel
-   Gestion des avions (consultation, recherche)
-   Système de logs de vol (création, modification, suppression)
-   Interface responsive et intuitive

## Technologies utilisées

### Backend

-   Laravel 10.x
-   PHP 8.1+
-   MySQL/PostgreSQL
-   API METAR pour les données météo

### Frontend

-   Vue.js 3
-   Tailwind CSS
-   Axios pour les requêtes HTTP

## Prérequis

-   PHP 8.1 ou supérieur
-   Composer
-   Node.js 16+ et NPM
-   MySQL 8.0+ ou PostgreSQL 13+
-   Serveur web (Apache, Nginx)

## Installation

1. Cloner le dépôt

```bash
git clone [URL_DU_REPO]
cd flight-tracker
```

2. Installer les dépendances PHP

```bash
composer install
```

3. Installer les dépendances JavaScript

```bash
npm install
```

4. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

5. Configurer la base de données

-   Ouvrir le fichier `.env`
-   Modifier les paramètres de connexion à la base de données :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flight_tracker
DB_USERNAME=root
DB_PASSWORD=
```

6. Migrer la base de données

```bash
php artisan migrate
```

7. Lancer le serveur de développement

```bash
# Terminal 1 - Serveur Laravel
php artisan serve

# Terminal 2 - Serveur Vite
npm run dev
```

L'application sera accessible à l'adresse : `http://localhost:8000`

## Configuration de l'API METAR

Pour utiliser les données météo en temps réel, vous devez configurer votre clé API METAR dans le fichier `.env` :

```
METAR_API_KEY:7wxt5Q2Ug0lYjz0dX5gAB8bI8XPb720Cq4I6J22SE=v
```

## Structure du projet

```
flight-tracker/
├── app/                # Contrôleurs, modèles et logique métier
├── resources/          # Vues, assets et composants Vue.js
├── routes/            # Routes de l'application
├── database/          # Migrations et seeders
└── tests/             # Tests unitaires et fonctionnels
```

## Auteur

CREN Paul

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Fonctionnalités

-   [METEO METAR EN DIRECT, Informations sur les principaux avions, connexion, inscription, log de vol]
-   [Par exemple: Recherche d'avions, filtrage par caractéristiques, etc.]
