<?php

require_once realpath(dirname(__FILE__)."/mobileDetect.php");
require_once realpath(dirname(__FILE__)."/config.php");

//uncomment if debugging in development
//ini_set('display_errors','1');

class KonnektiveSDK
{
    protected $noCurl = false;
	protected $deviceType = 'ALL';
	protected $mobileRedirectUrl = false;
	protected $autoImportLead = false;
	protected $disableBackButton = false;
	protected $allowReorder = false;
	
	public $allowCancels = true;
	public $allowHolds = true;
	public $allowQuickOrders = true;
	public $customLoadScript = '';
	
	public $redirectsTo = NULL;
	public $reorderUrl;
	public $pageType = NULL;
	public $config = NULL;
	static $instance = NULL;
	
	
	const ENDPOINT_IMPORT_CLICK = "https://api.konnektive.com/landingpages/clicks/import/2/";
	const ENDPOINT_IMPORT_LEAD = "https://api.konnektive.com/leads/import/";
	const ENDPOINT_IMPORT_ORDER = "https://api.konnektive.com/order/import/";
	const ENDPOINT_IMPORT_TAX = "https://api.konnektive.com/order/salestax/";
	const ENDPOINT_IMPORT_UPSALE = "https://api.konnektive.com/upsale/import/";
	const ENDPOINT_QUERY_PIXELS = "https://api.konnektive.com/landingpages/pixels/query/";
	const ENDPOINT_SEND_CONFIRM = "https://api.konnektive.com/order/confirm/";
	const ENDPOINT_CONFIRM_PAYPAL = "https://api.konnektive.com/transactions/confirmPaypal/";
	const ENDPOINT_IMPORT_AUTH = "https://api.konnektive.com/transactions/CardAuth1/";
	const ENDPOINT_CANCEL_ORDER = "https://api.konnektive.com/order/cancel/";

	const ENDPOINT_PROFILE_QUERY = 'https://api.konnektive.com/customer/profile/query/';
	const ENDPOINT_PROFILE_UPDATE = 'https://api.konnektive.com/customer/profile/update/';
	const ENDPOINT_PROFILE_CREATE = 'https://api.konnektive.com/customer/profile/import/';
	
	function __construct($pageType=NULL,$deviceType='ALL')
	{

		//validate configuration and do preprocessing
		$this->doConfiguration($pageType,$deviceType);

		//start the users web session
		$this->startSession();
		
		//redirect to https if connection is not secure

        /** TODO: Remember to uncomment this in production!!! */
        //$this->forceHttps();
		
		//detects device type and redirects to the proper set of pages
		$this->detectDeviceType();
	
		//check order status and redirect to thankyou if the order is already completed
		$this->checkOrderStatus();
		
		self::$instance = $this;
	}
	
	static function getInstance()
	{
		return self::$instance;	
	}
	
	function startSession()
	{
		if(!session_id()) 
			session_start();
			
		//check if session variables exist yet
		if(empty($_SESSION['KSDK']))
		{
			$_SESSION['KSDK'] = (object) array();
			$this->setSessValue('requestUri',$_SERVER['REQUEST_URI']);
			if(!empty($_SERVER['HTTP_USER_AGENT']))
				$this->setSessValue('userAgent',$_SERVER['HTTP_USER_AGENT']);
			if(!empty($_SERVER['REMOTE_ADDR']))
				$this->setSessValue('ipAddress',$_SERVER['REMOTE_ADDR']);
			if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				$this->setSessValue('ipAddress',$_SERVER['HTTP_X_FORWARDED_FOR']);
			if(!empty($_SERVER['HTTP_ACCEPT']))
				$this->setSessValue('acceptHeader',$_SERVER['HTTP_ACCEPT']);
			if(!empty($_SERVER['HTTP_REFERER']))
				$this->setSessValue('httpReferer',$_SERVER['HTTP_REFERER']);
		}

		$affId = $this->sanitizeInput('affId');
		if(!empty($affId))
			$this->setSessValue('affId',$affId);
		
		$sessionId = $this->sanitizeInput('sessionId');
		if(!empty($sessionId))
		{
			$this->setSessValue('sessionId',$sessionId);
			$this->redirect($this->getPageUrl($this->pageType));
		}
	}

	
	function doConfiguration($pageType,$deviceType)
	{
		$this->pageType = $pageType;
		$this->deviceType = $deviceType;

		if(empty(KFormConfig::$instance))
			$this->config = new KFormConfig;
		
		//do config
		$config = KFormConfig::$instance;
		$this->resourceDir = $config->resourceDir;
	
		if(empty(KFormConfig::$instance))
			new KFormConfig;
	
		$this->config = $config;
	
		if($config->isWordpress && $pageType == 'ASYNC')
			require_once( dirname(__FILE__) . '../../../../../wp-load.php' );
	
	
		//check required extensions
		if(!extension_loaded('json'))
			$this->throwFatalError("php-json extension is not loaded. This can be installed on most debian-based distributions with the command: apt-get install php-json");
		
		if(!extension_loaded('curl'))
		{
			$this->noCurl = true;
			if(ini_get('allow_url_fopen')!='1')
				$this->throwFatalError("must set allow_url_fopen = 1 in php.ini or install the php-curl extension. php-curl can be installed on most debian-based distributions with the command: apt-get install php-curl");
		}
		
		$this->mobileRedirectUrl = $config->mobileRedirectUrl;
		$this->desktopRedirectUrl = $config->desktopRedirectUrl;
		$this->currencySymbol = $config->currencySymbol;
		$this->landerType = $config->landerType;
		$this->termsOfService = $config->termsOfService;

		$url1 = '';
		$url2 = '';

		if($pageType != 'ASYNC')
		{
			$this->pageType = $pageType;
			$this->page = $this->getPage($pageType);
			if(empty($this->page))
				$this->throwFatalError("pageType ($pageType) passed to KonnektiveSDK not recognized.");
		
			if($pageType == 'presellPage')
			{
				$url1 = $this->getPageUrl('leadPage');
				$url2 = $this->getPageUrl('checkoutPage');
			}
			elseif($pageType == 'leadPage')
			{
				$url1 = $this->getPageUrl('checkoutPage');
			}
			elseif($pageType == 'checkoutPage')
			{
				$url1 = $this->getPageUrl('upsellPage1');
				$url2 = $this->getPageUrl('thankyouPage');
				$this->defaultOfferId = $config->webPages->checkoutPage->productId;
			}
			elseif($pageType == 'upsellPage1')
			{
				$url1 = $this->getPageUrl('upsellPage2');
				$url2 = $this->getPageUrl('thankyouPage');
			}
			elseif($pageType == 'upsellPage2')
			{
				$url1 = $this->getPageUrl('upsellPage3');
				$url2 = $this->getPageUrl('thankyouPage');
			}
			elseif($pageType == 'upsellPage3')
			{
				$url1 = $this->getPageUrl('upsellPage4');
				$url2 = $this->getPageUrl('thankyouPage');
			}
			elseif($pageType == 'upsellPage4')
			{
				$url1 = $this->getPageUrl('thankyouPage');
			}
			$this->redirectsTo = !empty($url1) ? $url1 : $url2;
		}
	}
	
