<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\BanksResource;
use App\Http\Filters\BanksFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BanksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  $request
     * @return array|AnonymousResourceCollection
     */
    public function toArray($request)
    {
        return BanksResource::collection($this->collection);
    }

    public function with($request)
    {
        return [
            'filters' => BanksFilter::getFilters()
        ];
    }
}
