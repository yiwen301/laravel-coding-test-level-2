<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData\Transformers;

use App\Models\Project;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class ProjectTransformer extends TransformerAbstract {
    /**
     * @param Project $project
     *
     * @return array
     * @throws \Exception
     */
    public function transform(Project $project): array {
        return [
            'id'             => $project->id,
            'name'           => $project->name,
            'owner_user_id'  => $project->owner_user_id,
            'owner_username' => $project->user->username,
            'status_id'      => $project->status_id,
            'status_name'    => $project->status->name ?? '',
            'remarks'        => $project->remarks,
            'created_at'     => (new Carbon($project->created_at))->toDateTimeString(),
            'updated_at'     => (new Carbon($project->updated_at))->toDateTimeString()
        ];
    }
}
