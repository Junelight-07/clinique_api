# Clinique Vétérinaire API

**Développé par Alexy DE BARROS et Olivier DELMAS**

Une API Symfony avec API Platform permettant de gérer une clinique vétérinaire avec différents niveaux d'accès basés sur les rôles utilisateur.

## Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration pour le premier démarrage](#configuration-pour-le-premier-démarrage)
- [Création du premier utilisateur](#création-du-premier-utilisateur)
- [Utilisation de l'API](#utilisation-de-lapi)
- [Structure des rôles](#structure-des-rôles)
- [Endpoints disponibles](#endpoints-disponibles)

## Prérequis

- Docker et Docker Compose
- Symfony CLI
- Composer
- OpenSSL (pour la génération des clés JWT)

## Installation

1. Clonez le dépôt
   ```bash
   git clone https://github.com/Junelight-07/clinique_api.git
   cd clinique
   ```

2. Installez les dépendances
   ```bash
   composer install
   ```

3. Lancez les conteneurs Docker
   ```bash
   docker-compose up -d
   ```

4. Démarrez le serveur Symfony
   ```bash
   symfony server:start
   ```

## Configuration pour le premier démarrage

### 1. Génération des clés JWT

Générez les clés JWT nécessaires à l'authentification :

```bash
php bin/console lexik:jwt:generate-keypair
```

> **Note pour Windows** : Si vous rencontrez une erreur OpenSSL, installez-le via Chocolatey :
> ```bash
> choco install openssl
> ```
> Puis redémarrez votre terminal et réessayez.

### 2. Création des migrations et de la base de données

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Création du premier utilisateur

1. Envoyez une requête POST à `https://127.0.0.1:8000/api/users` avec le corps suivant :

```json
{
  "email": "directeur@clinique.com",
  "plainPassword": "password",
  "firstName": "John",
  "lastName": "Doe",
  "roles": ["ROLE_DIRECTOR"]
}
```

> **Important** : N'oubliez pas d'ajouter le header `Content-Type: application/ld+json`

## Utilisation de l'API

### Authentification

1. Pour obtenir un token JWT, envoyez une requête POST à `https://127.0.0.1:8000/api/auth` :

```json
{
  "email": "directeur@clinique.com",
  "password": "password"
}
```

2. Utilisez le token reçu pour authentifier vos requêtes suivantes :
  - Dans Postman : Onglet "Authorization", sélectionnez "Bearer Token" et collez votre token
  - Dans les requêtes HTTP : Ajoutez l'en-tête `Authorization: Bearer <votre-token>`

### Types de requêtes et en-têtes

- Pour les requêtes GET, POST, DELETE :
  - Ajoutez l'en-tête `Content-Type: application/ld+json`

- Pour les requêtes PATCH :
  - Ajoutez l'en-tête `Content-Type: application/merge-patch+json`

## Structure des rôles

L'API dispose d'une hiérarchie de rôles avec des permissions spécifiques :

- **ROLE_DIRECTOR** (Directeur) : Accès complet, peut gérer le personnel et toutes les ressources
- **ROLE_VETERINARIAN** (Vétérinaire) : Peut gérer les rendez-vous, les traitements et les animaux
- **ROLE_ASSISTANT** (Assistant) : Peut gérer les rendez-vous et les animaux avec des permissions limitées
- **PUBLIC** (Client non authentifié) : Accès limité à certaines ressources publiques

## Endpoints disponibles

L'API propose plusieurs endpoints pour gérer la clinique vétérinaire :

- `/api/users` : Gestion des utilisateurs (personnel)
- `/api/animals` : Gestion des animaux
- `/api/consultations` : Gestion des rendez-vous
- `/api/treatments` : Gestion des traitements
- `/api/media` : Gestion des médias (photos des animaux)

Pour une documentation complète des endpoints, consultez la documentation Swagger accessible à l'adresse :
`https://127.0.0.1:8000/api/docs`

---

Pour toute question ou problème, veuillez contacter les développeurs :
- Alexy DE BARROS
- Olivier DELMAS