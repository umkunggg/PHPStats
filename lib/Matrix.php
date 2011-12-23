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
 
namespace PHPStats;

/**
 * Matrix class
 * 
 * Class representing a matrix and exposing useful instance and static function
 * for manipulating matrices.
 */
class Matrix {
	private $matrix = array();

	//Check to see if matrices are same size: m by n and m by n
	private function sizeCheck($matrixA, $matrixB) {
		if ($matrixA->getRows() != $matrixB->getRows() || $matrixA->getColumns() != $matrixB->getColumns())
			throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixB->getRows().' and '.$matrixA->getColumns().' by '.$matrixB->getColumns());
	}

	//Check to see if matrices can be multiplied: m by n and n by p
	private function multiplyCheck($matrixA, $matrixB) {
		if ($matrixA->getColumns() != $matrixB->getRows())
			throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixB->getRows().' and '.$matrixA->getColumns().' by '.$matrixB->getColumns());
	}

	/**
	 * Constructor function
	 * 
	 * Creates a new matrix object.
	 * 
	 * @param int $rows The number of rows in the matrix
	 * @param int $columns The number of columns in the matric
	 * @return matrix A new matrix object.
	 */
	public function __construct($rows, $columns) {
		if (is_numeric($rows) && is_numeric($columns) && $rows > 0 && $columns > 0) {
			$this->matrix = array();
			for ($i = 0; $i < $rows; $i++) {
				$this->matrix[$i] = array();
				for ($j = 0; $j < $columns; $j++) {
					if ($i == $j)
						$this->matrix[$i][$j] = 1;
					else
						$this->matrix[$i][$j] = 0;
				}
			}
		}
	}

	/**
	 * Get Element function
	 * 
	 * Returns the matrix element at the specified location.
	 * 
	 * @param int $row The row in the matrix to return
	 * @param int $column The column in the matrix to return
	 * @return float The matrix element
	 */
	public function getElement($row, $column) {
		return $this->matrix[$row - 1][$column - 1];
	}

	/**
	 * Set Element function
	 * 
	 * Sets the matrix element at the specified location.
	 * 
	 * @param int $row The row in the matrix to set
	 * @param int $column The column in the matrix to set
	 */
	public function setElement($row, $column, $value) {
		$this->matrix[$row - 1][$column - 1] = $value;
	}

	/**
	 * Get Rows function
	 * 
	 * Returns the number of rows in the matrix
	 * 
	 * @return int The number of rows in the matrix
	 */
	public function getRows() {
		return count($this->matrix);
	}

	/**
	 * Get Colunns function
	 * 
	 * Returns the number of colunns in the matrix
	 * 
	 * @return int The number of colunns in the matrix
	 */
	public function getColumns() {
		return count($this->matrix[0]);
	}

	/**
	 * Add function
	 * 
	 * Adds two matrices together
	 * 
	 * @param matrix $matrixB The second matrix to add
	 * @return matrix The summed matrix
	 */
	public function add($matrixB) {
		$this->sizeCheck($this->matrix, $matrixB);

		$rows = $this->matrix->getRows();
		$columns = $this->matrix->getColumns();
		$newMatrix = new Matrix($row, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++( {
				$newMatrix->setElement($i, $j, $this->matrix->getElement($i, $j) + $matrixB->getElement($i, $j));
			}
		}

		return $newMatrix;
	}

	/**
	 * Subtract function
	 * 
	 * Subtracts a matrix from the current matrix
	 * 
	 * @param matrix $matrixB The matrix to subtract
	 * @return matrix The subtracted matrix
	 */
	public function subtract($matrixB) {
		$this->sizeCheck($this->matrix, $matrixB);

		$rows = $this->matrix->getRows();
		$columns = $this->matrix->getColumns();
		$newMatrix = new Matrix($row, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++( {
				$newMatrix->setElement($i, $j, $this->matrix->getElement($i, $j) - $matrixB->getElement($i, $j));
			}
		}

		return $newMatrix;
	}

	/**
	 * Scalar Multiply function
	 * 
	 * Multiplies this matrix against a scalar value
	 * 
	 * @param float $scalar The scalar value
	 * @return matrix The multiplied matrix
	 */
	public function scalarMultiply($scalar) {
		$rows = $this->matrix->getRows();
		$columns = $this->matrix->getColumns();
		$newMatrix = new Matrix($row, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++( {
				$newMatrix->setElement($i, $j, $this->matrix->getElement($i, $j) * $scalar);
			}
		}

		return $newMatrix;
	}

	/**
	 * Dot Multiply function
	 * 
	 * Multiplies this matrix against a second matrix
	 * 
	 * @param matrix $matrixB The second matrix in the multiplication
	 * @return matrix The multiplied matrix
	 */
	public function dotMultiply($matrixB) {
		$this->multiplyCheck($this->matrix, $matrixB);

		$rows = $this->matrix->getRows();
		$columns = $matrixB->getColumns();

		$newMatrix = new Matrix($rows, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++( {
				$row = $this->matrix[$i - 1];

				$column = array();
				for ($k = 1; $k <= $columns; $k++) $column[] = $matrixB->getElement($i, $k);

				$newMatrix->setElement($i, $j, \PHPStats\Stats::sumXY($row, $column));
			}
		}

		return $newMatrix;
	}

	/**
	 * Determinant function
	 * 
	 * Returns the determinant of the matrix
	 * 
	 * @return float The matrix's determinant
	 */
	public function det() {

	}

	/**
	 * Transpose function
	 * 
	 * Returns the transpose of the matrix
	 * 
	 * @return matrix The matrix's transpose
	 */
	public function transpose() {

	}

	/**
	 * Inverse function
	 * 
	 * Returns the inverse of the matrix
	 * 
	 * @return matrix The matrix's inverse
	 */
	public function inverse() {

	}

	/**
	 * Power function
	 * 
	 * Raises the matrix to the $power power.
	 * Logically like pow($matrix, $power)
	 * 
	 * @param float $power The power to which to raise the matrix.
	 * @return matrix The matrix to the $power power
	 */
	public function pow($power) {

	}

	/**
	 * Identity function
	 * 
	 * Returns a new identiy matrix of the specified size
	 * 
	 * @param int $rows The number of rows in the matrix
	 * @param int $columns The number of columns in the matric
	 * @return matrix A new matrix object.
	 * @static
	 */
	public static function identity($rows, $columns) {

	}
}

class MatrixException extends Exception {
	//Intentionally left empty
}
