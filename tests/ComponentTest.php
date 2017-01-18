<?php

namespace Consigliere\Components\tests;

use Consigliere\Components\Json;
use Consigliere\Components\Component;

class ComponentTest extends BaseTestCase
{
    /**
     * @var Component
     */
    private $component;

    public function setUp()
    {
        parent::setUp();
        $this->component = new Component($this->app, 'Recipe', __DIR__ . '/stubs/Recipe');
    }

    /** @test */
    public function it_gets_component_name()
    {
        $this->assertEquals('Recipe', $this->component->getName());
    }

    /** @test */
    public function it_gets_lowercase_component_name()
    {
        $this->assertEquals('recipe', $this->component->getLowerName());
    }

    /** @test */
    public function it_gets_studly_name()
    {
        $this->assertEquals('Recipe', $this->component->getName());
    }

    /** @test */
    public function it_gets_component_description()
    {
        $this->assertEquals('recipe component', $this->component->getDescription());
    }

    /** @test */
    public function it_gets_component_alias()
    {
        $this->assertEquals('recipe', $this->component->getAlias());
    }

    /** @test */
    public function it_gets_component_path()
    {
        $this->assertEquals(__DIR__ . '/stubs/Recipe', $this->component->getPath());
    }

    /** @test */
    public function it_loads_component_translations()
    {
        $this->component->boot();

        $this->assertEquals('Recipe', trans('recipe::recipes.title.recipes'));
    }

    /** @test */
    public function it_reads_component_json_files()
    {
        $jsonComponent = $this->component->json();
        $composerJson = $this->component->json('composer.json');

        $this->assertInstanceOf(Json::class, $jsonComponent);
        $this->assertEquals('0.1', $jsonComponent->get('version'));
        $this->assertInstanceOf(Json::class, $composerJson);
        $this->assertEquals('asgard-component', $composerJson->get('type'));
    }

    /** @test */
    public function it_reads_key_from_component_json_file_via_helper_method()
    {
        $this->assertEquals('Recipe', $this->component->get('name'));
        $this->assertEquals('0.1', $this->component->get('version'));
        $this->assertEquals('my default', $this->component->get('some-thing-non-there', 'my default'));
    }

    /** @test */
    public function it_reads_key_from_composer_json_file_via_helper_method()
    {
        $this->assertEquals('consigliere/recipe', $this->component->getComposerAttr('name'));
    }

    /** @test */
    public function it_casts_component_to_string()
    {
        $this->assertEquals('Recipe', (string) $this->component);
    }

    /** @test */
    public function it_component_status_check()
    {
        $this->assertTrue($this->component->isStatus(1));
        $this->assertFalse($this->component->isStatus(0));
    }

    /** @test */
    public function it_checks_component_enabled_status()
    {
        $this->assertTrue($this->component->enabled());
        $this->assertTrue($this->component->active());
        $this->assertFalse($this->component->notActive());
        $this->assertFalse($this->component->disabled());
    }
}
