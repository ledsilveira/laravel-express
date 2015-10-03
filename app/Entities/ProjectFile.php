<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ProjectFile
 * @package CodeProject\Entities
 */
class ProjectFile extends Model implements  Transformable
{
    use TransformableTrait;

   // protected $table = 'project_files';
    //Campos que podem ser criados por array de dados pelo create
    protected $fillable = [
        'name',
        'description',
        'extension',
        'project_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
