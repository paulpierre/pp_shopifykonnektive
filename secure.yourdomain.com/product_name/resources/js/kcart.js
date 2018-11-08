
kcart = function(){};
kcart.prototype.construct = function(lander)
{
	"use strict";
	if(typeof lander !== 'object')
	{
		console.log("1001: Cannot build cart without instance of lander");
	    return;	
	}
	
	this.lander = lander;
	this.validator = lander.validator;
	this.defaultProduct = lander.defaultProduct;
	this.defaultShipProfile = lander.defaultShipProfile;
	
	//init some other objects
	this.products = lander.products;
	this.orderItems = lander.orderItems;
	this.sessionData = {};
	this.totalsNodes = {};
	
	//profiles
	this.profiles = {};
	this.profiles.shipping = lander.shipProfiles;
	this.profiles.coupon = lander.coupons;
	this.profiles.taxes = lander.taxes;
	
	this.currency = lander.currencySymbol;
	
	this.cartDetail = document.getElementById('kcartDetail');
	
	//define any kcartWidget blocks by 
	this.cartWidgets = [];
	var nodes = document.getElementsByClassName("kcartWidget");
	for(var i=0;i<nodes.length;i++){
		this.cartWidgets.push(nodes[i]);}
	
	this.cartTotals = [];
	nodes = document.getElementsByClassName("kcartTotals");
	for(i=0;i<nodes.length;i++){
		this.cartTotals.push(nodes[i]);}
	
	this.upgradeCartDetail();
	this.upgradeAddToCartButtons();
	this.upgradeWidgets();
	this.upgradeCartTotals();
	this.upgradeExtraCheckoutProducts();

};

//Utility functions
kcart.prototype.getProfile = function(name)
{
	"use strict";
	if(this.profiles[name]){
		return this.profiles[name];}	
	
	console.log("getProfile could not find profile with name "+name);
	return false;
};

kcart.prototype.sendAjaxRequest = function(url,params,callback)
{
	"use strict";
	return this.lander.sendAjaxRequest(url,params,callback);	
};
kcart.prototype.ajaxCallMethod = function(method,params,cb1,cb2)
{
	"use strict";
	return this.lander.ajaxCallMethod(method,params,cb1,cb2);
};


/*
displayWidget
-replaces html content of cartWidget div with what is returned from ajax call
*/

kcart.prototype.displayWidget = function()
{
	"use strict";
	var widgets = this.widgets;
	var success = function(html)
	{
		console.log(widgets);
		for(var i in widgets){
			if(widgets[i]){
			widgets[i].innerHTML = html;}}
	};
	
	this.ajaxCallMethod("getWidget",null,success);
};

/*
displayCart
-replaces html content of kcart div with what is returned from ajax call
*/

kcart.prototype.displayCart = function()
{
	"use strict";
	if(this.cartDetail)
	{
		var kcart = this;
		var success = function(result)
		{
			this.cartDetail.innerHTML = result.body;
			kcart.upgradeCartDetail();
		};
		
		this.ajaxCallMethod("getShoppingCart",null,success);
		//window.setTimeout(function(){kcart.displayCart();},5000);
	}
};

/*
updateSession
-updates php session with updated lineItems
*/

kcart.prototype.updateSession = function(items)
{	
	items = items || this.getOrderItems();
	var params = {};
	params.cartItems = items;
	params.cartItems = JSON.stringify(params.cartItems);
	var kcart = this;
	this.ajaxCallMethod("updateCart",params,function(){kcart.displayWidget();});
}

kcart.prototype.upgradeCartTotals = function()
{
	var classes = ['kcartSubTotal','kcartShipTotal','kcartSalesTax','kcartDiscount','kcartInsurance','kcartGrandTotal'];
	for(i in classes){
		var name = classes[i];
		this.totalsNodes[name] = [];
		var nodes = document.getElementsByClassName(name);
		for(var n=0;n<nodes.length;n++)
			this.totalsNodes[name].push(nodes[n]);	
	}
}

kcart.prototype.upgradeCartDetail = function()
{
	if(!this.cartDetail)
		return;
	
	this.upgradeShopButtons();
	this.upgradeLineItems();
}

