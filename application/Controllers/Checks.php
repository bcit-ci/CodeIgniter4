<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use Config\Database;
use Tests\Support\Models\JobModel;

class Checks extends Controller
{
	use ResponseTrait;

	public function index()
	{
		session()->start();
	}


	public function escape()
	{
		$db = Database::connect();
		$db->initialize();

		$jobs = $db->table('job')
						 ->whereNotIn('name', ['Politician', 'Accountant'])
						 ->get()
						 ->getResult();

		die(var_dump($jobs));
	}

	public function password()
	{
		$db = Database::connect();
		$db->initialize();

		$result = $db->table('misc')
					->insert([
						'key' => 'password',
						'value' => '$2y$10$ErQlCj/Mo10il.FthAm0WOjYdf3chZEGPFqaPzjqOX2aj2uYf5Ihq'
					]);

		die(var_dump($result));
	}


	public function forms()
	{
		helper('form');

		var_dump(form_open());
	}

	public function api()
	{
		$data = array(
			"total_users" => 3,
			"users" => array(
				array(
					"id" => 1,
					"name" => "Nitya",
					"address" => array(
						"country" => "India",
						"city" => "Kolkata",
						"zip" => 700102,
					)
				),
				array(
					"id" => 2,
					"name" => "John",
					"address" => array(
						"country" => "USA",
						"city" => "Newyork",
						"zip" => "NY1234",
					)
				),
				array(
					"id" => 3,
					"name" => "Viktor",
					"address" => array(
						"country" => "Australia",
						"city" => "Sydney",
						"zip" => 123456,
					)
				),
			)
		);

		return $this->respond($data);
	}

	public function db()
	{
		$db = Database::connect();
		$db->initialize();

		$query = $db->prepare(function($db){
			return $db->table('user')->insert([
				'name' => 'a',
				'email' => 'b@example.com',
				'country' => 'x'
			]);
		});

		$query->execute('foo', 'foo@example.com', 'US');
	}

	public function format()
	{
		echo '<pre>';
		var_dump($this->response->getHeaderLine('content-type'));
	}

	public function model()
	{
	    $model = new class() extends Model {
	        protected $table = 'job';
        };

	    $results = $model->findAll();

	    $developer = $model->findWhere('name', 'Developer');

	    $politician = $model->find(3);

	}

    public function curl()
    {
        $client = Services::curlrequest([
            'debug' => true,
            'follow_redirects' => true,
            'json' => ['foo' => 'bar']
        ]);

        echo '<pre>';
        $response = $client->request('PUT', 'http://ci4.dev/checks/catch');
        echo $response->getBody();
    }

    // Simply echos back what's given in the body.
    public function catch()
    {
        $body = print_r($this->request->getRawInput(), true);
        echo $body;
    }

	public function redirect()
	{
		redirect('/checks/model');
    }

	public function image()
	{
		$info = Services::image('imagick')
			->withFile("/Users/kilishan/Documents/BobHeader.jpg")
			->getFile()
			->getProperties(true);

		dd(ENVIRONMENT);

		$images = Services::image('imagick')
			->getVersion();
//			->withFile("/Users/kilishan/Documents/BobHeader.jpg")
//			->resize(500, 100, true)
//			->crop(200, 75, 20, 0, false)
//			->rotate(90)
//			->save('/Users/kilishan/temp.jpg');

//		$images = Services::image('imagick')
//			->withFile("/Users/kilishan/Documents/BobHeader.jpg")
//			->fit(500, 100, 'bottom-left')
//			->text('Bob is Back!', [
//				'fontPath'  => '/Users/kilishan/Downloads/Calibri.ttf',
//				'fontSize' => 40,
//				'padding' => 0,
//				'opacity'   => 0.5,
//				'vAlign'    => 'top',
//				'hAlign'    => 'right',
//				'withShadow' => true,
//			])
//			->save('/Users/kilishan/temp.jpg', 100);


		ddd($images);
	}

	public function time()
	{
		$time = new Time();

		echo($time);
		echo '<br/>';
		echo Time::now();
		echo '<br/>';
		echo Time::parse('First Monday of December');
		echo '<br/>';

		$time = new Time('Next Monday');
		die($time);
	}


}
