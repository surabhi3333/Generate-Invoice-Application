<?php
class InvoiceData{
	private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "";
    private $database  = "generate_invoice_system";
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }

    public function getLoginUserDetails($user_name, $user_password){
       $sqlQuery = "SELECT * FROM invoice_user WHERE username='".$user_name."' AND password='".$user_password."'";
       $result = mysqli_query($this->dbConnect, $sqlQuery);
       $data= array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[]=$row;            
        }
        return $data;
    }

    public function getProductList($product_id=0){
        $whr = '';
        if($product_id>0)
            $whr = "AND product_id=$product_id";
       $sqlQuery = "SELECT * FROM invoice_product_list WHERE product_deleted=1 $whr ORDER BY product_name ASC";
       $result = mysqli_query($this->dbConnect, $sqlQuery);
       $data= array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[]=$row;            
        }
        return $data;
    }

    public function saveInvoice($POST) { 
        //echo "<pre>"; print_r($POST);die;
        $inv_rep = "INV".rand(10,100000);    
        $invoice_created_by = "1";
        $sql = "
            INSERT INTO invoice_list(invoice_rep, invoice_created_by, invoice_receiver_name, invoice_receiver_address, invoice_sub_total, invoice_sub_total_with_tax, invoice_discount_type, invoice_discount_rate,invoice_total_amount,invoice_deleted) 
            VALUES ('".$inv_rep."', '".$invoice_created_by."', '".$POST['buyer_name']."', '".$POST['buyer_address']."', '".$POST['sub_total']."', '".$POST['sub_total_with_tax']."', '".$POST['discount_type']."', '".$POST['discount_rate']."', '".$POST['total_amount']."', 1)";        
        mysqli_query($this->dbConnect, $sql);
        $inv_id = mysqli_insert_id($this->dbConnect);
        for ($i = 0; $i < count($POST['product_name']); $i++) {
            $mysql = "
            INSERT INTO invoice_product_details(invpr_invoice_id, invpr_product_id, invpr_quantity,invpr_tax_rate) 
            VALUES ('".$inv_id."', '".$POST['product_name'][$i]."', '".$POST['quantity'][$i]."', '".$POST['tax_rate'][$i]."')";         
            mysqli_query($this->dbConnect, $mysql);
        } 
        return $inv_id;          
    } 

    public function getInvoiceList($invoice_id=0){
        $whr = '';
        if($invoice_id>0)
            $whr = "AND invoice_id=$invoice_id";

        $sqlQuery = "SELECT invoice_id, invoice_rep, invoice_created_date, invoice_receiver_name, invoice_receiver_address, invoice_sub_total, invoice_sub_total_with_tax, invoice_discount_type,invoice_discount_rate, invoice_total_amount, first_name, last_name FROM invoice_list inner join invoice_user on invoice_created_by=userid WHERE invoice_deleted=1 $whr ORDER BY invoice_id DESC";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $data= array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[]=$row;            
        }
        return $data;
    }  

    public function getInvoiceProductDetails($invoice_id){
        $sqlQuery = "SELECT invpr_id, invpr_invoice_id, invpr_product_id, invpr_quantity, invpr_tax_rate, product_name, product_unit_price FROM invoice_product_details INNER JOIN invoice_product_list ON invpr_product_id=product_id WHERE invpr_invoice_id=$invoice_id  ";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $data= array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[]=$row;            
        }
        return $data;
    }  





}


?>