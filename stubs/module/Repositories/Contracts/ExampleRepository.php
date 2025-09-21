<?php

namespace Modules\Example\Repositories\Contracts;

interface ExampleRepository
{
    /** @return array<int, array{id:int,title:string}> */
    public function latest(int $limit = 5): array;
}
