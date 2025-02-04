# A feladat leírása

> A projekt célja egy olyan versenykezelő rendszer létrehozása, amely állomásokból áll, ahol a csapatok valamilyen feladatok végrehajtanak aminek az eredményét összesítjük. A helyezés úgy alakul ki, hogy a helyszíneket végzett feladatok eredménye alapján sorba rendezzük a csapatokat.

```
Egy verseny létrehozása az alábbi lépések szerint történik:
1. Az admin létrehozza a versenyt valamint a hozzátartozó helyszíneket és a helyszíneket felügyelőket regisztrálja, meghatározza a verseny jelentkezési határidejét.
2. Nevezés: A regisztrálók regisztrálnak az oldalra, és bejegyzik a csapataik nevét és tagjait. Nevezni csak a jelentkezési határidőn belül lehetséges.
3. A verseny lebonyolítása: A program által adott ütemterv szerint a csapatok felkeresik a helyszíneket, ahol a felügyelők regisztrálják az eredményeket, ami alapján a rendszer automatikus generálja az eredményt amit bárki megtekinthet.
```

## Szerepkörök
```
Az alkalmazás négy szerepkörre épül, amik megfelelő jogosultsággal hozzáférhetnek a weboldal különböző tartalmaihoz.
> Admin - Teljes hozzáférés.
> Felügyelő [Supervisor] - Adott állomás felügyelete: pontozás.
> Regisztráló [Registrant] - A feladata a csapat beregisztrálja a versenyre.
> Vendég [Guest] - Szabadon nézheti az eseményeket és az eredményeket.
```

## Technológia
> <strong>Adatbázis</strong> > mysql

> <strong>Backend</strong> > Laravel

> <strong>Frontend</strong> > vue.js

> <strong>Csoportmunka</strong> > git & github

> <strong>Dizájn</strong> > css & bootstrap

# Diagram
![Diagram](documents/diagram_new.png)
 
# Táblák
 
## Users
 
><strong> id :</strong> *A felhasználó azonosítója, int(10), autoIncrement*
 
><strong> username(Név) :</strong> Itt van a felhasználónév, string(25), notNull
 
> **roleID()** -> A szerepkörhöz tartozó jogok [1 (regisztráló), 2 (felügyelő), 3 (admin)]
 
<h1 style="text-align:center;">Szerepkörök [Roles]</h1>

> **id(azonosító)** -> A szerepkör azonosítója
 
> **role(szerepkör)** -> Itt vannak a szerepkörök, string(10), notNull
 
> **permission(jog)** ->  A szerepkörrel megegyező jogot biztosít
 
##  Csapatok [Teams]
 
> **id** -> a csapatok id-je
 
> **competitionId(versenyId)** -> A verseny Id-je
 
> **teamName(csapatNev)** -> A csapatok neve(i)
 
> **school(iskola)** -> Az iskola neve
 
##  Állomások [situations]
 
> **azonosító(id)** -> Az állomások azonosítója(id)
 
> **stationName(állomásNév)** -> Az állomás neve
 
> **location(hely)** -> A terem neve
 
> **weighting(súlyozás)** -> Az állomás pontjainak súlyozása
 
> **moreIsBetter(többAjobb)** -> A verseny típusától függ a pontozás
 
> **resultTypeId(eredményTípusId)** ->  A típus azonosítója(id)
 
> **userId** -> A felhasználó azonosítója(id)
 
> **competitonId(versenyId)** -> A verseny azonosítója(id)
 
## csapattagok [teamMembers]
 
> **id(azonosító)** -> A csapattagok azonosítója(id)
 
> **teamId(csapatId)** -> A csapat azonosítója(id)
 
> **name(név)** -> A csapattagok nevei
 
> **teamCaptain(csapatkapitány)** -> A csapatkapitány neve
 
## eredményTípusok [resultType]
 
> **id(azonosító)** -> Az eredményTípusok azonosítója(id)
 
> **resultType(eredményTípus)** -> Az eredmény típusa
 
## csapatAzÁllomáson [teamAStation]
 
> **id(azonosító)** -> A csapatAzÁllomáson azonosítója(id)
 
> **teamId(csapatId)** -> A csapat azonosítója(id)
 
> **stationId(állomásId)** -> Az állomás azonosítója(id)
 
## versenyek [competitions]
 
> **id(azonosító)** -> A verseny azonosítója(id)
 
> **competitonName(versenyNév)** -> A verseny neve
 
> **competitonDate(versenyDátum)** -> A verseny megrendezésének dátuma
 
> **competitonLocation(versenyHelyszín)** -> A vereny helyszíne(iskola)
 
> **registerFrom(regisztrációTól)** -> A regisztráció kezdete
 
> **registerTo(regisztrációIg)** -> A regisztráció vége
 
## tagokEredményeiAzÁllomáson [memberResultsAtStation]
 
> **id(azonosító)** -> A tagok eredményeinek azonosítója(id)
 
> **teamAtStation(csapatAzÁllomáson)** -> Az adott csapat, amelyik részt vett az állomáson
 
> **teamMemberId(csapattagId)** -> A csapattag azonosítója(id)
 
> **result(eredmény)** -> Az adott csapattag eredménye az állomáson
 
> **resultTime(eredményIdő)** -> Az eredmény elérése közben mért idő