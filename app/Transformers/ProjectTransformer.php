<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 02/10/2015
 * Time: 00:19
 */

namespace CodeProject\Transformers;

use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['members'];

    public function transform( Project $project )
    {
        return [
            'project_id' =>$project->id,
            'client_id' =>$project->client_id,
            'owner_id' =>$project->owner_id,
            'client' =>$project->client,
            //'user' =>$project->user,
            //'membros'=>$project->members,
            'name' =>$project->name,
            'description' =>$project->description,
            'progress' =>(int)$project->progress,
            'status' =>$project->status,
            'due_date' =>$project->due_date,
        ];
    }
    //usa o transformer do ProjectMember para mostrar os dados serializados
    public function includeMembers( Project $project )
    {
        return $this->collection($project->members, new ProjectMemberTransformer());
    }

   /* public function includeClient( Project $project )
    {
        return $this->c($project->client, new ClientTransformer());
    }*/
}