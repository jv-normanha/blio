<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function store(array $data);

    public function listAll(int $take, bool $paginate);

    public function updateById(string $id, array $data);

    public function deleteById(string $id);

    public function getById(string $id);

}
