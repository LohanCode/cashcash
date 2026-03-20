# Tutoriel migration complete vers Docker (base centralisee)

Ce guide permet d'utiliser une base PostgreSQL Docker unique pour:
- l'application Symfony (web)
- le client lourd Electron

## 1. Prerequis

- Docker Desktop installe sur Windows
- Docker Desktop demarre (icone verte)
- PowerShell ouvert dans le projet

Commande de verification:

```powershell
docker version
```

Si erreur `docker_engine`, Docker Desktop n'est pas demarre.

## 2. Verifier la configuration du projet

Les fichiers suivants doivent etre alignes:
- `.env` (racine): PostgreSQL
- `compose.yaml`: service `database` en `postgres:16-alpine`
- `compose.override.yaml`: port `5433:5432`

Valeur attendue pour `DATABASE_URL`:

```dotenv
DATABASE_URL="postgresql://app:app@127.0.0.1:5433/app?serverVersion=16&charset=utf8"
```

## 3. Demarrer la base Docker

Depuis la racine du projet:

```powershell
docker compose up -d database
```

Verifier l'etat:

```powershell
docker compose ps
```

Le service `database` doit etre `Up`.

## 4. Initialiser la base Symfony

Toujours depuis la racine:

```powershell
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

Si `php` n'est pas dans le PATH, utilise le PHP embarque:

```powershell
.\ClientLourd\php-win\php.exe .\bin\console doctrine:database:create --if-not-exists
.\ClientLourd\php-win\php.exe .\bin\console doctrine:migrations:migrate --no-interaction
```

## 5. Charger des donnees (optionnel mais recommande)

```powershell
php bin/console doctrine:fixtures:load --no-interaction
```

ou avec PHP embarque:

```powershell
.\ClientLourd\php-win\php.exe .\bin\console doctrine:fixtures:load --no-interaction
```

## 6. Tester la connexion BDD

```powershell
php bin/console dbal:run-sql "SELECT 1"
```

ou:

```powershell
.\ClientLourd\php-win\php.exe .\bin\console dbal:run-sql "SELECT 1"
```

Resultat attendu: une ligne avec `1`.

## 7. Demarrer l'application web

```powershell
php -S 127.0.0.1:8000 -t public
```

## 8. Demarrer le client lourd

```powershell
cd ClientLourd
npm start
```

Le client lourd lance le backend Symfony local mais utilise la meme base PostgreSQL Docker via `DATABASE_URL`.

## 9. Cas particulier: application deja packagee (dist)

Si tu testes l'executable deja package dans `ClientLourd/dist/win-unpacked`, son `.env` interne doit aussi etre en PostgreSQL.

Ensuite, idealement, reconstruis le package pour embarquer la bonne config source:

```powershell
cd ClientLourd
npm run build
```

## 10. Procedure de migration propre depuis MySQL (si besoin)

Si tes anciennes donnees sont en MySQL:

1. Export MySQL (SQL dump)
2. Adapter le dump pour PostgreSQL (types, AUTO_INCREMENT, quotes)
3. Import PostgreSQL
4. Verifier les tables et contraintes
5. Tester les ecrans critiques (login, statistiques, affectation)

Pour un transfert fiable de schema + donnees, utilise de preference un outil ETL/migration (ex: pgloader).

## 11. Checklist finale

- [ ] Docker Desktop demarre
- [ ] `docker compose ps` montre `database` en Up
- [ ] `.env` racine en PostgreSQL
- [ ] migrations appliquees sans erreur
- [ ] `dbal:run-sql "SELECT 1"` OK
- [ ] web Symfony OK
- [ ] client lourd OK

## 12. Depannage rapide

- Erreur `docker_engine`: demarrer Docker Desktop
- Erreur `connection refused 127.0.0.1:5433`: conteneur non demarre ou port occupe
- Erreur auth PostgreSQL: verifier user/pass (`app/app`) dans `.env` et `compose.yaml`
- Erreur schema absent: relancer migrations

### Cas frequent: mot de passe PostgreSQL invalide apres changement de config

Si tu vois une erreur du type "authentification par mot de passe echouee pour l'utilisateur app", c'est souvent que le volume Docker contient un ancien mot de passe.

Attention: la commande suivante supprime les donnees PostgreSQL du volume local Docker.

```powershell
docker compose down -v
docker compose up -d database
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```
