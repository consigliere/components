<?php

namespace Consigliere\Components\tests;

use Consigliere\Components\Facades\Component;

class ComponentFacadeTest extends BaseTestCase
{
    /** @test */
    public function it_resolves_the_component_facade()
    {
        $components = Component::all();

        $this->assertTrue(is_array($components));
    }
}
