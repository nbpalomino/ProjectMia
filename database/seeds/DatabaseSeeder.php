<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if( !User::where('email', 'nbpalomino@gmail.com')->count() ) {
            User::create([
                'nombre'=>'Nick'
                ,'apellidos'=>'Palomino'
                ,'email'=>'nbpalomino@gmail.com'
                ,'password'=> Hash::make('1234567u')
                ,'celular'=>'966463589'
            ]);
        }

        $this->call('CategoriaTableSeeder');
    }

}
