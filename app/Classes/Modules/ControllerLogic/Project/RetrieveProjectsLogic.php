<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\ProjectTransformer;
use App\Repositories\Eloquent\Projects;
use Illuminate\Http\JsonResponse;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectsLogic {
    /** @var Projects */
    private Projects $projects;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveProjectsLogic constructor.
     *
     * @param Projects               $projects
     * @param HandlesApiResponseData $handlesApiResponseData
     */
    public function __construct(
        Projects $projects,
        HandlesApiResponseData $handlesApiResponseData
    ) {
        $this->projects               = $projects;
        $this->handlesApiResponseData = $handlesApiResponseData;
    }

    /**
     * @return JsonResponse
     */
    public function execute(): JsonResponse {
        $projects = $this->projects->getAll();

        return new JsonResponse($this->handlesApiResponseData->returnMany($projects, new ProjectTransformer()));
    }
}
