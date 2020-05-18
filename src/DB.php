<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use PDO;
use PDOException;
use MarwaDB\QueryBuilder;
use MarwaDB\Connection;
use Tracy\Debugger;

class DB
{

	/**
	 * database connection
	 * */
	var $conn = null;

	/*
	* var raw PDO
	*/
	protected $pdo=null;

  /**
   * [protected description]
   *
   * @var [type]
   */
	protected $result=null;
	/**
	 * function __construct
	 * */
	public function __construct($dbArray,bool $debug=false)
	{
		$this->conn = new Connection($dbArray);
    if($debug)
    {
      Debugger::enable();
    }
	}

	/**
	 * function the raw PDO Connection
	 * @return  \PDO description
	 * */
	public function getPdo()
	{
		return $this->conn->connect();
	}

	/**
	 * [toArray description] function to convert result object to resutl array
	 * @return [type] [description]
	 */
	public function toArray()
	{
		if(!is_null($this->result))
		{
			$res=json_decode(json_encode($this->result), true);
    		return $res;
		}
		return false;
	}

	/**
	 * function database query
	 * @param  $sqlQuery description
	 * @param  $bindParam
	 * */
	public function rawQuery($sqlQuery,$bindParam=[])
	{
		$this->result=$this->conn->query($sqlQuery,$bindParam);
		return $this->result;
	}

	/**
	 * function to retrieve conenction pdo
	 * @return  $this description
	 * */
	public function connection($name=null)
	{
		$this->pdo = $this->conn->getConnection($name);
		return $this;
	}

	/**
	 * function to retrieve conenction pdo
	 * @return  Connection description
	 * */
	public function getConnection()
	{
		return $this->conn;
	}

	/**
	 * function to return of result fetched rows
	 * @return  int number of rows
	 * */
	public function rows()
	{
		return $this->conn->getRows();
	}

  /**
   * [setFetchMode description]
   *
   * @method setFetchMode
   *
   * @param [type] $type [description]
   */
  public function setFetchMode($type)
  {
    $this->conn->setFetchMode($type);
    return $this;
  }

	/**
	 * function to move on QueryBuilder Class
	 * @param   $name table name
	 * @return  QueryBuilder description
	 * */
	public function table($name)
	{
		$this->conn->connect();
		$this->result = new QueryBuilder($this,$name);

		return $this->result;
	}


	/**
	 * alias function of database select
	 * */
	public function select($sql,$params=[])
	{
			return $this->rawQuery($sql,$params);
	}

	/**
	 * alias function of query for insert data
	 * */
	public function insert($sql,$params=[])
	{
		return $this->rawQuery($sql,$params);
	}
	/**
	 * alias function of Query
	 * */
	public function update($sql,$params=[])
	{
		return $this->rawQuery($sql,$params);
	}

	/**
	 * alias function to delete data
	 * */
	public function delete($sql,$params=[])
	{
		return $this->rawQuery($sql,$params);
	}

	/**
	 * begin Transaction
	 * */

	public function beginTrans()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->beginTransaction();
	}

	/**
	 * commit transaction
	 * */
	public function commit()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->commit();
	}

	/**
	 * rollback Transaction
	 * */
	public function rollback()
	{
		$this->pdo = $this->conn->connect();
		$this->pdo->rollback();
	}


	/**
	 * function for atabase transaction with callback function
	 *@param   $function_name description
	 * */
	public function transaction($function_name)
	{

		if(!is_callable($function_name))
		{
			throw new Exception('Function is not callable!');
		}

		//try to call function with start/commit transaction
		try
		{
			$this->beginsTrans();
			$function_name();
			$this->commit();
		}
		catch (Exception $e)
		{
  		$this->rollback();
  		throw $e;
		}

	}

}

?>
