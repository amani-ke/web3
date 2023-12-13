<?php

/*
    Class implementing Singleton pattern to get a cursor to the current database.
*/
class MysqlDatabase {

    /* cursor to DB connection */
    private $cursor = null;

    /* Singleton instance - not needed in class methods */
    private static $instance = null;

    /*
        Use this method to get access to the database connection.
    */
    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new MysqlDatabase();
        }
        return self::$instance;
    }

    /*
        Private constructor to implement Singleton. Do not use this method for instatiation!
    */
	private function __construct(){
		$host = '127.0.0.1';
		$db = 'realdb';
		$user = 'wt1_prakt';
		$pw = 'abcd';
		
		$dsn = "mysql:host=$host;port=3306;dbname=$db";
		
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_CASE => PDO::CASE_NATURAL
		];

		try{
            $this->cursor = new PDO($dsn, $user, $pw, $options);
		} 
		catch(PDOException $e){
			echo "Verbindungsaufbau gescheitert: " . $e->getMessage();
		}
    }
    
    /*
        Do not call this method directly.
    */
	public function __destruct(){
		$this->cursor = NULL;	
    }
    public function read_customers() {
        $sql = "SELECT customer_id, customer_name, customer_profit FROM Customer";
        $stmt = $this->cursor->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $customers = [];
        foreach ($result as $row) {
            $customer = new Customer($row['customer_id'], $row['customer_name'], $row['customer_profit']);
            $customers[] = $customer;
        }

        return $customers;
    }

    public function filter_customers($customer_name) {
        $sql = "SELECT customer_id, customer_name, customer_profit FROM Customer WHERE customer_name LIKE :customer_name";
        $stmt = $this->cursor->prepare($sql);
        $stmt->bindValue(':customer_name', '%' . $customer_name . '%', PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $customers = [];
        foreach ($result as $row) {
            $customer = new Customer($row['customer_id'], $row['customer_name'], $row['customer_profit']);
            $customers[] = $customer;
        }

        return $customers;
    }

    
		
}



?>
