<?php

// Customer.php

class Customer {
    public $customer_id;
    public $customer_name;
    public $customer_profit;

    public function __construct($id, $name, $profit) {
        $this->customer_id = $id;
        $this->customer_name = $name;
        $this->customer_profit = $profit;
    }
}


?>