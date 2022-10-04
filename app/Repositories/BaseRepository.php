<?php

namespace App\Repositories;

use App\Repositories\Criteria\CriteriaDefinition;
use App\Repositories\Criteria\CriteriaParam;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $model;

    protected $query;

    protected $request;

    protected $filters;

    public $criterias = [];


    /**
     * @return EloquentQueryBuilder
     */
    protected function newQuery(): QueryBuilder
    {
        return app($this->model)->newQuery();
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        return app($this->model);
    }


    /**
     * @param int $take
     * @param bool $paginate
     * @return EloquentCollection|LengthAwarePaginator
     */
    protected function doQuery(int $take = 10, bool $paginate = true)
    {
        if (is_null($this->query)) {
            $this->query = $this->newQuery();
        }

        if ($paginate) {
            return $this->query->paginate($take);
        }
        return $this->query->get();
    }

    /**
     * Aplica os filtros necessário na listagem de projetos
     * @throws Exception
     */
    public function applyFilters()
    {
        $filters = $this->request->all();
        foreach ($this->filters::FILTERS as $key => $filter) {
            if (isset($filters[$key])) {
                $this->addCriteria(
                    $filter,
                    new CriteriaParam($key, $filters[$key], '=')
                );
            }
        }
    }

    /**
     * Adiciona os criterios de consulta
     * @param string $criteria
     * @param CriteriaParam $param
     * @throws  Exception
     */
    public function addCriteria(string $criteria, CriteriaParam $param)
    {
        if (!$this->query) {
            $this->query = $this->newQuery();
        }

        switch ($criteria) {
            case CriteriaDefinition::SET_WHERE_EQUAL:
                // todo: Colocar uma classe de validação
                if (is_null($param->value) or is_null($param->column)) {
                    // todo: Melhorar o tratamento de exceções deste tipo
                    throw new Exception('Property $column and $value is required to this CriteriaDefinition::SET_WHERE_EQUAL');
                }
                $search = explode('-', $param->column);
                $this->query->where(
                    $search[0], $param->value
                );
                break;
            case CriteriaDefinition::SET_WHERE:
                if (is_null($param->value) or is_null($param->column) or is_null($param->operation)) {
                    throw new Exception('Property $column, $value and $operation are required to this CriteriaDefinition::SET_WHERE');
                }
                $this->query->where(
                    $param->column, $param->operation, $param->value
                );
                break;
            case CriteriaDefinition::SET_WHERE_LIKE:
                $this->query->where(
                    $param->column, 'like', '%' . $param->value . '%'
                );
                break;
            case CriteriaDefinition::SET_OR_WHERE:
                if (is_null($param->value) or is_null($param->column) or is_null($param->operation)) {
                    throw new Exception('Property $column, $value and $operation are required to this CriteriaDefinition::SET_OR_WHERE');
                }

                $this->query->orWhere(
                    $param->column, $param->operation, $param->value
                );
                break;
            case CriteriaDefinition::SET_OR_WHERE_LIKE:
                $this->query->orWhere(
                    $param->column, 'like', '%' . $param->value . '%'
                );
                break;
            case CriteriaDefinition::SET_OR_WHERE_EQUAL:
                $this->query->orWhere(
                    $param->column, $param->value
                );
                break;
            case CriteriaDefinition::SET_ORDER_BY:
                $this->query->orderBy(
                    $param->column, $param->value
                );
                break;
            case CriteriaDefinition::SET_GROUP_BY:
                $this->query->groupBy(
                    $param->column
                );
                break;
            case CriteriaDefinition::SET_FIND_ONE:
                $this->getOne = true;
                $this->query->where(
                    CriteriaDefinition::FIX_ID, $param->value
                );
                break;
            case CriteriaDefinition::SET_WHERE_DATE:
                $this->query->whereDate(
                    $param->column, $param->operation, $param->value
                );
                break;
            case CriteriaDefinition::SET_WHERE_MONTH_YEAR:
                if ($param->value != "null") {
                    $e = explode('-', $param->value);
                    if (count($e) == 1) {
                        $year = $e[0];

                        $this->query->whereYear(
                            $param->column, '=', $year
                        );
                    }else {
                        $month = $e[1];
                        $year = $e[0];

                        $this->query->whereMonth(
                            $param->column, '=', $month
                        );
                        $this->query->whereYear(
                            $param->column, '=', $year
                        );
                    }

                }

                break;
            case CriteriaDefinition::SET_WHERE_BIGGER_DATE:
                $this->query->whereDate(
                    $param->column, '>', $param->value
                );
                break;
            case CriteriaDefinition::SET_WHERE_PERIOD_DATE:
                $period = explode('@', $param->value);
                if ($period[0] != 'null')
                    $this->query->whereDate(
                        $param->column, '>=', $period[0]
                    );
                $this->query->whereDate(
                    $param->column, '<=', $period[1]
                );
                break;
            case CriteriaDefinition::SET_RELATIONSHIP:
                $relation = explode('-', $param->column)[0];
                $column = explode('-', $param->column)[1];

                $this->query
                    ->whereHas($relation,
                        function (QueryBuilder $query) use ($column, $param) {
                            $query->where(
                                $column,
                                'like',
                                '%' . $param->value . '%'
                            );
                        }
                    );
                break;
            case CriteriaDefinition::CUSTOM_FILTER:
                // todo: pensar na lógica
                break;
            default:
                throw new \Exception('Unexpected value');
        }

    }

    /**
     * Retorna todos os dados.
     *
     * @param int $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     */
    public function getAll(int $take = 10, bool $paginate = true)
    {
        return $this->doQuery($take, $paginate);
    }

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param string $id
     * @param bool $fail
     * @return EloquentCollection
     */
    public function findByID(string $id, bool $fail = true)
    {
        if ($fail) {
            return $this->newQuery()->findOrFail($id);
        }

        return $this->newQuery()->find($id);
    }

}
