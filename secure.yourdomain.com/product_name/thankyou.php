<?php
//This code must be included at the top of your script before any output is sent to the browser
//-even before <!DOCTYPE> declaration
require_once realpath(dirname(__FILE__)."/resources/konnektiveSDK.php");
$pageType = "thankyouPage"; //choose from: presalePage, leadPage, checkoutPage, upsellPage1, upsellPage2, upsellPage3, upsellPage4, thankyouPage
$deviceType = "ALL"; //choose from: DESKTOP, MOBILE, ALL
$ksdk = new KonnektiveSDK($pageType,$deviceType);

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

    <link rel="stylesheet" href="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/assets/css/app.css" />

    <title>Congratulations!</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/css/bootstrap.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .commentBoxOutter{background:#f2f2f2;border-bottom:1px solid #999;}
        .commentBoxInner{padding:20px;}
        .notebar{
            border-top:1px solid #ccc;
            border-bottom:1px solid #ccc;
            padding:5px 5px;
        }
    </style>

    <style type="text/css">

        .main ul.breadcrumb{
            display:none!important
        }
        .content.prH7{
            padding:8px 0
        }
        .ar64{
            width:100%
        }
        .ar64 .s8{
            font-size:14px;
            text-align:center;
            color:#fff;
            cursor:default;
            margin:0 3px;
            padding:9px 10px 9px 30px;
            float:left;
            position:relative;
            background-color:#d9e3f7;
            -webkit-user-select:none;
            -moz-user-select:none;
            -ms-user-select:none;
            user-select:none;
            width:20%
        }
        .ar64 .s8:after,.ar64 .s8:before{
            content:" ";
            position:absolute;
            top:0;
            right:-17px;
            width:0;
            height:0;
            border-top:19px solid transparent;
            border-bottom:17px solid transparent;
            border-left:17px solid #d9e3f7;
            z-index:2;
        }
        .ar64 .s8:before{
            right:auto;
            left:0;
            border-left:17px solid #fff;
            z-index:0
        }
        .ar64 .s8:first-child:before{
            border:none
        }
        .ar64 .s8:first-child{
            margin-left:0;
            border-top-left-radius:4px;
            border-bottom-left-radius:4px
        }
        .ar64 .s8 span{
            position:relative
        }
        .ar64 .s8.s8c{
            color:#fff;
            background-color:#23468c
        }
        .ar64 .s8.s8c:after{
            border-left:17px solid #23468c
        }
        .ar64 .s8 svg{
            position:absolute;
            left:-17px;
            top:2px
        }
        .ar64 .s8:first-child svg{
            left:-16px
        }
        .wyustit:before{
            border-top:1px solid #dfdfdf;
            content:"";
            margin:0 auto;
            position:absolute;
            top:50%;
            left:0;
            right:0;
            bottom:0;
            width:100%;
            z-index:-1
        }
        .wyuscs{
            display:table-row;
            padding-bottom:20px
        }
        .wyuscs1,.wyuscs2{
            display:table-cell;
            vertical-align:middle
        }
        .wyuscs1{
            width:20%;
            font-size:15px
        }
        .wyuscs2{
            width:80%
        }
        .wyuscs2 span{
            font-size:14px;
            font-weight:700;
            color:#666
        }
        .wyuscs2 p{
            font-size:12px;
            color:#777
        }
        @media(min-width:450px){
            .ar64 .s8{
                min-width:29%
            }
            .ar64 .s8 svg{
                position:relative!important;
                top:2px!important;
                left:-10px!important
            }
        }
        @media(max-width:750px){
            .ar64 .s8{
                font-size:11px
            }
            .ar64 .s8:first-child{
                padding-left:20px
            }
        }

    </style>
</head>
<body>

<?php
//pull the order information out of session
$orderId = $ksdk->getOrderId();
$customerName = $ksdk->getCustomerName();
$billingAddress = $ksdk->getBillingAddress();
$shippingAddress = $ksdk->getShippingAddress();
$phoneNumber = $ksdk->getPhoneNumber();
$emailAddress = $ksdk->getEmailAddress();
$itemsTable = $ksdk->getItemsTable();
$subTotal = $ksdk->getSubTotal();
$shipTotal = $ksdk->getShipTotal();
$taxTotal = $ksdk->getTaxTotal();
$insuranceTotal = $ksdk->getInsureTotal();
$discountTotal = $ksdk->getDiscountTotal();
$orderTotal = $ksdk->getOrderTotal();
$currency = $ksdk->currencySymbol;
?>

<div class="container">
    <div class="content prH7">

        <div class="wrap">
            <div class="ar64">
                <div id="sp1" class="s8"><span id="spn1">1. Order</span></div>
                <div id="sp2" class="s8 "><span id="spn2">2. Special Offer</span></div>
                <div id="sp3" class="s8 s8c"><span id="spn3">3. Confirmation</span></div>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="header clearfix" style="border-bottom:1px solid #ccc;margin-bottom:15px;padding-bottom:5px;">
                <nav>
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation">
                            <h3><a href="tel:+1866-935-9658">Customer Service Support: <span style="font-weight:bold;color:#000;" class="active">US: +1 866-935-9658 </span>
                                </a>
                            </h3>
                        </li>
                    </ul>
                </nav>
                <!-- <img src="/mo/v1/app/desktop/images/logo_03.png" width="186" height="55" alt=""/> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="success" class="alert alert-success" role="alert">
               <span class="lead text-center">
               <strong>Thank You!</strong> Your order will be shipped within 1 business day!  You can expect it to arrive in 3-5 days after shipment.
               </span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-4">
                <br>
                <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/checkmark.gif" width="92" height="91" alt=""><br> <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/keto-bottle.jpg" width="245" height="205" class="img-responsive" alt=""><br>
                <!-- ========================== savings block =========================== -->

                <!-- ========================== savings block ====================== -->
            </div>
            <div class="col-sm-5">

                <h1><br>
                    Your Order is Confirmed
                </h1>
                Credit Card Billing Description: <b style="color: #48c081;">GROW NEXUS INC. (www.ultimateketopro.com)</b>
                <br>
                <br>
                <b>We are happy you have chosen to achieve your weight loss goals with one of the most effective and hottest products on the market. </b>
                <br><br>
                Your order is currently being processed and will be shipped within 1 business day.  You’ll receive a shipping confirmation email with a tracking number once your product ships.  If for some reason you don’t receive that email, contact our customer service team immediately so we may investigate the issue – US: 1 866-935-9658
            </div>
            <div class="col-sm-3">
                <br>
                <span class="lead">Customer Service Hours: 8am to 8pm EST daily (excluding major holidays)</span><br><br>
                Please feel free to contact customer service for any questions, comments or testimonials.<br><br>
                <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/secureicons_14.png" width="446" height="104" class="img-responsive" alt=""/><br><br>
                <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/5STAR-rating.png" width="130" height="131" class="center-block img-responsive" alt=""/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="clearfix">&nbsp;</div>
            <br>
            <div class="clearfix">&nbsp;</div>
            <div class="panel panel-default col-sm-12" style="padding:0;">
                <div class="panel-heading col-sm-12">
                    <div class="col-sm-4"><img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/guarantee.png" width="140" height="99" class="center-block" alt=""/>
                    </div>
                    <div class="col-sm-8">
                        <h4>We are 100% committed to your product satisfaction and weight loss goals</h4>
                        <br>
                        Call us if you have any problems with your purchase – Customer Service Number: US: 1 866-935-9658  . Customer Service hours can be reached between 8am to 8pm EST daily!
                    </div>
                </div>
                <div class="panel-body col-sm-12">
                    <div class="col-sm-12 clearfix">
                        &nbsp;
                    </div>
                    <div class="col-sm-4 text-center">
                        <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/layla.png" width="78" height="79" alt="" class="center-block" /> <span class="h4">
                     <br>
                     "This product is amazing!"
                     </span>
                        <p class="small">"I am very impressed. When I got the product, I was already unsure about it but I was desperate to get rid of my belly. Turns out, it actually works! I'm down 6 lbs already and can't wait to lose 6 more..."</p>
                        <br><br>
                        <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/5-star.png" width="119" height="23" alt=""/>
                    </div>
                    <div class="col-sm-4 text-center">
                        <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/ariela.png" width="79" height="80" alt="" class="center-block" /> <span class="h4">
                     <br>
                     "Who would have thought...!"
                     </span>
                        <p class="small">"Ok, let me just say that I love watching my body take shape! You know that guilty feeling you get every day thinking that maybe tomorrow I will try again? Well it's gone! Love Love Love it!..."
                            <br>
                            <br>
                            <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/5-star.png" width="119" height="23" alt=""/>
                    </div>
                    <div class="col-sm-4 text-center">
                        <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/mike.png" width="79" height="80" alt="" class="center-block" /> <span class="h4">
                     <br>
                     "Works as described A+++"
                     </span>
                        <p class="small">"Product works just like they said it would. I feel great, lost some pounds. I have even started going to the gym now that I am not embarassed to be around people who are in shape..."</p>
                        <br>
                        <br>
                        <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/5-star.png" width="119" height="23" alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row marketing">
        <br><br><br>
        <div class="col-lg-7">
            <div class="catnav" style="padding:3px 0 0 0;">
                <center>
                    <a  href="javascript:void(0);" onclick="alert('We are sorry, an erro has occured. Please check back later.');"><img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/social.jpg" width="636" height="30" class="img-responsive" /></a>
                </center>
            </div>
            <div class="commentBoxOutter">
                <div class="commentBoxInner">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="11%" valign="top">
                                <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="54" height="54" style="border:1px solid #999;" />
                            </td>
                            <td width="89%" valign="top">
                                <textarea name="" cols="" rows="" class="form-control"></textarea>
                                <div>
                                    <div align="left" style="width:200px;font:11px arial;">
                                        Submit Your Comment
                                    </div>
                                    <div align="right" style="padding:5px 20px 0 0;float:right;width:150px;"> <span style="padding:5px 20px 0 0;float:right;width:150px;"><a  href="javascript:void(0);"  ><img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/Screen-shot-2013-09-12-at-9.55.12-PM.jpg" width="73" height="25" border="0" /></a></span> </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <div class="notebar">
                <strong>Comments</strong> (showing 7 of 231)
            </div>
            <br>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    I am so excited to get my keto, My friend bought it two weeks ago and says it's working! Happy dance :) <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">7 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Just ordered it, does this product have caffeine in it? I am very sensitive to caffeine and am hoping it doesnt. <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">26 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-xs-2 col-xs-offset-1">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-9 small">
                    No, you do not need to worry about caffeine. It only has natural ingredients. This is my second time buying it and you won't feel anything strange on it. I had my doctor look at it and he said it looked like good stuff. <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">12 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Well here goes. I've seen this product before but wasnt sure about buying it. I finally made the decision and am very hopefull.. just need to lose 25lbs.. no pressure keto lol <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">32 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Hey you will like it.. I lost 16 lbs using it. I just ordered 5 bottles! I was wondering why they offered so many bottles, but realize because it is actually something you would want to buy again and again... just makes it easier i guess.   <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">41 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Can you retun it if you don't like it? I just ordered the 1 month supply to see if it works. <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">1 hr ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-xs-2 col-xs-offset-1">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-9 small">
                    Yea they have a good return policy, you can just call em up and they will take care of it. <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">33 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Hello from Cali! We love your product. thank you! <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">2 hr 6 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
            <div class="col-xs-12" style="border-bottom:1px dashed #ccc;padding:20px;">
                <div class="col-xs-2">
                    <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/img_usercomment.jpg" width="60" height="60" alt="" class="img-responsive"/>
                </div>
                <div class="col-xs-10 small">
                    Just ordered, cant wait!  <br><br>
                    <div class="comments" style="padding:10px 0 0 0;font-size:10px ;" align="right">2 hr 45 min ago | <a  href="javascript:void(0);"  >report</a></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/customer-service_09.png" width="640" height="333" class="img-responsive" alt=""/>
            <h4>FAQ<br>
                <br>
            </h4>
            <h4>Q: How many milligrams of keto does it contain?</h4>
            <p>A: Each pill contains the recommended  700 MG of 3 types of Keto Salts - the most available from any company! &nbsp;This is the recommended dosage by experts.</p>
            <h4>Q: How do I know this works?</h4>
            <p>A: We have numerous clients who can attest to how well the products work. But, if you decide it's not for you, simply call customer service!</p>
            <h4>Q: Can men take this?</h4>
            <p>A: Of course. &nbsp;For both men and women, keto is designed to stimulate the release of stored fat from fat cells.<br>
            </p>
            <img src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/images/moneyback.png" width="152" height="152" class="center-block" alt=""/>
            <br><br>
            <!-- <img src="/mo/v1/app/desktop/images/ozonelogo.png" width="372" height="109" class="center-block img-responsive" alt=""/>
            <img src="/mo/v1/app/desktop/images/ozone-testimonial-4.jpg" width="331" height="226" class="center-block img-responsive" alt=""/> -->
            <!--  <p>
             <h3>As a special bonus for your purchase today,</h3>
             you also have the opportunity to try Premier Diet for <strong>Free for 25 days</strong>, one of the best online weight loss systems on the market.  With Premier Diet, you will receive a new workout every single day to give you that weight loss jumpstart.  To check it out click the login button below and start experiencing the new you!
             </p><br><br>
             <a  href="javascript:void(0);">
             <button class="btn btn-success btn-lg btn-block">Login</button>
             </a>
             <p>&nbsp;</p>
             <p><strong>Login Info:</strong><br>
                Username: <a  href="javascript:void(0);" class="__cf_email__" data-cfemail="57233224231733383a363e397934383a">[email&#160;protected]</a><br>
                Password: test12345
             </p> -->
        </div>
    </div>
    <footer class=" nav-divider navbar nav footer" style="border-top:1px solid #ccc;margin-top:50px;">
        <br><br>
        <!-- <img src="/mo/v1/app/desktop/images/logo_03.jpg" width="186" height="55" alt=""/> -->
        <p class="text-right">Copyright  &copy; 2018
            Ultimate Keto Pro</p>
    </footer>
</div>
<!-- /container -->




<script type="text/javascript">
    AJAX_PATH = "/keto_verify";
    app_config = {
        "valid_class": "no-error",
        "error_class": "has-error",
        "loading_class": "loading",
        "crm_type": "limelight",
        "exit_popup_enabled": false,
        "exit_popup_element_id": "",
        "exit_popup_page": "",
        "offer_path": "\/mo\/v1\/",
        "current_step": 1,
        "cbtoken": "",
        "dev_mode": "N",
        "show_validation_errors": "modal",
        "allowed_country_codes": ["US"],
        "countries": {
            "US": {
                "name": "United States",
                "states": {
                    "AL": {
                        "name": "Alabama"
                    },
                    "AK": {
                        "name": "Alaska"
                    },
                    "AS": {
                        "name": "American Samoa"
                    },
                    "AZ": {
                        "name": "Arizona"
                    },
                    "AR": {
                        "name": "Arkansas"
                    },
                    "AE": {
                        "name": "Armed Forces Middle East"
                    },
                    "AA": {
                        "name": "Armed Forces Americas"
                    },
                    "AP": {
                        "name": "Armed Forces Pacific"
                    },
                    "CA": {
                        "name": "California"
                    },
                    "CO": {
                        "name": "Colorado"
                    },
                    "CT": {
                        "name": "Connecticut"
                    },
                    "DE": {
                        "name": "Delaware"
                    },
                    "DC": {
                        "name": "District of Columbia"
                    },
                    "FM": {
                        "name": "Federated States of Micronesia"
                    },
                    "FL": {
                        "name": "Florida"
                    },
                    "GA": {
                        "name": "Georgia"
                    },
                    "GU": {
                        "name": "Guam"
                    },
                    "HI": {
                        "name": "Hawaii"
                    },
                    "ID": {
                        "name": "Idaho"
                    },
                    "IL": {
                        "name": "Illinois"
                    },
                    "IN": {
                        "name": "Indiana"
                    },
                    "IA": {
                        "name": "Iowa"
                    },
                    "KS": {
                        "name": "Kansas"
                    },
                    "KY": {
                        "name": "Kentucky"
                    },
                    "LA": {
                        "name": "Louisiana"
                    },
                    "ME": {
                        "name": "Maine"
                    },
                    "MD": {
                        "name": "Maryland"
                    },
                    "MA": {
                        "name": "Massachusetts"
                    },
                    "MI": {
                        "name": "Michigan"
                    },
                    "MN": {
                        "name": "Minnesota"
                    },
                    "MS": {
                        "name": "Mississippi"
                    },
                    "MO": {
                        "name": "Missouri"
                    },
                    "MT": {
                        "name": "Montana"
                    },
                    "NE": {
                        "name": "Nebraska"
                    },
                    "NV": {
                        "name": "Nevada"
                    },
                    "NH": {
                        "name": "New Hampshire"
                    },
                    "NJ": {
                        "name": "New Jersey"
                    },
                    "NM": {
                        "name": "New Mexico"
                    },
                    "NY": {
                        "name": "New York"
                    },
                    "NC": {
                        "name": "North Carolina"
                    },
                    "ND": {
                        "name": "North Dakota"
                    },
                    "MP": {
                        "name": "Northern Mariana Islands"
                    },
                    "OH": {
                        "name": "Ohio"
                    },
                    "OK": {
                        "name": "Oklahoma"
                    },
                    "OR": {
                        "name": "Oregon"
                    },
                    "PA": {
                        "name": "Pennsylvania"
                    },
                    "PR": {
                        "name": "Puerto Rico"
                    },
                    "MH": {
                        "name": "Republic of Marshall Islands"
                    },
                    "RI": {
                        "name": "Rhode Island"
                    },
                    "SC": {
                        "name": "South Carolina"
                    },
                    "SD": {
                        "name": "South Dakota"
                    },
                    "TN": {
                        "name": "Tennessee"
                    },
                    "TX": {
                        "name": "Texas"
                    },
                    "UT": {
                        "name": "Utah"
                    },
                    "VT": {
                        "name": "Vermont"
                    },
                    "VI": {
                        "name": "Virgin Islands of the U.S."
                    },
                    "VA": {
                        "name": "Virginia"
                    },
                    "WA": {
                        "name": "Washington"
                    },
                    "WV": {
                        "name": "West Virginia"
                    },
                    "WI": {
                        "name": "Wisconsin"
                    },
                    "WY": {
                        "name": "Wyoming"
                    }
                }
            }
        },
        "country_lang_mapping": {
            "US": {
                "state": "State:",
                "zip": "Zip Code:"
            },
            "GB": {
                "state": "County:",
                "zip": "Postal Code:"
            },
            "CA": {
                "state": "Province:",
                "zip": "Pin Code:"
            },
            "IN": {
                "state": "State:",
                "zip": "Pin:"
            }
        },
        "device_is_mobile": false,
        "pageType": "checkoutPage",
        "enable_browser_back_button": false
    }
</script>
<script type="text/javascript">
    app_lang = {
        "error_messages": {
            "zip_invalid": "Please enter a valid zip code!",
            "email_invalid": "Please enter a valid email id!",
            "cc_invalid": "Please enter a valid credit card number!",
            "cvv_invalid": "Please enter a valid CVV code!",
            "card_expired": "Card seems to have expired already!",
            "common_error": "Oops! Something went wrong! Can you please retry?",
            "not_checked": "Please check the agreement box in order to proceed.",
            "ca_zip_invalid": "Invalid Canada state code",
            "xv_invalid_shipping": "Your shipping address could not be verified",
            "xv_email": "Your email address could not be verified",
            "xv_phone": "Your phone number could not be verified"
        },
        "exceptions": {
            "config_error": "General config error",
            "config_file_missing": "General config error",
            "invalid_array": "Argument is not a valid array",
            "empty_prospect_id": "Prospect ID is empty or invalid",
            "curl_error": "Something went wrong with the request, Please try again.",
            "generic_error": "Something went wrong with the request, Please try again."
        }
    };
</script>

<script src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/assets/dist/codebase.min.js" type="text/javascript"></script>      <!-- Latest compiled and minified JavaScript -->
<script src="https://s3.us-east-2.amazonaws.com/funnels-images/keto-discountoffer-ukp-2/app/desktop/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $( "#success" ).delay(3000).slideDown( "slow", function() {

        });
    });