kcart.prototype.upgradeLineItems = function()
{
	//need mappings by productId for all the UI objects
	this.lineItems = {};
	
	//get the container div of the shopping cart
	var container = this.cartDetail;
	
	var nodes = container.getElementsByClassName("kcartItem");
	
	var kcart = this;
	for(var i=0;i<nodes.length;i++)
	{
		var node = nodes[i];
		var productId = parseInt(node.getAttribute('productId'));

		//map elements on this line by productId and assign onclick event listeners
		this.lineItems[productId] = {};
		var item = this.lineItems[productId];
		item.productId = productId;
		item.parentRow = node;
		
		//a useful function for this mini object
		this.lineItems[productId].getQty = function(){
			return parseInt(this.itemQty.innerHTML);
		};
		
		//upgrade minus button
		item.minusBtn = node.getElementsByClassName('kcartMinusBtn')[0];
		if(item.minusBtn)
		{
			item.minusBtn.lineItem = item;
			item.minusBtn.onclick = function(){kcart.minusItem(this.lineItem.productId)};
		}
		//upgrade plus button
		item.plusBtn = node.getElementsByClassName('kcartPlusBtn')[0];
		if(item.plusBtn)
		{
			item.plusBtn.lineItem = item;
			item.plusBtn.onclick = function(){kcart.plusItem(this.lineItem.productId)};
		}
		//upgrade remove button
		
		item.removeBtn = node.getElementsByClassName('kcartRemoveBtn')[0];
		if(item.removeBtn)
		{	
			item.removeBtn.lineItem = item;
			item.removeBtn.onclick = function(){kcart.removeItem(this.lineItem.productId)};
		}
		//upgrade itemQty span
		item.itemQty = node.getElementsByClassName('kcartItemQty')[0];
		item.itemQty.lineItem = item;
	}
	
	//now that everything is upgraded, we can pull the selected order items from the html
	this.getOrderItems();
	
	
}

kcart.prototype.getOrderItems = function()
{
	if(this.lander.pageType == 'checkoutPage')
	{

		this.orderItems = {};
		if(this.cartDetail)
		{
			for(var i in this.lineItems)
			{
				var item = this.lineItems[i];
				this.orderItems[i] = item.getQty();	
			}
		}
		else
		{
			
			var productId = this.getValue('productId');
			if(!productId || !this.products[productId])
				productId = this.defaultProduct;
	
	//		console.log(this.lander.selectedProduct);
			if(this.lander.selectedProduct)
				productId = this.lander.selectedProduct;
	
	
			this.orderItems[productId]=1;
			
			var nodes = document.getElementsByClassName('kformCheckoutUpsell');
			for(var i = 0;i<nodes.length;i++)
			{
				var node = nodes[i];
				productId = node.value;
				var qty = node.getAttribute('quantity') || '1';
				
				if(!this.lander.products[productId])
					console.log("skipping checkout upsell with productId: "+productId+". Product not found. It may be necessary to update config.php.");
				else if(node.checked)
					this.orderItems[productId] = qty;
			}
		}
	}
	return this.orderItems;
}

kcart.prototype.minusItem = function(productId)
{
	this.getOrderItems();
	var items = this.orderItems;
	if(items[productId])
	{
		if(items[productId] > 1)
			items[productId]--;
			
		if(this.cartDetail)
		{
			var itemQty = this.lineItems[productId].itemQty;
			itemQty.innerHTML = items[productId];
		}
	}
	this.updateSession(items);
	this.displayTotals();
}

kcart.prototype.plusItem = function(productId)
{
	var items = this.getOrderItems();
	if(items[productId])
	{
		items[productId]++;
		if(this.cartDetail)
		{
			var itemQty = this.lineItems[productId].itemQty;
			itemQty.innerHTML = items[productId];
		}
	}
	this.updateSession();
	this.displayTotals();
}
kcart.prototype.removeItem = function(productId)
{
	console.log(productId);
	var items = this.getOrderItems();
	if(items[productId])
	{
		if(this.cartDetail)
		{
			var node = this.lineItems[productId].parentRow;
			node.parentNode.removeChild(node);
			this.upgradeLineItems();
		}
	}
	this.updateSession();
	this.displayTotals();
}
kcart.prototype.upgradeAddToCartButtons = function()
{
	var kcart = this;
	
	//grab all nodes with matching class name
	var nodes = document.getElementsByClassName("kcartAddToCartButton");
	for(var i=0;i<nodes.length;i++)
	{
		var node = nodes[i];
		
		//set onclick event listener with the redirect action
		node.addEventListener('click',function()
		{
			var productId = parseInt(this.getAttribute('productId'));
			var qty = parseInt(this.getAttribute('quantity'));
			kcart.addToCart(productId,qty);
		});
		
	}
}

