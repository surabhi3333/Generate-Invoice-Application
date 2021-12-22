<?php
session_start();
include('header.php');
include 'class/InvoiceData.php';
$obj_invoice = new InvoiceData();

if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {	
	$user = $obj_invoice->getLoginUserDetails($_POST['user_name'], $_POST['user_password']); 
  //echo "<pre>"; print_r($user);
	if(!empty($user)) {
		$_SESSION['userid'] = $user[0]['userid'];
	}  
}

$invoice_list = $obj_invoice->getInvoiceList();
?>

<body style="background-color: #0c546042;">
  <div class="container">
    <h2>Invoice List</h2> 
    <div class="form-group" align="right"><button type="button" class="btn btn-primary" onclick="window.location.href='create_invoice.php'">Create Invoice</button> </div>      
    <table class="table table-striped table-bordered" id="invoice_list_table" style="background-color: #ffffff">
      <thead>
        <tr>
        	<th>Invoice Number</th>
          <th>Created Date</th>
          <th>Created By</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php 
          if(count($invoice_list)>0){
            foreach ($invoice_list as $key => $invoice) {
              $invoice_date = date("d/m/Y", strtotime($invoice["invoice_created_date"]));
          ?>
              <tr>
                <td><?php echo $invoice['invoice_rep'] ?></td>
                <td><?php echo $invoice_date ?></td>
                <td><?php echo $invoice['first_name']."".$invoice['last_name']; ?></td>
                <td>
                  <?php //echo '<a href="print_invoice.php?invoice_id='.$invoice["invoice_id"].'" >Generate Invoice</a>'; ?>
                  <div class="container">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#printInvoice">Generate Invoice</button>
                    <!-- Modal -->
                    <div id="printInvoice" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <?php echo '<embed src="print_invoice.php?invoice_id='.$invoice["invoice_id"].'" frameborder="0" width="100%" height="400px" >'; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
        <?php } } else{
          echo '<tr><td align="center" colspan="4">No data available</td></tr>';
        } ?>
      </tbody>
    </table>
  </div>
</body>
<?php include('footer.php'); ?>