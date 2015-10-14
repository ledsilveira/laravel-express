<?php
/**
 * Created by PhpStorm.
 * User: LED
 * Date: 02/10/2015
 * Time: 00:19
 */

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectNote;
use League\Fractal\TransformerAbstract;

class ProjectNoteTransformer extends TransformerAbstract
{

    public function transform( ProjectNote $projectNote )
    {
        return [
            'id' =>$projectNote->id,
            'project_id' =>$projectNote->project_id,
            'title' =>$projectNote->title,
            'note' =>$projectNote->note,
        ];
    }
}