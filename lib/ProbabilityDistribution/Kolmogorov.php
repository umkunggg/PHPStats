<?php
/**
 * PHP Statistics Library
 *
 * Copyright (C) 2011-2012 Michael Cordingley<Michael.Cordingley@gmail.com>
 * 
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Library General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Library General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Library General Public License
 * along with this library; if not, write to the Free Software Foundation, 
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * 
 * LGPL Version 3
 *
 * @package PHPStats
 */
 
namespace PHPStats\ProbabilityDistribution;

/**
 * Kolmogorov class
 * 
 * asdf
 *
 * For more information, see: http://en.wikipedia.org/wiki/Kolmogorov_distribution
 * Algorithms from http://www.mathworks.com/matlabcentral/fileexchange/4369-kolmogorov-distribution-functions
 */
class Kolmogorov extends ProbabilityDistribution {
	private $n;
	
	/**
	 * Constructor function
	 *
	 * @param int $n The number of trials
	 */
	public function __construct($n = 1) {
		$this->n = $n;
	}
	
	/**
	 * Returns a random variate of $n trials
	 * 
	 * @return float The random variate.
	 * @todo Untested
	 */
	public function rvs() {
		return self::getRvs($this->n);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pmf($x) {
		return self::getPmf($x, $this->n);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->n);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->n);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->n);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->n);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->n);
	}
	
	/**
	 * Returns a random variate of $n trials at $p probability each
	 * 
	 * @param int $n The number of trials.
	 * @return float The random variate.
	 * @static
	 * @todo Untested
	 */
	public static function getRvs($n = 1) {
		$successes = 0;

		for ($i = 0; $i < $n; $i++) {
			if (self::BernoulliTrial($p)) $successes++;
		}

		return $successes;
	}
	
	/**
	 * Returns the probability mass function
	 * 
	 * @param float $x The test value
	 * @param int $n The number of trials
	 * @return float The probability
	 * @static
	 */
	public static function getPmf($x, $n = 1) {
/*p=zeros(size(x));
num=find(x>0);
xnum=x(num);
pnum=zeros(size(num));
for k=1:1000,
   add=4*(-1)^(k+1)*k^2*exp(-2*k^2*xnum.^2).*xnum;
   pnum=pnum+2*add;
   if norm(add,1)==0,
      break
   end   
end   
p(num)=p(num)+pnum;*/
		return \PHPStats\Stats::combinations($n, $x)*pow($p, $x)*pow(1 - $p, $n - $x);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * float $x The test value
	 * @param int $n The number of trials
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $n = 1) {
/*p=zeros(size(x));
num=find(x>0);
xnum=x(num);
pnum=ones(size(num));
for k=1:1000,
   add=(-1)^k*exp(-2*k^2*xnum.^2);
   pnum=pnum+2*add;
   if norm(add,1)==0,
      break
   end   
end   
p(num)=p(num)+pnum;*/
		$sum = 0.0;
		for ($count = 0; $count <= $x; $count++) {
			$sum += self::getPmf($count, $p, $n);
		}
		return $sum;
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param int $n The number of trials
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $n = 1) {
		return 1.0 - self::getCdf($x, $p, $n);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param int $n The number of trials
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $n = 1) {
/*x=zeros(size(p));
%   Return NaN if the arguments are outside their respective limits.
k = find(p < 0 | p > 1);
if any(k),
   tmp  = NaN; 
   x(k) = tmp(ones(size(k)));
end
k = find(p == 0);
if any(k), 
    x(k) = zeros(size(k)); 
end
k = find(p==1);
if any(k), 
   tmp  = inf; 
   x(k) = tmp(ones(size(k)));
end

% Newton's Method.
% Permit no more than count_limit interations.
count_limit = 50;
count = 0;

k = find(p > 0 & p < 1);
if isempty(k)
   return;
end
pk = p(k);
%   Use the mean as a starting guess. 
xk = (pk-0.05)/0.9+0.5;
h = ones(size(pk));
crit = sqrt(eps); 
% Break out of the iteration loop for the following:
%  1) The last update is very small (compared to x).
%  2) The last update is very small (compared to 100*eps).
%  3) There are more than 100 iterations. This should NEVER happen. 
while(any(abs(h) > crit * abs(xk)) & max(abs(h)) > crit    ...
                                 & count < count_limit), 
    count = count+1;    
    h = (kolmcdf(xk) - pk) ./ kolmpdf(xk);
    xnew = xk - h;
    xk = xnew;  
end
% Return the converged value(s).
x(k) = xk;
if count==count_limit, 
    fprintf('\nWarning: KOLMINV did not converge.\n');
    str = 'The last step was:  ';
    outstr = sprintf([str,'%13.8f'],h);
    fprintf(outstr);
end*/
		$i = 0;
		$cdf = 0;
		
		while ($cdf < $x) {
			$cdf += self::getPmf($i, $p, $n);
			$i++;
		}
		
		return $i - 1;
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param int $n The number of trials
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $n = 1) {
		return self::getPpf(1.0 - $x, $p, $n);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @param int $n The number of trials
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $n = 1) {
		$return = array();

		if (strpos($moments, 'm') !== FALSE) $return['mean'] = $n*$p;
		if (strpos($moments, 'v') !== FALSE) $return['variance'] = $n*$p*(1-$p);
		if (strpos($moments, 's') !== FALSE) $return['skew'] = (1-2*$p)/sqrt($n*$p*(1-$p));
		if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = (1 - 6*$p*(1 - $p))/($n*$p*(1-$p));
		
		return $return;
	}
}
