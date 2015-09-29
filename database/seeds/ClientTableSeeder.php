<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Limpa a tabela Client
		//cria a seeder para criar sempre 10 registro quando rodar
		//\CodeProject\Entities\Client::truncate();
		factory(\CodeProject\Entities\Client::class, 10)->create();
    }
}
