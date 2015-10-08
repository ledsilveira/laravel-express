<?php

use Illuminate\Database\Seeder;

class ProjectTaskTableSeeder extends Seeder
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
       // \CodeProject\Entities\Project::truncate();
        factory(\CodeProject\Entities\ProjectTask::class, 50)->create();
    }
}
