<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Helilabs\Capital\Helpers\StdClass;

class StdClassTest extends TestCase
{
	public $expectedResults;

	public function setUp(){
		parent::setUp();

		$this->expectedResults = new \StdClass;
        $this->expectedResults->name = new \StdClass;
        $this->expectedResults->name->firstName = 'Mohammed';
        $this->expectedResults->name->lastName = 'Manssour';
        $this->expectedResults->age = '27';
        $this->expectedResults->{'attr-0'} = '10';

        $this->testArray = [
        	'name' => [
        		'firstName' => 'Mohammed',
	        	'lastName' => 'Manssour',
        	],
        	'age' => 27,
        	10
        ];
	}

    /** @test */
    function can_generate_std_class_from_array()
    {

        $results = StdClass::fromArray( $this->testArray )->generate();

        $this->assertEquals($this->expectedResults, $results);
    }

    /** @test */
    function can_add_attribute_to_std_class(){
    	$this->expectedResults->job = "softwareEngineer";

		$results = StdClass::fromArray( $this->testArray )
							->addAttribute('job','softwareEngineer')
							->generate();

		$this->assertEquals($this->expectedResults, $results);
    }
}