<?php

namespace Database\Seeders;

use App\Models\Municipality;
use Illuminate\Database\Seeder;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $okruga = [
            "ДНР (Народный Совет)",
            "Амвросиевский м.о.",
            "Володарский м.о.",
            "Горловский г.о.",
            "Дебальцевский г.о.",
            "Докучаевский г.о.",
            "Донецкий г.о.",
            "Енакиевский г.о.",
            "Иловайский г.о.",
            "Краснолиманский м.о.",
            "Макеевский г.о.",
            "Мангушский м.о.",
            "Мариупольский г.о.",
            "Снежнянский г.о.",
            "Старобешевский м.о.",
            "Торезский г.о.",
            "Харцызский г.о.",
            "Шахтерский м.о.",
            "Ясиноватский м.о.",
            "Новоазовский м.о.",
        ];

        foreach ($okruga as $okrug)
            Municipality::query()
                ->create([
                    "name" => $okrug
                ]);
    }
}
