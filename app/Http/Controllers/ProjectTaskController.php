<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Services\ProjectTaskService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

/**
 * Class ProjectTaskController
 * @package CodeProject\Http\Controllers
 */
class ProjectTaskController extends Controller
{

    /**
     * @var ProjectTaskRepository
     */
    private $repository;

    /**
     * @var ProjectTaskService
     */
    private $service;

    /**
     * @param ProjectTaskRepository $repository
     * @param ProjectTaskService $service
     */
    public function __construct(ProjectTaskRepository $repository, ProjectTaskService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id'=>$id]);
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
     * @param $id
     * @param $TaskId
     * @return mixed
     */
    public function show($id, $TaskId)
    {
        return $this->repository->findWhere(['project_id'=>$id, 'id'=>$TaskId]);
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
     * @param Request $request
     * @param $id
     * @param $TaskId
     * @return array|mixed
     */
    public function update(Request $request, $id, $TaskId)
    {
        return $this->service->update($request->all(),$TaskId);
    }


    /**
     * @param $id
     * @param $TaskId
     */
    public function destroy($id, $TaskId)
    {
        $this->repository->delete($TaskId);
    }
}
