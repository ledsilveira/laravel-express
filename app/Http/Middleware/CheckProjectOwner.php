<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Repositories\ProjectRepository;

class CheckProjectOwner
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Facade do Oauth pega o owner_id de quem está logado
        $userId = \Authorizer::getResourceOwnerId();
        //Usando o php artisan route:list que o parametro requeste nas chamdas de projetos
        // é o {project}, este é o nome que deve ser pego do request
        $projectId = $request->project;
        if( $this->repository->isOwner($projectId, $userId) == false )
        {
            return ['error'=>'Access forbiden'];
        }
        return $next($request);
    }
}
