# score-table-backend

To run project copy `.env.example` data to `.env`.

To test application recomended use SQLite. You must create database file `db.sqlite` and insert file path to `DB_DATABASE`. The `DB_CONNECTION` must be set to `sqlite`.

To run server type command

`php artisan serve`

To have database with all data you need you must run

`php artisan migrate --seed`

# endpoints

To test endpoints you have postman collections in `postman` directory.
