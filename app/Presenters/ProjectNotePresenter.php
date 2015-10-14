<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 02/10/2015
 * Time: 00:50
 */

namespace CodeProject\Presenters;

use CodeProject\Transformers\ProjectNoteTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

class ProjectNotePresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new ProjectNoteTransformer();
    }
}