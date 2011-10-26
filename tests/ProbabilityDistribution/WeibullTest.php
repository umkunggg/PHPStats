<?php
require_once('lib/Stats.php');
require_once('lib/ProbabilityDistribution/ProbabilityDistribution.php');
require_once('lib/ProbabilityDistribution/Weibull.php');

use \PHPStats\ProbabilityDistribution\Weibull as Weibull;

class WeibullTTest extends PHPUnit_Framework_TestCase {
	private $testObject;

	public function __construct() {
		$this->testObject = new Weibull(1, 5);
	}

	public function test_rvs() {
		//$this->assertEquals(, $this->testObject->rvs());
	}

	public function test_pdf() {
		$this->assertEquals(0.37961, round($this->testObject->pdf(0), 5));
		$this->assertEquals(0.10982, round($this->testObject->pdf(1.6), 5));
	}

	public function test_cdf() {
		$this->assertEquals(0.5, round($this->testObject->cdf(3.46574), 5));
		$this->assertEquals(0.9, round($this->testObject->cdf(11.5129), 5));
	}

	public function test_sf() {
		$this->assertEquals(0.5, round($this->testObject->sf(3.46574), 5));
		$this->assertEquals(0.1, round($this->testObject->sf(11.5129), 5));
	}

	public function test_ppf() {
		$this->assertEquals(3.46574, round($this->testObject->ppf(0.5), 5));
		$this->assertEquals(11.5129, round($this->testObject->ppf(0.9), 4));
	}

	public function test_isf() {
		$this->assertEquals(3.46574, round($this->testObject->isf(0.5), 5));
		$this->assertEquals(11.5129, round($this->testObject->isf(0.1), 4));
	}

	public function test_stats() {
		$summaryStats = $this->testObject->stats('mvsk');

		$this->assertEquals(5, round($summaryStats['mean'], 5));
		$this->assertEquals(25, round($summaryStats['variance'], 5));
		$this->assertEquals(2, round($summaryStats['skew'], 5));
		$this->assertEquals(6, round($summaryStats['kurtosis'], 5));
	}
}
?>
