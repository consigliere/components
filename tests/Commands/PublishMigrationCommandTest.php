<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class PublishMigrationCommandTest extends BaseTestCase
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;
    /**
     * @var string
     */
    private $componentPath;

    public function setUp()
    {
        parent::setUp();
        $this->componentPath = base_path('components/Blog');
        $this->finder = $this->app['files'];
        $this->artisan('component:make', ['name' => ['Blog']]);
        $this->artisan('component:make-migration', ['name' => 'create_posts_table', 'component' => 'Blog']);
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->componentPath);
        $this->finder->delete($this->finder->allFiles(base_path('database/migrations')));
        parent::tearDown();
    }

    /** @test */
    public function it_publishes_component_migrations()
    {
        $this->artisan('component:publish-migration', ['component' => 'Blog']);

        $files = $this->finder->allFiles(base_path('database/migrations'));

        $this->assertCount(1, $files);
    }
}
