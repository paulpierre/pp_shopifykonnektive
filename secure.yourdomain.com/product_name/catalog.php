<?php
//This code must be included at the top of your script before any output is sent to the browser
//-even before <!DOCTYPE> declaration
require_once realpath(dirname(__FILE__)."/resources/konnektiveSDK.php");
$pageType = "catalogPage"; //choose from: presalePage, leadPage, checkoutPage, upsellPage1, upsellPage2, upsellPage3, upsellPage4, thankyouPage
$deviceType = "ALL"; //choose from: DESKTOP, MOBILE, ALL
$ksdk = new KonnektiveSDK($pageType,$deviceType);
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



<div style="max-width:800px;min-width:500px">  

<div style="float:right;margin-bottom:20px">
<?php echo $ksdk->getWidget(); ?>

</div>
<div style="clear:both"></div>

        <?php
foreach($offers as $offer) 
{
	$link = $ksdk->getPageUrl("checkoutPage")."?productId=".$offer->productId;
	$prodLink = $ksdk->getPageUrl("productDetails");
	?>
	<div class="kform_catalogBox" >
		<img src="<?php echo $offer->imagePath; ?>" class="kform_productBoxImage">
		<div class="kcartAddToCartButton" productId="<?php echo $offer->productId?>"></div>
		
		<h3> <a href="<?php echo $prodLink?>?productId=<?php echo $offer->productId; ?>"><?php echo $offer->name; ?></a> &nbsp;&nbsp;&nbsp;(<?php echo $ksdk->currencySymbol.$offer->price; ?>)</h3>
		
		<?php echo $offer->description; ?>
		
		<div style="clear:both"></div>
	</div>
<?php
}
?>
 
</div>

</body>
</html>







