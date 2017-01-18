<?php

namespace Components\Recipe\Repositories\Eloquent;

use Components\Core\Repositories\Eloquent\EloquentBaseRepository;
use Components\Recipe\Events\RecipeWasCreated;
use Components\Recipe\Repositories\RecipeRepository;

class EloquentRecipeRepository extends EloquentBaseRepository implements RecipeRepository
{
    public function create($data)
    {
        $recipe = $this->model->create($data);

        event(new RecipeWasCreated($recipe, $data));

        return $recipe;
    }
}
