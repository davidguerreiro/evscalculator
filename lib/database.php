<?php

//Database connection class - Created by David Guerreiro - me@davidguerreiro.com - davidguerreiro.com

//This class allows you to use a database object as a handler. It is quite similar to wp global $wpdb


class Db{

	//Object constructor
	function __construct(){

		$mysql_server = 'localhost';		//Host where the database is installed
		$mysql_login = 'root';				//Username
		$mysql_pass = 'mypass';				//Mysql user's password -- Not used in this example
		$database_name = 'evcalculator';	//Name of the database that we want to connect

		$this->rows = 0;					

		$this->connection = mysqli_connect($mysql_server, $mysql_login);	//Establishing the connection
		settype($this->conenction, 'boolean');
		mysqli_select_db($this->connection, $database_name);				//Selecting the database

		$this->database_selected = $database_name;							

	}

	/**
	*
	**This method returns the connection status
	*
	*@return boolean status Connection status
	*
	*/
	public function check_connection(){

		$status = (mysqli_connect_error()) ? false : true;

		return $status;

	}

	/**
	*
	*This method returns the selected database name
	*
	*@return string $this->database_selected name of the current database in use
	*
	*/
	public function get_database(){

		return $this->database_selected;
	}

	/**
	*
	*This method change the current database
	*
	*@param string $database_name Name of the new database
	*@return boolean $this->database_selected Status of the new database change
	*
	*/
	public function change_database($database_name){

		$status = false;

		if(!is_string($database_name))
			return $status;

		if(mysqli_select_db($this->connection, $database_name)){
			$this->database_selected = $database_name;
			$status = true;
		}
		
		return $status;
	}

	/**
	*
	*This method execute a query in our current database
	*
	*@param string $query Query to execute
	*@return boolean $status Status of the executed query
	*
	*/
	public function query($query){

		$status = false;

		if(!is_string($query))
			return $status;

		$status = (mysqli_query($this->connection, $query)) ? true : false;
		$rows = ($status) ? mysqli_affected_rows($this->connection) : false;
		$this->rows = (!$rows) ? $rows : $this->rows;

		return $status;
	}

	/**
	*
	*This method returs either an array containg every row returned by the query or boolean false if the query was wrong
	*
	*@param string $query A query to execute in out current database
	*@return array $status || boolean $status An array containgin all rows or a boolean false
	*
	*/
	public function get_results($query){

		$status = false;

		if(!is_string($query))
			return $status;

		//If this is a select query, then it should start with 'select' + the query

		$control = explode(" ", $query);

		if($control[0] != 'select')
			return $status;

		$ex = mysqli_query($this->connection, $query);

		if($ex){

			
			while($a = mysqli_fetch_assoc($ex)){

				$status[] = $a;

			}
			
			if(!is_array($status))
				return false;

			$this->rows = mysqli_affected_rows($this->connection);

			return $status;

		}

		return $status;
	}

	/**
	*
	*This method returns a single row of the requested table
	*
	*@param string $query A query to be executed in our currente database
	*@return array $status ||  boolean $status An array containing all rows or a boolean false if an error occur
	*
	*/
	public function get_row($query){

		$status = false;

		if(!is_string($query))
			return $status;

		//If this is a select query, then it should start with 'select' + the query

		$control = explode(" ", $query);

		if($control[0] != 'select')
			return $status;

		if(!strpos($query, ' LIMIT'))
			$query .= ' LIMIT 1';
		else{
			$limit = strstr($query, 'LIMIT');
			$query = str_replace($limit, ' LIMIT 1', $query);
		}


		$ex = mysqli_query($this->connection, $query);

		if($ex){

			$status = mysqli_fetch_assoc($ex);

			if(!is_array($status))
				return false;

			$this->rows = mysqli_affected_rows($this->connection);

		}

		return $status;

	}

	/**
	*
	*This method returns a unique value of the requested table
	*
	*@param string $query A query to be executed in our current database
	*@param int $row The row wich has to be retrieved. This parameter is optional. Default : 0
	*@return unknow  $status The value of the data requested or false if there is a error
	*
	*/
	public function get_var($query, $row = 0){

		$status = false;

		if(!is_string($query))
			return $status;

		//If this is a select query, then it should start with 'select' + the query

		$control = explode(" ", $query);

		if($control[0] != 'select')
			return $status;

		//check if it is a count query

		$check = strpos($query, 'count');

		if($check !== false){
			
			//count query

			$ex = mysqli_query($this->connection, $query);

			if($ex){

				$status = mysqli_fetch_row($ex);

				if(!is_array($status))
					return false;

				$this->row = mysqli_affected_rows($this->connection);

				return $status;
			}

			return $status;
		}
		else{

			//other different query

			if(!strpos($query, ' LIMIT'))
				$query .= ' LIMIT 1';
			else{
				$limit = strstr($query, 'LIMIT');
				$query = str_replace($limit, ' LIMIT 1', $query);
			}

			$ex = mysqli_query($this->connection, $query);

			if($ex){

				$control = mysqli_fetch_array($ex);

				if(!is_array($control))
					return $status;

				$status = (array_key_exists($row, $control)) ? $control[$row] : false;
				$this->row = mysqli_affected_rows($this->connection);

				return $status;
			}

			return $status;
		}
	}

	/**
	*
	*This function close the mysql connection permanently
	*
	*@return boolean status True if the connection is susccessfully closed
	*
	**/
	public function close_connection(){

		$status = (mysqli_close($this->connection)) ? true : false;

		return $status;
	}

	
}


?>


