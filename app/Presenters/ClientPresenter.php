<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 02/10/2015
 * Time: 00:50
 */

namespace CodeProject\Presenters;

use CodeProject\Transformers\ClientTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ClientPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new ClientTransformer();
    }
}