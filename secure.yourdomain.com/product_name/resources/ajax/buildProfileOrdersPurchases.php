<?php

$customer = $ksdk->getCustomer();
$currency = $ksdk->currencySymbol;

$orders = $customer->orders;
$purchases = $customer->purchases;

$cancel_reasons = array();
$cancel_reasons[] = "No longer this product";
$cancel_reasons[] = "Don't Like the product";
$cancel_reasons[] = "Product is too expensive";
$cancel_reasons[] = "Found another product";

if(empty($orders))
	$orders = array();
if(empty($purchases))
	$purchases = array();
	//document.getElementById('cancelReasonOther').style.display = this.value == '' ? 'block' : 'none'
?>

<div id='kformCancelDialog' style='display:none;padding:20px'>
	<form class='kform'>
    	<select name='cancelReason' onchange="">
        	<option>Don't like the product</option>
            <option>Product is too expensive</option>
            <option>Found another product</option>
            <option value=''>Other</option>
        </select>
        <br>
        <input type='text' name='cancelReasonOther' style='display:none;margin-top:20px' placeholder="Enter Cancel Reason">
        <br>
        <br>
        <center>
        	<input type='submit' value='Cancel'>
        </center>
    </form>
</div>


<div id='kprofileRecentOrder'>

<div class='kprofileTitle'>Recent Orders</div>
<table class='kprofileTable'>
	<tr class='titleRow'>
    	<td>Order</td>
        <td>Total</td>
        <td>Status</td>
        <td>Items</td>
    </tr>
    
    
    
<?php

foreach($orders as $order){?>
	<?php
    $itemsStr = '';
	foreach($order->items as $item)
	{
		$itemsStr .= "<div style='white-space:nowrap;'>".$item->qty.' - '.$item->productName."</div>";	
	}
    
	$date = new DateTime($order->dateCreated);
	$dateStr = $date->format("m/d")
			   ." ".$date->format("g:ia");
	
	$statusStr = '';
	$shipmentDetails = '';
	$pending = 0;
	$shipped = 0;
	$cancelled = 0;
	$failed = 0;
	
	$shipmentDetails = "<br>
	<table class='kprofileTable' style='width:600px'>
		<tr class='titleRow'>
			<td>Products</td>
			<td>Ship Method</td>
			<td>Tracking</td>
			<td>Status</td>
			<td>Ship Date</td>
		</tr>";
			
	$hasHold = false;
	foreach($order->fulfillments as $f)
	{
		if($f->status == 'HOLD')
			$hasHold = true;
		
		if($f->status == 'HOLD' || $f->status == 'PENDING')
			$pending++;
		if($f->status == 'SHIPPED')
			$shipped++;
		if($f->status == 'CANCELLED')
			$cancelled++;
		if($f->status == 'FAILED')
			$failed++;
			
		
		if(!empty($f->dateShipped))
		{
			$dateShipped = new DateTime($f->dateShipped);
			$dateShipped = $date->format("m/d");
		}
		else
		{
			$dateShipped = '---';
		}	
		$items = implode("<br>",$f->items);
		$shipmentDetails .= "<tr><td>$items</td>"
							."<td>$f->shipMethod</td>"
							."<td>$f->trackingNumber</td>"
							."<td>$f->status</td>"
							."<td>$dateShipped</td></tr>";
			
	}
	
	$shipmentDetails .= "</table><br><br><center><input type='button' value='Ok' class='kdialogClose'></center>";
	
	if($pending > 0)
		$statusStr .= "Pending ".($pending > 1 ? "($pending)" : '')."<br>";
	if($shipped > 0)
		$statusStr .= "Shipped ".($shipped > 1 ? "($shipped)" : '')."<br>";
	if($cancelled > 0)
		$statusStr .= "Cancelled ".($cancelled > 1 ? "($cancelled)" : '')."<br>";
	if($failed > 0)
		$statusStr .= "Failed ".($failed > 1 ? "($failed)" : '')."<br>";
	
	$id = rand(10000,99999);
	
	if($hasHold)
	{
		$statusStr .= "<span class='kprofileChangeThis kprofileCancelOrder' orderId='{$order->clientOrderId}'>Cancel Order</span>";		
	}
	else
	{		
		$statusStr .= "<span class='kprofileChangeThis' onclick='kform.displayShipmentDetails({$order->orderId});'>Details</span>";
		$statusStr .= "<div class='kprofileShipmentDetails' orderId='{$order->orderId}'>".$shipmentDetails."</div>";
	}
    ?>
    <tr>
    	<td>
			<?php echo $order->clientOrderId; ?><br>
            <?php echo $dateStr ?>
        </td>
        <td><?php echo $currency.$order->amountPaid; ?></td>
        <td><?php echo $statusStr ?></td>
        <td>
			<?php echo $itemsStr ?>
            <?php if($ksdk->allowQuickOrders){ 
			
			$orderItems = array();
			foreach($order->items as $item)
			{
				$orderItems[$item->productId] = $item->qty;	
			}
			
			?>
            <span class='kprofileChangeThis kprofileReorderLink' orderItems='<?php echo json_encode($orderItems); ?>'>Reorder Now</span>
        	<?php } ?>
        </td>
    </tr>
    
<?php } ?>

</table>

</div>

<div id='kprofilePurchases'>

<div class='kprofileTitle'>Subscriptions</div>
<table class='kprofileTable'>
	<tr class='titleRow'>
 	    <td>Qty</td>
    	<td>Product</td>
        <td>Status</td>
        <td>Next Bill</td>
        <td>Amount</td>
        <td>&nbsp;</td>
    </tr>
    
    
<?php 

foreach($purchases as $purch) {
	
	$isActive = $purch->status == 'ACTIVE' || $purch->status == 'TRIAL';
	$isCancelled = $purch->status == 'CANCELLED';
	if(!$isActive)
		$purch->nextBillDate = NULL;
		
		
	if(!empty($purch->nextBillDate))
	{	
		$nextBillDate = date("m/d",strtotime($purch->nextBillDate));
		if($isActive && $ksdk->allowHolds)
		{
			$nextBillDate .= "<br><span class='kprofileHoldPurchase kprofileChangeThis' purchaseId='{$purch->purchaseId}'>hold</span><br>";	
		}
	}
	else
	{
		$nextBillDate = '---';	
	}
	$price = $purch->price;
	
	$status = ucfirst(strtolower($purch->status));
	if($status == 'Cancelled')
		$status = "<span style='color:red'>$status</span>";
	elseif($isActive)
		$status = "<span style='color:green'>$status</span>";
		
	
	
	$actions = '';
	if($isActive)
	{
		if($ksdk->allowCancels)
		{
			$actions .= "<span class='kprofileCancelPurchase kprofileChangeThis' purchaseId='{$purch->purchaseId}'>cancel</span><br>";	
		}
		
	}
	if($isCancelled)
	{
		$actions .= "<span class='kprofileChangeThis kprofileRestartPurchase' purchaseId='{$purch->purchaseId}'>reactivate</span><br>";	
	}
	
	echo "<tr>
			<td>{$purch->productQty}</td>
			<td>{$purch->productName}</td>
			<td>{$status}</td>
			<td>{$nextBillDate}</td>
    		<td>{$currency}{$price}</td>
			<td>{$actions}</td>
    	</tr>
		";
} ?>






</table>
</div>