kcart.prototype.upgradeShopButtons = function()
{
	if(!this.cartDetail)
		return;
		
	//get the container div of the shopping cart
	var container = this.cartDetail;
	
	//grab all nodes with matching class name
	var nodes = container.getElementsByClassName("kcartShopButton");
	for(var i=0;i<nodes.length;i++)
	{
		var node = nodes[i];
		//set onclick event listener with the redirect action
		node.addEventListener('click',function()
		{
			var url = this.getAttribute('href');
			window.location = url;
		});
		
	}
}
kcart.prototype.upgradeWidgets = function()
{
	this.widgets = [];
	
	//grab all nodes with matching class name
	var nodes = document.getElementsByClassName("kcartWidget");
	for(var i=0;i<nodes.length;i++)
	{
		var node = nodes[i];
		this.widgets.push(node);
	}
}


kcart.prototype.addToCart = function(productId,qty)
{
	var originalProductId = productId;
	productId = parseInt(productId);
	qty = parseInt(qty);
	
	if(isNaN(qty))
		qty = 1;
	
	if(isNaN(productId))
	{
		console.log("could not add product, invalid productId: "+originalProductId);	
		return;
	}
	
	var items = this.getOrderItems();
	if(items[productId])
		items[productId] += qty;
	else
		items[productId] = qty;	
	
	this.updateSession();
	
}

kcart.prototype.getValue = function(name)
{
	if(this.lander.getValue(name))
		return this.lander.getValue(name)
	else if(this.lander.validator)
		return this.lander.validator.fetchFormValue(name);
	else if(this.sessionData[name])
		return this.sessionData[name];
	return false;
}

kcart.prototype.getShipAddress = function()
{
	var address1 = this.getValue('shipAddress1') || this.getValue('address1');
	var address2 = this.getValue('shipAddress2') || this.getValue('address2');
	var city = this.getValue('shipCity') || this.getValue('city');
	var state = this.getValue('shipState') || this.getValue('state');
	var postalCode = this.getValue('shipPostalCode') || this.getValue('postalCode');
	var country = this.getValue('shipCountry') || this.getValue('country');
	return {address1:address1,address2:address2,city:city,state:state,postalCode:postalCode,country:country};
}

