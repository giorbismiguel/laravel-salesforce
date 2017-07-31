<?php

namespace Surge\LaravelSalesforce\Objects;

interface ObjectInterface
{
    public function create(array $params);

    public function update(string $id, array $params);

    public function delete(string $type, string $id);
}
