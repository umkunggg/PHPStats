<?php

require_once('lib/Stats.php');
require_once('lib/ProbabilityDistribution/StudentsT.php');
require_once('lib/ProbabilityDistribution/ChiSquare.php');
require_once('lib/StatisticalTests.php');

use \PHPStats\StatisticalTests as StatisticalTests;

class StatisticalTestsTest extends PHPUnit_Framework_TestCase {
	private $datax;
	private $datay;
	private $dataz;

	function __construct() {
		$this->datax = array(1, 2, 3, 4, 5);
		$this->datay = array(10, 11, 12, 13, 14);
		$this->dataz = array(28.8, 27.1, 42.4, 53.5, 90);
	}

	public function test_oneSampleTTest() {
	}

	public function test_twoSampleTTest() {
	}

	public function test_pairedTTest() {
	}

	public function test_chiSquareTest() {
	}
}
?>
