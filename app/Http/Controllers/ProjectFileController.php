<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Services\ProjectFileService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

/**
 * Class ProjectFileController
 * @package CodeProject\Http\Controllers
 */
class ProjectFileController extends Controller
{

    /**
     * @var ProjectFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;

    /**
     * @param ProjectFileRepository $repository
     * @param ProjectFileService $service
     */
    public function __construct(ProjectFileRepository $repository, ProjectFileService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return Response
     */
    public function index($id)
    {
        //return $this->repository->with('client')->with('user')->all();
        return $this->repository->findWhere(['project_id' => $id]);
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
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['project_id'] = $request->project_id;
        $data['description'] = $request->description;
        return $this->service->create($data);


    }

    public function showFile($id)
    {
        if( $this->service->checkProjectPermissions($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        $filePath = $this->service->getFilePath($id);
        $fileContent = file_get_contents($filePath);
        $file64 = base64_encode($fileContent);
        //essa funcão download inserir no response os bites do arquivo
        return [
            'file' => $file64,
            'size' => filesize($filePath),
            'name' => $this->service->getFileName($id)
        ];
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
        return $this->repository->find($id);
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
    public function destroy($id,$idFile)
    {
        if( $this->service->checkProjectOwner($id) == false )
        {
            return ['error'=>'access forbiden'];
        }
        $this->service->removeFile($id,$idFile);
    }
}
