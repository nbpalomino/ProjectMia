<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriaTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['nombre'=>'Calzado', 'disponible'=>1, 'activo'=>1],
            ['nombre'=>'Ternos', 'disponible'=>0, 'activo'=>1],
            ['nombre'=>'Camisas', 'disponible'=>1, 'activo'=>1],
            ['nombre'=>'Blusas', 'disponible'=>1, 'activo'=>1],
            ['nombre'=>'Carteras', 'disponible'=>1, 'activo'=>1],
            ['nombre'=>'Casacas de Cuero', 'disponible'=>0, 'activo'=>0],
            ['nombre'=>'Pantalones', 'disponible'=>1, 'activo'=>1]
        ];

        foreach($categories as $category) {
            if(Category::where('nombre', $category['nombre'])->count()) {
                continue;
            }
            Category::create($category);
        }
    }
}
