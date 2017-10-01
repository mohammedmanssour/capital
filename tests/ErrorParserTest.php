<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\MessageBag;
use Helilabs\Capital\Helpers\ErrorParser;

function trans($message)
{
  return $message;
}

class ErrorParserTest extends TestCase
{
	/** @test */
	function can_convert_errors_to_html_ul_list()
	{
	    $expectedResults = '<ul>';
	    $expectedResults .= '<li> fix 1 validation rule </li>';
	    $expectedResults .= '<li> fix 2 validation rule </li>';
	    $expectedResults .= '</ul>';

	    $errorsBag = new MessageBag;
	    $errorsBag->add('1validationrule', 'fix 1 validation rule');
	    $errorsBag->add('2validationrule', 'fix 2 validation rule');

	    $results = ErrorParser::parse( $errorsBag )->toHtmlUlList();

	    $this->assertEquals($expectedResults, $results);
	}

	/** @test */
	function can_convert_errors_to_array(){
		$errorsBag = new MessageBag;
	    $errorsBag->add('1validationrule', 'fix 1 validation rule');
	    $errorsBag->add('1validationrule', 'fix 1-2 validation rule');
	    $errorsBag->add('2validationrule', 'fix 2 validation rule');

	    $expectedResults = [
	    	'fix 1 validation rule',
	    	'fix 1-2 validation rule',
	    	'fix 2 validation rule'
	    ];

	    $results = ErrorParser::parse( $errorsBag )->toSimpleArray();

	    $this->assertEquals($expectedResults, $results);
	}
}