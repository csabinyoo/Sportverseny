# Leírás

Célja...

- Technológiák:

  - Adatbázis: MySQL
  - Backend: Laravel
  - Frontend: VueJs
  - Csoportmunka: Git, GitHub
  - Kommunikáció: Discord
  - Ütemterv: GitHub projekttervező rendszere

- A feladat csoportmunkában készült Jáger Kristóf és Kovács János (csoportvezető) részvételével.

# Az adatbázis:

## Diagram

![Diagram](/documents/Kepek/diagram_new.png)

## Táblák leírása
# Adatbázis séma leírása

## `users` (Felhasználók)
| Mező              | Típus    | Leírás                                     |
|--------------------|----------|-------------------------------------------|
| `id`              | INT      | Egyedi azonosító.                         |
| `name`            | VARCHAR  | A felhasználó neve.                       |
| `email`           | VARCHAR  | A felhasználó e-mail címe.                |
| `password`        | VARCHAR  | A felhasználó jelszava.                   |
| `roleId`          | INT      | Hivatkozás a `roles` táblára.             |

### Kapcsolatok
- A felhasználók szerepkörökhöz (`roles`) kapcsolódnak a `roleId` mezőn keresztül.

---

## `roles` (Szerepkörök)
| Mező         | Típus    | Leírás                            |
|--------------|----------|------------------------------------|
| `id`         | INT      | Egyedi azonosító.                 |
| `role`       | VARCHAR  | A szerepkör neve.                 |
| `permission` | INT      | A jogosultsági szint.             |

### Kapcsolatok
- A szerepkörök a felhasználókhoz (`users`) kapcsolódnak.

---

## `teams` (Csapatok)
| Mező            | Típus    | Leírás                           |
|------------------|----------|----------------------------------|
| `id`            | INT      | Egyedi azonosító.               |
| `competitionId` | INT      | Hivatkozás a `competitions` táblára. |
| `name`          | VARCHAR  | A csapat neve.                  |
| `school`        | VARCHAR  | A csapat iskolája.              |
| `userId`        | INT      | Hivatkozás a csapatkapitányra `users` táblára. |

### Kapcsolatok
- A csapatok a versenyekhez (`competitions`) és a csapattagokhoz (`team_members`) kapcsolódnak.

---

## `team_members` (Csapattagok)
| Mező         | Típus    | Leírás                           |
|--------------|----------|----------------------------------|
| `id`         | INT      | Egyedi azonosító.               |
| `teamId`     | INT      | Hivatkozás a `teams` táblára.   |
| `name`       | VARCHAR  | A csapattag neve.               |
| `captain`    | INT      | Jelzi, hogy csapatkapitány-e (1 vagy 0). |

### Kapcsolatok
- Csapattagok a csapatokhoz (`teams`) tartoznak.

---

## `competitions` (Versenyek)
| Mező           | Típus    | Leírás                           |
|-----------------|----------|----------------------------------|
| `id`           | INT      | Egyedi azonosító.               |
| `name`         | VARCHAR  | A verseny neve.                 |
| `date`         | DATE     | A verseny dátuma.               |
| `location`     | VARCHAR  | A verseny helyszíne.            |
| `registerFrom` | DATE     | Regisztráció kezdete.           |
| `registerTo`   | DATE     | Regisztráció vége.              |

### Kapcsolatok
- A versenyek csapatokkal (`teams`) és állomásokkal (`stations`) kapcsolódnak.

---

## `stations` (Állomások)
| Mező           | Típus    | Leírás                           |
|-----------------|----------|----------------------------------|
| `id`           | INT      | Egyedi azonosító.               |
| `name`         | VARCHAR  | Az állomás neve.                |
| `location`     | VARCHAR  | Az állomás helyszíne.           |
| `weighting`    | DOUBLE   | Súlyozási érték.                |
| `moreIsBetter` | TINYINT  | Jelzi, hogy nagyobb eredmény jobb-e (1 vagy 0). |
| `typeId`       | INT      | Hivatkozás a `result_types` táblára. |
| `userId`       | INT      | Hivatkozás a felelős felhasználóra. |
| `competitionId`| INT      | Hivatkozás a `competitions` táblára. |