kcart.prototype.getShipProfile = function()
{
	var profileId = this.getValue('shipProfileId');
	if(!profileId && this.defaultShipProfile)
		profileId = this.defaultShipProfile;
	if(profileId && this.profiles.shipping[profileId])
		return this.profiles.shipping[profileId];
};
kcart.prototype.getTaxRate = function()
{
	var shipAddress = this.getShipAddress();
	var country = shipAddress.country;
	var state = shipAddress.state;
	
	var tax_key = country+ '-' + state
	if(!this.profiles.taxes[tax_key])
		tax_key = country + '-*';
	
	return parseFloat(this.profiles.taxes[tax_key]) || 0;
};
//getting cardinal JTW auth for 3DS
kcart.prototype.getCardinalAuth = function(params)
{
	var deferred = new $.Deferred();
	
	
	
	var lander = this.lander;
    lander.setValue('JWTContainer','');
	lander.setValue('JWTReturn','');
	lander.setValue('CarSteps','');
	
    params = params || {};
	//params.orderId = lander.getValue('orderId');
    params.firstName = lander.getValue('firstName');
    params.lastName = lander.getValue('lastName');
    var shipAddress = lander.getValue('shipAddress1');
    var city = lander.getValue('shipCity');
    var postalCode = lander.getValue('shipPostalCode');
    var state = lander.getValue('shipState');
    var country = lander.getValue('shipCountry');
    if (shipAddress)
        params.shipAddress1 = shipAddress;
    else
        params.shipAddress1 = lander.getValue('address1');
    if (city)
        params.shipCity = city;
    else
        params.shipCity = lander.getValue('city');
    if (state)
        params.shipState = state;
    else
        params.shipState = lander.getValue('state');
    if (postalCode)
        params.shipPostalCode = postalCode;
    else
        params.shipPostalCode = lander.getValue('postalCode');
    if (country)
        params.shipCountry = country;
    else
        params.shipCountry = lander.getValue('country');
    params.method = 'importAuth';
	//params.action = 'AUTH';
    
	
    if(!params.shipAddress1 || !params.shipCity || !params.shipPostalCode || !params.shipState | !params.firstName)
        return;
	
	//do not set a redirect
    var loc = window.location.toString();
    params.errorRedirectsTo = loc.indexOf('?') > -1 ? loc.substr(0,loc.indexOf('?')) : loc;
    // we will have to check for upsell page later
    if(lander.pageType === 'checkoutPage')
    {
        params.orderItems = JSON.stringify(this.orderItems);
    }


    //lander.isProcessing = true;
	
	var success = function(result){
		lander.isProcessing = false;
        //lander.hideProgressBar();
		lander.setValue('JWTContainer',result);
		if(params.action === 'AUTH')
		{
			Cardinal.configure({
				logging: {
						level: "on"
				}
			});
			Cardinal.setup("init", {
				jwt: document.getElementById('JWTContainer').value
			});	
			Cardinal.on('payments.setupComplete', function(){
				console.log('setup completed');
			});
			Cardinal.start("cca", {
				OrderDetails: {
					OrderNumber: params.orderId,
					Amount: '2000',
					CurrencyCode: '840'
				},
				Consumer: {
					Account: {
					AccountNumber: params.cardNumber,
					ExpirationMonth: params.cardMonth,
					ExpirationYear: params.cardYear
				}

			}
			});
			Cardinal.on("payments.validated", function (data, jwt) {
				switch(data.ActionCode){
					case "SUCCESS":
					document.getElementById("JWTReturn").value = jwt;
					var step = 'STEP2';
					document.getElementById("CarSteps").value =  step;
					return 'SUCCESS';
					break;

					case "NOACTION":
					document.getElementById("JWTReturn").value = jwt;
					var step = 'STEP2';
					document.getElementById("CarSteps").value =  step;
					return 'SUCCESS';
					break;

					case "FAILURE":
					document.getElementById("JWTReturn").value = jwt;
					var step = 'STEP2';
					document.getElementById("CarSteps").value =  step;
					return 'SUCCESS';
					break;

					case "ERROR":
					document.getElementById("JWTReturn").value = jwt;
					var step = 'STEP2';
					document.getElementById("CarSteps").value =  step;
					return 'SUCCESS';
					break;
				}
			  });
		//Cardinal.off('payments.validated');
		}
		
      };
	var failure = function(result,code){
        lander.isProcessing = false;
        lander.hideProgressBar();
		kform.validator.triggerError(result); 
    };
    lander.displayProgressBar('Getting 3DS Perms...');
    lander.ajaxCallMethod(params.method,params,success,failure);
	
     	// SIMULATE SOME TIME TO PROCESS FUNCTION
    	setTimeout(function () {
        // This line is what resolves the deferred object
        // and it triggers the .done to execute
        	deferred.resolve('SUCCESS');
    	}, 8000) 
    	return deferred.promise();
	
};
//getting tax from tax service
kcart.prototype.getExternalTax = function(params)
{
    if(this.isProcessing)
    {
        this.validator.triggerError('paymentProcessing');
        return false;
    }
    if(!this.lander.taxServiceId)
    	return;

    var csymbol = this.currencySymbol;
	var lander = this.lander;
    params = params || {};
    params.firstName = lander.getValue('firstName');
    params.lastName = lander.getValue('lastName');
    var shipAddress = lander.getValue('shipAddress1');
    var city = lander.getValue('shipCity');
    var postalCode = lander.getValue('shipPostalCode');
    var state = lander.getValue('shipState');
    var country = lander.getValue('shipCountry');
    if (shipAddress)
         params.shipAddress1 = shipAddress;
    else
        params.shipAddress1 = lander.getValue('address1');
    if (city)
        params.shipCity = city;
    else
        params.shipCity = lander.getValue('city');
    if (state)
        params.shipState = state;
    else
        params.shipState = lander.getValue('state');
    if (postalCode)
        params.shipPostalCode = postalCode;
    else
        params.shipPostalCode = lander.getValue('postalCode');
    if (country)
        params.shipCountry = country;
    else
        params.shipCountry = lander.getValue('country');
    params.method = 'importTax';
    params.taxExemption = lander.getValue('taxExemption');

    if(!params.shipAddress1 || !params.shipCity || !params.shipPostalCode || !params.shipState | !params.firstName)
        return;
    //do not set a redirect
    var loc = window.location.toString();
    params.errorRedirectsTo = loc.indexOf('?') > -1 ? loc.substr(0,loc.indexOf('?')) : loc;
    // we will have to check for upsell page later
    if(lander.pageType === 'checkoutPage')
    {
        params.orderItems = JSON.stringify(this.orderItems);
    }

    lander.isProcessing = true;

    var success = function(result){
        lander.isProcessing = false;
        lander.hideProgressBar();
        cart = lander.cart;
        cart.grandTotal -= cart.salesTax;
        cart.salesTax = parseFloat(result);
        cart.grandTotal += cart.salesTax;
        for(var cls in cart.totalsNodes)
        {
            var name = cls.substr(5);
            name = name.substr(0,1).toLowerCase()+name.substr(1);
            var amount = cart[name];
            for(var i in cart.totalsNodes[cls])
            {
                var node = cart.totalsNodes[cls][i];
                node.innerHTML = cart.currency+amount.toFixed(2);
                if(name == 'discount' || name == 'insurance')
                {
                    if(node.parentNode.tagName == 'TR')
                        node.parentNode.style.display = amount > 0 ? 'table-row' : 'none';
                }
            }
        }
    };
    var failure = function(result,code){
        lander.isProcessing = false;
        lander.getTax = false;
        lander.hideProgressBar();
        kform.validator.triggerError(result);
    };
    lander.displayProgressBar('Calculating sales tax...');
    lander.ajaxCallMethod(params.method,params,success,failure);
    return false;

};
kcart.prototype.getInsurance = function()
{
	return this.getValue('insureShipment') ? parseFloat(this.lander.insureShipPrice) : 0; 
};
kcart.prototype.getMicroTime = function()
{
	return (new Date).getTime() / 1000;	
}
kcart.prototype.displayTotals = function()
{
	this.calculateTotals();
	for(var cls in this.totalsNodes)
	{
		var name = cls.substr(5);
		name = name.substr(0,1).toLowerCase()+name.substr(1);
		var amount = this[name];
		for(var i in this.totalsNodes[cls])
		{
			var node = this.totalsNodes[cls][i];
			node.innerHTML = this.currency+amount.toFixed(2);
			if(name == 'discount' || name == 'insurance')
			{
				if(node.parentNode.tagName == 'TR')
					node.parentNode.style.display = amount > 0 ? 'table-row' : 'none';
			}
		}
	}
}

