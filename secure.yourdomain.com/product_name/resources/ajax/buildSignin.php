<div id='kcartSignin'>
	<form id='kformSignup'>
    
    	<input type='text' name='eCommerceLogin' placeholder='Email Address' isRequired>
    	<br>	
    	<input type='password' name='eCommercePassword' placeholder='Password' isRequired>
    	<br>
        <br>
        
        <?php if($ksdk->pageType == 'ASYNC') { ?>
        
        <span id='kcartContinueGuest' style='color:blue;cursor:pointer'> Continue as Guest </span>
    	<?php } ?>
        
    	<input type='submit' style='float:right' value='Sign In'>
        
    	<div style='clear:both'></div>
    </form>
</div>