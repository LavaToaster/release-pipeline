<?php

namespace App\GraphQL\Resolvers;

use App\Entity\Project;

class Projects extends SimpleList
{
    protected function getEntityClassName(): string
    {
        return Project::class;
    }
}
