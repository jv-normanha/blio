<?php

namespace App\Repositories;

use App\Models\Banks;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Filters\BanksFilter;

class BanksRepository extends BaseRepository implements RepositoryInterface
{

    public function __construct(
        Request $request,
        BanksFilter $filter
    )
    {
        $this->model = Banks::class;
        $this->query = $this->newQuery();
        $this->request = $request;
        $this->filters = $filter;
    }

    /**
    * Cria um novo registro no banco
    * @param    array   $data
    *
    */
    public function store(array $data)
    {
        return $this->query->create($data);
    }

    /**
    * Recupera todos os registros do banco de dados
     * @param int $take
     * @param bool $paginate
     * @return LengthAwarePaginator|Collection
     */
    public function listAll(int $take, bool $paginate)
    {
        return $this->doQuery($take, $paginate);
    }

    /**
    * Busca e atualiza um registro atraves do ID
    * @param    string     $id
    * @param    array   $data
    * @return Model
    */
    public function updateById(string $id, array $data)
    {
        $model = $this->query->find($id);
        $model->update($data);
        return $model;
    }

    /**
    * Busca e exclui um registro atraves do ID
    * @param    string     $id
    */
    public function deleteById(string $id)
    {
        $model = $this->query->find($id);
        $model->delete();
        return $model;
    }

    /**
    * Busca um registro atraves do ID
    * @param    string     $id
    *
    */
    public function getById(string $id)
    {
        return $this->query->find($id);
    }
}
