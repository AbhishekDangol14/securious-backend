<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all();
    public function store(array $data);
    public function update(array $data, int $model);
    public function delete(int $id, bool $force = false);
}
