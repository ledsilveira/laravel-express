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
use Mockery\CountValidator\Exception;
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
     * @param $projectId
     * @return mixed
     */
    public function checkProjectOwner($projectId)
    {
        //Facade do Oauth pega o owner_id de quem está logado
        $userId = \Authorizer::getResourceOwnerId();
        //Usando o php artisan route:list que o parametro requeste nas chamdas de projetos
        // é o {project}, este é o nome que deve ser pego do request
        //$projectId = $request->project;
        return $this->repository->isOwner($projectId, $userId);
    }

    /**
     * @param $projectId
     * @return mixed
     */
    public function checkProjectMember($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userId);
    }

    /**
     * @param $projectId
     * @return bool
     */
    public function checkProjectPermissions($projectId)
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
            $project = $this->repository->skipPresenter()->find($id);
            $project->members()->forceDelete();
            $project->notes()->forceDelete();
            $project->tasks()->forceDelete();
            //@todo apagar arquivos do servidor
            $project->files()->forceDelete();

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
     * @return array
     */
    public function addMember($id, $member_id)
    {
        try
        {
            //carrega projeto
            $project = $this->repository->skipPresenter()->find($id);
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
            //@todo trocar por usersRelation e chamar conforme o membersRelation
            $user = User::find($member_id);

            if( $user )
            {
                $project->membersRelation()->create(['project_id'=>$id, 'member_id'=>$member_id]);
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
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message' =>'Not Found.'
            ];
        } catch (QueryException $e) {
            dd($e);
            $errorMsg = '['.$e->getCode().'] QueryException: Error to add member!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }

    /**
     *
     * Remove membro de um determinado projeto
     *
     * @param $id
     * @param $member_id
     * @return array
     */
    public function removeMember($id, $member_id)
    {
        try
        {
            //carrega projeto
            $project = $this->repository->skipPresenter()->find($id);

            //verifica se membro estah no projeto
            if( $this->repository->hasMember($id, $member_id) )
            {
                //Apagar membro
                if($project->members()->detach($member_id))
                {
                    return [
                        'error' => false,
                        'message' =>'Member removed.'
                    ];
                }
                else
                {
                    return [
                        'error' => true,
                        'message' =>'Error ro remove member.'
                    ];
                }
            }
            else
            {
                return [
                    'error' => true,
                    'message' =>'this member is not in project.'
                ];
            }


        } catch (ModelNotFoundException $e) {
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

    /**
     * Verifica se membro faz parte de determinado projeto
     *
     * @param $id
     * @param $member_id
     * @return array
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

}
