# Program futtatása

## Backend
- `CTRL + Ö` billentyűkombináció lenyomása után a `cd backend` paranccsal belépünk a backend mappájába
- Ahol kiadjuk a `composer i` parancsot, amivel a Laravel keretrendszerhez szükséges fájlokat letelepítjük.
- Ezután létrehozzuk az adatbázist a `php artisan migrate` paranccsal, és ezt követően `php artisan db:seed` paranccsal feltöltjük adatokkal.

## Frontend
- A `cd frontend` paranccsal belépünk a frontend mappájába
- Ahol az `npm i` paranccsal letelepítjük a szükséges csomagokat.
- A telepítés után elindítjuk a projectet az `npm run dev` paranccsal, és ha a `CTRL` lenyomásával rákattintunk, akkor átdob minket a weboldalra.