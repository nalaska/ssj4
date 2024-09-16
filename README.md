# Installation de l'application

## Prérequis

- PHP >= 8.3
- Composer
- Node.js >= 18.x
- Docker et Docker Compose

## Étapes d'installation

### 1. Cloner le dépôt

Clonez le dépôt sur votre machine locale :
`git clone https://github.com/nalaska/ssj4.git`
cd votre-repo


### 2. Installer les dépendances PHP

Utilisez Composer pour installer les dépendances PHP :
`composer install`


### 3. Installer les dépendances JavaScript

Utilisez npm pour installer les dépendances JavaScript :
`npm install`



### 4. Lancer le serveur de développement

Utilisez Artisan pour lancer le serveur de développement :
`php artisan serve`


### 5. Utiliser Docker Compose

Si vous préférez utiliser Docker, vous pouvez lancer les services avec Docker Compose :
`docker-compose up -d`


### 6. Lancer le serveur de développement front-end

Utilisez npm pour lancer le serveur de développement front-end :
`npm run dev`


## Accéder à l'application

Ouvrez votre navigateur et accédez à l'URL suivante :
`http://localhost:8000`


## Commandes utiles

- `php artisan migrate`: Exécuter les migrations de la base de données.
- `php artisan db:seed`: Peupler la base de données avec des données de test.
- `npm run build`: Compiler les assets pour la production.
- `php artisan breeze:install vue --ssr `: installer le système d'authentification Laravel Breeze dans une application Laravel avec une configuration spécifique pour Vue.js et le support du rendu côté serveur (SSR).

## Remarques

Assurez-vous que les ports nécessaires sont ouverts et que les configurations de votre serveur web (Apache/Nginx) sont correctement définies pour utiliser le certificat SSL généré.