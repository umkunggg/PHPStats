<?php
require_once('lib/Stats.php');
require_once('lib/ProbabilityDistribution/ProbabilityDistribution.php');
require_once('lib/ProbabilityDistribution/Normal.php');

use \PHPStats\ProbabilityDistribution\Normal as Normal;

class NormalTest extends PHPUnit_Framework_TestCase {
	private $testObject;

	public function __construct() {
		$this->testObject = new Normal(10, 25);
	}

	public function test_rvs() {
		//$this->assertEquals(, $this->testObject->rvs());
	}

	public function test_pdf() {
		$this->assertEquals(0.02218, round($this->testObject->pdf(2), 5));
		$this->assertEquals(0.06664, round($this->testObject->pdf(7), 5));
	}

	public function test_cdf() {
		$this->assertEquals(0.5, round($this->testObject->cdf(10), 5));
		$this->assertEquals(0.9, round($this->testObject->cdf(16.4078), 5));
	}

	public function test_sf() {
		$this->assertEquals(0.5, round($this->testObject->sf(10), 5));
		$this->assertEquals(0.1, round($this->testObject->sf(16.4078), 5));
	}

	public function test_ppf() {
		$this->assertEquals(10, $this->testObject->ppf(0.5));
		$this->assertEquals(16.4078, round($this->testObject->ppf(0.9), 4));
	}

	public function test_isf() {
		$this->assertEquals(10, $this->testObject->isf(0.5));
		$this->assertEquals(3.59224, round($this->testObject->isf(0.9), 5));
	}

	public function test_stats() {
		$summaryStats = $this->testObject->stats('mvsk');

		$this->assertEquals(10, $summaryStats['mean']);
		$this->assertEquals(25, $summaryStats['variance']);
		$this->assertEquals(0, $summaryStats['skew']);
		$this->assertEquals(0, $summaryStats['kurtosis']);
	}
}
?>
