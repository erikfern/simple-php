<?php  
   class Database{
   
   	private $dbh;
   	private $error;
   	private $statement; //statement of the SQL query you'd input
   
   	/*
   		$host - the address where the website is hosted (e.g. localhost)
   		$user - username to your WAMP/MAMP/AMPPS server
   		$pass - password
   		$dbname - name of your MySQL database
   	*/
   	public function __construct($host, $user, $pass, $dbname){
   		// Set DSN
   		$dsn = 'mysql:host='. $host . ';dbname='. $dbname;
   
   		// Set Options
   		$options = array(
   			PDO::ATTR_PERSISTENT => true,
   			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
   		);
   
   		// Create new PDO
   		try {
   			$this->dbh = new PDO($dsn, $user, $pass, $options);
   		} catch (PDOException $e){
   			$this->error = $e->getMessage();
   			echo $e;
   		}
   	}
   
   	public function query($query){
   		$this->statement = $this->dbh->prepare($query);
   	}
   
   	/*this allows the query to determine what kind of data type is being passed*/
   	public function bind($param, $value, $type = null){
   		if(is_null($type)){
   			switch(true){
   				case is_int($value):
   					$type = PDO::PARAM_INT;
   					break;
   				case is_bool($value):
   					$type = PDO::PARAM_BOOL;
   					break;
   				case is_null($value):
   					$type = PDO::PARAM_NULL;
   					break;
   				default:
   					$type = PDO::PARAM_STR;
   			}
   		}
   		$this->statement->bindValue($param, $value, $type);
   	}
   
   	public function execute(){
   		try {
   			return $this->statement->execute();
   		} catch (PDOException $e){
   			$this->error = $e->getMessage();
   			echo $e;
   		}
   	}
   
   	//returns the last insterted ID in the database
   	public function lastInsertId(){
   		$this->dbh->lastInsertId();
   	}
   
   	public function resultSet(){
   		$this->execute();
   		return $this->statement->fetchAll(PDO::FETCH_ASSOC);
   	}
   }
   
   ?>