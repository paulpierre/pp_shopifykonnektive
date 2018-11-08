
var k_items = {};

var product_map = {


    //This is the product mapping from Shopify to Konnektive product ID's

    12932093509743:139, //Buy 2 GET 1 FREE ($59 /each) SAVE 66%
    12932093444207:138, //Buy 3 GET 2 FREE ($53 /each) SAVE 70%
    12932093411439:137, //Buy 1 ($89 /each) SAVE 50%
    12932093542511:140, //Buy 2 ($69 /each) SAVE 61%
    12932093640815:142, //Buy 10 ($46 /each) SAVE 74%
    12932093608047:141, //Buy 4 ($55 Each)
}



$(document).ready(function(){
	//var cart_data = (window.location.hostname == "secure.product_name")?"/cart.json":"https://www.product_name.com/cart.json"
    var script = document.createElement('script');
    script.src = "https://www.product_name.com/cart.json?callback=display_cart";
    document.getElementsByTagName('head')[0].appendChild(script);


    $("[name='cardNumber']").change(function(e){

        if(!$("[name='billShipSame']").is(":checked"))
        {
            $("[name='shipAddress1']").val($("[name='address1']").val());
            $("[name='shipFirstName']").val($("[name='firstName']").val());
            $("[name='shipLastName']").val($("[name='lastName']").val());

            $("[name='shipCity']").val($("[name='city']").val());
            $("[name='shipPostalCode']").val($("[name='postalCode']").val());
            $("[name='shipCountry']").val($("[name='country']").val());
        }

});

function display_cart(o) {
	console.log(o);

    var currency, item_count, total_price;

    currency = o.currency;
    item_count = o.items.length;

    //lets redirect them if the cart is empty
    if(item_count === 0)
    {
        alert("Your cart is empty, you may have reached this page by mistake");
        window.location ="https://www.product_name.com/products/real_product_page"
    }

    total_price = (o.original_total_price * 0.01).toFixed(2);
    $(".payment-due__currency").text(currency);
    $(".payment-due__price").text("$" + total_price);

    console.log("items in cart: " + item_count);


    var line_items = $("tbody[data-order-summary-section='line-items']");
    for (var i = 0; i <= item_count-1; i++) {
        console.log("product #" + (i + 1));
        var line_item = $("tr.product_template").clone();
        $(line_item).removeClass("product_template");

        var product_image = o.items[i].image,
            product_title = o.items[i].title,
            product_price = (o.items[i].line_price * 0.01).toFixed(2),
            product_quantity = o.items[i].quantity,
            variant_id = o.items[i].variant_id;

        console.log("variant_id: " + variant_id);
        if(typeof product_map[variant_id] !== "undefined") k_items[product_map[variant_id]] = product_quantity;


        $(line_item).find("div.product-thumbnail__wrapper img").attr("src", product_image); //product image
        $(line_item).find("div.product-thumbnail__wrapper img").attr("alt", product_title); //alt description
        $(line_item).find("span.product-thumbnail__quantity").text(product_quantity); //product quantity
        $(line_item).find("td.product__price span").text("$" + product_price); //product price
        $(line_item).find("span.product__description__name").text(product_title); //product title
        $(line_items).append(line_item);
        $(line_item).show();
    }
    var ksize = 0, key;
    for (key in k_items) {
        if (k_items.hasOwnProperty(key)) ksize++;
    }
    console.log("k_items length: " + ksize + " k_items: "); console.log(k_items);


    if(ksize > 0)
    {
        console.log("setting orderItems: " + JSON.stringify(k_items));
        setTimeout(function(){$("input[name='orderItems']").val(JSON.stringify(k_items));},500);

        var url = "https://secure.product_name.com/product_name/resources/async.php";

        $.ajax({
            type: "POST",
            url: url,
            data: {
                method:"updateCart",
                cartItems:JSON.stringify(k_items)
            },
            success: function(o){console.log(url);console.log(o);}
        });
    }


}


function wc(cname, cvalue)
{
    var d = new Date();
    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function rc(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return false;
}

function uparam(name, url) {
    if (!url) url = window.location.href;
    if(!name) return null;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
