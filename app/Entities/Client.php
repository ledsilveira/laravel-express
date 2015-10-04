<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	//Campos que podem ser criados por array de dados pelo create
    protected $fillable = [
		'name',
		'responsible',
		'email',
		'phone',
		'address',
		'obs'
	];

	public function project()
	{
		return $this->hasMany(Project::class);
	}
	public function projectNotes()
	{
		return $this->hasManyThrough(ProjectNote::class,Project::class);
	}
	public function projectMembers()
	{
		return $this->hasManyThrough(ProjectMember::class,Project::class);
	}
}
