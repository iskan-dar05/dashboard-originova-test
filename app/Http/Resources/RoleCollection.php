<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
       function paginationInformation($request, $paginated, $default)
{
    $default['pagination']['current_page'] = $default['meta']['current_page'];
    $default['pagination']['last_page'] = $default['meta']['last_page'];
    $default['pagination']['per_page'] = $default['meta']['per_page'];
    $default['pagination']['total'] = $default['meta']['total'];

    unset($default['links']);
    unset($default['meta']);
    return $default;
}
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];    }
}
