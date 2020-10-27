<?php

use Illuminate\Database\Seeder;

class ComentariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comentarios')->insert([
            'produtos_id' => 1,
            'usuario' => 'João',
            'comentario' => 'Melhor produto que já comprei!!!!',
            'created_at' => date("Y/m/d h:i:s"),
            'updated_at' => date("Y/m/d h:i:s"),
        ]);
        DB::table('comentarios')->insert([
            'produtos_id' => 2,
            'usuario' => 'Maria',
            'comentario' => 'Melhor produto que já comprei!!!!',
            'created_at' => date("Y/m/d h:i:s"),
            'updated_at' => date("Y/m/d h:i:s"),
        ]);
    }
}