kcart.prototype.calculateTotals = function()
{
	return;
	this.subTotal = 0.00;
	this.salesTax = 0.00;
	this.shipTotal = 0.00;
	this.grandTotal = 0.00;
	this.discount = 0.00;
	this.insurance = 0.00;

	//get items currently in cart
	var items = this.getOrderItems();
	
	//profiles that affect pricing
	var products = this.getProducts();
	var shipProfile = this.getShipProfile();
	var taxRate = this.getTaxRate();
	this.insurance = this.getInsurance();
	
	//total up price and shipping
	for(var i in items)
	{
		var qty = items[i];
		var prod = products[i];
		this.subTotal +=  parseFloat(prod.price) * qty;
		this.shipTotal += parseFloat(prod.shipPrice) * qty;
	}
	
	if(shipProfile)
	{
		if(shipProfile.applyEntireOrder && shipProfile.matchedShipPrice)
		{
			if(shipProfile.isUpcharge)
				this.shipTotal += parseFloat(shipProfile.matchedShipPrice);
			else
				this.shipTotal = parseFloat(shipProfile.matchedShipPrice);
		}
		
		var freeShipThreshold = parseFloat(shipProfile.freeShipThreshold);
		if(freeShipThreshold > 0 && this.subTotal >= freeShipThreshold)
			this.shipTotal = 0;
	}

	
	var taxable = this.subTotal;
	var couponCode = this.getValue('couponCode')? this.getValue('couponCode') : null;
	var campaignId = this.campaignId;
	var coupons = this.profiles.coupon;
	if(couponCode)
		var discounts = this.getDiscounts(couponCode,items,coupons);
	else
		var discounts = false;
	var couponsDiv = document.getElementById("orderCoupons");
	if(discounts)
	{
		//apply any coupons
		
		//Now we have the discount values. Time to do the math and apply the discounts
		var orderPriceTotal = this.subTotal;
		var orderShipTotal = this.shipTotal;
		var discountPrice = 0;
		
		//always apply product-level coupons first
		for(var campaignProductId in discounts.productDiscounts)
		{
			var disc = discounts.productDiscounts[campaignProductId];
			var inCart = items[campaignProductId];
			var itm = products[campaignProductId];
			if(inCart)
			{				
				var basePrice = parseFloat(itm.price);
				var baseShipping = parseFloat(itm.shipPrice);
				
				//first apply the flat discounts
				var priceDisc = basePrice <= disc.priceFlat ? basePrice : disc.priceFlat;
				var shipDisc = baseShipping <= disc.shipFlat ? baseShipping : disc.shipFlat;
			
				//now apply the percent discounts
				priceDisc += disc.pricePerc * (basePrice - priceDisc); 
				shipDisc += disc.shipPerc * (baseShipping - shipDisc);
			
				//re-assign the item's discount values
				this.discount += shipDisc + priceDisc;
				
				//Reduce taxable by price discount
				taxable -= priceDisc;
				
				//add to order totals to use with coupons that apply discounts to the entire order
				orderPriceTotal -= priceDisc;
				orderShipTotal -= shipDisc;
			}
		}	
		
		//now calculate the total order discounts
		disc = discounts.orderDiscounts;
		
		//first apply the flat discounts
		priceDisc = orderPriceTotal <= disc.priceFlat ? 0 : disc.priceFlat;
		shipDisc = orderShipTotal <= disc.shipFlat ? 0 : disc.shipFlat;
		
		//now apply the percent discounts
		priceDisc += disc.pricePerc * (orderPriceTotal - priceDisc); 
		shipDisc += disc.shipPerc * (orderShipTotal - shipDisc);
		
		this.discount += priceDisc + shipDisc;
		
		//Reduce taxable by price discount 
		taxable -= priceDisc;
		if(couponsDiv){
			couponsDiv.innerHTML = discounts.coupMessage;
			couponsDiv.style.display = "block";
		}
		this.lander.setValue('couponCode',discounts.couponCode);
	}
	else
	{
		if(couponsDiv)
			couponsDiv.innerHTML = '';
		
		this.lander.setValue('couponCode','');
	}
	
	//apply tax (s&h is not taxed)
    if(this.lander.autoTax)
        this.getExternalTax();
	else
		this.salesTax = taxRate * taxable;
	//sum grand total
	this.grandTotal = this.subTotal + this.shipTotal + this.salesTax - this.discount + this.insurance;
	return {subTotal:this.subTotal,shipTotal:this.shipTotal,salesTax:this.salesTax,discount:this.discount,insurance:this.insurance};
};


