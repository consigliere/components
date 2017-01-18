<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class GenerateMiddlewareCommandTest extends BaseTestCase
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
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->componentPath);
        parent::tearDown();
    }

    /** @test */
    public function it_generates_a_new_middleware_class()
    {
        $this->artisan('component:make-middleware', ['name' => 'SomeMiddleware', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Http/Middleware/SomeMiddleware.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-middleware', ['name' => 'SomeMiddleware', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Http/Middleware/SomeMiddleware.php');

        $this->assertTrue(str_contains($file, 'class SomeMiddleware'));
        $this->assertTrue(str_contains($file, 'public function handle(Request $request, Closure $next)'));
    }
}
