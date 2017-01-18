<?php

namespace Consigliere\Components\tests;

use Illuminate\Filesystem\Filesystem;
use Consigliere\Components\Collection;
use Consigliere\Components\Exceptions\ComponentNotFoundException;
use Consigliere\Components\Component;
use Consigliere\Components\Repository;

class RepositoryTest extends BaseTestCase
{
    /**
     * @var Repository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new Repository($this->app);
    }

    /** @test */
    public function it_adds_location_to_paths()
    {
        $this->repository->addLocation('some/path');
        $this->repository->addPath('some/other/path');

        $paths = $this->repository->getPaths();
        $this->assertCount(2, $paths);
        $this->assertEquals('some/path', $paths[0]);
        $this->assertEquals('some/other/path', $paths[1]);
    }

    /** @test */
    public function it_returns_a_collection()
    {
        $this->repository->addLocation(__DIR__ . '/stubs');

        $this->assertInstanceOf(Collection::class, $this->repository->toCollection());
        $this->assertInstanceOf(Collection::class, $this->repository->collections());
    }

    /** @test */
    public function it_returns_all_enabled_components()
    {
        $this->repository->addLocation(__DIR__ . '/stubs');

        $this->assertCount(1, $this->repository->getByStatus(1));
        $this->assertCount(1, $this->repository->enabled());
    }

    /** @test */
    public function it_returns_all_disabled_components()
    {
        $this->repository->addLocation(__DIR__ . '/stubs');

        $this->assertCount(0, $this->repository->getByStatus(0));
        $this->assertCount(0, $this->repository->disabled());
    }

    /** @test */
    public function it_counts_all_components()
    {
        $this->repository->addLocation(__DIR__ . '/stubs');

        $this->assertEquals(1, $this->repository->count());
    }

    /** @test */
    public function it_finds_a_component()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->assertInstanceOf(Component::class, $this->repository->find('recipe'));
        $this->assertInstanceOf(Component::class, $this->repository->get('recipe'));
    }

    /** @test */
    public function it_find_or_fail_throws_exception_if_component_not_found()
    {
        $this->setExpectedException(ComponentNotFoundException::class);

        $this->repository->findOrFail('something');
    }

    /** @test */
    public function it_finds_the_component_asset_path()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');
        $assetPath = $this->repository->assetPath('recipe');

        $this->assertEquals(public_path('components/recipe'), $assetPath);
    }

    /** @test */
    public function it_gets_the_used_storage_path()
    {
        $path = $this->repository->getUsedStoragePath();

        $this->assertEquals(storage_path('app/components/components.used'), $path);
    }

    /** @test */
    public function it_sets_used_component()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->repository->setUsed('Recipe');

        $this->assertEquals('Recipe', $this->repository->getUsed());
        $this->assertEquals('Recipe', $this->repository->getUsedNow());
    }

    /** @test */
    public function it_returns_laravel_filesystem()
    {
        $this->assertInstanceOf(Filesystem::class, $this->repository->getFiles());
    }

    /** @test */
    public function it_gets_the_assets_path()
    {
        $this->assertEquals(public_path('components'), $this->repository->getAssetsPath());
    }

    /** @test */
    public function it_gets_a_specific_component_asset()
    {
        $path = $this->repository->asset('recipe:test.js');

        $this->assertEquals('//localhost/components/recipe/test.js', $path);
    }

    /** @test */
    public function it_can_detect_if_component_is_active()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->assertTrue($this->repository->active('Recipe'));
    }

    /** @test */
    public function it_can_detect_if_component_is_inactive()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->assertFalse($this->repository->notActive('Recipe'));
    }

    /** @test */
    public function it_can_get_and_set_the_stubs_path()
    {
        $this->repository->setStubPath('some/stub/path');

        $this->assertEquals('some/stub/path', $this->repository->getStubPath());
    }

    /** @test */
    public function it_gets_the_configured_stubs_path_if_enabled()
    {
        $this->app['config']->set('components.stubs.enabled', true);

        $this->assertEquals(base_path('vendor/consigliere/components/src/Commands/stubs'), $this->repository->getStubPath());
    }

    /** @test */
    public function it_returns_default_stub_path()
    {
        $this->assertNull($this->repository->getStubPath());
    }

    /** @test */
    public function it_can_disabled_a_component()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->repository->disable('Recipe');

        $this->assertTrue($this->repository->notActive('Recipe'));
    }

    /** @test */
    public function it_can_enable_a_component()
    {
        $this->repository->addLocation(__DIR__ . '/stubs/Recipe');

        $this->repository->enable('Recipe');

        $this->assertTrue($this->repository->active('Recipe'));
    }

    /** @test */
    public function it_can_delete_a_component()
    {
        $this->artisan('component:make', ['name' => ['Blog']]);

        $this->repository->delete('Blog');

        $this->assertFalse(is_dir(base_path('components/Blog')));
    }
}
