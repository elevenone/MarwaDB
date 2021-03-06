<?php
	
	namespace MarwaDB\Builders\Common\Sql;
	
	class LeftJoin extends InnerJoin {
		
		/**
		 * @param string $joinTable
		 * @param string $leftColumn
		 * @param string $condition
		 * @param string $rightColumn
		 */
		public function addJoin( string $joinTable, string $leftColumn, string $condition, string $rightColumn ) : void
		{
			$inner = "LEFT JOIN {$joinTable} ON {$leftColumn} {$condition} {$rightColumn}";
			array_push($this->_joins, $inner);
		}
	}
