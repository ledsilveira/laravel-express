<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:55
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ProjectNoteService
 * @package CodeProject\Services
 */
class ProjectNoteService
{
    /**
     * @var ProjectNoteRepository
     */
    protected $repository;

    /**
     * @var ProjectNoteValidator
     */
    protected $validator;

    /**
     * @param ProjectNoteRepository $repository
     * @param ProjectNoteValidator $validator
     */
    public function __construct(ProjectNoteRepository $repository, ProjectNoteValidator $validator)
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
    public function find($id, $noteId)
    {
        try{
            return $this->repository->find($noteId);
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
}
