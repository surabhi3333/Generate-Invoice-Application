<?php
session_start();
include('header.php');
include 'class/InvoiceData.php';

$obj_product = new InvoiceData();
if(isset($_GET['invoice_id']) && $_GET['invoice_id']>0) {
	$invoice_list	  = $obj_product->getInvoiceList($_GET['invoice_id']);	//echo "<pre>";print_r($invoice_list);
	$invoice_list 	  = $invoice_list[0];	 
	$inv_product_dtls = $obj_product->getInvoiceProductDetails($_GET['invoice_id']); //echo "<pre>";print_r($inv_product_dtls);die;
	
	$invoice_date = date("d/m/Y", strtotime($invoice_list['invoice_created_date']));
	$discount_type = $invoice_list['invoice_discount_type'];
	$type = "$";
	if($discount_type==2)
		$type = "%";

	$html_string = '';

	$html_string .= '
		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			<tr>
			<td colspan="2" align="center" style="font-size:18px"><b>Invoice</b></td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%" cellpadding="5">
						<tr>
							<td colspan="1">
							    <b>From </b><br />
							    Samsung <br> 
						        6th Floor, DLF Centre, <br>  
						        Sansad Marg, New Delhi-11000 <br>
							</td>
							<td colspan="1">         
							    <b>To </b><br />
								Name : '.$invoice_list['invoice_receiver_name'].'<br /> 
								Address : '.$invoice_list['invoice_receiver_address'].'<br />
							</td>
						</tr>
						<tr>
							<td width="70%"></td><td width="30%">         
								Invoice No. : '.$invoice_list['invoice_rep'].'<br />
								Invoice Date : '.$invoice_date.'<br />
							</td>
						</tr>
					</table>
					<br />';
	$html_string .= '
					<table width="100%" border="1" cellpadding="5" cellspacing="0">
						<tr>
							<th align="left">Sr No.</th>	
							<th align="left">Product Name</th>
							<th align="left">Quantity</th>
							<th align="left">Unit Price ($)</th>
							<th align="left">Tax Rate (%)</th>
							<th align="left">Total</th>
						</tr>';
	$i = 1;					
	foreach ($inv_product_dtls as $key => $product) {
		$inline_total          = $product['invpr_quantity']*$product['product_unit_price'];
		$inline_total_with_tax = $inline_total*$product['invpr_tax_rate']/100;
		$inline_total_with_tax = $inline_total+$inline_total_with_tax;
		
		$html_string .= '
						<tr>
							<td align="left">'.$i.'</td>	
							<td align="left">'.$product['product_name'].'</td>
							<td align="left">'.$product['invpr_quantity'].'</td>
							<td align="left">'.$product['product_unit_price'].'</td>
							<td align="left">'.$product['invpr_tax_rate'].'</td>
							<td align="left">'.$inline_total_with_tax.'</td>
						</tr>';
		$i++;				
	}
$html_string .= '
					    <tr>
							<td align="right" colspan="6"><br><br></td>
						</tr> 
						<tr>
							<td align="right" colspan="5"><b>Subtotal without Tax: </b></td>
							<td align="left"><b>'.$invoice_list['invoice_sub_total'].'</b></td>
						</tr>
						<tr>
							<td align="right" colspan="5">Subtotal with Tax: </td>
							<td align="left">'.$invoice_list['invoice_sub_total_with_tax'].'</td>
						</tr>
						<tr>
							<td align="right" colspan="5">Discount on Subtotal with Tax ('.$type.') : </td>
							<td align="left">'.$invoice_list['invoice_discount_rate'].'</td>
						</tr>
						<tr>
							<td align="right" colspan="5">Total Amount:</td>
							<td align="left">'.$invoice_list['invoice_total_amount'].'</td>
						</tr>';
	$html_string .= '
					</table>
				</td>
			</tr>
		</table>';

}
ob_end_clean();
require_once 'dompdf/autoload.inc.php';  
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
$dompdf = new Dompdf();
$dompdf->loadHtml(html_entity_decode($html_string));
$dompdf->setPaper('A4', 'landscape'); 
// Render the HTML as PDF 
$dompdf->render(); 
$invoiceFileName = 'Invoice-'.$invoice_list['invoice_id'];
$dompdf->stream($invoiceFileName, array("Attachment" => 0));
?>   
   