<?php

namespace Surge\LaravelSalesforce\Objects;

interface ObjectInterface
{
    public function create();

    public function update();

    public function delete();
}
