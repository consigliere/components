<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\tests\BaseTestCase;

class GenerateJobCommandTest extends BaseTestCase
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
    public function it_generates_the_job_class()
    {
        $this->artisan('component:make-job', ['name' => 'SomeJob', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Jobs/SomeJob.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-job', ['name' => 'SomeJob', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Jobs/SomeJob.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class SomeJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}

TEXT;
    }
}
