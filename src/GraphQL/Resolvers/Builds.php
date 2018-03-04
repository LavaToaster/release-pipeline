<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Build;

class Builds extends SimpleList
{
    protected function getEntityClassName(): string
    {
        return Build::class;
    }
}
