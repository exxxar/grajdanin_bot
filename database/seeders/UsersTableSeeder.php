<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {

        $people = [
            "Ачкасов Сергей Владимирович",
            "Батман Антон Викторович",
            "Доронин Александр Александрович",
            "Дорофеев Алексей Сергеевич",
            "Егоров Константин Викторович",
            "Зайцев Сергей Юрьевич",
            "Зинченко Руслан Владимирович",
            "Касьянова Наталья Викторовна",
            "Коваль Валерий Игоревич",
            "Маслова Ольга Григорьевна",
            "Матулевский Константин Михайлович",
            "Мусацкая Яна Сергеевна",
            "Новиков Богдан Александрович",
            "Нырков Павел Анатольевич",
            "Потапенко Петр Павлович",
            "Прописнов Александр Владимирович",
            "Рожков Кирилл Васильевич",
            "Селезнёв Георгий Константинович",
            "Ситников Евгений Александрович",
            "Тамразян Армен Михаелович", ];

        DB::table('users')->insert([
            'name' => "СуперАдмин",
            'email' => 'test@example.com',
            'fio_from_telegram' => "Test Test",
            'telegram_chat_id' => null,
            'role' => 4,
            'email_verified_at' => now(),
            'password' => Hash::make('secret'), // Пароль secret
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach($people as $p)
        DB::table('users')->insert([
            'name' => $p,
            'email' => Str::uuid().'@example.com',
            'fio_from_telegram' => $p,
            'telegram_chat_id' => null,
            'role' => 2,
            'email_verified_at' => now(),
            'password' => Hash::make('secret'), // Пароль secret
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
