<?php

namespace App\Http\Filters;

use App\Repositories\Criteria\CriteriaDefinition;

class BanksFilter implements FilterInterface
{
    const FILTERS = [

    ];

    /**
     * @override
     * @return string[]
     */
    static public function getFilters(): array
    {
        return array(

        );
    }
}
