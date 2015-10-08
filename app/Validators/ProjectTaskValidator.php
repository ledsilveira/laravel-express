<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 27/09/2015
 * Time: 21:58
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends LaravelValidator
{
    protected $rules = [
        'project_id' => 'required|integer',
        'name' => 'required',
        'status' => 'required|integer',
        'start_date' => 'required',
        'due_date' => 'required'
    ];

}