### Kapcsolatok
- Állomások a versenyekhez (`competitions`) és az eredménytípusokhoz (`result_types`) kapcsolódnak.

---

## `result_types` (Eredménytípusok)
| Mező  | Típus    | Leírás                           |
|-------|----------|----------------------------------|
| `id`  | INT      | Egyedi azonosító.               |
| `type`| VARCHAR  | Az eredmény típusa.             |

### Kapcsolatok
- Az eredménytípusok az állomásokhoz (`stations`) kapcsolódnak.

---

## `team_at_stations` (Csapatok állomásokon)
| Mező          | Típus    | Leírás                           |
|---------------|----------|----------------------------------|
| `id`          | INT      | Egyedi azonosító.               |
| `teamId`      | INT      | Hivatkozás a `teams` táblára.   |
| `stationId`   | INT      | Hivatkozás a `stations` táblára.|

### Kapcsolatok
- A táblázat csapatokat állomásokhoz (`stations`) kapcsolja.

---

## `member_results_at_stations` (Eredmények tagonként állomásokon)
| Mező            | Típus    | Leírás                           |
|------------------|----------|----------------------------------|
| `id`            | INT      | Egyedi azonosító.               |
| `teamAtStationId`| INT      | Hivatkozás a `team_at_stations` táblára. |
| `teamMemberId`  | INT      | Hivatkozás a `team_members` táblára. |
| `result`        | INT      | Az elért eredmény (ha szám).              |
| `resultTime`    | TIME     | Az eredmény időpontja (ha idő).          |

### Kapcsolatok
- Az eredmények csapattagokra (`team_members`) és állomásokra (`stations`) vonatkoznak.

# Backend

## Migráció
![Diagram](/documents/migrations.png)

### Példa
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('competitionId');
            $table->string('name');
            $table->string('school');
            $table->integer('userId');
            $table->foreign('competitionId')->references('id')->on('competitions');
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
```

## Seeder
```php
<?php
namespace Database\Seeders;

use App\Models\team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('csv\teams.csv');
        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ";");
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $data[] = [
                    'competitionId' => $row[0],
                    'name' => $row[1],
                    'school' => $row[2],
                    'userId' => $row[3]
                ];
            }
            fclose($handle);
        }
    
        if (team::count() === 0) {
            team::factory()->createMany($data);
        }
    }
}
```
- A kategóriák külső .csv fájlból (temakorok.csv) kerülnek beolvasásra.

- A fájl elérési útvonala: `database/csv/teams.csv`

- A sorokat szétválasztó karakter `;`

- Csak akkor történik feltöltés, ha a teams tábla még üres.

## Controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\team;
use App\Http\Requests\StoreteamRequest;
use App\Http\Requests\UpdateteamRequest;
use DB;

class TeamController extends Controller
{
    public function index()
    {
        // $rows = team::all();
        $rows = DB::table('teams as t')
            ->join('competitions as c', 't.competitionId', '=', 'c.id')
            ->join('users as u', 't.userId', '=', 'u.id')
            ->where('c.currentComp', 1)
            ->select('t.id', 't.name', 't.school', 't.userId')
            ->get();

        return response()->json(['data' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    public function store(StoreteamRequest $request)
    {
        try {
            $row = team::create($request->all());
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $data = [
                'message' => 'The post failed',
                'data' => $request->all()
            ];
        }

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function show(int $id)
    {
        $row = team::find($id);
        if ($row) {
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function update(UpdateteamRequest $request, int $id)
    {
        $row = team::find($id);
        if ($row) {
            $row->update($request->all());
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function destroy(int $id)
    {
        $row = team::find($id);
        if ($row) {
            $row->delete();
            $data = [
                'message' => 'ok',
                'data' => [
                    'id' => $id
                ]
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }
}

```