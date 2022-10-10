<?php

declare(strict_types=1);

namespace App\Classes\Modules\StructuredData;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
class HandlesApiResponseData {
    public const ITEMS_PER_PAGE = 3;

    public const DEFAULT_PAGE_INDEX = 0;

    public const DEFAULT_SORT_BY = 'name';

    public const DEFAULT_SORT_DIRECTION = 'ASC';

    /** @var Manager */
    private Manager $manager;

    /**
     * HandlesApiResponseData constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager) {
        $this->manager = $manager;
    }

    public function returnOne(
        object $object,
        TransformerAbstract $transformer
    ): array {
        return $this->manager->createData(new Item($object, $transformer))->toArray();
    }

    public function returnMany(
        object $object,
        TransformerAbstract $transformer
    ): array {
        return $this->manager->createData(new Collection($object, $transformer))->toArray();
    }

    public function returnError(\Throwable $throwable, TransformerAbstract $transformer): array {
        return $this->manager->createData(new Item($throwable, $transformer, 'errors'))->toArray();
    }

    public function returnPaginated(
        LengthAwarePaginator $paginator,
        TransformerAbstract $transformer,
        ?string $resourceKey = null
    ): array {
        $resource = new Collection($paginator, $transformer, $resourceKey);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->manager->createData($resource)->toArray();
    }
}
