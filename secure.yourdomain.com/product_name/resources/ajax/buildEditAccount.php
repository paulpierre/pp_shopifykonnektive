<?php

$customer = $ksdk->getCustomer();
if(empty($customer))
	$ksdk->triggerExpiredSession();
	
?>

<div class='kprofileTitle'>Edit Account Info</div>

<form id='kprofileAccountForm' class='kform' style='padding:10px; border:1px solid #CCC'>

	<div style='width:300px;float:left;padding:20px'>
    	<div class='kprofileChangeThis' id='kprofileCancelEdit'>
        	Cancel Edit
        </div>

    
        <div class='kprofileSubTitle'>
            Contact:
        </div>
        
        <div class='kformSpacer'>
            <input type='email' name='emailAddress' isRequired placeholder='Email Address'>
        </div>
        <div class='kformSpacer'>
            <input type='tel' name='phoneNumber' isRequired placeholder='Phone Number'>
        </div>
  
         <div class='kprofileSubTitle'>
            Shipping Address
        </div>
        
        <div class='kformSpacer'>
            <input name='billShipSame' type='CHECKBOX'>
            Shipping same as Billing
        </div>
        
        <div id='kprofileFormHiddenAddress'>
            <div class='kformSpacer'>
                <input name='shipAddress1' type='TEXT' isRequired placeholder='Address 1'>
            </div>
            <div class='kformSpacer'>
            <input name='shipAddress2' type='TEXT' placeholder='Address 2'>
            </div>
            <div class='kformSpacer'>
                <input name='shipCity' type='TEXT' isRequired  placeholder='City'> 
            </div>
            <div class='kformSpacer'>
                <select name='shipState' isRequired></select>
            </div>
            <div class='kformSpacer'>
                <select name='shipCountry'></select>
            </div>
            <div class='kformSpacer'>
                <input name='shipPostalCode' type='TEXT' placeholder='Postal Code' isRequired>
            </div>
        </div>
	
    </div>
    <div style='width:300px;float:left;padding:20px'>
    	<div class='kprofileSubTitle'>
    	Billing Address
    	</div>
  
        <div class='kformSpacer'>
            <input name='address1' type='TEXT' isRequired placeholder='Address 1'>
        </div>
        <div class='kformSpacer'>
            <input name='address2' type='TEXT' placeholder='Address 2'>
        </div>
        <div class='kformSpacer'>
            <input name='city' type='TEXT' isRequired placeholder='City'>
        </div>
        <div class='kformSpacer'>
            <select name='state'></select>
        </div>
        <div class='kformSpacer'>
            <select name='country'></select>
        </div>
        <div class='kformSpacer'>
            <input name='postalCode' type='TEXT' placeholder='Postal Code' isRequired>
        </div>
    
    	
    
    </div>
    
    <br>
    <div style='margin-left:20px;float:left'>
        <input type='submit' value='Update Info'>
	</div>

	<div style='clear:both'></div>
</form>



