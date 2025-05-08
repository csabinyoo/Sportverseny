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

![Diagram](/documents/diagram_new.png)

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