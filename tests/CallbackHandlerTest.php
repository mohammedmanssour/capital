<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Helilabs\Capital\Helpers\CallbackHandler;

class CallbackHandlerTest extends TestCase
{

    /** @test */
    function can_register_callback()
    {
    	$handler = new CallbackHandler;

    	$handler->registerCallback(function(){
    		//empty function
    	})->registerCallback(function(){
    		//empty function
    	});

		$this->assertEquals(2, $handler->getCallbacks()->count());
    }

    /** @test */
    function can_call_callbacks()
    {
    	$handler = new CallbackHandler;

        $called = false;
        $calledB = false;

    	$handler->registerCallback(function() use( &$called ){
    		$called = true;
    	})->registerCallback(function() use( &$calledB ){
    		$calledB = true;
    	})->handle();

    	$this->assertTrue($called, 'Callback should be called');
    	$this->assertTrue($calledB, 'Callback B should be called');
    }

    /** @test */
    function can_pass_arguments()
    {
        $handler = new CallbackHandler;

        $arguments = [
        	'firstArgumentValue',
        	'secondArgumentValue'
        ];

        $handler->registerCallback(function( $argument, $argumentB ){
    		$this->assertEquals('firstArgumentValue', $argument);
    		$this->assertEquals('secondArgumentValue', $argumentB);
    	});

    	$handler->passArguments( $arguments )->handle();
    }

    /** @test */
    function can_call_done_callback()
    {
        $handler = new CallbackHandler;

		$this->assertFalse($handler->hasDoneCallback());

        $result = $handler->registerCallback(function(){})
		        		->registerDoneCallback(function(){
		        			return 'doneCallback';
						})->handle();

		$this->assertTrue($handler->hasDoneCallback());

		$this->assertEquals('doneCallback', $result);
    }

}