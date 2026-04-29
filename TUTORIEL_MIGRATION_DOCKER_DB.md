# Docker (MySQL) + phpMyAdmin

Ce guide configure une base **MySQL Docker** + **phpMyAdmin** pour le projet CashCash.

## 1. Prérequis

- Docker Desktop installé et démarré
- PowerShell ouvert à la racine du projet

Vérification :

```powershell
docker version
```

## 2. Configuration attendue

- `compose.yaml` : service `database` en `mysql:8.0` + service `phpmyadmin`
- `compose.override.yaml` :
  - MySQL exposé en `127.0.0.1:3307` (évite les conflits avec XAMPP)
  - phpMyAdmin exposé en `http://127.0.0.1:8081`
- `.env` :

```dotenv
DATABASE_URL="mysql://app:app@127.0.0.1:3307/cashcash?serverVersion=8.0&charset=utf8mb4"
```

## 3. Démarrer MySQL + phpMyAdmin

```powershell
docker compose up -d database phpmyadmin
```

Puis ouvrir :
- phpMyAdmin : http://127.0.0.1:8081

Identifiants MySQL (Docker) :
- serveur : `database` (si tu es dans phpMyAdmin) / `127.0.0.1` (si tu es sur ta machine)
- utilisateur : `app`
- mot de passe : `app`
- base : `cashcash`

## 4. Créer le schéma (Doctrine)

```powershell
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

## 5. Importer les données dans phpMyAdmin (option 1)

Dans phpMyAdmin :
- sélectionne la base `cashcash`
- onglet **Importer**
- importe le fichier `export_bdd.sql` (script MySQL)

## 6. Charger les fixtures (option 2)

```powershell
php bin/console doctrine:fixtures:load --no-interaction
```

## Dépannage

- Si MySQL ne démarre pas, vérifie que le port `3307` est libre.
- Si tu veux utiliser `3306` au lieu de `3307`, modifie `compose.override.yaml` et `DATABASE_URL`.
