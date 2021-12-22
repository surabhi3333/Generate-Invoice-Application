$(document).ready(function(){
  
  $('#invoice_list_table').DataTable();
  
});

function inlineMsg(id, msg,hideErrors = false) {  
    var hide = hideErrors ? 'hide' : '';   
    var el = $('#'+id).next( "span" );
    if(el.length)
        el.after('<span style="color: red;" class="error '+hide+'">' + msg + '</span>');
    else
        $('#'+id).after('<span style="color: red;" class="error '+hide+'" >' + msg + '</span>');
}

function validateUserLogin(){
    var user_name     = $("#user_name").val();
    var user_password = $("#user_password").val();

    if(user_name==""){
      $("#login_error").show();
      $("#login_error").html("Please Enter Username");
      return false;
    }
    else if(user_password==""){
      $("#login_error").show();
      $("#login_error").html("Please Enter Password");
      return false;
    }
    else{
      var response = $.ajax({
          url:"custom.php",
          method:"POST",
          dataType: "json",
          data:{user_name:user_name,user_password:user_password,from:"login_user_form"},
          async: false
      }).responseText;
      if(response>0){
        $("#login_error").hide();
        $("#user_login_form").submit();
      }
      else{
        $("#login_error").show();
        $("#login_error").html("Please Enter Valid Username and Password");
        return false;
      }
    }
}

function addRow(){
  var count         = $(".product_name").length;
  var product_list  = JSON.parse($("#product_list").val());
  var rows = '';
    rows += '<tr id="row_'+count+'">';
    rows += '<td><select name="product_name[]" id="product_name_'+count+'" class="form-control product_name require" onchange="setProductDetils('+count+');" >';  
    rows += '<option value="0">Select Product </option>';
    for(var i=0;i<product_list.length;i++){
      rows += '<option value="'+product_list[i]["product_id"]+'">'+product_list[i]["product_name"]+'</option>';
    } 
    rows += '</select></td>';
    rows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity require" title="Product" onchange="changeQuantity('+count+');" autocomplete="off"></td>';      
    rows += '<td><input type="number" name="unit_price[]" id="unit_price_'+count+'" class="form-control unit_price" title="Quantity" autocomplete="off" disabled></td>';

    rows += '<td><div class="input-group-prepend"><select name="tax_rate[]" id="tax_rate_'+count+'" class="form-control tax_rate" onchange="calculateTotalAmount();" title="Tax Rate" >';
    rows += '<option value="0">0  </option> <option value="1">1  </option><option value="5">5  </option> <option value="10">10  </option>';
    rows += '</select><span class="input-group-text" id="basic-addon1">%</span></div></td>';
    rows += '<td><input type="number" name="line_total[]" id="line_total_'+count+'" class="form-control line_total" autocomplete="off" disabled></td>';  
    rows += '<td><button class="btn btn-success" id="addRows" onclick="addRow();" type="button">+</button><button style="margin:5px;" class="btn btn-danger delete" id="removeRows" onclick="deleteRow('+count+');" type="button">-</button></td>';   
    rows += '</tr>';
    $('#product_list_tbl').append(rows);
}

function setProductDetils(id){
  var product_id = $("#product_name_"+id).find("option:selected").val(); 
    if(product_id>0){
      $.ajax({
        url:"custom.php",
        method:"POST",
        dataType: "json",
        data:{product_id:product_id},        
        success:function(response) {
          var line_total = parseFloat(response.quantity*response.unit_price).toFixed(2);
          $("#quantity_"+id).val(response.quantity);
          $("#unit_price_"+id).val(response.unit_price);
          $("#line_total_"+id).val(line_total);
          calculateTotalAmount();
        }
      });
    }else{
      $("#quantity_"+id).val('');
      $("#unit_price_"+id).val('');
      $("#line_total_"+id).val('');
      calculateTotalAmount();
    }
}

