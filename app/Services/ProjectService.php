<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:55
 */

namespace CodeProject\Services;


use CodeProject\Entities\ProjectMember;
use CodeProject\Entities\User;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectService
 * @package CodeProject\Services
 */
class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;

    /**
     * @var ProjectValidator
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
     * @param ProjectValidator $validator
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator, Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    /**
     * @param array $data
     * @return array|mixed
     */
    public function create(array $data)
    {
        //validacoes podem ser em outros lugares, mas ficou no serviço junto as regras de negocio
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
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
            $errorMsg = '['.$e->getCode().'] QueryException: Error to save data. Data not save!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
        //enviar email
        //disparar notificacao

    }

    /**
     * @param array $data
     * @param $id
     * @return array|mixed
     */
    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();
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

    /**
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        try{
            return $this->repository->with('client')->with('user')->find($id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            $errorMsg = '['.$e->getCode().'] QueryException: Error to load data. Data not load!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        try{
            $project = $this->repository->find($id);
            $project->members()->forceDelete();
            $project->notes()->forceDelete();
            if( $project->delete() )
            {
                return [
                    'message' =>"Project: {$id} has been removed."
                ];
            }
            else
            {
                return [
                    'error' => true,
                    'message' =>'Error to remove Project.'
                ];
            }
            return $this->repository->find($id)->delete();
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            $errorMsg = '['.$e->getCode().'] QueryException: Error to remove data. Data not removed!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }

    /**
     * Adiciona membro a um determinado projeto
     *
     * @param $id
     * @param $member_id
     */
    public function addMember($id, $member_id)
    {
        //verifica se membro estah no projeto
        if( $this->repository->hasMember($id, $member_id) )
        {
            return [
                'error' => true,
                'message' =>'member is already part of the project.'
            ];
        }
        //verifica se user existe
        //caso nao adiciona o membro else erro
        $user = User::find($member_id);
        if( $user )
        {
            ProjectMember::create(['project_id'=>$id, 'member_id'=>$user->id]);
            return [
                'error' => false,
                'message' =>'User add to project.'
            ];
        }
        else
        {
            return [
                'error' => true,
                'message' =>'User not exists.'
            ];
        }
    }

    /**
     *
     * Remove membro de um determinado projeto
     *
     * @param $id
     * @param $member_id
     */
    public function removeMember($id, $member_id)
    {
        //verifica se membro estah no projeto
        if( $this->repository->hasMember($id, $member_id) )
        {
            //Apagar membro
            //ProjectMember::destroy();
        }
        else
        {
            return [
                'error' => true,
                'message' =>'this member is not in project.'
            ];
        }
    }

    /**
     * Verifica se membro faz parte de determinado projeto
     *
     * @param $id
     * @param $member_id
     */
    public function isMember($id, $member_id)
    {
        //verifica se membro esta assiciado ao projeto
        if( $this->repository->hasMember($id, $member_id) )
        {
            return [
                'error' => false,
                'message' =>'is member.'
            ];
        }
        else
        {
            return [
                'error' => true,
                'message' =>'not member.'
            ];
        }
    }

    public function createFile(array $data)
    {
        //skip no presenter para nao usar a entidade alterada pelo presenter
        $project = $this->repository->skipPresenter()->find($data['project_id']);
        $projectFile = $project->files()->create($data);

        $this->storage->put($projectFile->id."-".$data['name'].".".$data['extension'], $this->filesystem->get($data['file']));
    }
}
