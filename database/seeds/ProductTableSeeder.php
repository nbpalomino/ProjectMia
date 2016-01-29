<?php

use Illuminate\Database\Seeder;
use App\Product;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('es');
}
    public function run()
    {
        foreach(range(1,12) as $item) {
            $product = [
                'nombre'=>ucfirst($this->faker->words(2, true)),
                'precio'=>$this->faker->randomFloat(2, 10, 150),
                'modelo'=>ucfirst($this->faker->word),
                'disponible'=>1,
                'genero_id'=>$this->faker->numberBetween(1,4),
                'tipo_id'=>$this->faker->numberBetween(1,8),
                'categoria_id'=>$this->faker->numberBetween(1,7),
                'marca_id'=>$this->faker->numberBetween(1,5),
                'created_by'=>1,
            ];

            Product::create($product);
        }
    }
}
