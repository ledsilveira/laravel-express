<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 23/07/2015
 * Time: 02:33
 */

namespace CodeProject\Services;


use CodeProject\ClientValidator\ClientValidator;
use CodeProject\Repositories\ClientRepository;
use Illuminate\Contracts\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * @var ClientValidator
     */
    protected $validator;


    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        //validações podem ser em outros lugares, mas ficou no serviço junto as regras de negócio
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
        //disparar notificação

    }

    public function upddate(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        }
        catch(ValidatorException $e) {
            return [
                'error' => true,
                'message' =>$e->getMessage()
            ];

        }
    }
}