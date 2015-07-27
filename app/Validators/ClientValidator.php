<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 23/07/2015
 * Time: 02:42
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ClientValidator extends  LaravelValidator
{
    protected $rules = [
        'name' => 'required|max:255',
        'responsible' =>'required|max:255',
        'email' => 'required|email',
        'phone' =>'required',
        'address' => 'required'
     ];
}