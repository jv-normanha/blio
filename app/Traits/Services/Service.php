<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Traits\Services;

use App\Http\Resources\Errors\ErrorResource;

trait Service
{
    public function notFoundMessage()
    {
        return new ErrorResource(
            ['errors' => 'ID não encontrado']
        );
    }

    public function errorValidation(): ErrorResource
    {
        return new ErrorResource(['errors' => $this->validator->errors]);
    }

    public function showError($message): ErrorResource
    {
        return new ErrorResource(
            ['errors' => [
                'error' => [$message],
            ]]
        );
    }

    public function errorServer()
    {
        return $this->showError('Houve um problema no servidor');
    }
}