kcart.prototype.getDiscounts = function(couponCode,items,coupons)
{
	var ret = {couponCode:[],
				orderDiscounts:{priceFlat:0,pricePerc:0,shipFlat:0,shipPerc:0},
				productDiscounts:{},
				coupons:[],
				coupMessage:''
				};
	
	var cCodes = couponCode.trim().toUpperCase().split(',');

	var couponCount = 0;
	var obj,flatDiscount,percDiscount;
	for(var i in cCodes)
	{
		var code = cCodes[i];
		if(!coupons[code])
			continue;
		
		coupon = coupons[code];
		flatDiscount = coupon.couponDiscountPrice === null ? 0 : parseFloat(coupon.couponDiscountPrice);
		percDiscount = coupon.couponDiscountPerc === null ? 0 : parseFloat(coupon.couponDiscountPerc);
		if(coupon.couponMax && couponCount >= coupon.couponMax)
			break;
	
		if(!coupon.campaignProductId)
		{
			obj = ret.orderDiscounts;
		}
		else
		{
			if(!items[coupon.campaignProductId])
			{
				continue;
			}
			if(!ret.productDiscounts[coupon.campaignProductId])
				ret.productDiscounts[coupon.campaignProductId] = {priceFlat:0,pricePerc:0,shipFlat:0,shipPerc:0};
			obj = ret.productDiscounts[coupon.campaignProductId];
		}	
		
		ret.coupMessage += "<span style='color:green;font-weight:bold;'>" + code + "</span> ";
		
		isPerc = percDiscount > flatDiscount;
		var discount = flatDiscount + percDiscount;
		
		if(isPerc)
			discount = (parseFloat(discount) * 100).toString() + '%';
		var products = this.getProducts();	
		if(coupon.campaignProductId)
			var coupProd = products[coupon.campaignProductId].name;
		
		ret.coupMessage +=  discount + " off "+(coupon.applyTo == 'SHIPPING' ? 'shipping ' : '')+"on "+(coupon.campaignProductId ? coupProd : "Order") +"<br>";
		
		if(coupon.applyTo == 'BASE_PRICE')
		{
			obj.priceFlat += flatDiscount;
			obj.pricePerc += percDiscount;
		}
		else
		{
			obj.shipFlat += flatDiscount;
			obj.shipPerc += percDiscount;
		}
		
		//ensure percents do not add up more than 100
		if(obj.pricePerc > 100)
			obj.pricePerc = 100;
		if(obj.shipPerc > 100)
			obj.shipPerc = 100;
		
		
		ret.coupons.push(coupon);
		ret.couponCode.push(code);
		couponCount++;
	}
	if(couponCount == 0)
		return false;
	ret.couponCode = ret.couponCode.join();	
	return ret;
} 


