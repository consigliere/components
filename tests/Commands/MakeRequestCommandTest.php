<?php

namespace Consigliere\Components\tests\Commands;

use Consigliere\Components\Tests\BaseTestCase;

class MakeRequestCommandTest extends BaseTestCase
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
    public function it_generates_a_new_form_request_class()
    {
        $this->artisan('component:make-request', ['name' => 'CreateBlogPostRequest', 'component' => 'Blog']);

        $this->assertTrue(is_file($this->componentPath . '/Http/Requests/CreateBlogPostRequest.php'));
    }

    /** @test */
    public function it_generated_correct_file_with_content()
    {
        $this->artisan('component:make-request', ['name' => 'CreateBlogPostRequest', 'component' => 'Blog']);

        $file = $this->finder->get($this->componentPath . '/Http/Requests/CreateBlogPostRequest.php');

        $this->assertEquals($this->expectedContent(), $file);
    }

    private function expectedContent()
    {
        return <<<TEXT
<?php

namespace Components\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

TEXT;
    }
}
