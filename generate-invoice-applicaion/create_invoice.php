<?php
    session_start();
    include('header.php');

    include 'class/InvoiceData.php';
    $obj_product = new InvoiceData();
    $products = $obj_product->getProductList(); 
    $json_products = json_encode($products);
?>

<body style="background-color: #0c546042;">
  <div class="container py-5 h-100" style="background-color: #ffffff">
    <form action="" id="invoice_form" method="post" > 
      <h2>Create Invoice</h2> <br>
      <div class="row">
        <div class="col-sm-8">
          <h5>From,</h5>
          Samsung <br> 
          6th Floor, DLF Centre, <br>  
          Sansad Marg, New Delhi-11000 <br>
        </div> 

        <div class="col-sm-4 pull-right">
          <h5>To,</h5>
          <div class="form-group">
            <input type="text" class="form-control require" name="buyer_name" id="buyer_name" placeholder="Name" title="Name" autocomplete="off">
          </div>
          <div class="form-group">
            <textarea class="form-control require" rows="3" name="buyer_address" id="buyer_address" placeholder="Address" title="Address"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-bordered table-hover" id="product_list_tbl"> 
            <tr>
              <th width="25%">Product Name</th>
              <th width="15%">Quantity</th>
              <th width="15%">Unit Price($)</th> 
              <th width="15%">Tax Rate</th>                
              <th width="15%">Total</th>
              <th width="22%"></th>
            </tr>             
            <tr id="row_0">
              <td>
                <select name="product_name[]" id="product_name_0" class="form-control product_name require" onchange="setProductDetils(0);" title="Product">
                  <option value="0">Select Product </option>
                  <?php 
                    foreach ($products as $key => $product) {
                  ?>
                    <option value="<?php echo $product['product_id'] ?>"> <?php echo $product['product_name'] ?> </option>
                  <?php } ?>
                </select>
                <input type="hidden" name="product_list" id="product_list" value="<?php echo htmlentities($json_products);  ?>">     
              <td><input type="number" name="quantity[]" id="quantity_0" class="form-control quantity require" onchange="changeQuantity(0);" autocomplete="off" title="Quantity"></td>
              <td><input type="number" name="unit_price[]" id="unit_price_0" class="form-control unit_price" title="Unit Price" autocomplete="off" disabled></td>
              <td>
                <div class="input-group-prepend">
                  <select name="tax_rate[]" id="tax_rate_0" class="form-control tax_rate" onchange="calculateTotalAmount();" title="Tax Rate" >
                    <option value="0">0  </option>
                    <option value="1">1  </option>
                    <option value="5">5  </option>
                    <option value="10">10  </option>
                  </select>
                  <span class="input-group-text" id="basic-addon1">%</span>
                </div>
              </td>
              <td><input type="number" name="line_total[]" id="line_total_0" class="form-control line_total" title="Total" autocomplete="off" disabled></td>
              <td>
                  <button class="btn btn-success" id="addRows" onclick="addRow();" type="button">+</button>
              </td>
            </tr>           
          </table>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col"></div>
        <div class="col col-sm-6 float-right form-inline form-group">
          <table cellspacing="1" cellpadding="8" id="total_amount_tbl">
            <tr>
              <td>Subtotal without Tax : </td>
              <td>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input value="" type="text" class="form-control" name="sub_total" id="sub_total" placeholder=" without Tax" disabled>
                </div>  
              </td>
            </tr>
            <tr>
              <td>Subtotal with Tax : </td>
              <td>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input value="" type="text" class="form-control" name="sub_total_with_tax" id="sub_total_with_tax" placeholder="Subtotal with Tax" disabled></div>
                </div>  
              </td>
            </tr>
            <tr>
              <td>Discount on Subtotal with Tax : </td>
              <td> 
                <div class="input-group-prepend">
                  <select name="discount_type" id="discount_type" class="form-control" onchange="calculateTotalAmount();">
                    <option value="1"> $  </option>
                    <option value="2"> %  </option>
                  </select>
                  <input value="" type="number" class="form-control" name="discount_rate" id="discount_rate" placeholder="Discount on Total" onchange="calculateTotalAmount();">
                </div>
              </td>
            </tr>
            <tr>
              <td>Total Amount : &nbsp;</td>
              <td>
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">$</span>
                  <input value="" type="text" class="form-control" name="total_amount" id="total_amount" placeholder="Total Amount" disabled>
                </div>
              </td>
            </tr>
          </table>
        </div>  
      </div> 
      <br>
      <div class="row justify-content-center">
        <div class="form-group">
          <input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
          <button name="create_invoice_btn" type="button" class="btn btn-success submit_btn invoice-save-btm" onclick="validateCreateInvoiceForm();" >Save Invoice</button>
          <button name="cancel_invoice_btn" type="button" class="btn btn-danger " onclick="window.location.href='invoice_list.php'" >Cancel</button>
        </div>
      </div> 
    </form> 
  </div>
</body>
<?php include('footer.php'); ?>
