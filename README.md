# Laravel-Demo-2 – CRM & projectbeheer

Demo-omgeving op basis van **Laravel 13** en het **[Orchid](https://orchid.software/en/docs/installation/)** adminpanel, gevuld met gegenereerde mockup-data.

Een CRM voor een bureau: klanten, projecten, taken per project en facturatie.

## Wat zit erin

- **Klanten** (leads, actief, inactief) met hun projecten
- **Projecten** met budget, uurtarief, planning, voortgang en projectleider
- **Taken** met status, prioriteit, geschatte en bestede uren
- **Facturen** per project met status (concept, verzonden, betaald, te laat)
- Dashboard met statistieken en grafieken
- Gebruikers- en rollenbeheer van Orchid (inclusief permissies per onderdeel)
- Factories en seeders die bij elke `migrate:fresh --seed` nieuwe demo-data genereren
- Feature-tests die alle adminschermen en de belangrijkste acties controleren

## Structuur

```
.
├── app/                  # De Laravel-applicatie
│   ├── app/Models/       # Eloquent-modellen
│   ├── app/Orchid/       # Screens, Layouts en de PlatformProvider (menu + permissies)
│   ├── database/         # Migraties, factories en seeders
│   ├── routes/platform.php  # Routes van het adminpanel
│   └── tests/Feature/    # Feature-tests
├── docker-compose.yml    # Runtime-container (PHP 8.5 + artisan serve)
└── Makefile              # Handige commando's, alles via Docker
```

## Starten

Vereist alleen Docker. PHP en Composer hoeven niet lokaal geïnstalleerd te zijn.

```bash
make install   # composer install, .env, app key, migraties + demo-data
make up        # start de container
```

De applicatie draait daarna op <http://localhost:8002>, het adminpanel op <http://localhost:8002/admin>.

| Gebruiker | Wachtwoord |
|-----------|------------|
| admin@example.com | password |

Overige commando's:

```bash
make fresh                      # database opnieuw vullen met verse demo-data
make test                       # tests draaien
make artisan cmd="route:list"   # willekeurig artisan-commando
make down                       # container stoppen
```

## Zonder Docker

Met PHP 8.3+ en Composer lokaal werkt het ook zo:

```bash
cd app
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

De database is SQLite (`app/database/database.sqlite`), er is dus geen aparte databaseserver nodig.
