<?php
require_once('lib/Stats.php');
require_once('lib/ProbabilityDistribution/ProbabilityDistribution.php');
require_once('lib/ProbabilityDistribution/Normal.php');
require_once('lib/ProbabilityDistribution/Cauchy.php');

use \PHPStats\ProbabilityDistribution\Cauchy as Cauchy;

class CauchyTest extends PHPUnit_Framework_TestCase {
	private $testObject;

	public function __construct() {
		$this->testObject = new Cauchy(10, 5);
	}

	public function test_rvs() {
		//$this->assertEquals(, $this->testObject->rvs());
	}

	public function test_pdf() {
		$this->assertEquals(0.01273, round($this->testObject->pdf(0), 5));
		$this->assertEquals(0.02151, round($this->testObject->pdf(3), 5));
	}

	public function test_cdf() {
		$this->assertEquals(0.14758, round($this->testObject->cdf(0), 5));
		$this->assertEquals(0.19743, round($this->testObject->cdf(3), 5));
	}

	public function test_sf() {
		$this->assertEquals(0.85242, round($this->testObject->sf(0), 5));
		$this->assertEquals(0.80257, round($this->testObject->sf(3), 5));
	}

	public function test_ppf() {
		$this->assertEquals(0, $this->testObject->ppf(0.14758));
		$this->assertEquals(3, round($this->testObject->ppf(0.19743), 4));
	}

	public function test_isf() {
		$this->assertEquals(0, $this->testObject->isf(0.85242));
		$this->assertEquals(3, round($this->testObject->isf(0.80257), 5));
	}

	public function test_stats() {
		$summaryStats = $this->testObject->stats('mvsk');

		$this->assertTrue(is_nan($summaryStats['mean']));
		$this->assertTrue(is_nan($summaryStats['variance']));
		$this->assertTrue(is_nan($summaryStats['skew']));
		$this->assertTrue(is_nan($summaryStats['kurtosis']));
	}
}
?>
