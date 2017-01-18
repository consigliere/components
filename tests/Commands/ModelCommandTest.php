<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class ModelCommandTest extends BaseTestCase
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
    public function it_generates_a_new_model_class()
    {
        $this->artisan('component:make-model', ['model' => 'Post', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Entities/Post.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-model', ['model' => 'Post', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Entities/Post.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    /** @test */
    public function it_generates_correct_fillable_fields()
    {
        $this->artisan('component:make-model', ['model' => 'Post', 'component' => 'Blog', '--fillable' => 'title,slug']);

        $file = $this->finder->get($this->componentPath . '/Entities/Post.php');

        $this->assertTrue(str_contains($file, "protected \$fillable = [\"title\",\"slug\"];"));
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected \$fillable = [];
}

TEXT;
    }
}
