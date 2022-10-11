<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Classes\Modules\ControllerLogic\Task\PatchTaskLogic;
use App\Classes\Services\Authentication\IdentifiesUserFromRequest;
use App\Classes\Services\Task\DeterminesIfTeamMemberIsAvailable;
use App\Models\Task;
use App\Models\User;
use App\Models\UserRole;
use App\Repositories\Eloquent\Tasks;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Validation\UnauthorizedException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class UserChangeTaskStatusTest extends TestCase {
    /**
     * Test user change the status of a task assigned to themselves.
     *
     * @return void
     */
    public function testUserChangesTaskStatus(): void {
        $expectedUser = User::factory()->make([
            'id'      => Uuid::uuid(),
            'role_id' => UserRole::TEAM_MEMBER_ROLE
        ]);

        $task = Task::factory()->make([
            'id'      => Uuid::uuid(),
            'user_id' => $expectedUser->id,
            'status'  => Task::COMPLETED_STATUS
        ]);

        $request = new Request([
            'status' => Task::COMPLETED_STATUS
        ], [], [], [], [], ['REQUEST_URI' => 'api/v1/tasks/' . $task->id]);

        $request->setRouteResolver(function () use ($request) {
            return (new Route('PATCH', 'api/v1/tasks/{task_id}', []))->bind($request);
        });

        /** @var IdentifiesUserFromRequest|MockInterface $identifiesUserMock */
        $identifiesUserMock = Mockery::mock(IdentifiesUserFromRequest::class);
        $identifiesUserMock->shouldReceive('execute')->once()->with($request)->andReturn($expectedUser);

        /** @var DeterminesIfTeamMemberIsAvailable|MockInterface $determinesMock */
        $determinesMock = Mockery::mock(DeterminesIfTeamMemberIsAvailable::class);
        $determinesMock->shouldNotReceive('execute');

        /** @var Tasks|MockInterface $tasksMock */
        $tasksMock = Mockery::mock(Tasks::class);
        $tasksMock->shouldReceive('getById')->once()->with($task->id)->andReturn($task);
        $tasksMock->shouldReceive('updateWhere')->once()->with(Mockery::type('array'), Mockery::type('array'))
                  ->andReturn(true);

        $logic    = new PatchTaskLogic($identifiesUserMock, $determinesMock, $tasksMock);
        $response = $logic->execute($request);

        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::assertEquals(Task::COMPLETED_STATUS, $task->status);
    }

    /**
     * Test user change the status of a task assigned to others.
     *
     * @return void
     */
    public function testItThrowsUnauthorizedException(): void {
        $this->expectException(UnauthorizedException::class);

        $expectedUser = User::factory()->make([
            'id'      => Uuid::uuid(),
            'role_id' => UserRole::TEAM_MEMBER_ROLE
        ]);

        $task = Task::factory()->make([
            'id'      => Uuid::uuid(),
            'user_id' => Uuid::uuid(),
            'status'  => Task::COMPLETED_STATUS
        ]);

        $request = new Request([
            'status' => Task::COMPLETED_STATUS
        ], [], [], [], [], ['REQUEST_URI' => 'api/v1/tasks/' . $task->id]);

        $request->setRouteResolver(function () use ($request) {
            return (new Route('PATCH', 'api/v1/tasks/{task_id}', []))->bind($request);
        });

        /** @var IdentifiesUserFromRequest|MockInterface $identifiesUserMock */
        $identifiesUserMock = Mockery::mock(IdentifiesUserFromRequest::class);
        $identifiesUserMock->shouldReceive('execute')->once()->with($request)->andReturn($expectedUser);

        /** @var DeterminesIfTeamMemberIsAvailable|MockInterface $determinesMock */
        $determinesMock = Mockery::mock(DeterminesIfTeamMemberIsAvailable::class);
        $determinesMock->shouldNotReceive('execute');

        /** @var Tasks|MockInterface $tasksMock */
        $tasksMock = Mockery::mock(Tasks::class);
        $tasksMock->shouldReceive('getById')->once()->with($task->id)->andReturn($task);
        $tasksMock->shouldNotReceive('updateWhere');

        $logic = new PatchTaskLogic($identifiesUserMock, $determinesMock, $tasksMock);
        $logic->execute($request);
    }
}
