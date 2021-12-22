<?php
	session_start();
    include 'class/InvoiceData.php';
    $obj_invoice = new InvoiceData();
    
	if(isset($_POST["product_id"])){
		$product_id = $_POST["product_id"]; 
		if($product_id>0){
			$product_detail = $obj_invoice->getProductList($product_id);
			$data = array();
			$data['quantity'] 	 = $product_detail[0]['product_quantity'];
			$data['unit_price']  = $product_detail[0]['product_unit_price'];
			echo json_encode($data);	
		}
	}

	if(isset($_POST["from"]) && $_POST["from"]=="create_invoice"){
		$result = $obj_invoice->saveInvoice($_POST);
		echo $result;
	}

	if(isset($_POST["from"]) && $_POST["from"]=="login_user_form"){
		$result = $obj_invoice->getLoginUserDetails($_POST["user_name"], $_POST["user_password"]);
		$login_user_id = isset($result[0]['userid'])?$result[0]['userid']:0;
		echo $login_user_id;
	}



?>