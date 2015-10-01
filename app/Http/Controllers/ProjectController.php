<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

/**
 * Class ProjectController
 * @package CodeProject\Http\Controllers
 */
class ProjectController extends Controller
{

    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    /**
     * @param ProjectRepository $repository
     * @param ProjectService $service
     */
    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //return $this->repository->with('client')->with('user')->all();
        return $this->repository->with('client')->with('user')->with('members')->findWhere(['owner_id' => \Authorizer::getResourceOwnerId()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if( $this->checkProjectPermissions($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        return $this->repository->with('client')->with('user')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if( $this->checkProjectOwner($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        return $this->service->upddate($request->all(),$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if( $this->checkProjectOwner($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        $this->repository->find($id)->delete();
    }

    private function checkProjectOwner($projectId)
    {
        //Facade do Oauth pega o owner_id de quem está logado
        $userId = \Authorizer::getResourceOwnerId();
        //Usando o php artisan route:list que o parametro requeste nas chamdas de projetos
        // é o {project}, este é o nome que deve ser pego do request
        //$projectId = $request->project;
        return $this->repository->isOwner($projectId, $userId);
    }

    private function checkProjectMember($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userId);
    }

    private function checkProjectPermissions($projectId)
    {
        if( $this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
