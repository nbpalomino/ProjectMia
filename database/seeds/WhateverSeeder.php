<?php

use Illuminate\Database\Seeder;

class WhateverSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            ['nombre'=>'Hombre']
            ,['nombre'=>'Mujer']
            ,['nombre'=>'Unisex']
            ,['nombre'=>'Niños']
        ];

        $brands = [
            ['nombre'=>'Calimod']
            ,['nombre'=>'Catterpillar']
            ,['nombre'=>'Nike']
            ,['nombre'=>'Adidas']
            ,['nombre'=>'Reebok']
        ];

        $types = [
            ['nombre'=>'Zapato']
            ,['nombre'=>'Sandalia']
            ,['nombre'=>'Zapatilla']
            ,['nombre'=>'Pantalón']
            ,['nombre'=>'Bermuda']
            ,['nombre'=>'Camisa']
            ,['nombre'=>'Billetera']
            ,['nombre'=>'Polo']
        ];

        $util = [
            ['class' => 'App\Genre', 'elems'=>$genres]
            ,['class' => 'App\Brand', 'elems'=>$brands]
            ,['class' => 'App\Type', 'elems'=>$types]
        ];

        foreach($util as $item) {

            foreach($item['elems'] as $element) {
                if($item['class']::where('nombre', $element['nombre'])->count()) {
                    continue;
                }

                $item['class']::create($element);
            }
        }
    }
}
