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
 
namespace PHPStats\Clustering;

/**
 * kmeans class
 * 
 * Performs a k-means clustering on a set of data.
 * 
 * For more information, see: http://en.wikipedia.org/wiki/K-means_clustering
 */
class kmeans {
	private $centroids = array();
	private $observations = array();

	/**
	 * Constructor function
	 * 
	 * @param array $observations An array of 1 x n matrices, where n is how many dimensions the data has
	 * @param int $k The number of clusters to use
	 */
	public function __construct(array $observations, $k) {
		for ($i = 0; $i < $k; $i++) {
			$this->centroids[] = $observations[0]; //Fill centroids with vectors.  The specific values will be overwritten before use.
		}
	
		foreach ($observations as $observation) {
			$obs_array = array();
			$obs_array['coordinates'] = $observation;
			$obs_array['centroid'] = mt_rand(0, $k - 1); //Randomly assign observations to centroids, according to the Random Partition method.
			
			$this->observations[] = $obs_array;
		}
		
		//Iterate until convergence is reached. i.e. when cluster assignments no longer change
		$change = true;
		while ($change) {
			$this->update();
			$change = $this->assign();
		}
	}
	
	//Need getter functions, still
	
	//Assigns observations to the nearest centroid
	private function assign() {
		$change = false;
		$distance = array();
		
		foreach ($this->observations as &$observation) {
			$old_centroid = $observation['centroid'];
			
			foreach ($this->centroids as $index => $centroid) {
				$distance[$index] = $observation->subtract($centroid)->magnitude(); //Magnitude of the difference of the vectors
			}
			
			//Find the centroid with the least distance
			$observation['centroid'] = array_search(min($distance), $distance); //Should only be one, unless there's a tie, then just grab the first match.
			
			if ($observation['centroid'] != $old_centroid) $change = true;
		}
		
		return $change; //If anything has changed during this pass
	}
	
	//Centers centroids on current members
	private function update() {
		foreach ($this->centroids as $index => &$centroid) {
			$new_coordinates = \PHPStats\Matrix::zero(1, $centroid->getColumns());
			$members = 0;
			
			foreach ($this->observations as $observation) {
				if ($observation['centroid'] = $index) {
					$new_coordinates = $new_coordinates->add($observation['coordinates']);
					$members++;
				}
			}
			
			//Assign new value
			$centroid = $new_coordinates->scalar_multiply(1/$members);
		}
	}
}