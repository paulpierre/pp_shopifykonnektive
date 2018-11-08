<?php
//This code must be included at the top of your script before any output is sent to the browser
//-even before <!DOCTYPE> declaration
require_once realpath(dirname(__FILE__)."/resources/konnektiveSDK.php");
$pageType = "catalogPage"; //choose from: presalePage, leadPage, checkoutPage, upsellPage1, upsellPage2, upsellPage3, upsellPage4, thankyouPage
$deviceType = "ALL"; //choose from: DESKTOP, MOBILE, ALL
$ksdk = new KonnektiveSDK($pageType,$deviceType);
$productId = filter_input(INPUT_GET,"productId",FILTER_SANITIZE_NUMBER_INT);
$offer = $ksdk->getProduct($productId);
if(empty($productId) || empty($offer))
{
	$url = $ksdk->getPageUrl("catalogPage");
	$ksdk->redirect($url);
}


$offers = $ksdk->getOffers();


?>
<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width" />
<meta charset="utf-8" />

<?php 
//this line of code must go either inside the <head> </head> tags or inside the <body></body> tags
$ksdk->echoJavascript();
?>
</head>
<body>


<!-- All ktemplate class divs are for presentation purposes only and can be replaced with your own styling and html elements -->        
<div class="ktemplate_pageContainer">
	<div class="ktemplate_header">
    	<h1> Your Header </h1>
    </div>
	
	<div style="float:left;margin-bottom:20px">
		<?php echo $ksdk->getWidget(); ?>
	</div>
	<div style="clear:both"></div>
	
    <div class="ktemplate_boxLeft">
       
        <?php
		
		$productId = filter_input(INPUT_GET,"productId",FILTER_SANITIZE_NUMBER_INT);
		$offer = $offers[$productId];
		
		?>
		<div class="kform_catalogBox">
			<img src="<?php echo $offer->imagePath; ?>" class="kform_productBoxImage">
			<div class="kcartAddToCartButton" productId="<?php echo $offer->productId?>"></div>	
					
			<h3> <?php echo $offer->name; ?> &nbsp;&nbsp;&nbsp;(<?php echo $ksdk->currencySymbol.$offer->price; ?>)</h3>
			
			<?php echo $offer->description; ?>
			
			<div style="clear:both"></div>
		</div>

 	</div>
    <div class="ktemplate_sideBar">
        <h1> Your Sidebar </h1>
    </div>
</div>

</body>
</html>







