<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class ControllerCommandTest extends BaseTestCase
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
    public function it_generates_a_new_controller_class()
    {
        $this->artisan('component:make-controller', ['controller' => 'MyController', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Http/Controllers/MyController.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-controller', ['controller' => 'MyController', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Http/Controllers/MyController.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    /** @test */
    public function it_appends_controller_to_name_if_not_present()
    {
        $this->artisan('component:make-controller', ['controller' => 'My', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Http/Controllers/MyController.php'));
    }

    /** @test */
    public function it_appends_controller_to_class_name_if_not_present()
    {
        $this->artisan('component:make-controller', ['controller' => 'My', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Http/Controllers/MyController.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    /** @test */
    public function it_generates_a_plain_controller()
    {
        $this->artisan('component:make-controller', [
            'controller' => 'MyController',
            'component' => 'Blog',
            '--plain' => true,
        ]);

        $file = $this->finder->get($this->componentPath . '/Http/Controllers/MyController.php');

        $this->assertEquals($this->expectedPlainContent(), $file);
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('blog::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('blog::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request \$request
     * @return Response
     */
    public function store(Request \$request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('blog::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request \$request
     * @return Response
     */
    public function update(Request \$request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}

TEXT;
    }

    private function expectedPlainContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Http\Controllers;

use Illuminate\Routing\Controller;

class MyController extends Controller
{
}

TEXT;
    }
}
