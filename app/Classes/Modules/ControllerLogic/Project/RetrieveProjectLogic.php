<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use App\Classes\Modules\StructuredData\Transformers\ProjectTransformer;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class RetrieveProjectLogic {
    /** @var Projects */
    private Projects $projects;

    /** @var HandlesApiResponseData */
    private HandlesApiResponseData $handlesApiResponseData;

    /**
     * RetrieveProjectLogic constructor.
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
     * @param string $projectId
     *
     * @return JsonResponse
     */
    public function execute(string $projectId): JsonResponse {
        try {
            $project = $this->projects->getById($projectId);

            return new JsonResponse($this->handlesApiResponseData->returnOne($project, new ProjectTransformer()));
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.', $projectId));
        }
    }
}
