<?php

declare(strict_types=1);

namespace App\Traits;

use App\Classes\Modules\StructuredData\HandlesApiResponseData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
trait PaginatesData {
    private function getPaginatorForCollection(Collection $collection, Request $request): LengthAwarePaginator {
        $itemsPerPage = $request->query('pageSize', HandlesApiResponseData::ITEMS_PER_PAGE);
        $currentPage  = (int)$request->query('pageIndex', HandlesApiResponseData::DEFAULT_PAGE_INDEX);

        return new LengthAwarePaginator($collection->forPage($currentPage, $itemsPerPage), $collection->count(),
            $itemsPerPage, $currentPage);
    }
}
