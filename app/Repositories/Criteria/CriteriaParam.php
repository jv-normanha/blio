<?php

namespace App\Repositories\Criteria;

/**
 * Class CriteriaParam
 * Define os parametros necessários para fazer as consultas.
 * @package App\Repositories\Criteria
 */
class CriteriaParam
{
    /**
     * @var string
     */
    public $column;

    /**
     * @var string|null
     */
    public $operation;

    public $value;

    public function __construct(
        string $column,
        string $value = null,
        string $operation = null)
    {
        $this->column = $column;
        $this->operation = $operation;
        $this->value = $value;
    }

}
