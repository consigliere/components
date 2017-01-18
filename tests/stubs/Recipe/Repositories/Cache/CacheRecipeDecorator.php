<?php

namespace Components\Recipe\Repositories\Cache;

use Components\Core\Repositories\Cache\BaseCacheDecorator;
use Components\Recipe\Repositories\RecipeRepository;

class CacheRecipeDecorator extends BaseCacheDecorator implements RecipeRepository
{
    public function __construct(RecipeRepository $recipe)
    {
        parent::__construct();
        $this->entityName = 'recipe.recipes';
        $this->repository = $recipe;
    }
}
