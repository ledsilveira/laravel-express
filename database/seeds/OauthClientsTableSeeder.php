<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CodeProject\Entities\OauthClient::class)->create([
            'id' => '4aea09da27fdc71b8252d08b04c1fc0c6a5c7cd1',
            'secret' => 'avai',
            'name' => 'AngularAPP',
        ]);
    }
}
