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
	private function sizeCheck(Matrix $matrixA, Matrix $matrixB) {
		if ($matrixA->getRows() != $matrixB->getRows() || $matrixA->getColumns() != $matrixB->getColumns())
			throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixA->getColumns().' and '.$matrixB->getRows().' by '.$matrixB->getColumns());
	}

	//Check to see if matrices can be multiplied: m by n and n by p
	private function multiplyCheck(Matrix $matrixA, Matrix $matrixB) {
		if ($matrixA->getColumns() != $matrixB->getRows())
			throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixA->getColumns().' and '.$matrixB->getRows().' by '.$matrixB->getColumns());
	}

	//Check to see if matrix is square: m by m
	private function checkSquare(Matrix $matrix) {
		if ($matrix->getColumns() != $matrix->getRows())
			throw new MatrixException('Matrices is not square: '.$matrix->getRows().' by '.$matrix->getColumns());
	}

	/**
	 * Constructor function
	 * 
	 * Creates a new matrix object.  If given a pair of numbers, this will
	 * return a zero-filled matrix with ones running down the diagonal, i.e.
	 * an identity matrix if the matrix is square.  If given a string like
	 * '[1, 2, 3; 4, 5, 6; 7, 8, 9]' as the first argument, then it will
	 * attempt to construct a matrix using the supplied values.
	 * 
	 * @param mixed $rows The number of rows in the matrix or a string representing a matrix literal
	 * @param int $columns The number of columns in the matric
	 * @return matrix A new matrix object.
	 */
	public function __construct($rows = 3, $columns = 3) {
		$this->matrix = array();
		
		if (is_numeric($rows) && is_numeric($columns) && $rows > 0 && $columns > 0) {
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
		elseif (is_string($rows)) {
			$literal = substr($rows, strpos($rows, '[') + 1, strpos($rows, ']') - strpos($rows, '[') - 1);
			$rowStrings = explode(';', $literal);
			
			$i = 0;
			
			foreach ($rowStrings as $rowString) {
				$this->matrix[$i] = array();
				$j = 0;
				
				foreach (explode(',', $rowString) as $element) {
					$this->matrix[$i][$j] = (float)$element;
					$j++;
				}
				
				$i++;
			}
		}
		else throw new MatrixException('Invalid matrix constructor options.');
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
	public function add(Matrix $matrixB) {
		$this->sizeCheck($this, $matrixB);

		$rows = $this->getRows();
		$columns = $this->getColumns();
		$newMatrix = new Matrix($rows, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
				$newMatrix->setElement($i, $j, $this->getElement($i, $j) + $matrixB->getElement($i, $j));
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
	public function subtract(Matrix $matrixB) {
		$this->sizeCheck($this, $matrixB);

		$rows = $this->getRows();
		$columns = $this->getColumns();
		$newMatrix = new Matrix($rows, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
				$newMatrix->setElement($i, $j, $this->getElement($i, $j) - $matrixB->getElement($i, $j));
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
		$rows = $this->getRows();
		$columns = $this->getColumns();
		$newMatrix = new Matrix($rows, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
				$newMatrix->setElement($i, $j, $this->getElement($i, $j) * $scalar);
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
	public function dotMultiply(Matrix $matrixB) {
		$this->multiplyCheck($this, $matrixB);

		$rows = $this->getRows();
		$columns = $matrixB->getColumns();

		$newMatrix = new Matrix($rows, $columns);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
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
	public function determinant() {

	}

	/**
	 * Transpose function
	 * 
	 * Returns the transpose of the matrix
	 * 
	 * @return matrix The matrix's transpose
	 */
	public function transpose() {
		$rows = $this->getRows();
		$columns = $this->getColumns();
		$newMatrix = new Matrix($columns, $rows);

		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $columns; $j++) {
				$newMatrix->setElement($j, $i, $this->getElement($i, $j));
			}
		}

		return $newMatrix;
	}

	/**
	 * Inverse function
	 * 
	 * Returns the inverse of the matrix
	 * 
	 * @return matrix The matrix's inverse
	 */
	public function inverse() {
		$this->checkSquare($this);

		$rows = $this->getRows();
		$columns = $this->getColumns();

		$determinant = $this->determinant();
		if ($determinant == 0) return self::zero($rows, $columns);

		$newMatrix = new Matrix($rows, $columns);
		
		
		
		return $newMatrix->scalarMultiply(1/$determinant);
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
		$this->checkSquare($this);

		$rows = $this->getRows();
		$columns = $this->getColumns();
		$newMatrix = $this;

		for ($i = 0; $i < $power; $i++) $newMatrix = $newMatrix->dotMultiply($this);

		return $newMatrix;
	}

	/**
	 * Uniform function
	 * 
	 * Returns a matrix consisting entirely of the supplied value
	 * 
	 * @param int $rows The number of rows in the matrix
	 * @param int $columns The number of columns in the matrix
	 * @param float $value The value with which to fill the matrix
	 * @return matrix A matrix filled with elements of value $value
	 * @static
	 */
	static public function uniform($rows, $columns, $value = 0) {
		$literal = "[";

		for ($i = 0; $i < $rows; $i++) {
			for ($j = 0; $j < $columns; $j++) {
				$literal .= "0,";
			}
			$literal = substr($literal, 0, strlen($literal) - 1).";";
		}

		$literal = substr($literal, 0, strlen($literal) - 1)."]";
		return new Matrix($literal);
	}

	/**
	 * Zero function
	 * 
	 * Returns a matrix consisting entirely of zeroes
	 * 
	 * @param int $rows The number of rows in the matrix
	 * @param int $columns The number of columns in the matrix
	 * @return matrix A matrix filled with zero-valued elements
	 * @static
	 */
	static public function zero($rows, $columns) { return self::uniform($rows, $columns, 0); }

	/**
	 * One function
	 * 
	 * Returns a matrix consisting entirely of ones
	 * 
	 * @param int $rows The number of rows in the matrix
	 * @param int $columns The number of columns in the matrix
	 * @return matrix A matrix filled with elements of value one
	 * @static
	 */
	static public function one($rows, $columns) { return self::uniform($rows, $columns, 1); }
}

class MatrixException extends \Exception {
	//Intentionally left empty
}