	function forceHttps()
	{
		if((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") && (!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https'))
		{
			$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$this->redirect($redirect);
		}	
	}
	
	function detectDeviceType()
	{
		$detect = new Mobile_Detect;
		$this->isMobile = $detect->isMobile();
		
		if($this->deviceType == 'ALL')
			return;
		
		if($this->isMobile)
		{
			if($this->deviceType != 'MOBILE' && !empty($this->mobileRedirectUrl))
			{
				$link = $this->mobileRedirectUrl;
				$link .= !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
				$this->redirect($link); 
			}
		}
		else
		{
			if($this->deviceType != 'DESKTOP' && !empty($this->desktopRedirectUrl))
			{
				$link = $this->desktopRedirectUrl;
				$link .= !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
				$this->redirect($link); 
			}
		}
	}
	
	function checkOrderStatus()
	{
		if($this->pageType != 'ASYNC')
		{			
			
			if($this->sanitizeInput('finalizeTransaction','BOOL',INPUT_POST))
			{
				$order = json_decode($_POST['orderData']);	
				if(!empty($order))
				{
					$this->setSessValue('order',$order);	
				}
			}
			
			$order = $this->getOrder();
			$pages = array('leadPage','checkoutPage');
			if(!empty($order) &&  !empty($order->orderStatus) && !in_array($order->orderStatus,array('PARTIAL','DECLINED')) && in_array($this->pageType,$pages))
				$this->redirect($this->getPageUrl("thankyouPage"));
				
				
			if($this->sanitizeInput("paypalAccept","BOOL",INPUT_GET))
			{
				$token = $this->sanitizeInput("token","STR",INPUT_GET);
				$payerId = $this->sanitizeInput("PayerID","STR",INPUT_GET);
				$this->confirmPaypalOrder($token,$payerId);
			}
			
			if(!empty($this->config->amazonPayments) && $this->pageType == 'checkoutPage')
			{
				$this->checkAmazonProfileRedirect();
			}	
		}
	}
	
	
	function echoJavascript()
	{	
		$resourceDir = $this->resourceDir;

		$disableBack = $requirePayInfo = $autoImportLead = $isShoppingCart = $requireSig = $cardinalAuth = 'false';
		$replaceProductId = $defaultProduct = $config = $upsellId = $orderItems = 'null';
		$defaultProduct = $config = $upsellId = $orderItems = 'null';
		$reorderUrl = $redirectsTo = '';
		$displayPixels = 'true';
		$orderItems = $customer = '{}';
		
		$checkoutPageUrl = $this->getPageUrl('checkoutPage');
		
		$signinEmail = filter_input(INPUT_GET,'signinEmail',FILTER_VALIDATE_EMAIL);
		
		$page = $this->page;
		$pageType = $this->pageType;
		
		if(!empty($this->redirectsTo))
			$redirectsTo = $this->redirectsTo;
		if(!empty($page->disableBack))
			$disableBack = 'true';
		if(!empty($page->displayPixels))
			$displayPixels = 'true';		
		if(!empty($page->requirePayInfo))
			$requirePayInfo = 'true';
		if(!empty($page->autoImportLead))
			$autoImportLead = 'true';
		if(!empty($page->requireSig))
			$requireSig = 'true';
		if(!empty($page->cardinalAuth))
			$cardinalAuth = 'true';
		if($this->landerType != 'CART' && $pageType == 'checkoutPage')
			$defaultProduct = $page->productId;
			
		if(substr($pageType,0,-1) == 'upsellPage')
		{
			$upsellId = $page->productId;
			if(!empty($page->replaceProductId))
				$replaceProductId = $page->replaceProductId;
		}
	
		$config = clone $this->config;
		unset($config->apiLoginId,$config->apiPassword);
	
		$config = json_encode($config);
		
		if($this->landerType == 'CART')
			$isShoppingCart = 'true';
			
		if($pageType == 'thankyouPage' && $page->allowReorder) 
			$reorderUrl = $page->reorderUrl;
			
		$cart = $this->getSessValue('cart');
		if(!empty($cart))
			$orderItems = json_encode($cart);
		
		
		if($customer = $this->getSessValue('customer'))
			$customer = json_encode($customer);
		else
			$customer = 'null';
			
		if($pageType == 'checkoutPage' && !empty($this->config->amazonPayments))
		{
			
			if($this->config->amazonPayments->clientId)
				$amazonClientId = $this->config->amazonPayments->clientId;
			if($this->config->amazonPayments->sellerId)
				$amazonSellerId = $this->config->amazonPayments->sellerId;
			
			if(!empty($amazonClientId) && !empty($amazonSellerId))
			{
				if($this->getSessValue('amazonProfileId') != false)
				{
					
					$firstName = $this->getSessValue('amazonProfileFirstName');
					$lastName = $this->getSessValue('amazonProfileLastName');
					$emailAddress = $this->getSessValue('amazonProfileEmail');
					$accessToken = $this->getSessValue('amazonAccessToken');
					
					$this->customLoadScript .=
"
if(!kform.fetchValue('firstName'))
	kform.setValue('firstName','{$firstName}');
if(!kform.fetchValue('lastName'))
	kform.setValue('lastName','{$lastName}');
if(!kform.fetchValue('emailAddress'))
	kform.setValue('emailAddress','{$emailAddress}');
if(!kform.fetchValue('amazonAccessToken'))
	kform.storeValue('amazonAccessToken','{$accessToken}');

document.getElementById('formfields').style.display = 'none';

";		
				}
				$amazonTag = "<script type='text/javascript' src='https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js?sellerId=$amazonSellerId'></script>";
			}
		}	
			

?>
<link  type='text/css' href='<?php echo $resourceDir; ?>css/kprofile.css' rel='stylesheet' />
<link  type='text/css' href='<?php echo $resourceDir; ?>css/kform.css' rel='stylesheet' />
<link  type='text/css' href='<?php echo $resourceDir; ?>css/kcart.css' rel='stylesheet' />

<script type='text/javascript' src="<?php echo $resourceDir; ?>js/kvalidator.js"></script>
<script type='text/javascript' src="<?php echo $resourceDir; ?>js/klander.js"></script>
<script type='text/javascript' src="<?php echo $resourceDir; ?>js/kcart.js"></script>

<?php
if($requireSig)
{
	if(!empty($page->sigType))
		echo "<script type='text/javascript' src='{$resourceDir}js/signature/html2canvas.min.js'></script>";
	else
		echo "<script type='text/javascript' src='{$resourceDir}js/signature/signature_pad.js'></script>";
}

if($cardinalAuth)
{
    echo "<script src='https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js'></script>";
    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>";
}

if(isset($this->config->klaviyo))
	echo $this->echoKlaviyoJavascript();
?>


<?php
if($pageType == 'checkoutPage' && !empty($amazonClientId))
	echo "
<script type='text/javascript'>  		
window.onAmazonLoginReady = function() {
	amazon.Login.setClientId('$amazonClientId');
};
</script>
";

if(!empty($amazonTag))
	echo $amazonTag;
			
?>

<script type='text/javascript'>  
window.addEventListener('load',function()
{
	kform = new klander;
	kform.disableBack = <?php echo $disableBack ?>;
	kform.requirePayInfo = <?php echo $requirePayInfo ?>;
	kform.autoImportLead = <?php echo $autoImportLead ?>;
	kform.config = <?php echo $config ?>;
	kform.defaultProduct = <?php echo $defaultProduct ?>;
	kform.isShoppingCart = <?php echo $isShoppingCart ?>;
	kform.displayPixels = <?php echo $displayPixels ?>;
	kform.requireSig = <?php echo $requireSig ?>;
	kform.reorderURL = '<?php echo $reorderUrl ?>';
	kform.redirectsTo = '<?php echo $redirectsTo ?>';
	kform.resourceDir = '<?php echo $resourceDir; ?>';
	kform.upsellId = <?php echo $upsellId; ?>;
	kform.orderItems = <?php echo $orderItems; ?>;
	kform.customer = <?php echo $customer; ?>;
	kform.replaceProductId = <?php echo $replaceProductId; ?>;
	kform.checkoutPageUrl = '<?php echo $checkoutPageUrl; ?>';
	kform.signInEmail = '<?php echo $signinEmail; ?>';
    kform.construct("<?php echo $pageType; ?>");
	
	<?php
	if(isset($this->config->klaviyo))
	echo "initKlaviyo();"
	?>

	<?php
	if(!empty($this->customLoadScript))
		echo $this->customLoadScript;
	?>
	
});

</script>
		<?php
		
		$this->echoGoogleAnalytics();
		
	}
	
	function echoGoogleAnalytics() 
	{
		if(empty(KFormConfig::$instance->googleTrackingId))
			return;
		?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo KFormConfig::$instance->googleTrackingId ?>', 'auto');
ga('send', 'pageview');
</script>
		<?php
		if($this->pageType == "thankyouPage")
		{
				?>
<script type='text/javascript'>
	ga('require', 'ecommerce');
	<?php $order = $this->getSessValue('order'); ?>
	ga('ecommerce:addTransaction', {
		'id': '<?php echo $order->orderId ?>',
		'affiliation': '<?php echo KFormConfig::$instance->companyName ?>',
		'revenue': '<?php echo $order->totalAmount ?>',
		'shipping': '<?php echo $order->baseShipping ?>',
	})
	<?php foreach($order->items as $item) { ?>
	ga('ecommerce:addItem', {
		'id': '<?php echo $order->orderId ?>',
		'sku': '<?php echo $item->productId ?>',
		'name': '<?php echo $item->name ?>',
		'price': '<?php echo $item->price ?>',
		'quantity': '<?php echo $item->qty ?>',
		'currency': 'USD'						
	})
	<?php } ?>
	ga('ecommerce:send');
</script>
           <?php
		}
	}
	
	function echoAmazonWidgets()
	{
		if(!empty($this->config->amazonPayments->sellerId))
			$amazonSellerId = $this->config->amazonPayments->sellerId;
		
		$profileId = $this->getSessvalue('amazonProfileId');
		
		if(empty($profileId))
			return;
		
		?>
		
<div id="addressBookWidgetDiv"></div> 
<script type='text/javascript'>
window.addEventListener('load',function()
{
	AmazonAddressBook = new OffAmazonPayments.Widgets.AddressBook({
		sellerId: '<?php echo $amazonSellerId; ?>',
		 onOrderReferenceCreate: function(orderReference) {
			var id = orderReference.getAmazonOrderReferenceId();
			kform.storeValue('amazonOrderId',id);
		  },
		onAddressSelect: function(something) {
			kform.setValue('paySource','AMAZON');
			kform.storeValue('amazonAddressSelected','1');
			kform.storeValue('amazonPaymentSelected','0');
		},
		design:{
			designMode: 'responsive'
		},
		onError: function(error) {
			//alert('AMAZON ERROR\n'+error.getErrorCode() + ': ' + error.getErrorMessage());
		}
	}).bind('addressBookWidgetDiv');
});
</script>


<div id="walletWidgetDiv"></div>
<script type='text/javascript'>
window.addEventListener('load',function()
{
	AmazonWallet = new OffAmazonPayments.Widgets.Wallet({
		sellerId:  '<?php echo $amazonSellerId; ?>',
		onPaymentSelect: function(billingAgreement){
			kform.storeValue('amazonPaymentSelected','1');
			},
		design: {
			designMode: 'responsive'
		},
		onError: function(error) {
			//alert('AMAZON ERROR\n'+error.getErrorCode() + ': ' + error.getErrorMessage());
		}
	}).bind('walletWidgetDiv');
});
</script>
		
		<?php
	}
	
	function echoAmazonSignInButton()
	{
		
		if(!empty($this->config->amazonPayments->sellerId))
			$amazonSellerId = $this->config->amazonPayments->sellerId;
		
		if($this->getSessValue('paypalProfileId') != false)
			return;	
			
		if($this->getSessValue('amazonProfileId') != false)
			return;
			
			
		?>
		<div id="AmazonPayButton"></div>
		<script type='text/javascript'>
        var authRequest;
SignInButton = OffAmazonPayments.Button("AmazonPayButton", "<?php echo $amazonSellerId; ?>", {
	type:  "PwA", //LwA, A, Pay, 
	color: "Gold", //LightGray, DarkGray
	//size:  "medium", // small, large, x-large
	useAmazonAddressBook: true,
	authorization: function() {
	  loginOptions = {scope: "profile postal_code payments:widget payments:shipping_address", popup: "true"};
	  authRequest = amazon.Login.authorize(loginOptions, "<?php echo $this->getPageUrl('checkoutPage'); ?>");
	},
	onError: function(error) {
	  MY_ERROR = error;
		alert("AMAZON ERROR\n"+error.getErrorCode() + ": " + error.getErrorMessage());
	}
});	
</script>

		<?php
		
	}
	
	function echoAmazonPayNowButton()
	{
		if($this->getSessValue('amazonProfileId') == false)
			return;

		?>
        <img onclick='kform.ajaxSubmit()' style='cursor:pointer;margin-top:20px' src='resources/images/pay-with-amazon.png'>
        <br>
        <a href='javascript:kform.logoutOfAmazon()' style='margin-top:10px;font-size:smaller;display:block'>Nevermind, I do not want to pay with amazon </a>
        
        <script type='text/javascript'>
            document.getElementById('kformSubmit').style.display = 'none';
			window.addEventListener('load',function()
			{
				var paypal;
				if(paypal = document.getElementById('kform_payPalButton'))
				{
					paypal.style.display = 'none';	
				}
			});
        </script>
        <?php	
	}
	
	function echoUpsaleCheckoutButton()
	{
		$paypalPayment = $this->sanitizeInput("paypalSuccess","BOOL",INPUT_GET);
		if($paypalPayment)
			$this->echoPaypalCheckoutButton();
		?>
		<input type="button" value="Add to Order" <?php if ($paypalPayment) echo 'style="display:none;"'; ?> class="kform_upsellBtn"  id="kformSubmit"><br><br>
		<?php
	}
	
	function echoPaypalCheckoutButton()
	{
		?>
        <img src='resources/images/paypal.png' id='kform_payPalButton' style='width:150px'>
        <br>
        <?php
	}

	function echoSignatureCode()
	{
		if(!empty($this->page->sigType))
		{
			?>
            <p class="kform_signtureText">Your signature below confirms that you have agreed to the terms and conditions.</p><br>
            <div id="kform_sigDisplay"></div>
			<?php
		}
		else
		{
			?>
            <button type="button" id="kform_clearSig">Clear</button>
            <br><br>
            <canvas id="kform_sigPad"></canvas>
			<?php

		}
		?>
        <input type="hidden" name="signature" id="signature" value=''>
		<?php
	}
	
	function echoKlaviyoJavascript()
	{
		$klaviyoApiKey = $this->config->klaviyo->API_KEY;
		$sessionId = $this->getSessValue('sessionId');

		ob_start(); ?>
			<script type='text/javascript'>
				var _learnq = _learnq || [];
				_learnq.push(['account', '<?php echo $klaviyoApiKey ?>' ]);
				// Sending the IP (or other data) along with $id forces the logging of the user on Klaviyo
				_learnq.push(['identify', {'$id': '<?php echo $sessionId ?>', 'ip':'<?= $_SERVER['REMOTE_ADDR'] ?>'}]);
				(function () {
					var b = document.createElement('script'); b.type = 'text/javascript'; b.async = true;
					b.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'a.klaviyo.com/media/js/analytics/analytics.js';
					var a = document.getElementsByTagName('script')[0]; a.parentNode.insertBefore(b, a);
				})();

				function initKlaviyo()
				{
					<?php
					if ($this->pageType=='catalogPage'&&$_GET['productId'])
						echo "var productId = ".$_GET['productId']."
							var product = kform.config.offers[productId];
							_learnq.push(['account', '$klaviyoApiKey' ]);
							_learnq.push(['identify', {'\$id': '$sessionId'}]);
							_learnq.push(['track', 'Viewed Product', product]);";
					elseif ($this->pageType=='checkoutPage')
					{
						echo "_learnq.push(['account', '$klaviyoApiKey' ]);
							_learnq.push(['identify', {'\$id': '$sessionId'}]);";
						echo "trackStartedCheckout();";
					}
					?>

					var emailField = document.querySelector('input[name=emailAddress]'); 
					var firstNameField = document.querySelector('input[name=firstName]'); 

					if(emailField){
						emailField.addEventListener('change', function(e){

						var email = this.value; 
						if (email && /@/.test(email)) 
						{
							_learnq.push(['account', '<?php echo $klaviyoApiKey ?>' ]);
							_learnq.push(['identify', { '$email': email, '$id' : '<?php echo $sessionId ?>'}]);
							// trackStartedCheckout();		
						}
					  }); 
					}
					if (firstNameField) {	// Should probably be moved to the above pageType/checkoutPage IF statement, unless there are other page that may need it?
						firstNameField.addEventListener('change', function(e) {
							updateKlaviyoIdentity('$first_name', this.value);
						});
						var lastNameField = document.querySelector('input[name=lastName]'); 
						var phoneNumberField = document.querySelector('input[name=phoneNumber]'); 
						var address1Field = document.querySelector('input[name=lastName]'); 
						var address2Field = document.querySelector('input[name=phoneNumber]'); 
						var cityField = document.querySelector('input[name=lastName]'); 
						var Field = document.querySelector('input[name=phoneNumber]'); 
						var lastNameField = document.querySelector('input[name=lastName]'); 
						var phoneNumberField = document.querySelector('input[name=phoneNumber]'); 
						
						document.querySelector('input[name=lastName]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$last_name', this.value);
						});
						document.querySelector('input[name=phoneNumber]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$phone_number', this.value);
						});
						document.querySelector('input[name=address1]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('address1', this.value);
						});
						document.querySelector('input[name=address2]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('address2', this.value);
						});
						document.querySelector('input[name=city]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$city', this.value);
						});
						document.querySelector('select[name=state]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$region', document.querySelector('select[name=state]').options[document.querySelector('select[name=state]').selectedIndex].text);
						});
						document.querySelector('select[name=country]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$country', document.querySelector('select[name=country]').options[document.querySelector('select[name=country]').selectedIndex].value);
						});
						document.querySelector('input[name=postalCode]').addEventListener('change', function(e) {
							updateKlaviyoIdentity('$zip', this.value);
						});
					}
				}

				function updateKlaviyoIdentity(field, value)	// Updates our user on Klaviyo whenever a checkout form field is changed (for capturing user info)
				{
					var ident2 = field;
					ident = {};
					ident['$id'] = '<?php echo $sessionId ?>';
					ident[ident2] = value;
					_learnq.push(['account', '<?php echo $klaviyoApiKey ?>' ]);
					_learnq.push(['identify', ident]);
					//_learnq.push(['track', 'Identity Update']);	// Uncommenting this will track on Klaviyo when user data is updated.  		
				}

				function trackStartedCheckout()
				// Called when they hit the checkout page (whether cart is empty or not).
				// Was originally set to call when they entered email address.
				// Should maybe be called on product count update, but 'Placed Order' call will store updated order details
				{
					_learnq.push(['track', 'Started Checkout',
					<?php
					$cart = $this->getSessValue('cart'); 
					$sendObject = (object) array();
					$sendObject->ItemNames = array(); 
					$sendObject->Items = array();
					foreach($cart as $k=>$v)
					{
						$sendObject->ItemNames[] = $this->config->offers->$k->name; 
						$sendObject->Items[]= $this->config->offers->$k;
					}
					$sendObject = json_encode($sendObject);
					echo $sendObject; 
					?>]);
				}
			</script>
		<?php 
		return ob_get_clean(); 
	}

	function getOffers()
	{
		if(empty($this->offers))
		{
			$this->offers = array();
			$prods = (array) $this->config->offers;
			foreach($prods as $k=>$v)
				$this->offers[(int) $k] = $v;
		}
		return $this->offers;
	}
	function getUpsells()
	{
		if(empty($this->upsells))
		{
			$this->upsells = array();
			$prods = (array) $this->config->upsells;
			foreach($prods as $k=>$v)
				$this->upsells[(int) $k] = $v;
		}
		return $this->upsells;
	}
	
	
	
	function getProducts()
	{
		$upsells = $this->getUpsells();
		$offers = $this->getOffers();
		return $offers + $upsells;
	}
	
	function getProduct($productId)
	{
		$prods = $this->getProducts();
		
		
		if(!empty($prods[$productId]))
			return $prods[$productId];
		else
			return false;
	}
	
	function getWidget()
	{
		$file = dirname(realpath(__FILE__))."/ajax/buildWidget.php";
		
		if(!is_file($file))
			throw new Exception("file does not exist");
		$ksdk = $this;
		ob_start();

		require $file;
		
		$html = ob_get_clean();
		if($this->pageType != 'ASYNC')
			$html = "<a href='".$this->getPageUrl('checkoutPage')."' class='kcartWidget'> ".$html."</a>";
		
		return $html;		
		
	}
	
	function includeAjaxFile($file)
	{
		$file = dirname(realpath(__FILE__))."/ajax/{$file}";
		
		if(!is_file($file))
			throw new Exception("file does not exist: $file");
		$ksdk = $this;
		ob_start();
		require $file;
		return ob_get_clean();	
		
	}
	
	function getOrdersPurchases()
	{
		return $this->includeAjaxFile("buildProfileOrdersPurchases.php");
	}
	
	function getAccountEditForm()
	{
		return $this->includeAjaxFile("buildEditAccount.php");
	}
	
	function getAccountInfo()
	{
		return $this->includeAjaxFile("buildAccount.php");
	}
	
	function getSigninForm()
	{
		return $this->includeAjaxFile("buildSignin.php");
	}
	
	function getCreateAccount()
	{
		return $this->includeAjaxFile("buildCreateAccount.php");
	}
	
	function echoShoppingCart()
	{
		echo $this->getShoppingCart();	
	}
	
	function getShoppingCart()
	{
		if($this->pageType == 'checkoutPage')
		{		
			$cart = (array) $this->getSessValue('cart');
			if(empty($cart))
				$cart = array();
	
			if($productId = $this->sanitizeInput('productId','INT',INPUT_GET))
			{
				if(empty($cart[$productId]))
				{
					$qty = $this->sanitizeInput('qty','INT',INPUT_GET);
					if(empty($qty))
						$qty = 1;
					
					$cart[$productId] = $qty;
				}
			}	
			$this->updateCart($cart);
		}

		return $this->includeAjaxFile("buildCart.php");
	}
	
	function updateCart($cartItems)
	{
		if(is_string($cartItems))
		{
			$cartItems = str_replace('\\','',$cartItems);
			$cartItems = json_decode($cartItems);
		}
		$products = (array) $this->getProducts();
		
		if(is_object($cartItems) || is_array($cartItems))
		{
			
			$items = array();
			foreach((array) $cartItems as $prid=>$qty)
			{
				if(isset($products[$prid]))
					$items[$prid] = $qty;	
			}
			
			$cartItems = (object) $items;
		
			$this->setSessValue('cart',$cartItems);
			return array(200,$cartItems);
			
			return array(500,'cartItems is not an object');
		}
	}
	
	
	function getPage($pageType)
	{
		if(!empty($this->config->webPages->$pageType))
			return $this->config->webPages->$pageType;
		else 
			return false;
	}
	
	function getPageUrl($pageType)
	{
		if($page = $this->getPage($pageType))
			return $page->url;
		else
			return false;
	}
	
	
	function redirect($url)
	{
		
		if(!headers_sent())
		{
			if(is_callable('wp_redirect'))
				wp_redirect($url);
			else
				header("Location: $url");
			die();
		}
		else
			echo "<script type='text/javascript'>window.location='$url';</script>";
	}
	
	function hasSessValue($key)
	{
		return isset($_SESSION['KSDK']->$key);
	}
	
	function setSessValue($key,$val)
	{
		$_SESSION['KSDK']->$key = $val;	
	}
	function getSessValue($key)
	{
		return $this->hasSessValue($key) ? $_SESSION['KSDK']->$key : NULL;
	}
	
	function throwFatalError($message)
	{
		die("KonnektiveSDK Fatal Error: ".$message);
	}
	
	function sanitizeInput($key,$type='STR',$input_type=INPUT_GET)
	{
		switch($type)
		{
			case 'STR':	return filter_input($input_type,$key,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
			case 'INT':	return filter_input($input_type,$key,FILTER_SANITIZE_NUMBER_INT);
			case 'DECIMAL': return filter_input($input_type,$key,FILTER_SANITIZE_NUMBER_FLOAT);
			case 'BOOL': return filter_input($input_type,$key,FILTER_VALIDATE_BOOLEAN);
			default: 	trigger_error("expected \$type of STR, INT, DECIMAL or BOOL. Got $type");
		}
	}
	
	function sendApiRequest($url,$params = array())
	{
		$params = (object) $params;
		$params->loginId = $this->config->apiLoginId;
		$params->password = $this->config->apiPassword;
		$params->campaignId = $this->config->campaignId;
		
		if($sessionId = $this->getSessValue('sessionId'))
			$params->sessionId = $sessionId;
		if($order = $this->getSessValue('order'))
			$params->orderId = $order->orderId;

		if($this->noCurl)
		{
			$url .= '?'.http_build_query((array) $params);
			$raw = file_get_contents($url);
		}
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			$raw = curl_exec($ch);
			if(empty($raw))
				$raw = curl_error($ch);
			curl_close($ch);
		}
		$response = json_decode($raw);
		return !empty($response) ? $response : "Cannot parse- ".$raw;
	}
	
	
	function echoPayCertifyPixel()
	{
		$payCertifyMerchantId = $this->config->payCertifyMerchantId;
		$companyName = $this->config->companyName;
		$sessionId = $this->getSessValue('sessionId');
		$sessionId = substr($sessionId,0,32);
		$order = $this->getOrder();
 		
		if(isset($order->merchantTxnId)) 
		{ 
			?>
			<div id='paycertify-confirmation-box' style='width:400px;marginbottom:20px;'></div>
			<script type='text/javascript' charset='utf-8'>
				window.PCTransactionID = <?php echo trim($order->merchantTxnId);?>;
				(function()
				{
					document.createElement('script').src = 'http://paycertify.com/merchant/confirmations/iframe_install.js';
					document.createElement('script').setAttribute('type','text/javascript');
					document.getElementsByTagName('body')[0].appendChild(document.createElement('script'));
				}());
			</script>
            <?php
		 }
	}
	
	function importClick($pageType)
	{
		//pageType variable must be passed
		if(empty($pageType))
			return;
		
		if(!isset($_SESSION['KSDK']->clicks) || !is_array($_SESSION['KSDK']->clicks))
			$_SESSION['KSDK']->clicks = array();
		$clicks = &$_SESSION['KSDK']->clicks;

		//check if we've already imported this click
		if(in_array($pageType,$clicks))
			return array(200,array('sessionId'=>$this->getSessValue('sessionId')));

		$params = (object) array();
		$params->ipAddress = $this->getSessValue('ipAddress');
		$params->affId = $this->getSessValue('affId');
		$params->requestUri = $this->getSessValue('requestUri');
		$params->userAgent = $this->getSessValue('userAgent');
		$params->acceptHeader = $this->getSessValue('acceptHeader');
		$params->httpReferer = $this->getSessValue('httpReferer');
		$params->isMobile = $this->isMobile;
		$params->pageType = $pageType;
		
		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_CLICK,$params);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS')
			{
				$clicks[] = $pageType;
				if(!empty($response->message->sessionId))
				{
					$sessionId = $response->message->sessionId;
					$this->setSessValue('sessionId',$sessionId);
				}
				if(!empty($response->message->affVals))
					$this->setSessValue('affVals',$response->message->affVals);	
				$ret = (object) array();
				if(!empty($sessionId))
					$ret->sessionId = $sessionId;
				else
					$ret->sessionId = $this->getSessValue('sessionId');
					
				if(!empty($response->message->pixel))
					$ret->pixel =  $response->message->pixel;
				
				if($pageType == 'checkoutPage' && isset($this->config->pinpointMerchantId) && !$this->getSessValue('pinpointPixelFired'))
				{
					if(empty($ret->pixel))
						$ret->pixel = '';
					$query = "?c=".$this->config->companyName."&s=".$sessionId;
					$ret->pixel .= "<iframe width=1 height=1 frameborder=0 scrolling=no src='https://lp.konnektive.com/logos/p/logo.htm{$query}'><img width=1 height=1 src='https://lp.konnektive.com/logos/p/logo.gif{$query}'></iframe>";
					$this->setSessValue('pinpointPixelFired',true);	
				}
				
				if($pageType == 'checkoutPage' && isset($this->config->payCertifyMerchantId) && !$this->getSessValue('payCertifyPixelFired'))
				{
					if(empty($ret->pixel))
						$ret->pixel = '';
					$query = "?c=".$this->config->companyName."&m=".$this->config->payCertifyMerchantId."&s=".$sessionId;
					$ret->pixel .= "<iframe width=1 height=1 frameborder=0 scrolling=no src='https://lp.konnektive.com/logos/c/logo.htm{$query}'><img width=1 height=1 src='https://lp.konnektive.com/logos/c/logo.gif{$query}'></iframe>";
					$this->setSessValue('payCertifyPixelFired',true);	
				}
					
				if($pageType == 'checkoutPage' && isset($this->config->kountMerchantId) && !$this->getSessValue('kountPixelFired'))
				{
					if(empty($ret->pixel))
						$ret->pixel = '';
					$query = "?c=".$this->config->companyName."&s=".$sessionId;
					$ret->pixel .= "<iframe width=1 height=1 frameborder=0 scrolling=no src='https://lp.konnektive.com/logos/logo.htm{$query}'><img width=1 height=1 src='https://lp.konnektive.com/logos/logo.gif{$query}'></iframe>";
					$this->setSessValue('kountPixelFired',true);
				}
									
				return array(200,$ret);	
			}
			else
			{
				return array(500,$response->message);
			}
		}
		else
		{
			return array(500,$response);	
		}
	}
	
	function customerEdit($post)
	{
		$params = (object) $post;
		$customer = $this->getCustomer();
		$params->customerId = $customer->customerId;
		$params->eCommerceLogin = $customer->eCommerceLogin;
	
		$response = $this->sendApiRequest(self::ENDPOINT_PROFILE_UPDATE,$params);
		if(is_object($response) && $response->result == 'SUCCESS' && is_object($response->message))
		{ 
			$this->setSessValue('customer',$response->message);
			return array(200);
		}
		$response = is_object($response) ? $response->message : $response;
		return array(500,$response);
	}
	
	
	function createAccount($post)
	{
		if(empty($post['eCommerceLogin2']) || empty($post['eCommercePassword2']))
			return array(500,"Login and password are required");
			
		$params = (object) array();
		$params->eCommerceLogin = $post['eCommerceLogin2'];
		$params->eCommercePassword = $post['eCommercePassword2'];
		$params->customerId = $this->getCustomerId();

		$response = $this->sendApiRequest(self::ENDPOINT_PROFILE_CREATE,$params);
		if(is_object($response))
		{ 	
			if($response->result == 'SUCCESS' && is_object($response->message))
			{
				$customer = $response->message;
				$this->setSessValue('customer',$customer);
				return array("200");
			}
			else
				return array('500',$response->message);	
		}
		else
			return array('500',$response);
	}
	
	function refreshProfile()
	{
		$customer = $this->getSessValue('customer');

		
		if(empty($customer) || empty($customer->eCommerceLogin))
			return;
			
		$params = (object) array();
		$params->eCommerceLogin = $customer->eCommerceLogin;
		$params->customerId = $customer->customerId;
		
		$response = $this->sendApiRequest(self::ENDPOINT_PROFILE_QUERY,$params);
	
		if(is_object($response))
		{ 	
			if($response->result == 'SUCCESS' && is_object($response->message))
			{
				$customer = $response->message;
				$this->setSessValue('customer',$customer);
				return;
			}
		}
		
		unset($_SESSION['KSDK']->customer);
	}
	
	function customerLogin($post)
	{	
		if(empty($post['eCommerceLogin']) || empty($post['eCommercePassword']))
			return array(500,"Login and password are required");
			
		$params = (object) array();
		$params->eCommerceLogin = $post['eCommerceLogin'];
		$params->eCommercePassword = $post['eCommercePassword'];
		
		$response = $this->sendApiRequest(self::ENDPOINT_PROFILE_QUERY,$params);
		if(is_object($response))
		{ 	
			if($response->result == 'SUCCESS' && is_object($response->message))
			{
				$customer = $response->message;
				$this->setSessValue('customer',$customer);
				return array("200");
			}
			else
				return array('500',$response->message);	
		}
		else
			return array('500',$response);
	}
	
	function importLead($params)
	{
		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_LEAD,$params);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS' && is_object($response->message))
			{
				$this->setSessValue('order',$response->message);
				return array(200,$response->message);
			}
			else
				return array(500,$response->message);
		}
		else
		{
			return array(500,$response);	
		}
	}
	//For Cardinal 3ds
	function importAuth($params)
	{
		$card = $_POST['cardNumber'];
		$order = $this->getSessValue('order');
		 //var_dump($card);die();
		$params = (object) $params;
		
		if(empty($_POST['orderItems']))
			return array('ERROR','No products in order');
		
		
		$params->orderItems = str_replace("\\",'',$_POST['orderItems']);
		$params->firstName = $order->firstName;
		$params->lastName = $order->lastName;
		$params->emailAddress = $order->emailAddress;
		$params->phoneNumber = $order->phoneNumber;
		$params->address1 = $order->address1;
		$params->address2 = $order->address2;
		$params->city = $order->city;
		$params->state = $order->state;
		$params->country = $order->country;
		$params->postalCode = $order->postalCode;
		$params->shipFirstName = $order->shipFirstName;
		$params->shipLastName = $order->shipLastName;
		$params->shipAddress1 = $order->shipAddress1;
		$params->shipAddress2 = $order->shipAddress2;
		$params->shipCity = $order->shipCity;
		$params->shipState = $order->shipState;
		$params->shipCountry = $order->shipCountry;
		$params->shipPostalCode = $order->shipPostalCode;
		$params->customerId = $order->customerId;
		$params->cardNumber = $card;
		$params->totalAmount = $this->getOrderTotal();
		
		$checkout = $this->getPage('checkoutPage');
		$params->salesUrl = $checkout->url;
		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_AUTH,$params);
		
		//var_dump($response);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS' && is_object($response->message))
			{
				$this->setSessValue('order',$response->message);
			}
			if($response->result == 'SUCCESS')
			{
				//return array(200,$response->message);
			
			   return $response->message;
			}
			elseif($response->result == 'MERC_REDIRECT')
				return array(302,$response->message);
			else
				return array(500,$response->message);
		}
		else
		{
			return array(500,$response);
			
		}
		
		
	}
	
	function importTax($params)
	{
		if(empty($this->config->taxServiceId))
			return array('ERROR','No tax service set');

		$order = $this->getSessValue('order');

		$params = (object) $params;
		if(empty($_POST['orderItems']))
			return array('ERROR','No products in order');
		$params->orderItems = str_replace("\\",'',$_POST['orderItems']);

		$checkout = $this->getPage('checkoutPage');
		$params->salesUrl = $checkout->url;
		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_TAX,$params);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS' && is_object($response->message))
				$this->setSessValue('order',$response->message);

			if($response->result == 'SUCCESS')
				return array(200,$response->message);
			elseif($response->result == 'MERC_REDIRECT')
				return array(302,$response->message);
			else
				return array(500,$response->message);
		}
		else
		{
			return array(500,$response);
		}
	}
	
	function importOrder($params)
	{
		$params = (object) $params;
				
		if(empty($_POST['orderItems']))
			return array('ERROR','No products in order');
		
		$params->orderItems = str_replace("\\",'',$_POST['orderItems']);
		
		$checkout = $this->getPage('checkoutPage');
		$params->salesUrl = $checkout->url;

		if($params->paySource == 'PAYPAL')
		{
			$params->paypalBillerId = $this->config->paypal->paypalBillerId;
			$this->setSessValue('paypalOrderItems',$params->orderItems);
			if(isset($params->couponCode))
				$this->setSessValue('paypalCouponCode',$params->couponCode);
			if(isset($params->shipProfileId))
				$this->setSessValue('paypalShipProfileId',$params->shipProfileId);
		}
		elseif($params->paySource == 'AMAZON')
		{
			$params->firstName = $this->getSessValue('amazonProfileFirstName');
			$params->lastName = $this->getSessValue('amazonProfileLastName');
			$params->emailAddress = $this->getSessValue('amazonProfileEmail');
		}

		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_ORDER,$params);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS' && is_object($response->message))
				$this->setSessValue('order',$response->message);
			
			if($response->result == 'SUCCESS')
				return array(200,$response->message);
			elseif($response->result == 'MERC_REDIRECT')
				return array(302,$response->message);
			elseif($response->result == 'ERROR' && $response->message == 'Transaction Declined: Prepaid Credit Cards Are Not Accepted')
				return array('400','prepaid');
			else
				return array(500,$response->message);
		}
		else
		{
			return array(500,$response);
		}
		
	}
	
	function importUpsale($params)
	{
		$response = $this->sendApiRequest(self::ENDPOINT_IMPORT_UPSALE,$params);
		if(is_object($response))
		{
			if($response->result == 'SUCCESS' && is_object($response->message))
				$this->setSessValue('order',$response->message);
			
			if($response->result == 'SUCCESS')
				return array(200,$response->message);
			elseif($response->result == 'MERC_REDIRECT')
				return array(302,$response->message);
			elseif($response->result == 'ERROR' && $response->message == 'Transaction Declined: Prepaid Credit Cards Are Not Accepted')
				return array('400','prepaid');
			else
				return array(500,$response->message);
		}
		else
		{
			return array(500,$response);
		}
	}
	
	//amazon payments
	function checkAmazonProfileRedirect()
	{
		
		if(!empty($this->config->amazonPayments->clientId))
			$amazonClientId = $this->config->amazonPayments->clientId;
		if(!empty($this->config->amazonPayments->sellerId))
			$amazonSellerId = $this->config->amazonPayments->sellerId;
		
		if($access_token = $this->sanitizeInput("access_token","STR",INPUT_GET))
		{
			// verify that the access token belongs to us
			$c = curl_init('https://api.amazon.com/auth/o2/tokeninfo?access_token='.urlencode($access_token));
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$r = curl_exec($c);
			//print_r($r);
			curl_close($c);
			$d = json_decode($r);
			
			if ($d->aud != $amazonClientId)
			{
				// the access token does not belong to us
				header('HTTP/1.1 404 Not Found');
				echo 'Page not found';
				exit;
			}
			
			// exchange the access token for user profile
			$c = curl_init('https://api.amazon.com/user/profile');
			curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: bearer '.$access_token));
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$r = curl_exec($c);
			//var_dump($r);
			//print_r($r);
			curl_close($c);
			$d = json_decode($r);
		
			$name = trim($d->name);
			
			$spacepos = strpos($name,' ');
			
			if(!empty($spacepos))
			{
				$first = substr($name,0,$spacepos);
				$last = substr($name,$spacepos+1);
			}
			else
			{
				$first = $name;
				$last = ' ';	
			}
			
			$email = $d->email;
			$profileId = $d->user_id;
				
			$this->setSessValue('amazonAccessToken',$access_token);
			$this->setSessValue('amazonProfileFirstName',$first);
			$this->setSessValue('amazonProfileLastName',$last);
			$this->setSessValue('amazonProfileEmail',$email);
			$this->setSessValue("amazonProfileId",$profileId);
			
			$this->redirect($this->getPageUrl("checkoutPage"));
		}
	}
	
	//paypal payments have a unique order flow
	function confirmPaypalOrder($token,$payerId)
	{
		
		if(empty($token)||empty($payerId))
			return;
		
		$params = (object) array();
		$params->token = $token;
		$params->payerId = $payerId;
		$params->orderItems = $this->getSessValue('paypalOrderItems');
		$params->paypalBillerId = $this->config->paypal->paypalBillerId;
		$params->campaignId = KFormConfig::$instance->campaignId;
		if($couponCode = $this->getSessValue('paypalCouponCode'))
			$params->couponCode = $couponCode;
		$params->shipProfileId = $this->getSessValue('paypalShipProfileId');
		
		$response = $this->sendApiRequest(self::ENDPOINT_CONFIRM_PAYPAL,$params);	
		
		if($response->result == 'SUCCESS')
		{
			$this->setSessValue('order',$response->message);
			$this->redirect($this->redirectsTo.'?paypalSuccess=1');
		}
		else
			$this->customLoadScript .= 'kform.validator.triggerError("'.str_replace("Please redirect your customer to PayPal.","",$response->message).'");';
	}
	
	function closeOrderSession()
	{
		$data = $_SESSION['KSDK'];
		if(is_object($data))
		{
			unset($data->order,$data->cart,$data->orderId,$data->sessionId,$data->clicks);
		}
		
	}
	
	function getSigninWidget()
	{
		ob_start();

	 $customer = $this->getSessValue('customer'); 
	 if(!empty($customer)) { ?>
    	<span class='kcartLogoutWrap'>
        	logged in as <?php echo $customer->emailAddress; ?>
            <span id='kcartLogout'>log out</span>
        </span>
<?php } else {?>
    	<span  class='kcartLogoutWrap'> Have an Account ? </span><span id='kcartSigninButton'>Sign In</span>
<?php } 

		return ob_get_clean();
		
	}
	
	//Getters for different types of values
	//primary used by thankyou page and as quick reference for clients customizing their pages
	
	function getAffVals()
	{
		return $this->getSessValue('affVals');	
	}
	
	function getOrder()
	{	
		return $this->getSessValue('order');
	}
	function getOrderItems()
	{
		if($order = $this->getOrder())
		{
			if(!empty($order->items))
				return $order->items;	
		}
	}
	
	function getCustomerName()
	{
		if($order = $this->getOrder())
			return $order->firstName.' '.$order->lastName;	
	}
	
	function getCustomer()
	{
		if($customer = $this->getSessValue('customer'))
			return $customer;
	}
	
	function getCustomerId()
	{
		if($customer = $this->getCustomer())
			return $customer->customerId;
		if($order = $this->getOrder())
			return $order->customerId;
		else
			return false;	
	}
	
	function getOrderId()
	{
		if($order = $this->getOrder())
			return $order->orderId;
	}
	function getBillingAddress()
	{
		if($order = $this->getOrder())
		{
			extract((array) $order);
			$billingAddress = $firstName.' '.$lastName.'<br>'.$address1.' '.$address2.'<br>'.$city.', '.$state.' '.$country.' '.$postalCode;
			return $billingAddress;	
		}
	}
	function getShippingAddress()
	{
		if($order = $this->getOrder())
		{
			extract((array) $order);
			$shippingAddress = $shipFirstName.' '.$shipLastName.'<br>'.$shipAddress1.' '.$shipAddress2.'<br>'.$shipCity.', '.$shipState.' '.$shipCountry.' '.$shipPostalCode;
			return $shippingAddress;
		}
	}
	function getPhoneNumber()
	{
		if($order = $this->getOrder())
			return $order->phoneNumber;	
	}
	function getEmailAddress()
	{
		if($order = $this->getOrder())
			return $order->emailAddress;	
	}
	function getItemsTable()
	{
		if($order = $this->getOrder())
		{
			$itemsTable = "<table class='kthanksItemsTable'>
							  <tr class='kthanksItemsTable_TitleRow'>
							  		<td>Product</td>
									<td>Price</td>
									<td>Qty.</td>
									<td>Amount</td>
							  </tr>
							";  
							 
			
			
			foreach($order->items as $item)
			{
				$price = $this->currencySymbol.number_format($item->price / $item->qty,2);
				$total = $this->currencySymbol.number_format($item->price,2);
				$itemsTable .= "<tr class='kthanks_row'>
									<td>$item->name</td>
									<td>$price</td>
									<td>$item->qty</td>
									<td>$total</td>
							   </tr>";
			}
			return $itemsTable."</table>";
		}
	}
	function getSubTotal()
	{
		if($order = $this->getOrder())
		{
			$basePrice = 0.00;
			foreach($order->items as $item)
				$basePrice += $item->price;

			return number_format($basePrice,2);			
		}
	}
	function getShipTotal()
	{
		if($order = $this->getOrder()) 
		{ 
			if(!empty($order->items)) 
			{ 
				$sum = 0.00; 
				foreach($order->items as $k => $v) 
				{ $sum += $v->shipping; } 
				return number_format($sum,2); 
			} else { 
				return number_format($order->baseShipping,2); 
			} 
		} 
	}
	function getTaxTotal()
	{
		if($order = $this->getOrder())
		{
			if (!empty($order->items))
			{
				$salesTax = 0;
				foreach($order->items as $item)
				{
					$salesTax += $item->initialSalesTax;
				}
			}
			else
				$salesTax = number_format($order->salesTax, 2);
			return $salesTax;
		}
	}

	function getInsureTotal()
	{
		if($order = $this->getOrder())
		{
			if(empty($order->shipmentInsurancePrice))
				return $this->currencySymbol."0.00";
				
			return number_format($order->shipmentInsurancePrice,2);
		}
	}
	function getOrderTotal()
	{
		$discount = $this->getDiscountTotal();
		$subtotal = $this->getSubTotal();
		$shipping = $this->getShipTotal();
		$salesTax = $this->getTaxTotal();
		$discount = preg_replace("/[^0-9.]/",'',$discount);
		$subtotal = preg_replace("/[^0-9.]/",'',$subtotal);
		$shipping = preg_replace("/[^0-9.]/",'',$shipping);
		$salesTax = preg_replace("/[^0-9.]/",'',$salesTax);
		
		return number_format($subtotal + $shipping + $salesTax - $discount,2);
	}
	function getDiscountTotal()
	{
		if($order = $this->getOrder())
			return number_format($order->totalDiscount,2);
	}
}
