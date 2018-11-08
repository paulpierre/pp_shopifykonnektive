<?php

//init vars
$customerId = $paySources = $billShipSame = $primaryPaySourceId = $emailAddress = $phoneNumber = NULL;
$firstName = $lastName = $address1 = $address2 = $city = $state = $country = NULL;
$shipFirstName = $shipLastName = $shipAddress1 = $shipAddress2 = $shipCity = $shipState = $shipCountry = NULL;

//contains data on the customer
$customer = $ksdk->getCustomer();

//vars initialized to null are overwriten by the extract statement
extract((array) $customer);

?>

 <div class='kprofileTitle'>Account Info</div>

  <div style='float:left;width:300px'>
    <div class='kprofileChangeThis' id='kprofileEditAccountInfo'>
        Edit Account Info
      </div>
      
      <div class='kprofileSubTitle'>
        Contact
      </div>
      
      
      <div class='kprofileLabel'>
        Email:
      </div>
      <div class='kprofileValue'>
        <?php echo $customer->emailAddress; ?>
      </div>
      <br>
      
      <div class='kprofileLabel'>
        Phone:
      </div>
      <div class='kprofileValue'>
        <?php echo $customer->phoneNumber; ?>
      </div>
      <br>
      
      
      <div class='kprofileSubTitle'>
        Billing Address
     
      </div>
      
      <?php

        echo $firstName.' '.$lastName.'<br>'
             .$address1.'<br>'
             .($address2 ? $address2.'<br>' : '')
             .$city.', '.(isset($state) ? $state.' ' : '').$postalCode;
        
      ?>
     <br>

    <div style='margin-top:10px'>    
      <input type='checkbox' disabled name='billShipSame' <?php echo $billShipSame ? 'checked' : '' ?>>
      Shipping same as billing
     
    </div>
    <br>
  </div>
  <div style='float:left'>

      <div class='kprofileSubTitle'>Pay Sources</div>
      
      <?php
	  //display primary pay source at top of list
	  if(!empty($paySources->$primaryPaySourceId)){  
		$sortps = array($paySources->$primaryPaySourceId);
		foreach((array) $paySources as $ps){
			if(!$ps->isPrimary){
				$sortps[] = $ps;
	   }}}

	  

	  foreach($sortps as $ps) {
		  
		  $name = ($ps->paySourceType == 'CREDITCARD' ? ucfirst(strtolower($ps->cardType)) : "eCheck")." ".($ps->paySourceType == 'CREDITCARD' ? $ps->cardLast4 : $ps->achLast4);
		  $cls = 'kprofilePaySourceTile';
		  if($ps->isPrimary)
		  	$cls .= ' kprofilePrimaryPaySource';
          ?>
      <div class='<?php echo $cls ?>' style='width:320px'>
      	<?php if(!$ps->isPrimary) { ?>
        <div class='kprofileChangeThis kprofilePaySourceEdit' paySourceId='<?php echo $ps->paySourceId ?>' paySourceAbbr='<?php echo $name; ?>' style='float:right;margin-top:-5px'>Edit</div>
		<?php } ?>
        <?php if($ps->paySourceType == 'CREDITCARD') {
            echo ucfirst(strtolower($ps->cardType)).'<br>'
                 .'************'.$ps->cardLast4."<br>"
                 .'<b>exp.</b> '.$ps->cardMonth.'/'.$ps->cardYear;
                 
        }elseif($ps->paySourceType == 'CHECK'){
            echo $ps->achBankName."<br>"
                 . ucfirst(strtolower($ps->achAccountType))." Account<br>"
                 .'*********'.$ps->achLast4;
        } ?>
      </div>
    
  <?php } ?>
  
 </div>
 
 <div id='kformPaySourceDialog' style='display:none'>
	<form class='kform'>
    	<br>
    	<input type='radio' name='action' value='primary'>
        Make this your primary pay source<br>
        <input type='radio' name='action' value='delete'>
        Delete this pay source<br>
        <br>
        <center>
        	<input type='submit' value='Submit'>
        </center>
    </form>
</div>
 
 <div style='clear:both'></div>