<?php

namespace Components\Recipe\Events;

use Components\Media\Contracts\StoringMedia;

class RecipeWasCreated implements StoringMedia
{
    /**
     * @var
     */
    private $recipe;
    /**
     * @var
     */
    private $data;

    public function __construct($recipe, $data)
    {
        $this->recipe = $recipe;
        $this->data = $data;
    }

    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->recipe;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
