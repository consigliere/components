<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class GenerateRouteServiceProviderTest extends BaseTestCase
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
    public function it_generates_a_new_service_provider_class()
    {
        $this->artisan('component:route-provider', ['component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Providers/RouteServiceProvider.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:route-provider', ['component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Providers/RouteServiceProvider.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected \$rootUrlNamespace = 'Components\Blog\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @param  Router \$router
     * @return void
     */
    public function before(Router \$router)
    {
        //
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(Router \$router)
    {
        // require __DIR__ . '/../Http/routes.php';
    }
}

TEXT;
    }
}
