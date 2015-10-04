<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:55
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;

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
     * @param ProjectRepository $repository
     * @param ProjectValidator $validator
     */
    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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
}
