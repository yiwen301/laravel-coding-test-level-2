<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Classes\Modules\ControllerLogic\Project\CreateProjectLogic;
use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectStatus;
use App\Models\User;
use App\Models\UserRole;
use App\Repositories\Eloquent\ProjectMembers;
use App\Repositories\Eloquent\Projects;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Tests\TestCase;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class CreateProjectLogicTest extends TestCase {
    /**
     * Test creating a project and assign 2 members to it.
     *
     * @return void
     */
    public function testItCreatesProjectAndAssignMembers(): void {
        $productOwner = User::factory()->make([
            'id'      => Uuid::uuid(),
            'role_id' => UserRole::PRODUCT_OWNER_ROLE
        ]);
        $firstMember  = User::factory()->make([
            'id' => Uuid::uuid()
        ]);
        $secondMember = User::factory()->make([
            'id' => Uuid::uuid()
        ]);

        $request = new Request([
            'name'      => 'ACN - Laravel Test',
            'status_id' => ProjectStatus::NEW_STATUS,
            'members'   => [
                $firstMember->id,
                $secondMember->id
            ],
            'remark'    => 'New Project'
        ]);

        $projectId = Uuid::uuid();

        $expectedProject = Project::factory()->make([
            'id'            => $projectId,
            'name'          => $request->get('name'),
            'owner_user_id' => $productOwner->id,
            'status_id'     => ProjectStatus::NEW_STATUS,
            'remark'        => $request->get('remark')
        ]);

        $firstExpectedProjectMember  = ProjectMember::factory()->make([
            'user_id'    => $firstMember->id,
            'project_id' => $projectId
        ]);
        $secondExpectedProjectMember = ProjectMember::factory()->make([
            'user_id'    => $secondMember->id,
            'project_id' => $projectId
        ]);

        /** @var IdentifiesUserFromRequest|MockInterface $identifiesUserMock */
        $identifiesUserMock = Mockery::mock(IdentifiesUserFromRequest::class);
        $identifiesUserMock->shouldReceive('execute')->once()->with($request)->andReturn($productOwner);

        /** @var Projects|MockInterface $projectsMock */
        $projectsMock = Mockery::mock(Projects::class);
        $projectsMock->shouldReceive('findBy')->once()->with('name', $request->get('name'))
                     ->andThrow(new ModelNotFoundException());
        $projectsMock->shouldReceive('create')->once()->with(Mockery::type('array'))->andReturn($expectedProject);

        /** @var ProjectMembers|MockInterface $projectMembersMock */
        $projectMembersMock = Mockery::mock(ProjectMembers::class);
        $projectMembersMock->shouldReceive('create')->once()->with(Mockery::type('array'))
                           ->andReturn($firstExpectedProjectMember);
        $projectMembersMock->shouldReceive('create')->once()->with(Mockery::type('array'))
                           ->andReturn($secondExpectedProjectMember);

        $logic    = new CreateProjectLogic($identifiesUserMock, $projectsMock, $projectMembersMock);
        $response = $logic->execute($request);

        Assert::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Test it throws exception if the project name exists.
     */
    public function testItThrowsConflictHttpException(): void {
        $this->expectException(ConflictHttpException::class);

        $productOwner = User::factory()->make([
            'id'      => Uuid::uuid(),
            'role_id' => UserRole::PRODUCT_OWNER_ROLE
        ]);
        $firstMember  = User::factory()->make([
            'id' => Uuid::uuid()
        ]);
        $secondMember = User::factory()->make([
            'id' => Uuid::uuid()
        ]);

        $request = new Request([
            'name'      => 'ACN - Laravel Test',
            'status_id' => ProjectStatus::NEW_STATUS,
            'members'   => [
                $firstMember->id,
                $secondMember->id
            ],
            'remark'    => 'New Project'
        ]);

        $projectId = Uuid::uuid();

        $expectedProject = Project::factory()->make([
            'id'            => $projectId,
            'name'          => $request->get('name'),
            'owner_user_id' => $productOwner->id,
            'status_id'     => ProjectStatus::NEW_STATUS,
            'remark'        => $request->get('remark')
        ]);

        /** @var IdentifiesUserFromRequest|MockInterface $identifiesUserMock */
        $identifiesUserMock = Mockery::mock(IdentifiesUserFromRequest::class);
        $identifiesUserMock->shouldReceive('execute')->once()->with($request)->andReturn($productOwner);

        /** @var Projects|MockInterface $projectsMock */
        $projectsMock = Mockery::mock(Projects::class);
        $projectsMock->shouldReceive('findBy')->once()->with('name', $request->get('name'))
                     ->andReturn($expectedProject);
        $projectsMock->shouldNotReceive('create');

        /** @var ProjectMembers|MockInterface $projectMembersMock */
        $projectMembersMock = Mockery::mock(ProjectMembers::class);
        $projectMembersMock->shouldNotReceive('create');
        $projectMembersMock->shouldNotReceive('create');

        $logic = new CreateProjectLogic($identifiesUserMock, $projectsMock, $projectMembersMock);
        $logic->execute($request);
    }
}
