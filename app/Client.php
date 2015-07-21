<?php

namespace CodeProject;

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
}
