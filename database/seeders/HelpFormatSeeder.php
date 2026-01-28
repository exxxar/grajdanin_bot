<?php

namespace Database\Seeders;

use App\Models\HelpFormat;
use Illuminate\Database\Seeder;

class HelpFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $helps = [
            "Разработка и организация мероприятия",
            "Закупка материалов (призы, грамоты, раздаточный материал)",
            "Разработка дизайна грамота, баннеров, иного",
            "Предоставление оборудования, материалов (флаги, виндеры, мерч)",
            "Привлечение актива партии",
            "Иное",
            "Помощь не требуется"
        ];

        foreach ($helps as $help)
            HelpFormat::query()
                ->create([
                    "name" => $help
                ]);
    }
}
