<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use Mockery\CountValidator\Exception;

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
        if( $this->service->checkProjectPermissions($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        return $this->service->find($id);
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
        if( $this->service->checkProjectOwner($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        return $this->service->update($request->all(),$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if( $this->service->checkProjectOwner($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        return $this->service->delete($id);
    }

    public function addMember(Request $request,$projectId)
    {
        $memberId = $request->member_id;
        return $this->service->addMember($projectId,$memberId);
    }

    public function removeMember($projectId,$memberId)
    {
        return $this->service->removeMember($projectId,$memberId);
    }

    public function isMember($projectId,$memberId)
    {
        return $this->service->isMember($projectId,$memberId);
    }

    public function members($projectId)
    {
        //@todo implementar, pegar a lista de membros para este projeto
    }
}
