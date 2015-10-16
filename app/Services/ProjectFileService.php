<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:55
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectFileValidator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use League\Flysystem\FileNotFoundException;
use Mockery\CountValidator\Exception;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectFileService
 * @package CodeProject\Services
 */
class ProjectFileService
{
    /**
     * @var ProjectFileRepository
     */
    protected $repository;

    protected $projectRepository;

    /**
     * @var ProjectFileValidator
     */
    protected $validator;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @param ProjectRepository $repository
     * @param ProjectFileValidator $validator
     */
    public function __construct(ProjectFileRepository $repository,
                                ProjectRepository $projectRepository,
                                ProjectFileValidator $validator,
                                Filesystem $filesystem,
                                Storage $storage)
    {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    /**
     * @param $projectFileId
     * @return mixed
     */
    public function checkProjectOwner($projectFileId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;
        return $this->projectRepository->isOwner($projectId, $userId);
    }

    /**
     * @param $projectFileId
     * @return mixed
     */
    public function checkProjectMember($projectFileId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;
        return $this->projectRepository->hasMember($projectId, $userId);
    }

    /**
     * @param $projectFileId
     * @return bool
     */
    public function checkProjectPermissions($projectFileId)
    {
        if( $this->checkProjectOwner($projectFileId) || $this->checkProjectMember($projectFileId))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param array $data
     * @return array|mixed
     */
    public function create(array $data)
    {
        try{

            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            //skip no presenter para nao usar a entidade alterada pelo presenter
            $project = $this->projectRepository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            //$this->storage->put($projectFile->id."-".$data['name'].".".$data['extension'], $this->filesystem->get($data['file']));
            $this->storage->put($projectFile->getFileName(), $this->filesystem->get($data['file']));
            return $projectFile;
        }
        catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            $errorMsg = '['.$e->getCode().'] QueryException: Error to remove member!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        } catch(ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }

    }

    /**
     * @param array $data
     * @param $id
     * @return array|mixed
     */
    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            return $this->repository->update($data, $id);
        }
        catch(ValidatorException $e) {
            return [
                'error' => true,
                'message' =>$e->getMessageBag()
            ];

        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            $errorMsg = '['.$e->getCode().'] QueryException: Error to update data. Data not updated!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }

    public function getFilePath($id){
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $this->getBaseUrl($projectFile);
    }

    public function getBaseUrl($projectFile){
        switch ($this->storage->getDefaultDriver()) {
            case 'local':
                return $this->storage->getDriver()->getAdapter()->getPathPrefix()
                . '/' . $projectFile->getFileName();
        }

    }

    public function getFileName($id){
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $projectFile->getFileName();
    }

    public function delete($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        if($this->storage->exists($projectFile->getFileName())) {
            $this->storage->delete($projectFile->getFileName());
            return $projectFile->delete();
        }
    }
    /**
     * @param $id
     * @param $idFile
     * @return array
     */
    public function removeFile($id, $idFile)
    {
        try{

            //skip no presenter para nao usar a entidade alterada pelo presenter
            $project = $this->projectRepository->skipPresenter()->find($id);
            $projectFile = $project->files()->find($idFile);

            $deleted = $this->storage->delete($projectFile->getFileName());
            if( $deleted )
            {
                $projectFile->delete();
            }
        }
        catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            dd($e);
            $errorMsg = '['.$e->getCode().'] QueryException: Error to remove member!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }
}
