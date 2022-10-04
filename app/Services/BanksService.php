<?php

namespace App\Services;

use App\Http\Validators\BanksValidator;
use App\Repositories\BanksRepository;
use App\Http\Resources\BanksResource;
use App\Http\Resources\Collections\BanksCollection;
use App\Traits\Services\Service;
use App\Http\Resources\Errors\ErrorResource;


class BanksService
{
    use Service;

    public $repository;
    public $validator;

    public function __construct(
        BanksRepository $repository,
        BanksValidator $validator
    )
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Recupera todos os dados do banco
     * @return BanksCollection
     */
    public function listAll()
    {
        $paginate = request()->get('paginate');
        $this->repository->applyFilters();

        if (!isset($paginate)) {
            $paginate = true;
        }
        return new BanksCollection(
            $this->repository->listAll(
                $this->request['take'] ?? 10,
                (bool) $paginate)
            );
    }

    /**
     * Salva um novo registro no banco
     * @param array $data
     */
    public function store(array $data)
    {
        if ($this->validator->validate($data))
            return new BanksResource(
                $this->repository->store($data)
            );

        return $this->errorValidation();

    }

    /**
     * Recupera um registro especifico
     *
     * @param string $id
     * @return BanksResource
     */
    public function getOne(string $id)
    {
        $model = $this->repository->getById($id);
        if ($model) {
            return new BanksResource($model);
        }
        return $this->showError('Registro não encontrado');

    }

    /**
     * Atualiza um registro especifico.
     *
     * @param string   $id
     * @param array $data
     * @return BanksResource|ErrorResource
     */
    public function update(array $data, string $id)
    {
        if ($this->repository->getById($id)) {
            if ($this->validator->validate($data))
                return new BanksResource(
                    $this->repository->updateById($id, $data)
                );

            return $this->errorValidation();
        }
        return $this->showError('Registro não encontrado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return BanksResource|ErrorResource
     */
    public function delete(string $id)
    {
        if ($this->repository->getById($id)) {
            return new BanksResource(
                $this->repository->deleteById($id)
            );
        }
        return $this->showError('Registro não encontrado');
    }
}
