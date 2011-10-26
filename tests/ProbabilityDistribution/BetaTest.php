<?php
require_once('lib/Stats.php');
require_once('lib/ProbabilityDistribution/ProbabilityDistribution.php');
require_once('lib/ProbabilityDistribution/Beta.php');

use \PHPStats\ProbabilityDistribution\Beta as Beta;

class NormalTest extends PHPUnit_Framework_TestCase {
	private $testObject;

	public function __construct() {
		$this->testObject = new Beta(10, 5);
	}

	public function test_rvs() {
		//$this->assertEquals(, $this->testObject->rvs());
	}

	public function test_pdf() {
		$this->assertEquals(0.00210, round($this->testObject->pdf(0.2), 5));
		$this->assertEquals(3.27191, round($this->testObject->pdf(0.7), 5));
	}

	public function test_cdf() {
		$this->assertEquals(0.5, round($this->testObject->cdf(0.674249), 5));
		$this->assertEquals(0.9, round($this->testObject->cdf(0.814866), 5));
	}

	public function test_sf() {
		$this->assertEquals(0.5, round($this->testObject->sf(0.674249), 5));
		$this->assertEquals(0.1, round($this->testObject->sf(0.814866), 5));
	}

	public function test_ppf() {
		$this->assertEquals(0.674249, round($this->testObject->ppf(0.5), 5));
		$this->assertEquals(0.814866, round($this->testObject->ppf(0.9), 4));
	}

	public function test_isf() {
		$this->assertEquals(0.674249, $this->testObject->isf(0.5));
		$this->assertEquals(0.508035, round($this->testObject->isf(0.9), 5));
	}

	public function test_stats() {
		$summaryStats = $this->testObject->stats('mvsk');

		$this->assertEquals(0.66667, round($summaryStats['mean'], 5));
		$this->assertEquals(0.01389, round($summaryStats['variance'], 5));
		$this->assertEquals(-0.33276, round($summaryStats['skew'], 5));
		$this->assertEquals(-0.17647, round($summaryStats['kurtosis'], 5));
	}
}
?>