//gets product pricing with ship profile calculations
//ship profiles have a lot of configurations so there's a bunch of logic to check here
kcart.prototype.getProducts = function()
{
	this.profileShipPrice = 0;
	
	var products = JSON.parse(JSON.stringify(this.products));
	var profile = this.getShipProfile();
	
	if(!profile)
		return products;
		
	profile.matchedShipPrice = null;
	
	var shipAddress = this.getShipAddress();
	var shipCountry = shipAddress.country;
	var shipState = shipAddress.state;
	var shipContinent = this.lander.config.continents[shipCountry];
	
	if(profile.applyEntireOrder){
		var matchRule = null;
		var maxRStrength = 0;
		var rStrength;
		
		for(var i in profile.rules){
			rStrength = 0;
			var rule = profile.rules[i];	
			if(rule.region){
				if(rule.region.substr(0,4) == 'REG_'){
					if(rule.region.substr(4,2) != shipContinent)
						continue;
					rStrength = 1;
				}else{
					if(rule.region == shipCountry){
						rStrength = 2;
						if(rule.state){
							if(rule.state == shipState)
								rStrength = 3;
							else
								continue;
						}
					}else
						continue;	
				}
			}
			if(rStrength >= maxRStrength){
				maxRStrength = rStrength;
				matchRule = rule;
			}
			else
				continue;
			
		}
		if(matchRule)
			profile.matchedShipPrice = matchRule.shipPrice;
	}else{
		for(var i in products){
			var prod = products[i];
			var productId = prod.productId;
			
			var matchRule = null;
			var maxPStrength = 0;
			var maxRStrength = 0;
			for(var i in profile.rules){
				var rule = profile.rules[i];	
				var pStrength = 0;
				var rStrength = 0;
				if(rule.region){
					if(rule.region.substr(0,4) == 'REG_'){
						if(rule.region.substr(4,2) != shipContinent)
							continue;
						rStrength = 1;
					}else{			
						if(rule.region == shipCountry){
							rStrength = 2;
							if(rule.state)
							{
								if(rule.state == shipState)
									rStrength = 3;
								else
									continue;
							}
						}else
							continue;
					}				
				}
				if(rStrength >= maxRStrength)
					maxRStrength = rStrength;	
				else
					continue;
				if(rule.productTypeSelect == 'SINGLE' && productId == rule.campaignProductId)
					pStrength = 5;
				else if(rule.productTypeSelect == 'CATEGORY' && prod.productCategoryId == rule.productCategoryId)
					pStrength = 4;
				else if(rule.productTypeSelect == 'OFFERS' && prod.productType == 'OFFER')
					pStrength = 3;
				else if(rule.productTypeSelect == 'UPSELLS' && prod.productType == 'UPSALE')
					pStrength = 2;
				else if(rule.productTypeSelect == 'PRODUCTS')
					pStrength = 1;
					
				if(pStrength >= maxPStrength){
					matchRule = rule;
					maxPStrength = pStrength;
				}
			}
			var shipPrice = parseFloat(products[productId].shipPrice);
			if(matchRule){
				var profileShipPrice = parseFloat(matchRule.shipPrice);
				if(profile.isUpcharge)
					shipPrice += profileShipPrice;
				else
					shipPrice = profileShipPrice;
				prod.shipPrice = shipPrice;
			}
		}
	}
	
	//shipProfiles that only bill the highest ship price item
	if(profile.highestShipPriceOnly){
		var maxShipPrid = null;
		var maxShipPrice = 0;		
		for(var i in products){
			var prod = products[i];
			var shipPrice = parseFloat(prod.shipPrice);
			if(shipPrice > maxShipPrice){
				maxShipPrid = i;
				maxShipPrice = shipPrice;
			}
		}
		for(var i in products){
			if(i != maxShipPrid)
				products[i].shipPrice = 0;	
		}
	}
	
	
	return products;
}

