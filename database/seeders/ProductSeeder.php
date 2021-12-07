<?php
/**
 *  * @author Eng.Asma Hawari
 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("database/data/products.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Product::create([
                    "sku" => $data['0'],
                    "name" => $data['1']
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}