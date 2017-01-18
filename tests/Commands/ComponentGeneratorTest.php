<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class ComponentGeneratorTest extends BaseTestCase
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
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->componentPath);
        if ($this->finder->isDirectory(base_path('components/ComponentName'))) {
            $this->finder->deleteDirectory(base_path('components/ComponentName'));
        }
        parent::tearDown();
    }

    /** @test */
    public function it_generates_component()
    {
        $code = $this->artisan('component:make', ['name' => ['Blog']]);

        $this->assertTrue(is_dir($this->componentPath));
        $this->assertSame(0, $code);
    }

    /** @test */
    public function it_generates_component_folders()
    {
        $this->artisan('component:make', ['name' => ['Blog']]);

        foreach (config('components.paths.generator') as $directory) {
            $this->assertTrue(is_dir($this->componentPath . '/' . $directory));
        }
    }

    /** @test */
    public function it_generates_component_files()
    {
        $this->artisan('component:make', ['name' => ['Blog']]);

        foreach (config('components.stubs.files') as $file) {
            $this->assertTrue(is_file($this->componentPath . '/' . $file));
        }
    }

    /** @test */
    public function it_generates_correct_composerjson_file()
    {
        $this->artisan('component:make', ['name' => ['Blog']]);

        $file = $this->finder->get($this->componentPath . '/composer.json');

        $this->assertEquals($this->getExpectedComposerJson(), $file);
    }

    /** @test */
    public function it_generates_component_folder_using_studly_case()
    {
        $this->artisan('component:make', ['name' => ['ComponentName']]);

        $this->assertTrue($this->finder->exists(base_path('components/ComponentName')));
    }

    /** @test */
    public function it_generates_component_namespace_using_studly_case()
    {
        $this->artisan('component:make', ['name' => ['ComponentName']]);

        $file = $this->finder->get(base_path('components/ComponentName') . '/Providers/ComponentNameServiceProvider.php');

        $this->assertTrue(str_contains($file, 'namespace App\Components\ComponentName\Providers;'));
    }

    private function getExpectedComposerJson()
    {
        return <<<TEXT
{
    "name": "consigliere/blog",
    "description": "",
    "authors": [
        {
            "name": "Nicolas Widart",
            "email": "n.widart@gmail.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "Components\\\Blog\\\": ""
        }
    }
}

TEXT;
    }
}
