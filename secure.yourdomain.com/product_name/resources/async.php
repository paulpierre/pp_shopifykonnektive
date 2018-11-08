<?php


if(!class_exists('KonnektiveSDK'))
{
	require_once realpath(dirname(__FILE__)."/konnektiveSDK.php");
}
$ksdk = new KonnektiveSDK('ASYNC');

$method = $ksdk->sanitizeInput('method','STR',INPUT_POST);
$result = NULL;
switch($method)
{
	case 'importClick':
		$result = $ksdk->importClick($_POST['pageType']);
		break;
	case 'importLead':
		$result = $ksdk->importLead($_POST);
		break;
	case 'customerLogin':
		$result = $ksdk->customerLogin($_POST);
		break;
	case 'customerEdit':
		$result = $ksdk->customerEdit($_POST);
		break;
	case 'createAccount':
		$result = $ksdk->createAccount($_POST);
		break;		
	case 'importOrder':
		$result = $ksdk->importOrder($_POST);
		break;
	case 'importTax':
		$result = $ksdk->importTax($_POST);
		$code = 200;
		break;
	case 'importAuth': //for Cardinal 3ds
		$result = $ksdk->importAuth($_POST);
		$body = $result;
		$code = 200;
		break;
	case 'importUpsale':
		$result = $ksdk->importUpsale($_POST);
		break;
	case 'accountLogout':
		unset($_SESSION['KSDK']->customer,$_SESSION['KSDK']->customerId);
		unset($_SESSION['KSDK']->order,$_SESSION['KSDK']->orderId);
		unset($_SESSION['KSDK']->sessionId);
		$code = 200;
		break;
	case 'closeSession':
		$ksdk->closeOrderSession();
		$code = 200;
		break;
	case 'updateCart':
		$result = $ksdk->updateCart($_POST['cartItems']);
		break;
	case 'cancelOrder':
		$result = $ksdk->cancelOrder($_POST);
		break;
	case 'getWidgetMiniCart':
		$body = $ksdk->getMiniCart();
		$code = 200;
		break;
	case 'getShoppingCart':
		$body = $ksdk->getShoppingCart();
		$code = 200;
		break;
	case 'getWidget':
		$body = $ksdk->getWidget();
		$code = 200;
		break;
	case 'getSigninForm':
		$body = $ksdk->getSigninForm();
		$code = 200;
		break;
	case 'getCreateAccount':	
		$body = $ksdk->getCreateAccount();
		$code = 200;
		break;
	case 'getAccountInfo':	
		$body = $ksdk->getAccountInfo();
		$code = 200;
		break;
	case 'getAccountEditForm':	
		$body = $ksdk->getAccountEditForm();
		$code = 200;
		break;
	case 'logoutAmazon':
		unset($_SESSION['KSDK']->amazonProfileId,
			  $_SESSION['KSDK']->amazonAccessToken,
			  $_SESSION['KSDK']->amazonProfileFirstName,
			  $_SESSION['KSDK']->amazonProfileLastName,
			  $_SESSION['KSDK']->amazonProfileEmail);
		$code = 200;
		break;
}

if(is_array($result))
{
	$code = $result[0];
	$body = isset($result[1]) ? $result[1] : '';	
}

if(empty($code))
	$code = 500;
if(empty($body))
	$body = '';

die(json_encode(compact('code','body')));