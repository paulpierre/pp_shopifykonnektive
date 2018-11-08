<?php

//get current cart items from ksdk
$cart = (array) $ksdk->getSessValue('cart');

//get product details from ksdk
$products = $ksdk->getProducts();

//if the customer has logged into their eCommerce account, this object will exist in session
$customer = $ksdk->getSessValue('customer');

//where user is sent after clicking the "Continue Shopping" html button
$shopUrl = $ksdk->getPageUrl('catalogPage');
		
		
		?>
<div id='kcartDetail'>
	<br>
	<!-- a button for going back and adding more items to the cart -->
    <!-- the href attribute does not usually go on input buttons, but we've added it here as the button behaves like a link
    and sends the user to the url specified in the href attribute -->
    <input type='button' value='Continue Shopping' class='kcartShopButton' href="<?php echo $shopUrl; ?>">
    <div id='kcartTitle'>Your Shopping Cart</div>
    
    <?php if(!empty($customer)) { ?>
    	<span class='kcartLogoutWrap'>
        	logged in as <?php echo $customer->emailAddress; ?>
            <span id='kcartLogout'>log out</span>
        </span>
    <?php } else {?>
    	<span  class='kcartLogoutWrap'> Have an Account ? </span><span id='kcartSigninButton'>Sign In</span>
    	<?php
	}
		//build a table with column titles
		?>
<table id='kcartTable'>
	<tr id='kcartTitleRow'> 
    	<td  style='width:200px'>Item</td>
        <td>Qty</td>
        <td>Description</td> 
        <td>Amount</td> 
        <td >&nbsp;</td> 
    </tr>
		<?php
		
		//If cart is empty, display a message about the empty cart
		if(empty($cart))
		{
			?>
	<tr>
        <td colspan=5 id='kcartEmptyCartWarning'>
    Your shopping cart is currently empty. Click the continue shopping button to add products to your cart.
        </td>
    </tr>
</table>

			<?php
			return;
		}
		
		//loop through products in the cart, building each row of the table
		foreach($cart as $productId=>$qty)
		{
			//get detailed information about the individual item
			//details are stored in an object in the products array
			$item = $products[$productId];
			
			//define variables to output in html
			$name = $item->name;
			$description = "<i>*description unvailable</i>";
			if(!empty($item->description))
				$description = $item->description;
				
			$currency = $ksdk->currencySymbol;
			$itemSubTotal = number_format($item->price,2);
			$imageUrl = $item->imagePath;
			
			//build the row <tr>
			
			?>			
	<tr class='kcartItem' productId='<?php echo $productId ?>'>
        <td style='width:200px'>
            <img src='<?php echo $imageUrl ?>' class='kcartProductImage'>
            &nbsp;&nbsp;<?php echo  $name ?>
        </td>
        <td>
        	<div style='white-space:nowrap'>
                <span class='kcartMinusBtn'>-</span>
                <span class='kcartItemQty'><?php echo $qty ?></span>
                <span class='kcartPlusBtn'>+</span>
            </div>	
        </td>
        <td style='text-decoration:underline'>
            <?php echo $description ?> 
        </td>
        <td> 
            <?php echo  $currency ?><span class='kcartItemTotal'><?php echo $itemSubTotal; ?></span>
        </td>
        <td style='text-align:center'>
            <input type='button' value='X' class='kcartRemoveBtn'>
        </td>
    </tr>
<?php 
		}
		
		//done looping, close out the table
		
		?>
	</table>
</div>
<div>
</div>

