<?php

namespace Consigliere\Components\tests\Commands;

use Illuminate\Support\Facades\Schema;
use Consigliere\Components\Repository;
use Consigliere\Components\Tests\BaseTestCase;

abstract class MigrateCommandTest extends BaseTestCase
{
    /**
     * @var Repository
     */
    private $repository;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;

    public function setUp()
    {
        parent::setUp();
        $this->repository = new Repository($this->app);
        $this->finder = $this->app['files'];
    }

    /** @test */
    public function it_migrates_a_component()
    {
        $this->repository->addLocation(__DIR__ . '/../stubs/Recipe');

        $this->artisan('component:migrate', ['component' => 'Recipe']);

        dd(Schema::hasTable('recipe__recipes'), $this->app['db']->table('recipe__recipes')->get());
    }
}
