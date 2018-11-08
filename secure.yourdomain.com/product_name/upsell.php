<?php
//This code must be included at the top of your script before any output is sent to the browser
//-even before <!DOCTYPE> declaration
require_once realpath(dirname(__FILE__)."/resources/konnektiveSDK.php");
$pageType = "upsellPage1"; //choose from: presalePage, leadPage, checkoutPage, upsellPage1, upsellPage2, upsellPage3, upsellPage4, thankyouPage
$deviceType = "ALL"; //choose from: DESKTOP, MOBILE, ALL
$ksdk = new KonnektiveSDK($pageType,$deviceType);
$productId = $ksdk->page->productId;
$upsell = $ksdk->getProduct((int) $productId);
?>
<!DOCTYPE html>
<html dir="ltr" class="js mac firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity csspointerevents placeholder no-touchevents displaytable display-table generatedcontent  flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <style type="text/css">
        .pac-container{background-color:#fff;position:absolute!important;z-index:1000;border-radius:2px;border-top:1px solid #d9d9d9;font-family:Arial,sans-serif;box-shadow:0 2px 6px rgba(0,0,0,0.3);-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden}.pac-logo:after{content:"";padding:1px 1px 1px 0;height:18px;box-sizing:border-box;text-align:right;display:block;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3.png);background-position:right;background-repeat:no-repeat;background-size:120px 14px}.hdpi.pac-logo:after{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png)}.pac-item{cursor:default;padding:0 4px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;line-height:30px;text-align:left;border-top:1px solid #e6e6e6;font-size:11px;color:#999}.pac-item:hover{background-color:#fafafa}.pac-item-selected,.pac-item-selected:hover{background-color:#ebf2fe}.pac-matched{font-weight:700}.pac-item-query{font-size:13px;padding-right:3px;color:#000}.pac-icon{width:15px;height:20px;margin-right:7px;margin-top:6px;display:inline-block;vertical-align:top;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons.png);background-size:34px}.hdpi .pac-icon{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons_hdpi.png)}.pac-icon-search{background-position:-1px -1px}.pac-item-selected .pac-icon-search{background-position:-18px -1px}.pac-icon-marker{background-position:-1px -161px}.pac-item-selected .pac-icon-marker{background-position:-18px -161px}.pac-placeholder{color:gray}
    </style>
    <link rel='icon' type='image/png' href='https://d2saw6je89goi1.cloudfront.net/uploads/digital_asset/file/414105/fastcoolairfavicon-32.png'></link>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height, minimum-scale=1.0, user-scalable=0">
    <meta name="referrer" content="origin">
    <title>Customer information - Checkout</title>
    <meta data-browser="firefox" data-browser-major="61">
    <meta data-body-font-family="Helvetica Neue" data-body-font-type="system">

    <!--[if lt IE 9]>
    <link rel="stylesheet" media="all" href="template/css/oldie.css" />
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <link rel="stylesheet" media="all" href="template/css/v2-ltr-edge-4d0cd57abbeffc1b2c0b7b4bb1e35dd0-990465509318999.css">
    <!--<![endif]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?php
    $ksdk->echoJavascript();
    ?>
</head>
<body>

<div class="container" style="text-align:center;margin-top:20px;"><img style="max-width:150px;" src="https://images.clickfunnels.com/2a/b3e5509a3f11e8996b97c9b71bda1d/fastcoolair-logo-2.png"/></div>
<div class="content prH7" style="">

    <div class="wrap">
        <div class="ar64">
            <div id="sp1" class="s8"><span id="spn1">1. Order</span></div>
            <div id="sp2" class="s8 s8c"><span id="spn2">2. Special Offer</span></div>
            <div id="sp3" class="s8"><span id="spn3">3. Confirmation</span></div>
        </div>
    </div>

</div>

<div class="content" data-content="">
    <div class="wrap">
        <div class="main" role="main">

            <div class="main__content" style="text-align:center;">
                <form id="kform"  onsubmit="return false">

                <h1 style="color:#005db2;font-weight:bolder;font-size:3em">Limited Time Only!</h1>
                <h2 style="color:#555;font-size:2em;padding:15px;">
                    Add 1 Additional CoolAir To Your Order:
                </h2>
                <div style="box-shadow:0px 0px 15px 3px  #999;width:60%; margin:0 auto;padding:25px;">
                    <strong style="font-size:1.8em;font-weight:bolder;">
                        <span style="color:#e43b2c">1 MORE</span>: $39 Each
                        <img src="https://product_name/img/product.png" style="max-width:300px;display:block;margin:0 auto;"/>
                        <div style="color:#e43b2c;padding:10px;">Value: $158</div>
                        <div style="color:#555;padding:10px;">Total: $39</div>

                        <button value="Add to Order"id="kformSubmit" type="button" class=" btn btn--size-small" style="background-color:#41cd62;margin-top:20px;background-attachment:scroll;background-clip:border-box;background-color:rgb(69, 201, 54);background-image:none;background-origin:padding-box;background-position-x:0px;background-position-y:0px;background-repeat-x:;background-repeat-y:;background-size:auto;border-bottom-color:rgba(0, 0, 0, 0.2);border-bottom-left-radius:10px;border-bottom-right-radius:10px;border-bottom-style:solid;border-bottom-width:3px;border-image-outset:0px;border-image-repeat:stretch;border-image-slice:100%;border-image-source:none;border-image-width:1;border-left-color:rgba(0, 0, 0, 0.2);border-left-style:solid;border-left-width:1px;border-right-color:rgba(0, 0, 0, 0.2);border-right-style:solid;border-right-width:1px;border-top-color:rgba(0, 0, 0, 0.2);border-top-left-radius:10px;border-top-right-radius:10px;border-top-style:solid;border-top-width:1px;box-shadow:rgba(255, 255, 255, 0.2) 0px 0px 0px 2px inset;box-sizing:border-box;color:rgb(255, 255, 255);cursor:pointer;display:inline-block;font-family:Lato, Helvetica, sans-serif;font-size:18px;font-weight:700;height:49px;line-height:25.7143px;margin-left:0px;margin-right:0px;padding-bottom:10px;padding-left:25px;padding-right:25px;padding-top:10px;text-align:center;text-decoration-color:rgb(255, 255, 255);text-decoration-line:none;text-decoration-style:solid;text-shadow:rgba(0, 0, 0, 0.2) 1px 1px 0px;text-size-adjust:100%;transform:none;width:232.453px;word-wrap:break-word;-webkit-font-smoothing:antialiased;-webkit-tap-highlight-color:rgba(0, 0, 0, 0);">
                            <span class="btn__content">Add 1 More CoolAir</span>
                            <svg class="icon-svg icon-svg--size-18 btn__spinner icon-svg--spinner-button" aria-hidden="true" focusable="false">
                                <use xlink:href="#spinner-button"></use>
                            </svg>
                        </button>
                    </strong>


                </div>

                <br/>
                <img src="https://images.clickfunnels.com/b2/8ebdc089c211e89b51b90fafd22e7b/secure-logos.png" style="margin:0 auto;max-width:400px;"/>

                    <input type="hidden" name="productId" value="<?php echo $upsell->productId; ?>" noSaveFormValue readonly>

                    <div style="margin-top:40px;">
                        <a href="<?php echo $ksdk->redirectsTo; ?>" style="color:#366ccc;font-size:1.2em;text-decoration:underline;">
                            No, I don't want 1 additional Fast CoolAir, knowing that I could use it to cool an extra room in my house.
                        </a>
                    </div>



            </div>

            </form>


            <div class="main__footer">

            </div>
        </div>
    </div>
</div>


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
<div class="pac-container pac-logo hdpi" style="display: none;"></div>
</body>
</html>







