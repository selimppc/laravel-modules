<?php

namespace Modules\Example\Repositories;

use Modules\Example\Repositories\Contracts\ExampleRepository;

final class EloquentExampleRepository implements ExampleRepository
{
    public function latest(int $limit = 5): array
    {
        return array_map(
            fn ($i) => ['id' => $i, 'title' => "Item #{$i}"],
            range(1, $limit)
        );
    }
}
