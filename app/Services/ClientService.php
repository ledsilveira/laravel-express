<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 23/07/2015
 * Time: 02:33
 */

namespace CodeProject\Services;


use CodeProject\Validators\ClientValidator;
use CodeProject\Repositories\ClientRepository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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


    /**
     * @param ClientRepository $repository
     * @param ClientValidator $validator
     */
    public function __construct(ClientRepository $repository, ClientValidator $validator)
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
            $errorMsg = '['.$e->getCode().'] QueryException: Error to load data. Data not load!';
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
        }
        catch (ModelNotFoundException $e) {
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
            return $this->repository->with('project')->with('projectNotes')->find($id);
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
            $client = $this->repository->find($id);

            $client->projectNotes()->forceDelete();
            $client->projectMembers()->forceDelete();
            $client->project()->forceDelete();

            if( $client->delete() )
            {
                return [
                    'message' =>"Client: {$id} has been removed."
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
            dd($e);
            $errorMsg = '['.$e->getCode().'] QueryException: Error to remove data. Data not removed!';
            return [
                'error' => true,
                'message' => $errorMsg
            ];
        }
    }
}
