<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 23/07/2015
 * Time: 01:23
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Client;
use CodeProject\Presenters\ClientPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    protected $fieldSearchable = [
        'name'
    ];

    public function model()
    {
        return Client::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }


    public function presenter()
    {
        return ClientPresenter::class;
    }

}