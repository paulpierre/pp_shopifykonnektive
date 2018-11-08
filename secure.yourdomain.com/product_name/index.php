<?php
//This code must be included at the top of your script before any output is sent to the browser
//-even before <!DOCTYPE> declaration
require_once realpath(dirname(__FILE__)."/resources/konnektiveSDK.php");
$pageType = "checkoutPage"; //choose from: presalePage, leadPage, checkoutPage, upsellPage1, upsellPage2, upsellPage3, upsellPage4, thankyouPage
$deviceType = "ALL"; //choose from: DESKTOP, MOBILE, ALL
$ksdk = new KonnektiveSDK($pageType,$deviceType);

?>
<!DOCTYPE html>
<html dir="ltr" class="js mac firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity csspointerevents placeholder no-touchevents displaytable display-table generatedcontent  flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <style type="text/css">
        .pac-container{background-color:#fff;position:absolute!important;z-index:1000;border-radius:2px;border-top:1px solid #d9d9d9;font-family:Arial,sans-serif;box-shadow:0 2px 6px rgba(0,0,0,0.3);-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;overflow:hidden}.pac-logo:after{content:"";padding:1px 1px 1px 0;height:18px;box-sizing:border-box;text-align:right;display:block;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3.png);background-position:right;background-repeat:no-repeat;background-size:120px 14px}.hdpi.pac-logo:after{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png)}.pac-item{cursor:default;padding:0 4px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;line-height:30px;text-align:left;border-top:1px solid #e6e6e6;font-size:11px;color:#999}.pac-item:hover{background-color:#fafafa}.pac-item-selected,.pac-item-selected:hover{background-color:#ebf2fe}.pac-matched{font-weight:700}.pac-item-query{font-size:13px;padding-right:3px;color:#000}.pac-icon{width:15px;height:20px;margin-right:7px;margin-top:6px;display:inline-block;vertical-align:top;background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons.png);background-size:34px}.hdpi .pac-icon{background-image:url(https://maps.gstatic.com/mapfiles/api-3/images/autocomplete-icons_hdpi.png)}.pac-icon-search{background-position:-1px -1px}.pac-item-selected .pac-icon-search{background-position:-18px -1px}.pac-icon-marker{background-position:-1px -161px}.pac-item-selected .pac-icon-marker{background-position:-18px -161px}.pac-placeholder{color:gray}
    </style>
    <meta charset="utf-8">
    <link rel='icon' type='image/png' href='https://d2saw6je89goi1.cloudfront.net/uploads/digital_asset/file/414105/fastcoolairfavicon-32.png'></link>
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
    <script src="resources/js/checkout.js"></script>
    <?php
    $ksdk->echoJavascript();
    ?>
</head>
<body>
<div class="content prH7" style="">
    <div class="wrap">
        <div class="ar64">
            <div id="sp1" class="s8 s8c"><span id="spn1">1. Order</span></div>
            <div id="sp2" class="s8"><span id="spn2">2. Special Offer</span></div>
            <div id="sp3" class="s8"><span id="spn3">3. Confirmation</span></div>
        </div>
    </div>
</div>
<a href="#main-header" class="skip-to-content">
    Skip to content
</a>
<div class="banner" data-header="">
    <div class="wrap">
        <a class="logo logo--left" href="#">
            <img alt="" class="logo__image logo__image--medium" src="template/img/checkout_logo_8.png">
        </a>
        <h1 class="visually-hidden">
            Customer information
        </h1>
    </div>
</div>
<button class="order-summary-toggle order-summary-toggle--show shown-if-js" data-trekkie-id="order_summary_toggle" aria-expanded="false" aria-controls="order-summary" data-drawer-toggle="[data-order-summary]">
         <span class="wrap">
            <span class="order-summary-toggle__inner">
               <span class="order-summary-toggle__icon-wrapper">
                  <svg width="20" height="19" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__icon">
                     <path d="M17.178 13.088H5.453c-.454 0-.91-.364-.91-.818L3.727 1.818H0V0h4.544c.455 0 .91.364.91.818l.09 1.272h13.45c.274 0 .547.09.73.364.18.182.27.454.18.727l-1.817 9.18c-.09.455-.455.728-.91.728zM6.27 11.27h10.09l1.454-7.362H5.634l.637 7.362zm.092 7.715c1.004 0 1.818-.813 1.818-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817zm9.18 0c1.004 0 1.817-.813 1.817-1.817s-.814-1.818-1.818-1.818-1.818.814-1.818 1.818.814 1.817 1.818 1.817z"></path>
                  </svg>
               </span>
               <span class="order-summary-toggle__text order-summary-toggle__text--show">
                  <span>Show order summary</span>
                  <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
                     <path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path>
                  </svg>
               </span>
               <span class="order-summary-toggle__text order-summary-toggle__text--hide">
                  <span>Hide order summary</span>
                  <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
                     <path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z"></path>
                  </svg>
               </span>
               <span class="order-summary-toggle__total-recap total-recap" data-order-summary-section="toggle-total-recap">
               <span class="total-recap__final-price" data-checkout-payment-due-target="1495">$14.95</span>
               </span>
            </span>
         </span>
</button>
<div class="content" data-content="">
    <div class="wrap">
        <div class="main" role="main">
            <div class="main__header">
                <a class="logo logo--left" href="#">
                    <img alt="" class="logo__image logo__image--medium" src="template/img/checkout_logo_8.png">
                </a>
                <h1 class="visually-hidden">
                    Customer information
                </h1>
                <ul class="breadcrumb ">
                    <li class="breadcrumb__item breadcrumb__item--current">
                        <span class="breadcrumb__text" aria-current="step">Customer information</span>
                        <svg class="icon-svg icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
                            <use xlink:href="#chevron-right"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb__item breadcrumb__item--blank">
                        <span class="breadcrumb__text">Shipping method</span>
                        <svg class="icon-svg icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
                            <use xlink:href="#chevron-right"></use>
                        </svg>
                    </li>
                    <li class="breadcrumb__item breadcrumb__item--blank">
                        <span class="breadcrumb__text">Payment method</span>
                    </li>
                </ul>
                <div data-alternative-payments="" style="display: none;">
                    <div class="alt-payment-list-container">
                        <ul class="alt-payment-list ">
                            <li class="alt-payment-list__item alt-payment-list__item--paypalv4">
                                <div class="alt-payment-list__item__link alt-payment-list__item--paypal-btn--desktop" data-strategy="checkout" aria-label="Checkout with: PayPal" aria-describedby="forwarding-external-new-window-message" aria-haspopup="true" id="2b3bfe840989ff001736d5e5753bc6db" data-button-rendered="true">
                                    <div id="xcomponent-paypal-button-0dda02e1c4" class="paypal-button paypal-button-context-iframe paypal-button-label-paypal paypal-button-size-responsive paypal-button-layout-horizontal" style="">
                                        <style>
                                            #xcomponent-paypal-button-0dda02e1c4 {
                                                font-size: 0;
                                                width: 100%;
                                                overflow: hidden;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4.paypal-button-size-responsive {
                                                text-align: center;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet {
                                                display: inline-block;
                                                min-width: 150px;
                                                max-width: 750px;
                                                position: relative;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4.paypal-button-layout-vertical > .xcomponent-outlet {
                                                min-width: 150px;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet {
                                                width:  150px;
                                                height: 42px;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4.paypal-button-size-responsive > .xcomponent-outlet {
                                                width: 100%;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet > iframe {
                                                min-width: 100%;
                                                max-width: 100%;
                                                width: 150px;
                                                height: 100%;
                                                position: absolute;
                                                top: 0;
                                                left: 0;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet > iframe.xcomponent-component-frame {
                                                z-index: 100;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet > iframe.xcomponent-prerender-frame {

                                                z-index: 200;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet > iframe.xcomponent-visible {
                                                opacity: 1;
                                            }
                                            #xcomponent-paypal-button-0dda02e1c4 > .xcomponent-outlet > iframe.xcomponent-invisible {
                                                opacity: 0;
                                                pointer-events: none;
                                            }
                                        </style>
                                    </div>
                                </div>
                                <div class="alt-payment-list__item__link alt-payment-list__item--paypal-btn--mobile" data-strategy="checkout" aria-label="Checkout with: PayPal" aria-describedby="forwarding-external-new-window-message" aria-haspopup="true" id="8d3c9918ffa507c83e9457da264b819c" data-button-rendered="true">
                                    <div id="xcomponent-paypal-button-186ead6f60" class="paypal-button paypal-button-context-iframe paypal-button-label-paypal paypal-button-size-responsive paypal-button-layout-horizontal" style="">
                                        <style>
                                            #xcomponent-paypal-button-186ead6f60 {
                                                font-size: 0;
                                                width: 100%;
                                                overflow: hidden;
                                            }
                                            #xcomponent-paypal-button-186ead6f60.paypal-button-size-responsive {
                                                text-align: center;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet {
                                                display: inline-block;
                                                min-width: 150px;
                                                max-width: 750px;
                                                position: relative;
                                            }
                                            #xcomponent-paypal-button-186ead6f60.paypal-button-layout-vertical > .xcomponent-outlet {
                                                min-width: 150px;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet {
                                                width:  150px;
                                                height: 54px;
                                            }
                                            #xcomponent-paypal-button-186ead6f60.paypal-button-size-responsive > .xcomponent-outlet {
                                                width: 100%;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet > iframe {
                                                min-width: 100%;
                                                max-width: 100%;
                                                width: 150px;
                                                height: 100%;
                                                position: absolute;
                                                top: 0;
                                                left: 0;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet > iframe.xcomponent-component-frame {
                                                z-index: 100;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet > iframe.xcomponent-prerender-frame {

                                                z-index: 200;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet > iframe.xcomponent-visible {
                                                opacity: 1;
                                            }
                                            #xcomponent-paypal-button-186ead6f60 > .xcomponent-outlet > iframe.xcomponent-invisible {
                                                opacity: 0;
                                                pointer-events: none;
                                            }
                                        </style>
                                        <!--<div class="xcomponent-outlet" style="height: 54px;"><iframe style="background-color: transparent;" class="xcomponent-component-frame xcomponent-visible" allowtransparency="true" name="xcomponent__ppbutton__min__pmrhk2leei5cenruguzggobsge4gmirmej2gczzchirhaylzobqwyllcov2hi33oeiwcey3pnvyg63tfnz2faylsmvxhiir2pmrhezlgei5ce5dpoarh2lbcojsw4zdfojigc4tfnz2ceot3ejzgkzrchirhi33qej6syitqojxxa4zchj5se5dzobsseorcojqxoirmej3gc3dvmurdu6zcmvxhmir2ejyhe33eovrxi2lpnyrcyitdn5ww22luei5gmylmonssyittor4wyzjchj5se3dbmjswyir2ejygc6lqmfwcelbcnrqxs33voqrduitin5zgs6tpnz2gc3bcfqrgg33mn5zceorcm5xwyzbcfqrhg2dbobsseorcojswg5bcfqrhg2l2murduitsmvzxa33oonuxmzjcfqrg2ylymj2xi5dpnzzseorrfqrhiylhnruw4zjchjtgc3dtmuwce2dfnftwq5bchi2ti7jmejygc6lnmvxhiir2pmrf6x3upfygkx27ei5cex27mz2w4y3unfxw4x27ej6syitpnzaxk5din5zgs6tfei5hwis7l52hs4dfl5pseorcl5pwm5lomn2gs33ol5pse7jmejxw4utfnvsw2ytfojkxgzlsei5hwis7l52hs4dfl5pseorcl5pwm5lomn2gs33ol5pse7jmejsg63lbnfxceorcon2hkztgmnqw4zdzfzrw63jcfqrhgzltonuw63sjiqrduirsgeztaylegrrdsok7m5stg5dvnv5hg2djpj2ggirmejrhk5dun5xfgzltonuw63sjiqrduirrgnrtonzzgq2gin27m5stg5dvnv5hg2djpj2ggirmejwwk5dbei5hw7jmejzw65lsmnsseorcnvqw45lbnqrcyitqojswmzlumnuey33hnfxceotgmfwhgzjmejthk3tenfxgoir2pmrgc3dmn53wkzbchjnv2lbcmruxgylmnrxxozleei5fwitwmvxg23zcluwce4tfnvsw2ytfojswiir2lnosyitsmvwwk3lcmvzceot3ejpv65dzobsv6xzchirf6x3govxgg5djn5xf6xzcpv6syitpnzjgk3temvzceot3ejpv65dzobsv6xzchirf6x3govxgg5djn5xf6xzcpuwce33oknugs4dqnfxgoq3imfxgozjchj5sex27or4xazk7l4rduis7l5thk3tdoruw63s7l4rh2lbcn5xegylomnswyir2pmrf6x3upfygkx27ei5cex27mz2w4y3unfxw4x27ej6syitpnzbwy2ldnmrdu6zcl5pxi6lqmvpv6ir2ejpv6ztvnzrxi2lpnzpv6it5fqrgy33dmfwgkir2ejsw4x2vkmrcyitmn5tuyzlwmvwceorco5qxe3rcfqrgc53bnf2fa33qovyee4tjmrtwkir2pmrf6x3upfygkx27ei5cex27mz2w4y3unfxw4x27ej6syittovyha3dfnvsw45bchj5sez3forigc6lnmvxhit3qoruw63ttei5hwis7l52hs4dfl5pseorcl5pwm5lomn2gs33ol5pse7jmejqwizcqmf4w2zloorcgk5dbnfwhgir2pmrf6x3upfygkx27ei5cex27mz2w4y3unfxw4x27ej6syithmv2faylznvsw45cemv2gc2lmomrdu6zcl5pxi6lqmvpv6ir2ejpv6ztvnzrxi2lpnzpv6it5puwce5dfon2ceot3ejqwg5djn5xceorcmnugky3ln52xiit5fqrhk2leei5cemjygzswczbwmy3dairmej3gk4ttnfxw4ir2ejwws3rcfqrg63sfojzg64rchj5sex27or4xazk7l4rduis7l5thk3tdoruw63s7l4rh27l5fqrgszbchirdgyzygu3wmyzsgjtcelbcmrxw2yljnyrduitior2ha4z2f4xxg5dvmztggylomr4s4y3pnurh2__" title="ppbutton" scrolling="no" allowpaymentrequest="allowpaymentrequest" src="index_files/button_002.html" frameborder="0"></iframe></div>
                                    -->
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="alternative-payment-separator" data-alternative-payment-separator="">
                        <span class="alternative-payment-separator__content">
                        OR
                        </span>
                    </div>
                </div>
            </div>
            <div class="main__content">
                <div style="width:100%;display:table">
                    <div style="display:table-cell;vertical-align:middle"><img src="template/img/flame_24.png"></div>
                    <div style="font-weight:bold;padding-left:5px">An item you ordered is in high demand. No worries, we have reserved your order.</div>
                </div>
                <div>
                    <div id="ct836" style="display:block;background:#fff5d2;padding:10px 20px;border:1px solid #fac444;font-size:14px;color:#2c2c2c;font-weight:bold;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; margin:5px 0px 20px 0px">Your order is reserved for <span id="time">09:36</span> minutes!</div>
                </div>
                <div class="step" data-step="contact_information">
                    <form  id='kform' class="edit_checkout animate-floating-labels"  accept-charset="UTF-8" onsubmit='return false;'>
                        <input name="utf8" value="✓" type="hidden">
                        <input name="previous_step" id="previous_step" value="contact_information" type="hidden">
                        <div class="step__sections">
                            <div class="section section--contact-information">
                                <div class="section__header">
                                    <div class="layout-flex layout-flex--tight-vertical layout-flex--loose-horizontal layout-flex--wrap">
                                        <h2 class="section__title layout-flex__item layout-flex__item--stretch" id="main-header" tabindex="-1">
                                            Contact information
                                        </h2>
                                    </div>
                                </div>
                                <div class="section__content" data-section="customer-information" data-shopify-pay-validate-on-load="true">
                                    <div class="fieldset">
                                        <div class="field field--required">
                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_email">Email</label>
                                                <input placeholder="Email" autocapitalize="off" spellcheck="false" autocomplete="shipping email" data-trekkie-id="email_field" data-shopify-pay-handle="true" data-autofocus="true" data-backup="customer_email" aria-describedby="checkout-context-step-one" aria-required="true" class="field__input" size="30" name="emailAddress" id="checkout_email" type="email">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="section section--shipping-address" data-shipping-address="" data-update-order-summary="">

                                <div class="section__header">
                                    <h2 class="section__title">
                                        Billing address
                                    </h2>
                                </div>

                                <div class="section__content">
                                    <div class="fieldset" data-address-fields="">

                                        <input class="visually-hidden" autocomplete="shipping given-name" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="first_name" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][first_name]">
                                        <input class="visually-hidden" autocomplete="shipping family-name" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="last_name" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][last_name]">

                                        <input class="visually-hidden" autocomplete="shipping address-line1" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="address1" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][address1]">

                                        <input class="visually-hidden" autocomplete="shipping address-level2" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="city" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][city]">
                                        <input class="visually-hidden" autocomplete="shipping country" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="country" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][country]">
                                        <input class="visually-hidden" autocomplete="shipping address-level1" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="province" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][province]">
                                        <input class="visually-hidden" autocomplete="shipping postal-code" tabindex="-1" aria-hidden="true" aria-label="no-label" data-autocomplete-field="zip" data-honeypot="true" aria-required="true" size="30" type="text" name="checkout[shipping_address][zip]">








                                        <div class="field field--required field--half" data-address-field="first_name">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
                                                <input placeholder="First name" autocomplete="shipping given-name" data-trekkie-id="shipping_firstname_field" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="firstName" id="checkout_shipping_address_first_name" isRequired>
                                            </div>
                                        </div><div class="field field--required field--half" data-address-field="last_name">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
                                                <input placeholder="Last name" autocomplete="shipping family-name" data-backup="last_name" data-trekkie-id="shipping_lastname_field" class="field__input" aria-required="true" size="30" type="text" name="lastName" id="checkout_shipping_address_last_name" isRequired>
                                            </div>
                                        </div><div class="field field--required" data-address-field="address1" data-google-places="true">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_address1">Address</label>
                                                <input placeholder="Address" autocomplete="off" autocorrect="off" role="combobox" aria-autocomplete="list" aria-owns="address-autocomplete" aria-expanded="false" aria-required="true" data-backup="address1" data-trekkie-id="shipping_address1_google_autocomplete_field" data-google-autocomplete="true" data-google-autocomplete-title="Suggestions" data-google-autocomplete-single-item="1 item available" data-google-autocomplete-multi-item="{{number}} items available" data-google-autocomplete-item-selection="{{number}} of {{total}}" class="field__input" size="30" type="text" name="address1" id="checkout_shipping_address_address1" isRequired>
                                            </div>
                                        </div><div data-address-field="city" data-google-places="true" class="field field--required">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_city">City</label>
                                                <input placeholder="City" autocomplete="shipping address-level2" autocorrect="off" data-trekkie-id="shipping_city_field" data-backup="city" class="field__input" aria-required="true" size="30" type="text" name="city" id="checkout_shipping_address_city" isRequired>
                                            </div>
                                        </div><div class="field field--required field--three-eights field--show-floating-label" data-address-field="country" data-google-places="true">

                                            <div class="field__input-wrapper field__input-wrapper--select"><label class="field__label field__label--visible" for="checkout_shipping_address_country">Country</label>
                                                <select size="1" autocomplete="shipping country" data-trekkie-id="shipping_country_field" data-backup="country" class="field__input field__input--select" aria-required="true" name="country" id="checkout_shipping_address_country" isRequired>
                                                </select>
                                            </div>
                                        </div>







                                        <div class="field field--required field--show-floating-label field--three-eights" data-address-field="province" data-google-places="true">

                                            <div class="field__input-wrapper field__input-wrapper--select"><label class="field__label field__label--visible" for="checkout_shipping_address_province">State</label>
                                                <select placeholder="New York" autocomplete="shipping address-level1" data-trekkie-id="shipping_province_field" data-backup="province" class="field__input field__input--select" aria-required="true" name="state" id="checkout_shipping_address_province" isRequired>
                                                </select>
                                            </div>
                                        </div><div class="field field--required field--quarter" data-address-field="zip" data-google-places="true">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_zip">ZIP code</label>
                                                <input placeholder="ZIP code" autocomplete="shipping postal-code" data-backup="zip" data-trekkie-id="shipping_zip_google_autocomplete_field" data-google-autocomplete="true" data-google-autocomplete-title="Suggestions" data-google-autocomplete-single-item="1 item available" data-google-autocomplete-multi-item="{{number}} items available" data-google-autocomplete-item-selection="{{number}} of {{total}}" class="field__input field__input--zip" aria-required="true" size="30" type="text" name="postalCode" id="checkout_shipping_address_zip" isRequired>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>





                            <!-- SHIPPING DETAILS -->
                            <div style="margin-top:30px;color:#999;" class='kform_spacer kform_checkbox' >
                                <input name='billShipSame' type='checkbox' class="form-check-input" style="border:#999 2px solid;width:12px; height:12px;color:#333;" checked>
                                <label for='billShipSame'>
                                    Shipping Address same as Billing
                                </label>
                            </div>
                            <div id="kform_hiddenAddress" class="section section--shipping-address kform_hiddenAddress" data-shipping-address="" data-update-order-summary="">

                                <div class="section__header">
                                    <h2 class="section__title">
                                        Shipping address
                                    </h2>
                                </div>

                                <div class="section__content">
                                    <div class="fieldset" data-address-fields="">








                                        <div class="field field--required field--half" data-address-field="first_name">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
                                                <input placeholder="First name" autocomplete="shipping given-name" data-trekkie-id="shipping_firstname_field" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="shipFirstName" id="checkout_shipping_address_first_name" isRequired>
                                            </div>
                                        </div><div class="field field--required field--half" data-address-field="last_name">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
                                                <input placeholder="Last name" autocomplete="shipping family-name" data-backup="last_name" data-trekkie-id="shipping_lastname_field" class="field__input" aria-required="true" size="30" type="text" name="shipLastName" id="checkout_shipping_address_last_name" isRequired>
                                            </div>
                                        </div><div class="field field--required" data-address-field="address1" data-google-places="true">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_address1">Address</label>
                                                <input placeholder="Address" autocomplete="off" autocorrect="off" role="combobox" aria-autocomplete="list" aria-owns="address-autocomplete" aria-expanded="false" aria-required="true" data-backup="shipAddress1" data-trekkie-id="shipping_address1_google_autocomplete_field" data-google-autocomplete="true" data-google-autocomplete-title="Suggestions" data-google-autocomplete-single-item="1 item available" data-google-autocomplete-multi-item="{{number}} items available" data-google-autocomplete-item-selection="{{number}} of {{total}}" class="field__input" size="30" type="text" name="shipAddress1" id="checkout_shipping_address_address1" isRequired>
                                            </div>
                                        </div><div data-address-field="city" data-google-places="true" class="field field--required">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_city">City</label>
                                                <input placeholder="City" autocomplete="shipping address-level2" autocorrect="off" data-trekkie-id="shipping_city_field" data-backup="city" class="field__input" aria-required="true" size="30" type="text" name="shipCity" id="checkout_shipping_address_city" isRequired>
                                            </div>
                                        </div><div class="field field--required field--three-eights field--show-floating-label" data-address-field="country" data-google-places="true">

                                            <div class="field__input-wrapper field__input-wrapper--select"><label class="field__label field__label--visible" for="checkout_shipping_address_country">Country</label>
                                                <select size="1" autocomplete="shipping country" data-trekkie-id="shipping_country_field" data-backup="country" class="field__input field__input--select" aria-required="true" name="shipCountry" id="checkout_shipping_address_country" isRequired>
                                                </select>
                                            </div>
                                        </div>







                                        <div class="field field--required field--show-floating-label field--three-eights" data-address-field="province" data-google-places="true">

                                            <div class="field__input-wrapper field__input-wrapper--select"><label class="field__label field__label--visible" for="checkout_shipping_address_province">State</label>
                                                <select placeholder="New York" autocomplete="shipping address-level1" data-trekkie-id="shipping_province_field" data-backup="province" class="field__input field__input--select" aria-required="true" name="shipState" id="checkout_shipping_address_province" isRequired>
                                                </select>
                                            </div>
                                        </div><div class="field field--required field--quarter" data-address-field="zip" data-google-places="true">

                                            <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_zip">ZIP code</label>
                                                <input placeholder="ZIP code" autocomplete="shipping postal-code" data-backup="zip" data-trekkie-id="shipping_zip_google_autocomplete_field" data-google-autocomplete="true" data-google-autocomplete-title="Suggestions" data-google-autocomplete-single-item="1 item available" data-google-autocomplete-multi-item="{{number}} items available" data-google-autocomplete-item-selection="{{number}} of {{total}}" class="field__input field__input--zip" aria-required="true" size="30" type="text" name="shipPostalCode" id="checkout_shipping_address_zip" isRequired>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>






                            <div class="section section--payment-method">
                                <div class="section__header">
                                    <h2 class="section__title" id="main-header" tabindex="-1">Payment Method</h2>
                                    <p class="section__text">All transactions are secure and encrypted.</p>
                                </div>
                                <div class="section__content">
                                    <div>
                                        <fieldset class="content-box">
                                            <div class="radio-wrapper content-box__row">
                                                <div class="radio__input">
                                                    <input class="input-radio" type="radio" checked>
                                                </div>
                                                <div class="radio__label content-box__emphasis payment-method-wrapper">
                                                    <label class="radio__label__primary">Credit card</label>
                                                    <span class="radio__label__accessory">
                          <span class="visually-hidden">Pay with:</span>
                          <span>
                          <span class="payment-icon payment-icon--visa">
                          <span class="visually-hidden">Visa,</span>
                          </span><span class="payment-icon payment-icon--master">
                          <span class="visually-hidden">Mastercard,</span>
                          </span><span class="payment-icon payment-icon--american-express">
                          <span class="visually-hidden">American Express,</span>
                          </span><span class="payment-icon payment-icon--jcb">
                          <span class="visually-hidden">JCB,</span>
                          </span>
                          <span class="payment-icon-list__more">
                          <span class="content-box__small-text">and more…</span>
                          </span>
                          </span>
                          </span>
                                                </div>
                                            </div>
                                            <div class="radio-wrapper content-box__row content-box__row--secondary">
                                                <div class="fieldset">
                                                    <div class="field field--required">
                                                        <div class="field__input-wrapper field__input-wrapper--icon-right">
                                                            <label class="field__label field__label--visible">Card number</label>
                                                            <input name="cardNumber" type='TEXT' maxlength=16 isRequired class="field__input" size="30" autocomplete="cc-number">
                                                            <div id="credit_card_number_tooltip" class="field__icon">
                                                                <div class="field__icon-svg">
                                                                    <svg class="icon-svg icon-svg--color-adaptive-lighter icon-svg--size-16 icon-svg--block" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="12" height="14" viewBox="-295 389 12 14" enable-background="new -295 389 12 14"><path opacity=".5" d="M-284 395h-1v-2c0-2.2-1.8-4-4-4s-4 1.8-4 4v2h-1c-.5 0-1 .5-1 1v6c0 .5.5 1 1 1h10c.5 0 1-.5 1-1v-6c0-.5-.5-1-1-1zm-7-2c0-1.1.9-2 2-2s2 .9 2 2v2h-4v-2z"/></svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="field--third field field--required">
                                                        <div class="field__input-wrapper">
                                                            <label class="field__label field__label--visible">MM / YY</label>
                                                            <select name="cardMonth" class="field__input field__input--select" isRequired autocomplete="cc-exp-month">
                                                                <option value=''>Expiration Month</option>
                                                                <option value='01'>01 (Jan)</option>
                                                                <option value='02'>02 (Feb)</option>
                                                                <option value='03'>03 (Mar)</option>
                                                                <option value='04'>04 (Apr)</option>
                                                                <option value='05'>05 (May)</option>
                                                                <option value='06'>06 (Jun)</option>
                                                                <option value='07'>07 (Jul)</option>
                                                                <option value='08'>08 (Aug)</option>
                                                                <option value='09'>09 (Sep)</option>
                                                                <option value='10'>10 (Oct)</option>
                                                                <option value='11'>11 (Nov)</option>
                                                                <option value='12'>12 (Dec)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="field--third field field--required">
                                                        <div class="field__input-wrapper">
                                                            <label class="field__label field__label--visible">MM / YY</label>
                                                            <select name="cardYear" isRequired class="field__input field__input--select" autocomplete="cc-exp-year">
                                                                <option value=''>Expiration Year</option>
                                                                <option value='2018'>2018</option>
                                                                <option value='2019'>2019</option>
                                                                <option value='2020'>2020</option>
                                                                <option value='2021'>2021</option>
                                                                <option value='2022'>2022</option>
                                                                <option value='2023'>2023</option>
                                                                <option value='2024'>2024</option>
                                                                <option value='2025'>2025</option>
                                                                <option value='2026'>2026</option>
                                                                <option value='2027'>2027</option>
                                                                <option value='2028'>2028</option>
                                                                <option value='2029'>2029</option>
                                                                <option value='2030'>2030</option>
                                                                <option value='2031'>2031</option>
                                                                <option value='2032'>2032</option>
                                                                <option value='2033'>2033</option>
                                                                <option value='2034'>2034</option>
                                                                <option value='2035'>2035</option>
                                                                <option value='2036'>2036</option>
                                                                <option value='2037'>2037</option>
                                                                <option value='2038'>2038</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="field--third field field--required">
                                                        <div class="field__input-wrapper field__input-wrapper--icon-right">
                                                            <label class="field__label field__label--visible">CVV</label>
                                                            <input name='cardSecurityCode' type='TEXT' placeholder="CVV code" maxlength=4 class="field__input field__input--zip" size="30">
                                                            <div id="credit_card_verification_value_tooltip" role="tooltip" class="field__icon has-tooltip">
                                <span id="tooltip-for-verification_value" class="tooltip">
                                <span data-cvv-tooltip="unknown">3-digit security code usually found on the back of your card. American Express cards have a 4-digit code located on the front.
                                </span>
                                </span>
                                                                <div class="field__icon-svg">
                                                                    <svg class="icon-svg icon-svg--color-adaptive-lighter icon-svg--size-16 icon-svg--block" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="-297 388 16 16" enable-background="new -297 388 16 16"><path opacity=".5" d="M-289 388c-4.4 0-8 3.6-8 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm.7 13h-1.9v-2h1.9v2zm2.6-7.1c0 1.8-1.3 2.6-2.8 2.8l-.1 1.1h-1.1l-.3-2.3.1-.1c1.8-.1 2.6-.6 2.6-1.6 0-.8-.6-1.3-1.6-1.3-.9 0-1.6.4-2.3 1.1l-1.1-1.1c.8-.9 1.9-1.6 3.4-1.6 1.9.1 3.2 1.2 3.2 3z"/></svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="step__footer" data-step-footer>
                                <button type="button" id="kformSubmit" class="step__footer__continue-btn btn "  style="background-color:#41cd62;">
                                    <span class="btn__content">Confirm Order</span>
                                    <svg class="icon-svg icon-svg--size-18 btn__spinner icon-svg--spinner-button" aria-hidden="true" focusable="false">
                                        <use xlink:href="#spinner-button" />
                                    </svg>
                                </button>
                                <a class="step__footer__previous-link" data-trekkie-id="previous_step_link" href="#">
                                    <svg focusable="false" aria-hidden="true" class="icon-svg icon-svg--color-accent icon-svg--size-10 previous-link__icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                                        <path d="M8 1L7 0 3 4 2 5l1 1 4 4 1-1-4-4"/>
                                    </svg>
                                    <span class="step__footer__previous-link-content">Return To Cart</span>
                                </a>
                            </div>
                            <!-- End Shopify Billing -->

                        </div>



                        <input type="hidden" name='paySource' value="CREDITCARD">
                        <input type='hidden' name='orderItems' value=''>

                    </form>
                    <div data-shopify-pay-validation-modal="">
                    </div>
                </div>
                <span class="visually-hidden" id="forwarding-external-new-window-message">
                  Opens external website in a new window.
                  </span>
                <span class="visually-hidden" id="forwarding-new-window-message">
                  Opens in a new window.
                  </span>
                <span class="visually-hidden" id="forwarding-external-message">
                  Opens external website.
                  </span>
                <span class="visually-hidden" id="checkout-context-step-one">
                  Customer information -  Checkout
                  </span>
            </div>
            <div class="main__footer">

            </div>
        </div>
        <div class="sidebar" role="complementary">
            <div class="sidebar__header">
                <a class="logo logo--left" href="#">
                    <img alt="" class="logo__image logo__image--medium" src="template/img/checkout_logo_8.png">
                </a>
                <h1 class="visually-hidden">
                    Customer information
                </h1>
            </div>
            <div class="sidebar__content">
                <div id="order-summary" class="order-summary order-summary--is-collapsed" data-order-summary="">
                    <h2 class="visually-hidden-if-js">Order summary</h2>
                    <div class="order-summary__sections">
                        <div class="order-summary__section order-summary__section--product-list">
                            <div class="order-summary__section__content">
                                <table class="product-table">
                                    <caption class="visually-hidden">Shopping cart</caption>
                                    <thead>
                                    <tr>
                                        <th scope="col"><span class="visually-hidden">Product image</span></th>
                                        <th scope="col"><span class="visually-hidden">Description</span></th>
                                        <th scope="col"><span class="visually-hidden">Quantity</span></th>
                                        <th scope="col"><span class="visually-hidden">Price</span></th>
                                    </tr>
                                    </thead>
                                    <tbody data-order-summary-section="line-items">
                                    <!-- START PRODUCT TEMPLATE -->
                                    <tr style="display:none;" class="product product_template" data-product-id="" data-variant-id="" data-product-type="" data-customer-ready-visible="">
                                        <td class="product__image">
                                            <div class="product-thumbnail">
                                                <div class="product-thumbnail__wrapper">
                                                    <img alt="Product title goes here" class="product-thumbnail__image" src="template/img/51g3TABwgBL_small.jpg">
                                                </div>
                                                <span class="product-thumbnail__quantity" aria-hidden="true">1</span>
                                            </div>
                                        </td>
                                        <td class="product__description">
                                            <span class="product__description__name order-summary__emphasis">Product title goes here</span>
                                            <span class="product__description__variant order-summary__small-text"></span>
                                        </td>
                                        <td class="product__quantity visually-hidden">
                                            1
                                        </td>
                                        <td class="product__price">
                                            <span class="order-summary__emphasis">$0.00</span>
                                        </td>
                                    </tr>
                                    <!-- END PRODUCT TEMPLATE -->
                                    </tbody>
                                </table>
                                <div class="order-summary__scroll-indicator" aria-hidden="true" tabindex="-1">
                                    Scroll for more items
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="12" viewBox="0 0 10 12">
                                        <path d="M9.817 7.624l-4.375 4.2c-.245.235-.64.235-.884 0l-4.375-4.2c-.244-.234-.244-.614 0-.848.245-.235.64-.235.884 0L4.375 9.95V.6c0-.332.28-.6.625-.6s.625.268.625.6v9.35l3.308-3.174c.122-.117.282-.176.442-.176.16 0 .32.06.442.176.244.234.244.614 0 .848"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="order-summary__section order-summary__section--total-lines" data-order-summary-section="payment-lines">
                            <table class="total-line-table">
                                <caption class="visually-hidden">Cost summary</caption>
                                <thead>
                                <tr>
                                    <th scope="col"><span class="visually-hidden">Description</span></th>
                                    <th scope="col"><span class="visually-hidden">Price</span></th>
                                </tr>
                                </thead>
                                <tbody class="total-line-table__tbody">
                                <tr class="total-line total-line--subtotal">
                                    <th class="total-line__name" scope="row">Sales Tax</th>
                                    <td class="total-line__price">
                                       <span class="order-summary__emphasis" data-checkout-subtotal-price-target="1495">
                                       $0.00
                                       </span>
                                    </td>
                                </tr>
                                <tr class="total-line total-line--shipping">
                                    <th class="total-line__name" scope="row">Shipping</th>
                                    <td class="total-line__price">
                                       <span class="order-summary__emphasis" data-checkout-total-shipping-target="0">
                                        <span class="order-summary__emphasis" data-checkout-subtotal-price-target="1495">
                                       FREE
                                       </span>                                       </span>
                                    </td>
                                </tr>
                                <tr class="total-line total-line--taxes hidden" data-checkout-taxes="">
                                    <th class="total-line__name" scope="row">Taxes</th>
                                    <td class="total-line__price">
                                        <span class="order-summary__emphasis" data-checkout-total-taxes-target="0">$0.00</span>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot class="total-line-table__footer">
                                <tr class="total-line">
                                    <th class="total-line__name payment-due-label" scope="row">
                                        <span class="payment-due-label__total">Total</span>
                                    </th>
                                    <td class="total-line__price payment-due">
                                        <span class="payment-due__currency">USD</span>
                                        <span class="payment-due__price" data-checkout-payment-due-target="1495">
                                       $14.95
                                       </span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="visually-hidden" aria-live="polite" aria-atomic="true" role="status">
                                Updated total price:
                                <span data-checkout-payment-due-target="1495">
                              $14.95
                              </span>
                            </div>
                        </div>
                        <div style="position:relative;padding:10px 0px">
                            <div class="wyustit" style="position:relative;z-index:1;text-align:center"><span style="background:#fafafa;padding:0 15px">Why choose us?</span></div>
                            <div style="display:table;vertical-align:middle;width:100%;border-spacing:0px 20px">
                                <div class="wyuscs">
                                    <div class="wyuscs1"><img src="template/img/money-back.png"></div>
                                    <div class="wyuscs2">
                                        <span>30-day Satisfaction guarantee with Money Back</span>
                                        <p>If you're not satisfied with your products we will issue a full refund, no questions asked.</p>
                                    </div>
                                </div>
                                <div class="wyuscs">
                                    <div class="wyuscs1"><img src="template/img/mail-truck.png"></div>
                                    <div class="wyuscs2">
                                        <span>Over 34.245 successfully shipped orders</span>
                                        <p>We made as much happy customers as many orders we shipped. You simply have to join our big family.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="partial-icon-symbols" data-tg-refresh="partial-icon-symbols" data-tg-refresh-always="true" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg">
                        <symbol id="powered-by-google">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 116 15">
                                <path fill="#737373" d="M4.025 3.572c1.612 0 2.655 1.283 2.655 3.27 0 1.974-1.05 3.27-2.655 3.27-.902 0-1.63-.393-1.974-1.06h-.09v3.057H.95V3.68h.96v1.054h.094c.404-.726 1.16-1.166 2.02-1.166zm-.24 5.63c1.16 0 1.852-.884 1.852-2.36 0-1.477-.692-2.362-1.846-2.362-1.14 0-1.86.91-1.86 2.362 0 1.447.72 2.36 1.857 2.36zm7.072.91c-1.798 0-2.912-1.243-2.912-3.27 0-2.033 1.114-3.27 2.912-3.27 1.8 0 2.913 1.237 2.913 3.27 0 2.027-1.114 3.27-2.913 3.27zm0-.91c1.196 0 1.87-.866 1.87-2.36 0-1.5-.674-2.362-1.87-2.362-1.195 0-1.87.862-1.87 2.362 0 1.494.675 2.36 1.87 2.36zm12.206-5.518H22.05l-1.244 5.05h-.094L19.3 3.684h-.966l-1.412 5.05h-.094l-1.242-5.05h-1.02L16.336 10h1.02l1.406-4.887h.093L20.268 10h1.025l1.77-6.316zm3.632.78c-1.008 0-1.71.737-1.787 1.856h3.48c-.023-1.12-.69-1.857-1.693-1.857zm1.664 3.9h1.005c-.305 1.085-1.277 1.747-2.66 1.747-1.752 0-2.848-1.262-2.848-3.26 0-1.987 1.113-3.276 2.847-3.276 1.705 0 2.742 1.213 2.742 3.176v.386h-4.542v.047c.053 1.248.75 2.04 1.822 2.04.815 0 1.366-.3 1.63-.857zM31.03 10h1.01V6.086c0-.89.696-1.535 1.657-1.535.2 0 .563.038.645.06V3.6c-.13-.018-.34-.03-.504-.03-.838 0-1.565.434-1.752 1.05h-.094v-.938h-.96V10zm6.915-5.537c-1.008 0-1.71.738-1.787 1.857h3.48c-.023-1.12-.69-1.857-1.693-1.857zm1.664 3.902h1.005c-.304 1.084-1.277 1.746-2.66 1.746-1.752 0-2.848-1.262-2.848-3.26 0-1.987 1.113-3.276 2.847-3.276 1.705 0 2.742 1.213 2.742 3.176v.386h-4.542v.047c.053 1.248.75 2.04 1.822 2.04.815 0 1.366-.3 1.63-.857zm5.01 1.746c-1.62 0-2.657-1.28-2.657-3.266 0-1.98 1.05-3.27 2.654-3.27.878 0 1.622.416 1.98 1.108h.087V1.177h1.008V10h-.96V8.992h-.094c-.4.703-1.15 1.12-2.02 1.12zm.232-5.63c-1.15 0-1.846.89-1.846 2.364 0 1.476.69 2.36 1.846 2.36 1.148 0 1.857-.9 1.857-2.36 0-1.447-.715-2.362-1.857-2.362zm7.875-3.115h1.024v3.123c.23-.3.507-.53.827-.688.32-.16.668-.238 1.043-.238.78 0 1.416.27 1.9.806.49.537.73 1.33.73 2.376 0 .992-.24 1.817-.72 2.473-.48.656-1.145.984-1.997.984-.476 0-.88-.114-1.207-.344-.195-.137-.404-.356-.627-.657v.8h-.97V1.363zm4.02 7.225c.284-.454.426-1.05.426-1.794 0-.66-.142-1.207-.425-1.64-.283-.434-.7-.65-1.25-.65-.48 0-.9.177-1.264.532-.36.356-.542.942-.542 1.758 0 .59.075 1.068.223 1.435.277.694.795 1.04 1.553 1.04.57 0 .998-.227 1.28-.68zM63.4 3.726h1.167c-.148.402-.478 1.32-.99 2.754-.383 1.077-.703 1.956-.96 2.635-.61 1.602-1.04 2.578-1.29 2.93-.25.35-.68.527-1.29.527-.147 0-.262-.006-.342-.017-.08-.012-.178-.034-.296-.065v-.96c.183.05.316.08.4.093.08.012.152.018.215.018.195 0 .338-.03.43-.094.092-.065.17-.144.232-.237.02-.033.09-.193.21-.48.122-.29.21-.506.264-.646l-2.32-6.457h1.196l1.68 5.11 1.694-5.11zM73.994 5.282V6.87h3.814c-.117.89-.416 1.54-.87 1.998-.557.555-1.427 1.16-2.944 1.16-2.35 0-4.184-1.882-4.184-4.217 0-2.332 1.835-4.215 4.184-4.215 1.264 0 2.192.497 2.873 1.135l1.122-1.117C77.04.697 75.77 0 73.99 0c-3.218 0-5.923 2.606-5.923 5.805 0 3.2 2.705 5.804 5.923 5.804 1.738 0 3.048-.57 4.073-1.628 1.05-1.045 1.382-2.522 1.382-3.71 0-.366-.028-.708-.087-.992h-5.37zm10.222-1.29c-2.082 0-3.78 1.574-3.78 3.748 0 2.154 1.698 3.747 3.78 3.747S87.998 9.9 87.998 7.74c0-2.174-1.7-3.748-3.782-3.748zm0 6.018c-1.14 0-2.127-.935-2.127-2.27 0-1.348.983-2.27 2.124-2.27 1.142 0 2.128.922 2.128 2.27 0 1.335-.986 2.27-2.128 2.27zm18.54-5.18h-.06c-.37-.438-1.083-.838-1.985-.838-1.88 0-3.52 1.632-3.52 3.748 0 2.102 1.64 3.747 3.52 3.747.905 0 1.618-.4 1.988-.852h.06v.523c0 1.432-.773 2.2-2.012 2.2-1.012 0-1.64-.723-1.9-1.336l-1.44.593c.414.994 1.51 2.213 3.34 2.213 1.94 0 3.58-1.135 3.58-3.902v-6.74h-1.57v.645zm-1.9 5.18c-1.144 0-2.013-.968-2.013-2.27 0-1.323.87-2.27 2.01-2.27 1.13 0 2.012.967 2.012 2.282.006 1.31-.882 2.258-2.01 2.258zM92.65 3.992c-2.084 0-3.783 1.574-3.783 3.748 0 2.154 1.7 3.747 3.782 3.747 2.08 0 3.78-1.587 3.78-3.747 0-2.174-1.7-3.748-3.78-3.748zm0 6.018c-1.143 0-2.13-.935-2.13-2.27 0-1.348.987-2.27 2.13-2.27 1.14 0 2.126.922 2.126 2.27 0 1.335-.986 2.27-2.127 2.27zM105.622.155h1.628v11.332h-1.628m6.655-1.477c-.843 0-1.44-.38-1.83-1.135l5.04-2.07-.168-.426c-.314-.84-1.274-2.39-3.227-2.39-1.94 0-3.554 1.516-3.554 3.75 0 2.1 1.595 3.745 3.736 3.745 1.725 0 2.724-1.05 3.14-1.658l-1.285-.852c-.427.62-1.01 1.032-1.854 1.032zm-.117-4.612c.668 0 1.24.342 1.427.826l-3.405 1.4c0-1.574 1.122-2.226 1.978-2.226z"></path>
                            </svg>
                        </symbol>
                        <symbol id="close">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                <path d="M15.1 2.3L13.7.9 8 6.6 2.3.9.9 2.3 6.6 8 .9 13.7l1.4 1.4L8 9.4l5.7 5.7 1.4-1.4L9.4 8"></path>
                            </svg>
                        </symbol>
                        <symbol id="spinner-button">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M20 10c0 5.523-4.477 10-10 10S0 15.523 0 10 4.477 0 10 0v2c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8h2z"></path>
                            </svg>
                        </symbol>
                        <symbol id="mobile-phone">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M5 2.99C5 1.34 6.342 0 8.003 0h7.994C17.655 0 19 1.342 19 2.99v18.02c0 1.65-1.342 2.99-3.003 2.99H8.003C6.345 24 5 22.658 5 21.01V2.99zM7 5c0-.552.456-1 .995-1h8.01c.55 0 .995.445.995 1v14c0 .552-.456 1-.995 1h-8.01C7.445 20 7 19.555 7 19V5zm5 18c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1z" fill-rule="evenodd"></path>
                            </svg>
                        </symbol>
                        <symbol id="check-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34">
                                <g fill-rule="evenodd">
                                    <circle fill="#EBEBEB" cx="17" cy="17" r="17"></circle>
                                    <path d="M10.6 17.4c-.3-.3-1-.3-1.3 0-.4.4-.4 1 0 1.4l5.2 5.2 10.3-11.4c.3-.4.3-1 0-1.4-.5-.3-1-.3-1.5 0l-9 10-3.7-3.8z"></path>
                                </g>
                            </svg>
                        </symbol>
                        <symbol id="exclamation-point">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
                                <g fill="none" fill-rule="evenodd">
                                    <path d="M18 36c9.94 0 18-8.06 18-18S27.94 0 18 0 0 8.06 0 18s8.06 18 18 18z" fill="#FF3E3E"></path>
                                    <path fill="#FFF" d="M17 9h2v13h-2z"></path>
                                    <rect fill="#FFF" x="16.5" y="26" width="3" height="3" rx="1.5"></rect>
                                </g>
                            </svg>
                        </symbol>
                        <symbol id="chevron-right">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                                <path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
                            </svg>
                        </symbol>
                        <symbol id="arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                <path d="M16 8.1l-8.1 8.1-1.1-1.1L13 8.9H0V7.3h13L6.8 1.1 7.9 0 16 8.1z"></path>
                            </svg>
                        </symbol>
                        <symbol id="spinner-small">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path d="M32 16c0 8.837-7.163 16-16 16S0 24.837 0 16 7.163 0 16 0v2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14h2z"></path>
                            </svg>
                        </symbol>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="template/js/checkoutv1.js"></script>


<style type="text/css">.main ul.breadcrumb{display:none!important}.content.prH7{padding:8px 0}.ar64{width:100%}.ar64 .s8{font-size:14px;text-align:center;color:#fff;cursor:default;margin:0 3px;padding:9px 10px 9px 30px;float:left;position:relative;background-color:#d9e3f7;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;width:20%}.ar64 .s8:after,.ar64 .s8:before{content:" ";position:absolute;top:0;right:-17px;width:0;height:0;border-top:19px solid transparent;border-bottom:17px solid transparent;border-left:17px solid #d9e3f7;z-index:2;}.ar64 .s8:before{right:auto;left:0;border-left:17px solid #fff;z-index:0}.ar64 .s8:first-child:before{border:none}.ar64 .s8:first-child{margin-left:0;border-top-left-radius:4px;border-bottom-left-radius:4px}.ar64 .s8 span{position:relative}.ar64 .s8.s8c{color:#fff;background-color:#23468c}.ar64 .s8.s8c:after{border-left:17px solid #23468c}.ar64 .s8 svg{position:absolute;left:-17px;top:2px}.ar64 .s8:first-child svg{left:-16px}.wyustit:before{border-top:1px solid #dfdfdf;content:"";margin:0 auto;position:absolute;top:50%;left:0;right:0;bottom:0;width:100%;z-index:-1}.wyuscs{display:table-row;padding-bottom:20px}.wyuscs1,.wyuscs2{display:table-cell;vertical-align:middle}.wyuscs1{width:20%;font-size:15px}.wyuscs2{width:80%}.wyuscs2 span{font-size:14px;font-weight:700;color:#666}.wyuscs2 p{font-size:12px;color:#777}@media(min-width:450px){.ar64 .s8{min-width:29%}.ar64 .s8 svg{position:relative!important;top:2px!important;left:-10px!important}}@media(max-width:750px){.ar64 .s8{font-size:11px}.ar64 .s8:first-child{padding-left:20px}}</style>
<div class="pac-container pac-logo hdpi" style="display: none;"></div>
</body>
<!--<body>


<div class='ktemplate_pageContainer'>
	<div class='ktemplate_header'>
    	<h1> Your Header </h1>
    </div>
    <br><br>

		<form id='kform' class='kform kform_kcartCheckout' onsubmit='return false;'>
		<div class='kform_layout2Col kform_layout2Col_L'>

        				<div class="kform_spacer">
                    <input name="firstName" type="TEXT" isRequired>
                </div>
                <div class="kform_spacer">
                    <input name="lastName" type="TEXT" isRequired>
                </div>
				
				<div class="kform_spacer">
                    <input name="emailAddress" type="TEXT" isRequired>
                </div>
				
				<div class="kform_spacer">
                    <input name="phoneNumber" type="TEXT" isRequired>
                </div>
				<h3> Billing Address</h3>				<div class='kform_spacer'>
                    <input name='address1' type='TEXT' isRequired>
                </div>

                <div class='kform_spacer'>
                    <input name='address2' type='TEXT'>
                </div>

                <div class='kform_spacer'>
                    <input name='city' type='TEXT' isRequired>
                </div>

                <div class='kform_spacer'>
                    <select name='state' isRequired>
                        <option value=''>Select State</option>
                    </select>
                </div>

                <div class='kform_spacer'>
                    <select name='country' defaultText='country'>
                    </select>
                </div>

                 <div class='kform_spacer'>
                    <input name='postalCode' type='TEXT' isRequired>
                </div>
            <div class='kform_spacer kform_checkbox'>
                <input name='billShipSame' type='CHECKBOX' checked>
                <label for='billShipSame'>
                    Shipping Address same as Billing
                </label>
            </div>
            <div id='kform_hiddenAddress'>
                    <div class='kform_spacer'>
                        <input name='shipAddress1' type='TEXT' isRequired>
                    </div>

                    <div class='kform_spacer'>
                        <input name='shipAddress2' type='TEXT'>
                    </div>

                    <div class='kform_spacer'>
                        <input name='shipCity' type='TEXT' isRequired>
                    </div>

                    <div class='kform_spacer'>
                        <select name='shipState' isRequired>
                        </select>
                    </div>

                    <div class='kform_spacer'>
                        <select name='shipCountry'></select>
                    </div>

                     <div class='kform_spacer'>
                        <input name='shipPostalCode' type='TEXT' isRequired>
                    </div>
			</div>
            
        	</div>

			<div class='kform_layout2Col  kform_layout2Col_R'>

 	
                <table class='kcartTotals'>
					<tr>
                    	<td>Sub Total</td>
                        <td class='kcartSubTotal'>0.00</td>
                    </tr>
                    <tr>
                    	<td>Shipping</td>
                        <td class='kcartShipTotal'>0.00</td>
                    </tr>
                    <tr>
                    	<td>Sales Tax</td>
                        <td class='kcartSalesTax'>0.00</td>
                    </tr>
                    <tr>
                    	<td>Discount</td>
                        <td class='kcartDiscount'>0.00</td>
                    </tr>
                    <tr>
                    	<td>Insurance</td>
                        <td class='kcartInsurance'>0.00</td>
                    </tr>
                    <tr>
                    	<td><b>Grand Total</b></td>
                        <td class='kcartGrandTotal'>0.00</td>
                    </tr>
                </table>
	

                    <input type="hidden" name='paySource' value="CREDITCARD">
                    <div class='kform_spacer'>
                        Credit Card:<br>
                        <div style='display:none'>
                            <div id='kformPaySourceWrap' inputType='radio'></div>
                            <div class='kform_spacer' id='kformNewPaymentType'>
                                <input type='checkbox' name='newPaymentType'>
                                <span>
                                New Credit Card
                            </span>
                            </div>
                        </div>
                    </div>
                
                   <div id='kform_paySourceCard'>
                       <div class='kform_spacer'>
                            <input name='cardNumber' type='TEXT' maxlength=16 isRequired>
                        </div>

                        <div class='kform_spacer'>
                            <input name='cardSecurityCode' type='TEXT' maxlength=4>
                        </div>

                        <div class='kform_spacer' style='text-align:right'>
                            <label for='cardMonth'  style='width:30%;text-align:middle;'>
                                Expiration:
                            </label>
                            <select name='cardMonth' style='width:30%' isRequired>
                                <option value='01'>01 (Jan)</option>
                                <option value='02'>02 (Feb)</option>
                                <option value='03'>03 (Mar)</option>
                                <option value='04'>04 (Apr)</option>
                                <option value='05'>05 (May)</option>
                                <option value='06'>06 (Jun)</option>
                                <option value='07'>07 (Jul)</option>
                                <option value='08'>08 (Aug)</option>
                                <option value='09'>09 (Sep)</option>
                                <option value='10'>10 (Oct)</option>
                                <option value='11'>11 (Nov)</option>
                                <option value='12'>12 (Dec)</option>
                            </select>
                            <select name='cardYear' style='width:30%' isRequired>
                                <option value='2018'>2018</option><option value='2019'>2019</option><option value='2020'>2020</option><option value='2021'>2021</option><option value='2022'>2022</option><option value='2023'>2023</option><option value='2024'>2024</option><option value='2025'>2025</option><option value='2026'>2026</option><option value='2027'>2027</option><option value='2028'>2028</option><option value='2029'>2029</option><option value='2030'>2030</option><option value='2031'>2031</option><option value='2032'>2032</option><option value='2033'>2033</option><option value='2034'>2034</option><option value='2035'>2035</option><option value='2036'>2036</option><option value='2037'>2037</option><option value='2038'>2038</option>                            </select>
                        </div>
                    </div>
                
			 <input type='button' value='Rush My Order!' class='kform_submitBtn' id='kformSubmit'>
            
            			

             
            <input type='hidden' name='orderItems' value=''>
			</div>
           
          
        </form>
        <br><br>
	</div>
	

</body>-->
</html>







