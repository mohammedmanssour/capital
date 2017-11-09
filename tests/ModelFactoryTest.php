<?php

namespace Tests;

use \Mockery;
use \Exception;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Helilabs\Capital\Factory\ModelFactory;
use Helilabs\Capital\Helpers\CallbackHandler;

Class ModelFactoryTest extends TestCase{

	public $factory;

	public function setUp(){
		parent::setUp();
		$this->newFactory();
	}

	public function newFactory(){
		$this->factory = $this->getMockForAbstractClass(ModelFactory::class);
		$this->factory->expects($this->any())
			 ->method('theJob')
			 ->will($this->returnValue(TRUE));
	}

	/** @test */
	function can_add_arg()
	{

		$this->factory->addArg('name','Mohammed')->addArg('age',25);

		$this->assertEquals([
				'name' => 'Mohammed',
				'age' => 25
			], $this->factory->args->all());
	}

	/** @test */
	function can_merge_args()
	{

		$this->factory->addArg('name','Mohammed')
						->addArg('age',25)
						->setArgs(['job' => 'Software Engineer']);

		$this->assertEquals([
				'name' => 'Mohammed',
				'age' => 25,
				'job' => 'Software Engineer'
			], $this->factory->args->all());
	}

	/** @test */
	public function ensure_that_add_arg_has_higher_priority_to_keep_args_than_set_args(){

		$this->factory->addArg('name', 'Mohammed')->setArgs([
				'name' => 'Jhon',
				'age' => 25
			]);


		$this->assertEquals([
				'name' => 'Mohammed',
				'age' => 25
			], $this->factory->args->all());

	}

	/** @test */
	function can_add_additional_args()
	{
		$this->factory->addAdditionalArg('name','Mohammed')
					->addAdditionalArg('age',25);

		$this->assertEquals([
				'name' => 'Mohammed',
				'age' => 25,
				'action' => 'new',
				'id' => null
			], $this->factory->additionalArgs->all());
	}

	/** @test */
	public function can_merge_additional_args(){
		$this->factory->addAdditionalArg('name','Mohammed')
					->addAdditionalArg('age',25)
					->setAdditionalArgs([
						'action' => 'edit',
						'id' => 1
					]);

		$this->assertEquals([
				'name' => 'Mohammed',
				'age' => 25,
				'action' => 'edit',
				'id' => 1
			], $this->factory->additionalArgs->all());
	}

	/** @test */
	public function ensure_that_setAdditionalArgs_has_higher_priority_than_addArg()
	{
	    $this->factory->addAdditionalArg('name','Mohammed')
					->addAdditionalArg('age',25)
					->setAdditionalArgs([
						'name' => 'Jhon',
						'age' => 27,
						'id' => 1
					]);

		$this->assertEquals([
				'name' => 'Jhon',
				'age' => 27,
				'action' => 'new',
				'id' => 1
			], $this->factory->additionalArgs->all());
	}

	/** @test */
	public function can_set_get_model(){
		$model = Mockery::mock( Model::class );
		$this->factory->setModel( $model );

		$this->assertSame( $model, $this->factory->getModel() );
		$this->assertInstanceOf(Model::class, $this->factory->getModel());
	}

	/** @test */
	public function can_set_get_handlers(){

		$handler = new CallbackHandler;

		$this->factory->setSuccessHandler( $handler );

		$this->assertSame($handler, $this->factory->getSuccessHandler());
		$this->assertInstanceOf(CallbackHandler::class, $this->factory->getSuccessHandler());


		$handler = new CallbackHandler;

		$this->factory->setFailureHandler( $handler );

		$this->assertSame($handler, $this->factory->getFailureHandler());
		$this->assertInstanceOf(CallbackHandler::class, $this->factory->getFailureHandler());

	}

	/** @test */
	function the_job_has_been_done_correctly_and_success_handler_was_executed()
	{
	    $handler = (new CallbackHandler())
	    			->registerDoneCallback(function($factory){
	    				$this->assertInstanceOf(ModelFactory::class, $factory);
	    				return 'success';
	    			});

	    $failureHandler = (new CallbackHandler())
		    			->registerDoneCallback(function($factory, $exception){
		    				$this->assertInstanceOf(ModelFactory::class, $factory);
		    				$this->assertInstanceOf(Exception::class, $exception);
		    				$this->assertEquals("exception throwed", $exception->getMessage());
		    				return 'failure';
		    			});

	    $result = $this->factory
	    				->setSuccessHandler( $handler )
	    				->setFailureHandler( $failureHandler )
	    				->doTheJob();

	    $this->assertEquals('success', $result);

	    $this->factory->expects($this->any())
			 ->method('theJob')
			 ->will($this->throwException( new Exception("exception throwed") ));

		$result = $this->factory
	    				->setSuccessHandler( $handler )
	    				->setFailureHandler( $failureHandler )
	    				->doTheJob();

	    $this->assertEquals('failure', $result);
	}
}