</script>


<div style="display:none;" class="ktemplate_pageContainer">
<div class="ktemplate_header">
	<h1> Your Header </h1>
</div>

	<div class="kthanks" style="width:500px">
	
	<!-- remove this link if you do not want customers to be able to place a second order -->
	<a href="#" id="kthanks_reorderLink">Place a new order</a>
  
    <h3>
    	Thank you <?php echo $customerName?>!<br>
        ORDER#: <?php echo $orderId ?>
    </h3>


    <div class="kthanks_box" style="width:480px; float:left">
        <div class="kthanks_boxTitle">
            Items Ordered
        </div>
        <div class="kthanks_boxContent">
           
           <?php echo $itemsTable ?>
           
            <hr />
            <div style="float:right">
                <div class="kthanks_spacer">
                    <div class="kthanks_label">
                        SubTotal:
                    </div>
                    <?php echo $currency.$subTotal ?>
                </div>
                <div class="kthanks_spacer">
                    <div class="kthanks_label">
                        S &amp; H:
                    </div>
                    <?php echo $currency.$shipTotal ?>
                </div>
                <?php if($taxTotal > 0 ) { ?>
                <div class="kthanks_spacer">
                    <div class="kthanks_label" >
                        Tax:
                    </div>
                   <?php echo $currency.$taxTotal ?>
                </div>
                <?php } ?>
                <?php if($insuranceTotal > 0 ) { ?>
                <div class="kthanks_spacer">
                    <div class="kthanks_label" >
                        Insurance:
                    </div>
                   <?php echo $currency.$insuranceTotal ?>
                </div>
                <?php } ?>
                <?php if($discountTotal > 0) { ?>
                <div class="kthanks_spacer" style="color:green">
                    <div class="kthanks_label" >
                        Discount:
                    </div>
                   <?php echo $currency.$discountTotal ?>
                </div>
                <?php } ?>
                <div class="kthanks_spacer" style="border-top:1px solid #CCC">
                    <div class="kthanks_label">
                        Grand Total:
                    </div>
                    <?php echo $currency.$orderTotal ?>
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
    </div>
    
    
    <div style="width:300px">
        
        <div class="kthanks_box">
            <div class="kthanks_boxTitle">
            Billing Information
            </div>
            <div class="kthanks_boxContent">
                <?php echo $billingAddress ?><br />
                <?php echo $emailAddress ?><br />
                <?php echo $phoneNumber ?><br />
            </div>
        </div>
    
        <div class="kthanks_box">
            <div class="kthanks_boxTitle">
                Shipping Information
            </div>
            
            <div class="kthanks_boxContent">
                <?php echo $shippingAddress ?>
            </div>
			
        </div>
    </div>
	<div style="clear:both"></div>
	
	<p>*A confirmation email has been sent to <?php echo $emailAddress ?> </p>
	
</div>

</div>

</body>
</html>







