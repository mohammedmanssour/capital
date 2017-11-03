<?php

namespace Tests;

use \Mockery;
use \Exception;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Helilabs\Capital\CURD\CurdFactory;
use Helilabs\Capital\Helpers\CallbackHandler;
use Helilabs\Capital\Controllers\CurdController;

Class CurdControllerTest extends TestCase{

	public $controller;

	public $factory;

	public function setUp(){
		parent::setUp();
		$this->newControllerInstance();
	}

	public function newFactory(){
		$this->factory = Mockery::mock( CurdFactory::class );
		$this->factory->shouldReceive('doTheJob')->andReturn( 'returened' );
		$this->factory->shouldReceive('setModel')->andReturn( $this->factory );
		$this->factory->shouldReceive('setArgs')->andReturn( $this->factory );
		$this->factory->shouldReceive('setAdditionalArgs')->andReturn( $this->factory );
		$this->factory->shouldReceive('setSuccessHandler')->andReturn( $this->factory );
		$this->factory->shouldReceive('setFailureHandler')->andReturn( $this->factory );
	}

	public function newControllerInstance(){

		$this->newFactory();

		$this->controller = $this->getMockForAbstractClass(CurdController::class);
		$this->controller->expects($this->any())
			 ->method('findModel')->will($this->returnValue(TRUE));
		$this->controller->expects($this->any())
			 ->method('handleUpdateOnSuccessHandler')->will($this->returnValue(new CallbackHandler));
		$this->controller->expects($this->any())
			 ->method('handleUpdateOnFailureHandler')->will($this->returnValue(new CallbackHandler));
		$this->controller->expects($this->any())
			 ->method('handleStoreOnSuccessHandler')->will($this->returnValue(new CallbackHandler));
		$this->controller->expects($this->any())
			 ->method('handleStoreOnFailureHandler')->will($this->returnValue(new CallbackHandler));
	}

	/** @test */
	function test_store_method()
	{

		$this->newControllerInstance();

		$result = $this->controller->store( (new Request), $this->factory, (new CallbackHandler), (new CallbackHandler) );

	    $this->assertEquals('returened', $result);
	}

	/** @test */
	function update_method()
	{
	    $this->newControllerInstance();
	    $result = $this->controller->update( (new Request), $this->factory, (new CallbackHandler), (new CallbackHandler) );

	    $this->assertEquals('returened', $result);
	}

	/** @test */
	function test_generate_path()
	{
		$this->newControllerInstance();

		$this->controller->viewPath = 'path/to/viewfolder/';

		$this->assertEquals( 'path/to/viewfolder/view', $this->controller->generateViewPath( 'view' ) );

	}
}