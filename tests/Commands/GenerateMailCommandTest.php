<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\tests\BaseTestCase;

class GenerateMailCommandTest extends BaseTestCase
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
    public function it_generates_the_mail_class()
    {
        $this->artisan('component:make-mail', ['name' => 'SomeMail', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Emails/SomeMail.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-mail', ['name' => 'SomeMail', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Emails/SomeMail.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return \$this
     */
    public function build()
    {
        return \$this->view('view.name');
    }
}

TEXT;
    }
}
