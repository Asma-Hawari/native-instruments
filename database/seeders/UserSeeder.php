<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("database/data/users.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $user = User::create([
                    "id" => $data['0'],
                    "name" => $data['1'],
                    "email" => $data['2'],
                    "password" => $data['3']
                ]);
                $user->products()->sync(rand(1,18),rand(1,18),rand(1,18));
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}