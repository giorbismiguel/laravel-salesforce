<?php

namespace Surge\LaravelSalesforce\Objects;

interface ObjectInterface
{
    public function create(array $params);

    public function update(string $id, array $params);

    public function delete(string $id);

    public function get(string $id, array $fields = []);
}
