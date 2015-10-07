<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:55
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectTaskRepository;
use CodeProject\Validators\ProjectTaskValidator;

/**
 * Class ProjectTaskService
 * @package CodeProject\Services
 */
class ProjectTaskService
{
    /**
     * @var ProjectTaskRepository
     */
    protected $repository;

    /**
     * @var ProjectTaskValidator
     */
    protected $validator;

    /**
     * @param ProjectTaskRepository $repository
     * @param ProjectTaskValidator $validator
     */
    public function __construct(ProjectTaskRepository $repository, ProjectTaskValidator $validator)
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

        }
    }
}