function changeQuantity(id){
    var quantity   = $("#quantity_"+id).val();
    var unit_price = $("#unit_price_"+id).val();
    var line_total = parseFloat(quantity*unit_price).toFixed(2);
    $("#line_total_"+id).val(line_total);
    calculateTotalAmount();
}

function calculateTotalAmount(){
  var sub_total = 0;
  var sub_total_with_tax = 0;
  $(".line_total").each(function () {
    var id = $(this).attr('id');
    id     = id.replace("line_total_",'');
    var quantity   = $("#quantity_"+id).val();
    var unit_price = $("#unit_price_"+id).val();
    var tax_rate   = $("#tax_rate_"+id).val();

    var line_total = quantity*unit_price;
    sub_total      =  sub_total+line_total;

    var line_total_with_tax =line_total*tax_rate/100;
    line_total_with_tax = line_total+line_total_with_tax;

    sub_total_with_tax  = sub_total_with_tax+line_total_with_tax;
    $("#line_total_"+id).val(parseFloat(line_total_with_tax).toFixed(2));


  }); 
  $("#sub_total").val(parseFloat(sub_total).toFixed(2));
  $("#sub_total_with_tax").val(parseFloat(sub_total_with_tax).toFixed(2));

  var discount_type = $("#discount_type").val();
  var discount_rate = $("#discount_rate").val();
  if(discount_rate>0){
    discount_rate = parseFloat(discount_rate);

    if(discount_type==1){
      var total_amount = sub_total_with_tax-discount_rate;
      $("#total_amount").val(parseFloat(total_amount).toFixed(2));
    }
    else{
      var total_amount =parseFloat(sub_total_with_tax*discount_rate/100);
      total_amount =sub_total_with_tax-total_amount;
      $("#total_amount").val(parseFloat(total_amount).toFixed(2));
    }
  }else{
     $("#total_amount").val(sub_total_with_tax.toFixed(2));
  }
}

function  validateCreateInvoiceForm(){
  var hideErrors = (hideErrors == 'true' || hideErrors == true)?true:false;
  var error_flag = 0;
  var focus_id;

  $(".require",'#invoice_form').each(function(i, e){ 
    switch(e.type){ 
      case 'text':
                 if($(e).val()==""){
                  inlineMsg(e.id,'Please enter '+$(e).attr('title'),hideErrors);
                  error_flag = 1;
                  focus_id = focus_id ? focus_id : e.id;
                 }
                 break;
      case 'textarea':
                      if($(e).val()==""){
                        inlineMsg(e.id,'Please enter '+$(e).attr('title'),hideErrors);
                        error_flag = 1;
                        focus_id = focus_id ? focus_id : e.id;
                      }
                      break; 
      case 'select-one': 
                      if($(e).val() ==0 ){  
                        inlineMsg(e.id,'Please select '+$(e).attr('title'),hideErrors);
                        error_flag = 1;
                        focus_id = focus_id ? focus_id : e.id;
                      } 
                      break;
      case 'number': 
                      if($(e).val() <=0 ){  
                        inlineMsg(e.id,'Please select '+$(e).attr('title'),hideErrors);
                        error_flag = 1;
                        focus_id = focus_id ? focus_id : e.id;
                      } 
                      break;                                
      default:
            break;
    } 
  }); 

   if(focus_id && !hideErrors){
        $('#'+focus_id).focus();
    }

      if(error_flag==1){
        return false;
      }
      else{
        var data               = $("#invoice_form").serialize();
        var sub_total          =  $("#sub_total").val();
        var sub_total_with_tax =  $("#sub_total_with_tax").val();
        var total_amount       =  $("#total_amount").val();
        
        var response = $.ajax({
                            url:"custom.php",
                            method:"POST",
                            dataType: "json",
                            data:data+"&from=create_invoice&sub_total="+sub_total+"&sub_total_with_tax="+sub_total_with_tax+"&total_amount="+total_amount, 
                            async: false
                        }).responseText;
        if(response>0)
          window.location.href='invoice_list.php';
        
      }
}

function deleteRow(id){
  $("#row_"+id).remove();
  calculateTotalAmount();
}



