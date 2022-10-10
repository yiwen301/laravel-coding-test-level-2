<?php

declare(strict_types=1);

namespace App\Classes\Modules\ControllerLogic\Project;

use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Classes\Services\Project\VerifiesProjectBelongsToProductOwner;
use App\Repositories\Eloquent\Projects;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UpdateProjectLogic {
    /** @var IdentifiesUserFromRequest */
    private IdentifiesUserFromRequest $identifiesUserFromRequest;

    /** @var VerifiesProjectBelongsToProductOwner */
    private VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner;

    /** @var Projects */
    private Projects $projects;

    /**
     * UpdateProjectLogic constructor.
     *
     * @param IdentifiesUserFromRequest            $identifiesUserFromRequest
     * @param VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner
     * @param Projects                             $projects
     */
    public function __construct(
        IdentifiesUserFromRequest $identifiesUserFromRequest,
        VerifiesProjectBelongsToProductOwner $verifiesProjectBelongsToProductOwner,
        Projects $projects
    ) {
        $this->identifiesUserFromRequest            = $identifiesUserFromRequest;
        $this->verifiesProjectBelongsToProductOwner = $verifiesProjectBelongsToProductOwner;
        $this->projects                             = $projects;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function execute(Request $request): JsonResponse {
        try {
            $user = $this->identifiesUserFromRequest->execute($request);

            $this->verifiesProjectBelongsToProductOwner->execute($request->route('project_id'), $user);

            $this->projects->updateWhere(['id' => $request->route('project_id')], [
                'name'      => $request->get('name'),
                'status_id' => $request->get('status_id'),
                'remarks'   => $request->get('remarks'),
            ]);

            return new JsonResponse();
        } catch (ModelNotFoundException $exception) {
            throw new ResourceNotFoundException(sprintf('Project id: %s does not exist.',
                $request->route('project_id')));
        }
    }
}