//for multi-product landers -- selects a product
kcart.prototype.selectProduct = function(productId)
{
	if(kform.selectedProduct)
	{		
		var curVal = kform.selectedProduct;
		if(curVal == productId)
			return;
		if(kform.productSelectNodes)
		{
			var node = kform.productSelectNodes[curVal];
			if(node)
			{
				node.className = node.className.replace(/kform_selectedProduct/,"");
				node.parentBox.className = node.parentBox.className.replace(/kform_selectedProduct/,"");
			}
		}
	}
	
	if(kform.productSelectNodes)
	{		
		var node;		
		if(node = kform.productSelectNodes[productId])
		{
	
			kform.selectedProduct = productId;
			node.className += " kform_selectedProduct";
			node.parentBox.className += " kform_selectedProduct";
			
		}
	}
	kform.storeValue('selectedProduct',productId);
	
	if(typeof kform_userSelectProduct == 'function')
		kform_userSelectProduct(productId);
		
	this.displayTotals();
};


//used by multi-product landers
kcart.prototype.upgradeProductSelector = function()
{
	var nodes = document.getElementsByClassName('kform_productSelect');
	var len = nodes.length;

	if(len == 0)
		return;
	
	this.productSelectNodes = {};
	var defaultProductIsOption = false;
	for(var i = 0;i<len;i++)
	{
		var node = nodes[i];
		
		var productId = node.getAttribute('productId');
		if(!productId)
			alert("kformError: productSelect button must have the productId attribute");
		node.productId = productId;
		
		var parent = node;
		var foundParent = false;
		while(parent = parent.parentNode)
		{
			if(typeof parent != 'object')
				break;
			
			if(typeof parent.className == 'undefined')
				continue;
			
			if(parent.className.indexOf("kform_productBox") > -1)
			{
				node.parentBox = parent;
				foundParent = true;
			}
		}
		
		if(!foundParent)
			alert("kformError: productSelect button must be a child of an element with the kform_productBox className");
		
		this.productSelectNodes[productId] = node;
		
		if(!this.products[productId])
			alert("kformError: productSelect button has a productId value of "+productId+" but that productId does not exist in this campaign");
		
		
		if(!this.hasInput('productSelector'))
		{
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'productSelector';
			this.node.appendChild(input);
			this.inputs['productSelector'] = input;
		}
		

		if(productId == this.defaultProduct)
			defaultProductIsOption = true;

		node.addEventListener('click',function()
		{
			kform.selectProduct(this.productId);
		});
	}
	
	if(!defaultProductIsOption)
		alert("kformError: default productId is "+this.defaultProduct+" but this option does not exist in the productSelector");
	
	var curProd = this.fetchValue('selectedProduct');
	if(!curProd)
		curProd = this.defaultProduct;
	
	this.selectProduct(curProd);
	
};

//upgrades checkboxes used for additional upsells
kcart.prototype.upgradeExtraCheckoutProducts = function()
{
	var nodes = document.getElementsByClassName('kformCheckoutUpsell');
	var len = nodes.length;
	
	if(len == 0)
		return;
	
	var cart = this;
	
	for(var i = 0;i<len;i++)
	{
		var node = nodes[i];
		if(node.type == 'checkbox')
		{
			var upsellId = node.value;
			if(!this.products[upsellId])
				alert("kformError: upsell checkbox has an value of "+upsellId+" but that productId does not exist in this campaign");
			
			node.addEventListener('click',function(){cart.displayTotals()});
		}
	}
}