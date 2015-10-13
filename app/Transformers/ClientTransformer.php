<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 02/10/2015
 * Time: 00:19
 */

namespace CodeProject\Transformers;

use CodeProject\Entities\Client;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{

    public function transform( Client $client )
    {
        return [
            'id' =>$client->id,
            'name' =>$client->name,
            'responsible' =>$client->responsible,
            'email' =>$client->email,
            'phone' =>$client->phone,
            'address' =>$client->address,
            'obs' =>$client->obs,
        ];
    }
}