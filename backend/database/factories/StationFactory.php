<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\result_type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('hu_HU'); // Faker példány létrehozása magyar lokalizációval
        static $usedNames = []; // Tárolja az eddig használt sportágneveket, hogy ne ismétlődjenek
        static $usedUserIds = []; // Tárolja az eddig kiválasztott felhasználók ID-jait
    
        // Sportágak kategóriák szerint
        $sportsByType = [
            1 => ['Labdarúgás', 'Kosárlabda', 'Baseball', 'Röplabda', 'Jégkorong', 'Krikett', 'Golf', 'Birkózás', 'Íjászat'], // Sportágak, ahol a pontszám a mérvadó
            2 => ['Úszás', 'Futás', 'Kerékpározás', 'Evezés', 'Síelés', 'Snowboard', 'Vívás'] // Sportágak, ahol az idő a mérvadó
        ];
    
        // Véletlenszerűen kiválaszt egy result_type ID-t
        $typeId = result_type::inRandomOrder()->first()->id;
    
        // A megfelelő sportágak kiválasztása a typeId alapján, kiszűrve a már használtakat
        $availableSports = array_diff($sportsByType[$typeId] ?? [], $usedNames);
    
        // Ha már az összes sportágat felhasználtuk, akkor újratöltjük a listát
        if (empty($availableSports)) {
            $usedNames = [];
            $availableSports = $sportsByType[$typeId] ?? [];
        }
    
        // Véletlenszerű sportág kiválasztása
        $name = $faker->randomElement($availableSports);
        $usedNames[] = $name; // Kiválasztott sportág hozzáadása a már használt listához
    
        // Felhasználó kiválasztása, aki még nem lett hozzárendelve és roleId == 2
        $userId = User::where('roleId', 2)
                      ->whereNotIn('id', $usedUserIds)
                      ->inRandomOrder()
                      ->first()?->id;
    
        // Ha találtunk megfelelő felhasználót, hozzáadjuk a listához, hogy ne ismétlődjön
        if ($userId) {
            $usedUserIds[] = $userId;
        }
    
        // Végső tömb visszaadása a generált adatokkal
        return [
            'name' => $name, // Sportág neve, amely illeszkedik a typeId követelményeihez
            'location' => $faker->numberBetween(1, 60) . '. terem', // Véletlenszerű teremszám generálása
            'weighting' => $faker->boolean(50) ? 1.0 : $faker->randomFloat(1, 0.5, 0.8), // Véletlenszerű súlyozás 0.5 és 1.0 között
            'moreIsBetter' => $typeId == 1 ? 1 : 0, // Ha typeId 1, akkor a magasabb pont jobb; ha 2, akkor az alacsonyabb idő jobb
            'typeId' => $typeId, // Kiválasztott típus ID
            'userId' => $userId, // Kiválasztott felhasználó ID
            'competitionId' => Competition::inRandomOrder()->first()->id // Véletlenszerű verseny ID kiválasztása
        ];
    }    
}
