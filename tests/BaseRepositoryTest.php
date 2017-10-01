<?php

namespace Tests;

use \Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Helilabs\Capital\Repository\BaseRepository;

class BaseRepositoryTest extends TestCase
{

	public $model;

	public function setUp(){
		parent::setUp();

		$this->model = Mockery::mock(Model::class);
	}

	public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

	/** @test */
	function can_interact_with_eloquent_fetching_function()
	{

		$repo = new BaseRepository( $this->model );

		$expectedResults = Collection::make( [1, 2] );
		$this->model->shouldReceive('get')->once()->andReturn( $expectedResults );

		//get should execute on the model and return values from the model
		// if it can interacts with get then it can interacts with any fetching function.
		$results = $repo->get();

		$this->assertInstanceOf( Collection::class, $results);
		$this->assertEquals( $expectedResults->all(), $results->all() );

	}

	/** @test */
	function when_is_working_correctly()
	{
		$repo = new BaseRepository( $this->model );

		$called = false;

		$repo = $repo->when(true, function( $repo ) use( &$called ){
			$called = true;
		});

		$this->assertTrue($called, 'Callback should be called');
		$this->assertInstanceOf(BaseRepository::class, $repo);

		$called = false;

		$repo = $repo->when(false, function( $repo ) use( &$called ){
			$called = true;
		});

		$this->assertFalse($called, 'Callback should NOT be called');
		$this->assertInstanceOf(BaseRepository::class, $repo);
	}

	/** @test */
	function getAllModels_is_working_correctly()
	{
		$expectedResults = Collection::make([
				1 => 'Mohammed',
				2 => 'Manssour'
			]);

		$this->model->shouldReceive( 'pluck' )->andReturn($expectedResults);

		$repo = new BaseRepository( $this->model );

		$results = $repo->getAllModels();

		$this->assertInstanceOf(Collection::class, $results);
		$this->assertEquals( $expectedResults->all() , $results->all());

	}

}