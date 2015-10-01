<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectMember extends Model implements  Transformable
{
    use TransformableTrait;
    //Campos que podem ser criados por array de dados pelo create
    protected $fillable = [
        'project_id',
        'member_id',
    ];

}
