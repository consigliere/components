<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class GenerateListenerCommandTest extends BaseTestCase
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
    public function it_generates_a_new_event_class()
    {
        $this->artisan('component:make-listener',
            ['name' => 'NotifyUsersOfANewPost', 'component' => 'Blog', '--event' => 'UserWasCreated']);

        $this->assertTrue(is_file($this->componentPath . '/Events/Handlers/NotifyUsersOfANewPost.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-listener',
            ['name' => 'NotifyUsersOfANewPost', 'component' => 'Blog', '--event' => 'UserWasCreated']);

        $file = $this->finder->get($this->componentPath . '/Events/Handlers/NotifyUsersOfANewPost.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    private function expectedContent()
    {
        $event = '$event';

        return <<<TEXT
<?php

namespace Components\Blog\Events\Handlers;

use Components\Blog\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUsersOfANewPost
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Components\Blog\Events\UserWasCreated $event
     * @return void
     */
    public function handle(\Components\Blog\Events\UserWasCreated $event)
    {
        //
    }
}

TEXT;
    }
}
