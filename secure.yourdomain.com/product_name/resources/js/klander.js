klander = function()
{
	this.constructed = false;
	this.validator = null;
	this.taxes = {};
	this.coupons = {};
	this.shipProfiles = {};
	this.products = {};
	this.pageType = null;
	this.resourceDir = '/resources/';
	this.isShoppingCart = false;
	this.disableBack = false;
	this.redirectsTo = null;
	this.orderItems = {}; 
	this.defaultProduct = null;
	this.defaultShipProfile = null;
	
	
	//sets values and handles dom upgrades
	this.construct = function(type)
	{
		this.pageType = type;
		
		if(type.substr(0,10) === 'upsellPage'){
			type = 'upsellPage';}
		
		this.triggerClick();
		
		var data = this.config;
		
		if(data.countries){			this.countries = data.countries;}
		if(data.states){			this.states = data.states;}
		if(data.countries){			this.countries = data.countries;}
		if(data.coupons){			this.coupons = data.coupons;}
		if(data.shipProfiles){		this.shipProfiles = data.shipProfiles;}
		if(data.taxes){				this.taxes = data.taxes;}
		if(data.taxServiceId){		this.taxServiceId = data.taxServiceId;}
		if(data.autoTax){			this.autoTax = data.autoTax;}
		if(data.currencySymbol){	this.currencySymbol = data.currencySymbol;}
		if(data.insureShipPrice){	this.insureShipPrice = data.insureShipPrice;}
		if(data.offers){			this.products = data.offers;}
		if(data.upsells){			for(var i in data.upsells){if(data.upsells[i]){this.products[i] = data.upsells[i];}}}
		if(data.defaultShipProfile)	{ this.defaultShipProfile = data.defaultShipProfile;}
		if(data.prepaidRedirectUrl) { this.prepaidRedirectUrl = data.prepaidRedirectUrl;}
		
		this.cart = new kcart();
		this.cart.construct(this);
		
		if(type === 'presellPage'){}
		else if(type === 'leadPage'){this.upgradeLeadPage();}
		else if(type === 'checkoutPage'){this.upgradeCheckoutPage();}
		else if(type === 'upsellPage'){this.upgradeUpsellPage();}
		else if(type === 'thankyouPage'){this.upgradeThankyouPage();}
		else if(type === 'profilePage'){this.upgradeAccountPage();}

		var lander = this;
		if(document.getElementById('kformSignup'))
		{		
			var vald = new kvalidator('kformSignup');
			vald.onSubmit = function(args){
				var t1 = function(r){window.location = window.location;};
				var f0 = function(r){vald.triggerError(r);};
				lander.ajaxCallMethod('customerLogin',args,t1,f0);
			};
		}
		
		var node = document.getElementById('kcartLogout');
		if(node){
			node.addEventListener('click',function(){lander.accountLogout();});}
		
		if(this.disableBack){
			this.disableBackButton();}
		
		
		this.constructed = true;
		
		
		
	};

	/*XXXXXXXXXXXXXXXXXXXXXXXX
	/* -ajaxSubmit
	/* Submits forms via ajax
	/*XXXXXXXXXXXXXXXXXXXXXXXX */
	this.ajaxSubmit = function(params)
	{
		if(this.pageType === 'checkoutPage' && this.config.webPages[this.pageType].cardinalAuth == 1) {
            var CardinalAuth = document.getElementById('cardinalAuth').value;
            if (CardinalAuth === '1') {

                var CarSteps = document.getElementById('CarSteps').value;

                if (CarSteps === 'STEP1') {
                    params.action = 'AUTH';
                    $.when(kform.cart.getCardinalAuth(params)).done(function () {
                        kform.ajaxSubmit(params);
                    });
                    return;
                }
                else if (CarSteps === 'STEP2') {
                    params.action = 'RECORD';
                    this.displayProgressBar();
                    var jwt = document.getElementById("JWTReturn").value;
                    params.jwt = jwt;
                    $.when(kform.cart.getCardinalAuth(params)).done(function () {
                        document.getElementById('CarSteps').value = 'DONE';
                        kform.ajaxSubmit(params);
                    });
                    return;
                }
            }
        }
		
		
		params = params || {};
		
		if(this.isProcessing){
			this.validator.triggerError('paymentProcessing');
			return false;}
		
		params.method = this.method;
		params.redirectsTo = this.redirectsTo;
		var loc = window.location.toString();
		params.errorRedirectsTo = loc.indexOf('?') > -1 ? loc.substr(0,loc.indexOf('?')) : loc;
		
		if(this.pageType === 'accountPage'){
			params.update = 'ACCOUNT';}
		
		if(this.pageType === 'checkoutPage')
		{
			var paySource = this.getValue('paySource');
	
			if(params.paySourceId && this.customer.paySources[params.paySourceId]){
				params.paySource =  this.customer.paySources[params.paySourceId].paySourceType;
			}else if(params.newPaymentType === true){
				params.paySourceId = null;
			}else{
				params.paySourceId = null;}
			
			if(paySource === 'CREDITCARD')
			{
				
				if(params.paySourceId === null)
				{
					var m = params.cardMonth;
					var y = params.cardYear;
					
					if(!m || !y){
						return this.validator.triggerError("cardExpired");}
						
					var date = new Date(y,m,0);
					var curDate = new Date();	
					
					if(date.getTime() < curDate.getTime()){
						return this.validator.triggerError("cardExpired");}
				}
				else
				{
					params.cardNumber = null;
					params.cardSecurityCode = null;
					params.cardMonth = null;
					params.cardYear = null;
				}
				
				params.achAccountHolderType = null;
				params.achAccountType = null;
				params.achAccountNumber = null;
                params.achRoutingNumber = null;
                params.achNameOnCheck = null;
                params.iban = null;
                params.ddbic = null;
                params.accountHolder = null;
            }
			else if(paySource === 'AMAZON')
			{
				params.paySource = 'AMAZON';
				params.amazonOrderId = this.fetchValue('amazonOrderId');	
				params.amazonBillerId = this.config.amazonPayments.amazonBillerId;
				params.amazonAddressConsent = this.fetchValue('amazonAccessToken');
			}
			else if(paySource === 'PAYPAL')
			{
				params.paySource = 'PAYPAL';
				var couponCode = this.getValue('couponCode');
				if(couponCode){
					params.couponCode = couponCode;
				}
			}
			else if(paySource === 'CHECK')
			{
				params.cardNumber = null;
				params.cardSecurityCode = null;
				params.cardMonth = null;
				params.cardYear = null;
                params.iban = null;
                params.ddbic = null;
                params.accountHolder = null;
			}
            else if(paySource === 'DIRECTDEBIT')
            {
                params.cardNumber = null;
                params.cardSecurityCode = null;
                params.cardMonth = null;
                params.cardYear = null;
                params.achAccountHolderType = null;
                params.achAccountType = null;
                params.achAccountNumber = null;
                params.achRoutingNumber = null;
                params.achNameOnCheck = null;
            }
            else if(paySource === 'COD')
			{
                params.cardNumber = null;
                params.cardSecurityCode = null;
                params.cardMonth = null;
                params.cardYear = null;
                params.achAccountHolderType = null;
                params.achAccountType = null;
                params.achAccountNumber = null;
                params.achRoutingNumber = null;
                params.achNameOnCheck = null;
                params.iban = null;
                params.ddbic = null;
                params.accountHolder = null;
			}

			//params.orderItems = JSON.stringify(this.cart.orderItems);
		}
		else if(this.pageType.substr(0,10) === 'upsellPage')
		{
			params.productId = this.getValue('productId') || this.upsellId;	
			params.replaceProductId = this.getValue('replaceProductId') || this.replaceProductId;
		}

		this.isProcessing = true;
		var lander = this;
		
		var success = function(result){
			lander.isProcessing = false;
			var redirectUrl = lander.redirectsTo;
			if(result.paypalUrl){
				redirectUrl = result.paypalUrl;}
			window.onbeforeunload = null;
			var redirect = true;
			if(typeof kform_formSubmitCallback == 'function')
				redirect = kform_formSubmitCallback(result,true) !== false;
			if(redirect)
				window.location = redirectUrl;
		};
		var failure = function(result,code){
			lander.isProcessing = false;
			if(code != '302'){
				lander.hideProgressBar();}
			if(code == '302'){
				if(result.url){
					window.location = url;}
				else if(result.script){
					eval(result.script);}	
			}else if(code == '400' && lander.prepaidRedirectUrl) {
				window.location = kform.prepaidRedirectUrl+'?sessionId='+lander.sessionId;
			}else{
				kform.validator.triggerError(result);
			}
		};
		
		this.ajaxCallMethod(this.method,params,success,failure);
		
		this.displayProgressBar();
		return false;
	};
	
	
	
	/*XXXXXXXXXXXXXXXXXXXXXXXX
	/*UPGRADE FUNCTIONS
	/*-functions that upgrade elements on the page
	/*XXXXXXXXXXXXXXXXXXXXXXXX
	*/
	
	this.loadAccountEdit = function()
	{
		var lander = this;
		
		this.ajaxCallMethod('getAccountEditForm',null,function(html)
		{
			var node = document.getElementById('kprofileAccountInfoWrap');
			if(node){
				node.innerHTML = html;
				
				lander.validator = new kvalidator('kprofileAccountForm','kprofile');
				lander.validator.onSubmit = function(params){lander.ajaxSubmit(params);};
				//load countries dynamically
				lander.loadCountries('country');
				lander.loadCountries('shipCountry');
				lander.loadStates('state');
				lander.loadStates('shipState');
				
				node = document.getElementById('kprofileCancelEdit');
				if(node)
				{
					node.addEventListener('click',function()
					{
						lander.loadAccountInfo();
					});
				}
				
				if(lander.hasInput('billShipSame')){
					lander.hiddenAddressDiv = document.getElementById('kprofileFormHiddenAddress');
					lander.getInput('billShipSame').addEventListener('click',function(){lander.toggleHiddenAddress();});
				}
				
				for(var key in lander.customer){
					if(lander.customer.hasOwnProperty(key)){
						var val = lander.customer[key];
						if(lander.hasInput(key)){
							lander.setValue(key,val);}
					}
				}
				
				if(lander.customer.billShipSame)
				{
					console.log('clearing ship fields');
					lander.setValue('shipAddress1','');	
					lander.setValue('shipAddress2','');	
					lander.setValue('shipCity','');	
					lander.setValue('shipState','');	
					lander.setValue('shipCountry','');	
					lander.setValue('shipPostalCode','');						
				}
				
				
				lander.toggleHiddenAddress();
			}		
		});
		
	};
	this.loadAccountInfo = function()
	{
		var lander = this;
		
		this.ajaxCallMethod('getAccountInfo',null,function(html)
		{
			var node = document.getElementById('kprofileAccountInfoWrap');
			if(node)
			{
				node.innerHTML = html;		
				lander.upgradeAccountPage();
			}		
		});
	};
	
	//accountPage
	this.upgradeAccountPage = function()
	{
		this.method = 'customerEdit';
		var lander = this;
		var node = document.getElementById('kprofileEditAccountInfo');
		if(node)
		{
			node.addEventListener('click',function()
			{
				lander.loadAccountEdit();
			});
		}
		
		this.shipmentDetailsNodes = {};
		
		var nodes = document.getElementsByClassName('kprofileShipmentDetails');
		for(var i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){
				continue;}
				
			var orderId = node.getAttribute('orderId');
			this.shipmentDetailsNodes[orderId] = node;
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofileCancelPurchase');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){
				continue;}
			
			node.addEventListener('click',function()
			{
				lander.cancelPurchase(this.getAttribute('purchaseId'));
			});
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofileHoldPurchase');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){
				continue;}
			
			node.addEventListener('click',function()
			{
				lander.holdPurchase(this.getAttribute('purchaseId'));
			});
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofileRestartPurchase');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){continue;}
			
			node.addEventListener('click',function()
			{
				lander.restartPurchase(this.getAttribute('purchaseId'));
			});
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofilePaySourceEdit');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){continue;}
			
			node.addEventListener('click',function()
			{
				lander.editPaySource(this.getAttribute('paySourceId'),this.getAttribute('paySourceAbbr'));
			});
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofileReorderLink');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){continue;}
			
			node.addEventListener('click',function()
			{
				lander.reorder(this.getAttribute('orderItems'));
			});
			node.isUpgraded = true;
		}
		
		nodes = document.getElementsByClassName('kprofileCancelOrder');
		for(i=0;i<nodes.length;i++)
		{
			node = nodes[i];
			if(node.isUpgraded){continue;}
			
			node.addEventListener('click',function()
			{
				lander.cancelOrder(this.getAttribute('orderId'));
			});
			node.isUpgraded = true;
		}
		
	};
	

	
	
	//presellPages
	this.upgradePresellPage = function(sessionId)
	{
		var nodes = document.getElementsByClassName('kform_presellLink');
		for(var i = 0;i<nodes.length;i++)
		{
			var arg = 'sessionId='+sessionId;
			if(!nodes[i].href){
				nodes[i].href = this.redirectsTo+'?'+arg;}
			else{
				nodes[i].href += (nodes[i].href.indexOf('?') > -1 ? '&' : '?') + arg;}
		}
		var node = document.getElementById('kcartSigninButton');
		if(node)
			node.addEventListener('click',function(){lander.displaySigninForm();});
	};
	
	//leadPages
	this.upgradeLeadPage = function()
	{
		this.method = 'importLead';
		var lander = this;
		
		var input,i;
		
		this.validator = new kvalidator('kform','kcart');
		this.validator.addSubmitButton(document.getElementById('kformSubmit'));
		this.validator.onSubmit = function(params){lander.ajaxSubmit(params);};
		this.validator.lander = this;
		
		//load countries dynamically
		this.loadCountries('country');
		this.loadCountries('shipCountry');
		this.loadStates('state');
		this.loadStates('shipState');
		
			if(this.hasInput('billShipSame')){
			this.hiddenAddressDiv = document.getElementById('kform_hiddenAddress');}
		
		this.toggleHiddenAddress();
		
		var node = document.getElementById('kcartSigninButton');
		if(node){
			node.addEventListener('click',function(){lander.displaySigninForm();});}
		
	};
	
	//checkoutPage
	this.upgradeCheckoutPage = function()
	{
		this.method = 'importOrder';
		var lander = this;
		var input,i;
		
		this.validator = new kvalidator('kform','kcart');
		this.validator.addSubmitButton(document.getElementById('kformSubmit'));
		this.validator.onSubmit = function(params){lander.ajaxSubmit(params);};
		this.validator.lander = this;
		
		//load countries dynamically
		this.loadCountries('country');
		this.loadCountries('shipCountry');
		this.loadStates('state');
		this.loadStates('shipState');
		
		
		var cart = this.cart;
        if(this.requireSig)
        {
            if(this.config.webPages[this.pageType].sigType == 1)
            {
                this.renderTypedSig();
                //adds renderTypedSig function to name inputs
                var nameFields = ['firstName','lastName','shipFirstName','shipLastName'];
                for(var i in nameFields){
                    if(this.hasInput(nameFields[i]))
                        this.getInput(nameFields[i]).addEventListener('change',function(){kform.renderTypedSig();});
                }
            }
            else
            {
                //signature pad for drawn signature
                var canvas = document.getElementById("kform_sigPad");
                this.signaturePad = new SignaturePad(canvas, {
                    onEnd:function(){
                        kform.setValue('signature', this.toDataURL());
                    },
                    penColor: "#06093B"
                });
                this.addClearSigButton();
            }
        }

        if(this.taxServiceId)
		{
            if(this.autoTax)
            {
                var addressFields = ['firstName','lastName','shipFirstName','shipLastName','address1','shipAddress1','city','shipCity','state','shipState','country','shipCountry','postalCode','shipPostalCode'];
                for(var i in addressFields){
                    if(this.hasInput(addressFields[i]))
                        this.getInput(addressFields[i]).addEventListener('change',function(){cart.getExternalTax();})
                }
            }
            else
            	this.addTaxButton();
		}


		if(this.hasInput('couponCode')){
			this.getInput('couponCode').addEventListener('change',function(){cart.displayTotals();});}
		
		var noSaveFields = ['cardNumber','cardSecurityCode','achAccountNumber'];
		for(i in noSaveFields){
			if(noSaveFields.hasOwnProperty(i)){
				input = this.getInput(noSaveFields[i]);
				if(input){
					input.noSaveFormValue = true;
				}
			}
		}
		
		if(this.hasInput('billShipSame')){
			this.hiddenAddressDiv = document.getElementById('kform_hiddenAddress');
			this.getInput('billShipSame').addEventListener('click',function(){lander.toggleHiddenAddress();});
			this.toggleHiddenAddress();
		}
		
		input = this.getInput('shipProfileId');
		if(input){
			if(input.tagName === 'SELECT'){
				for(i in this.shipProfiles){
					if(this.shipProfiles.hasOwnProperty(i)){
					var opt = document.createElement('OPTION');
					opt.value = i;
					opt.innerHTML = this.shipProfiles[i].profileName;
					input.appendChild(opt);
					}
				}
				this.setValue('shipProfileId',this.fetchValue('shipProfileId'));
				input.addEventListener('change',function(){cart.displayTotals();});
			}
			else if(input.type == 'radio')
			{
				input = this.validator.getInput('shipProfileId');
				if(input)
				{
					for(var n=0;n<input.radios.length;n++)
					{
						var radio = input.radios[n];
						radio.addEventListener('click',function(){cart.displayTotals();});	
					}	
				}
			}
		}
		
		this.cart.displayTotals();
		
		var node = document.getElementById('kcartSigninButton');
		if(node){
			node.addEventListener('click',function(){lander.displaySigninForm();});}
		
		//fix paypalBtn so that it changes the paySource properly before submitting the form
		var paypalBtn = document.getElementById('kform_payPalButton');
		if(paypalBtn){
			this.paypalBtn = paypalBtn;
			paypalBtn.onclick = function(){
				var paySource = lander.getValue('paySource');
				lander.setValue('paySource','PAYPAL');
				lander.ajaxSubmit();
				lander.setValue('paySource', paySource);	// Reset the paySource incase paypal decline or they decide to pay with another method
			};
		}
			
		input = this.validator.getInput('paySource');
		if(input)
		{
			var toggle = function(){lander.togglePaySource();};
			if(input.type === 'radio'){
				for(i in input.radios){
					if(input.radios.hasOwnProperty(i)){
					input.radios[i].addEventListener('click',toggle);}
				}
			}
			else if(input.type == 'select-one')
			{
				input.node.addEventListener('change',toggle);	
			}
		}
		
		this.loadProductSelector();
		
		if(this.customer)
		{
			//set fields that are empty
			var fields = ['firstName','lastName','address1','address2','city','state','country','postalCode','emailAddress','phoneNumber','shipAddress1','shipAddress2','shipCity','shipState','shipCountry','shipPostalCode'];
			for(var i in fields)
			{
				var name = fields[i];
				if(!this.getValue(name) && this.customer[name])
					this.setValue(name,this.customer[name]);
			}

			//load paysources
			
			var ps = this.fetchValue('paySource');
			var pid = this.fetchValue('paySourceId');
			
			this.loadPaySources();
			
			if(this.customer.primaryPaySourceId && pid === null)
			{
				this.setValue('newPaymentType',false);
				this.setValue('paySourceId',this.customer.primaryPaySourceId);
			}
			else if(this.getValue('newPaymentType'))
			{
				this.setValue('paySourceId','');	
			}
			else if(pid !== null)
			{
				this.setValue('newPaymentType',false);
				this.setValue('paySourceId',pid);
			}
		}
		else
		{
			if(this.signInEmail)
				this.displaySigninForm(this.signInEmail);
		}
		
		this.togglePaySource();
	};
	
	//upsellPage
	this.upgradeUpsellPage = function()
	{
		var lander = this;
		this.method = 'importUpsale';
		
		this.validator = new kvalidator('kform','kcart');
		this.validator.addSubmitButton(document.getElementById('kformSubmit'));
		this.validator.onSubmit = function(params){lander.ajaxSubmit(params);};
		
		var noSaveFields = ['cardNumber','cardSecurityCode','achAccountNumber'];
		for(var i in noSaveFields)
		{
			var input = this.getInput(noSaveFields[i]);	
			if(input)
				input.noSaveFormValue = true;
		}
		
		if(this.config.webPages[this.pageType].createAccountDialog == 1)
		{
			if(!this.customer)
				this.displayCreateAccount();
		}

		//fix paypalBtn so that it changes the paySource properly before submitting the form
		var paypalBtn = document.getElementById('kform_payPalButton');
		if(paypalBtn){
			this.paypalBtn = paypalBtn;
			paypalBtn.onclick = function(){
				lander.setValue('paySource','PAYPAL');
				lander.ajaxSubmit();
			};
		}
		
	};
	
	//thankyouPage
	this.upgradeThankyouPage = function()
	{
		var node = document.getElementById('kthanks_reorderLink');
		if(this.reorderURL){
			if(node){
				var lander = this;
				node.addEventListener('click',function(){
					lander.ajaxCallMethod('closeSession',null,function(){
						window.location = lander.reorderURL;
					});
				});
			}
		}
		else if(node)
			node.style.display = 'none';
	
		if(this.config.webPages[this.pageType].createAccountDialog == 1)
		{
			if(!this.customer)
				this.displayCreateAccount();
		}
	};
	
	//load payment options for customers who are logged into the page
	this.loadPaySources = function()
	{
		var lander = this;
		var paySources = this.customer.paySources;

		this.ccards = {};
		this.bankAccts = {};
		this.directDebitAccts = {};
		var hasCard, hasAch, hasDD = false;
		
		var node = document.getElementById('kformPaySourceWrap');
		node.parentNode.style.display = 'block';
		for(var i in paySources)
		{
			var paySource = paySources[i];
			if(paySource.paySourceType == 'CREDITCARD'){
				this.ccards[paySource.paySourceId] = paySource;
				hasCard = true;}
			else if(paySource.paySourceType == 'CHECK'){
				this.bankAccts[paySource.paySourceId] = paySource;	
				hasAch = true;
			}
            else if(paySource.paySourceType == 'DIRECTDEBIT'){
                this.directDebitAccts[paySource.paySourceId] = paySource;
                hasDD = true;
            }
		}
		
		if(hasCard){
			for(var i in this.ccards){
				if(this.ccards.hasOwnProperty(i)){
					var name = this.ccards[i].cardType.substr(0,1)+this.ccards[i].cardType.substr(1).toLowerCase();
					var html = "<div class='kformPaySourceTile'><input type='radio' name='paySourceId' value='"+i+"'><span>"+name+'<br>'+'************'+this.ccards[i].cardLast4+"</span></div>";
					node.innerHTML += html;
				};
			}
		}
		if(hasAch){
			for(var i in this.bankAccts){
				if(this.bankAccts.hasOwnProperty(i)){
					var name = this.bankAccts[i].achAccountType.substr(0,1)+this.bankAccts[i].achAccountType.substr(1).toLowerCase();
					var html = "<div class='kformPaySourceTile'><input type='radio' name='paySourceId' value='"+i+"'><span>"+this.bankAccts[i].achBankName+'<br>'+name+'<br>**********'+this.bankAccts[i].achLast4+"</span></div>";
					node.innerHTML += html;
				};
			}
		}
        if(hasDD){
            for(var i in this.directDebitAccts){
                if(this.directDebitAccts.hasOwnProperty(i)){
                    var name = 'Iban';
                    var html = "<div class='kformPaySourceTile'><input type='radio' name='paySourceId' value='"+i+"'><span>"+name+'<br>'+this.directDebitAccts[i].ibanFirst2+'**********'+this.directDebitAccts[i].ibanLast4+"</span></div>";
                    node.innerHTML += html;
                };
            }
        }
		
		if(hasCard || hasAch || hasDD)
		{
			this.validator.buildInputs();
			node = this.validator.getInput('paySourceId');
			if(node){
				for(var i in node.radios){
					if(node.radios.hasOwnProperty(i)){
						node.radios[i].addEventListener('click',function(){
							lander.setValue('newPaymentType',false);
							lander.togglePaySource();
						});
			}}}
			
			
			
			node = document.getElementById('kformNewPaymentType');
			var input = this.getInput('newPaymentType');
			input.addEventListener('click',function(){
				if(this.checked)
					lander.setValue('paySourceId','');
	
				lander.togglePaySource();
			});
			
		}
	};
	
	this.togglePaySource = function()
	{
		var paySource = this.getInput('paySource');
		var ps = this.getValue('paySource');
		var newpay = this.getValue('newPaymentType');
		
		if(this.customer)
		{
			if(newpay)
			{
				
				this.storeValue('newPaymentType',true);	
				paySource.style.display = 'block';
			}
			else
			{
				this.storeValue('newPaymentType',false);	
				paySource.style.display = 'none';
			}
		}

		var cardDiv = document.getElementById('kform_paySourceCard');
		var achDiv = document.getElementById('kform_paySourceCheck');
		var directDiv = document.getElementById('kform_paySourceDirectDebit');
		
		
		if(cardDiv)
		{
			cardDiv.style.display = 'none';
			if(!(this.customer && !newpay))
			{
				if(ps == 'CREDITCARD')
					cardDiv.style.display = 'block';
			}
		}
		
		if(achDiv)
		{
			achDiv.style.display = 'none';
			if(!(this.customer && !newpay))
			{
				if(ps == 'CHECK')
					achDiv.style.display = 'block';
			}
		}

        if(directDiv)
        {
            directDiv.style.display = 'none';
            if(!(this.customer && !newpay))
            {
                if(ps == 'DIRECTDEBIT')
                    directDiv.style.display = 'block';
            }
        }
		
	}
	
	//populates the countries select box
	this.loadCountries = function(countryName)
	{
		var input = this.getInput(countryName);
		if(!input)return;
		var defaultVal = this.fetchValue(countryName) || false;
		if(input && input.type == 'select-one'){
			for(var code in this.countries)
			{
				var name = this.countries[code];
				if(!defaultVal)
					defaultVal = code;
				var opt = document.createElement('OPTION');
				opt.value = code;
				opt.innerHTML = name;
				input.add(opt);
			}
			this.setValue(countryName,defaultVal);
			this.storeValue(countryName,defaultVal);
			var stateName = countryName == 'country' ? 'state' : 'shipState';
			var lander = this;
			var cart = this.cart;
			input.addEventListener('change',function(){lander.loadStates(stateName);cart.displayTotals()});	
		}
	}
	
	//populates the state select box
	this.loadStates = function(stateKey)
	{
		var input = this.getInput(stateKey);
		if(!input || input.tagName != 'SELECT')
			return false;
			
		if(!input)return;
		var countryKey = stateKey == 'state' ? 'country' : 'shipCountry';	
		var countryCode = this.getValue(countryKey);
		var val = this.fetchValue(stateKey) || '';
		var states = this.states[countryCode];
		var hasStates = typeof states == 'undefined' || states.length == 0 ? false : true;
		if(!hasStates){
			input.innerHTML = '<option value=" ">Select State</option>';
			input.style.display = 'none';
		}else{
			input.innerHTML = '<option value="">Select State</option>';	
			input.style.display = 'inline-block';
			for(var code in states){
				var opt = document.createElement('option');
				opt.value = code;
				opt.innerHTML = states[code];
				input.add(opt);
			}
		}
		var cart = this.cart;
		input.addEventListener('change',function(){cart.displayTotals()});
		this.setValue(stateKey,val);
	}

	//toggles displaying hidden address fields
	this.toggleHiddenAddress = function()
	{	
		if(this.hiddenAddressDiv)
			this.hiddenAddressDiv.style.display = this.getValue('billShipSame') ? 'none' : 'block';
	};
	
	//add event to the insureship checkbox
	this.loadInsureShip = function()
	{
		var cart = this.cart;
		try{
			this.getInput('insureShipment').addEventListener('click',function(){cart.displayTotals();});
		}catch(e){}
	};
	
	this.loadProductSelector = function()
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
			
			
			if(productId == this.defaultProduct)
				defaultProductIsOption = true;

			node.addEventListener('click',function()
			{
				kform.selectProduct(this.productId);
			});
		}
		
		if(!defaultProductIsOption)
			console.log("kformError: default productId is "+this.defaultProduct+" but this option does not exist in the productSelector");
		

		var curProd = this.fetchValue('selectedProduct');
		if(!curProd)
			curProd = this.defaultProduct;
		
		this.selectProduct(curProd);
		
	}

	this.selectProduct = function(productId)
	{
		if(kform.selectedProduct)
		{		
			var curVal = kform.selectedProduct;
			if(curVal == productId)
				return;
	
			var node = kform.productSelectNodes[curVal];
			node.className = node.className.replace(/kform_selectedProduct/,"");
			node.parentBox.className = node.parentBox.className.replace(/kform_selectedProduct/,"");
		}
		var node = kform.productSelectNodes[productId];

		kform.selectedProduct = productId;
		node.className += " kform_selectedProduct";
		node.parentBox.className += " kform_selectedProduct";
		kform.storeValue('selectedProduct',productId);
		
		if(typeof kform_userSelectProduct == 'function')
			kform_userSelectProduct(productId);
			
		kform.cart.displayTotals();
	};
	
	//disables the back button
	this.disableBackButton = function()
	{
		history.pushState(null, null, window.location);
		window.addEventListener('popstate', function(event){
			history.pushState(null, null, window.location);});	
	};
	
	this.displaySigninForm = function(email)
	{
		var lander = this;
		this.ajaxCallMethod('getSigninForm',null,function(html){
			var dialog = new kdialog('Sign In');
			dialog.display(html);
			
			var btn = document.getElementById('kcartContinueGuest');
			btn.onclick = function(){dialog.hide()};
			var vald = new kvalidator('kformSignup');
			if(email)
				vald.setValue('eCommerceLogin',email);
			vald.onSubmit = function(args){
				var t1 = function(r){window.location = window.location;};
				var f0 = function(r){vald.triggerError(r)};
				lander.ajaxCallMethod('customerLogin',args,t1,f0);
			};
		});
	};
	
	this.displayCreateAccount = function()
	{
		var lander = this;
		this.ajaxCallMethod('getCreateAccount',null,function(html){
			var dialog = new kdialog('Create Your Account');
			dialog.display(html);
		
			var vald = new kvalidator('kformSignup');
			vald.submitErrorPosition = 'after_submit';
			vald.onSubmit = function(args){
				var t1 = function(r){window.location = window.location;};
				var f0 = function(r){vald.triggerError(r)};
				lander.ajaxCallMethod('createAccount',args,t1,f0);
			};
		});
	};
	
	this.accountLogout = function()
	{
		this.ajaxCallMethod('accountLogout',null,function()
		{
			document.location.reload(true);
		});
	}
	
	this.restartPurchase = function(purchaseId)
	{
		var dialog = new kdialog("Confirm");
		dialog.isConfirm = true;
		dialog.display("Are you sure you want to restart billing?");
		var lander = this;
		dialog.onConfirm = function()
		{
			var params = {};
			params.restartPurchaseId = purchaseId;
			params.update = 'PURCHASE';
			lander.ajaxCallMethod('customerEdit',params,function()
			{
				document.location.reload(true);
			});
			return false;
		}
	}
	
	this.holdPurchase = function(purchaseId)
	{
		var dialog = new kdialog("Confirm");
		dialog.isConfirm = true;
		dialog.display("Are you sure you want to hold payment for a full billing cycle?");
		var lander = this;
		dialog.onConfirm = function()
		{
			var params = {};
			params.holdPurchaseId = purchaseId;
			params.update = 'PURCHASE';
			var success = function(){window.location = window.location;};
			var fail = function(msg){kdialog_alertError(msg)};
			lander.ajaxCallMethod('customerEdit',params,success,fail);
			return false;
		}
	};
	
	this.editPaySource = function(paySourceId,abbr)
	{
		var dialog = new kdialog("Edit Paysource: "+abbr);
		
		var div = document.getElementById('kformPaySourceDialog').cloneNode(true);
		
		dialog.display(div.innerHTML);
		
		var node = dialog.node.getElementsByClassName('kform')[0];
		var validator = new kvalidator(node);
		var lander = this;
		
		validator.onSubmit = function(params)
		{
			params.update = 'PAYSOURCE';
			if(validator.getValue('action') == 'delete')
				params.deletePaySourceId = paySourceId;
			else if(validator.getValue('action') == 'primary')
				params.makePrimaryPaySourceId = paySourceId;
				
			lander.ajaxCallMethod('customerEdit',params,function()
			{
				document.location.reload(true);
			});
		};
		
	}
	
	this.cancelOrder = function(orderId)
	{
		var dialog = new kdialog("Cancel Order #"+orderId);

		var cancelDialog = document.getElementById('kformCancelDialog');
		var cancelDialog = cancelDialog.cloneNode(true);
		var html = cancelDialog.innerHTML;
		
		dialog.display(html);
		
		var node = dialog.node.getElementsByClassName('kform')[0];
		
		var validator = new kvalidator(node);
		validator.toggleOtherReason = function()
		{
			validator.getInput('cancelReasonOther').node.style.display = validator.getValue('cancelReason') == '' ? 'block' : 'none';
		};
	
		node = validator.getInput('cancelReason').node;
		node.addEventListener('change',function()
		{
			validator.toggleOtherReason();
		});

		var lander = this;
		validator.onSubmit = function(params)
		{
			params.cancelOrderId = orderId;
			params.update = 'ORDER';
			
			var cancelReason = params.cancelReason;
			if(cancelReason == '')
				cancelReason = validator.getValue('cancelReasonOther');
			if(cancelReason == 	'')
				validator.triggerError("must provide a cancel reason");
				
				
			params.cancelReason = cancelReason;
			
			
			lander.ajaxCallMethod('customerEdit',params,function()
			{
				document.location.reload(true);
			});
			return false;
		};
		
	};
	
	
	this.reorder = function(orderItems)
	{
		params = {};
		params.cartItems = orderItems;
	
		var lander = this;
		this.ajaxCallMethod('updateCart',params,function()
		{
			window.location = lander.checkoutPageUrl;	
		});	
	};
	
	this.displayShipmentDetails= function(orderId)
	{
		var node = this.shipmentDetailsNodes[orderId];
		node = node.cloneNode(true);
		var dialog= new kdialog('Shipment Details');
		dialog.display(node.innerHTML);
	}
	
	this.cancelPurchase = function(purchaseId)
	{
		
		
		var dialog = new kdialog("Are you sure you want to cancel?");
		//dialog.isConfirm = true;
		
		var cancelDialog = document.getElementById('kformCancelDialog');
		var cancelDialog = cancelDialog.cloneNode(true);
		var html = cancelDialog.innerHTML;
		
		dialog.display(html);
		
		var node = dialog.node.getElementsByClassName('kform')[0];
		
		var validator = new kvalidator(node);
		validator.toggleOtherReason = function()
		{
			validator.getInput('cancelReasonOther').node.style.display = validator.getValue('cancelReason') == '' ? 'block' : 'none';
		};
	
		node = validator.getInput('cancelReason').node;
		node.addEventListener('change',function()
		{
			validator.toggleOtherReason();
		});

		var lander = this;
		validator.onSubmit = function(params)
		{
			params.cancelPurchaseId = purchaseId;
			params.update = 'PURCHASE';
			
			var cancelReason = params.cancelReason;
			if(cancelReason == '')
				cancelReason = validator.getValue('cancelReasonOther');
			if(cancelReason == 	'')
				validator.triggerError("must provide a cancel reason");
				
			params.cancelReason = cancelReason;
			
			lander.ajaxCallMethod('customerEdit',params,function()
			{
				document.location.reload(true);
			});
			return false;
		};
		
		
	}
	
	
	
	//displays a pixel
	this.firePixel = function(html)
	{
		var div = document.createElement('DIV');
		div.innerHTML = html;
		document.body.appendChild(div);
	}
	
	this.triggerClick = function()
	{
		params = {pageType:this.pageType};
		var lander = this;
		this.ajaxCallMethod("importClick",params,function(result)
		{
			if(result.pixel)
				lander.firePixel(result.pixel);
			
			if(result.sessionId)
			{
				lander.sessionId = result.sessionId;
					
				if(lander.pageType == 'presellPage')
					lander.upgradePresellPage(lander.sessionId);
			}
			if(lander.validator && lander.autoImportLead)
				window.setInterval(function(){lander.captureLead();},10000);
				
		});
	};
	
	this.captureLead = function()
	{
		var params = {};
		var tmpParams = this.validator.getValues();
		for(var i in tmpParams)
		{
			if(!this.validator.isHidden(i))
				params[i] = tmpParams[i];	
		}
		
		
		var checkThis = ['firstName','lastName','emailAddress','phoneNumber'];
		for(var i in checkThis){
			var k = checkThis[i];
			if(!params[k])
				params[k] = this.fetchValue(k);
		}
		if(!params.firstName || !params.lastName || (!params.emailAddress && !params.phoneNumber))
			return;
		
		var query = this.paramsToQuery(params);
		if(this.lastCaptureQuery == query)
			return;
		this.lastCaptureQuery = query;
		
		var lander = this;

		this.ajaxCallMethod('importLead',params,function(){
			window.setTimeout(function(){lander.captureLead();},10000);
		});
	}
	
	//method - predefined, see async.php file
	//params - post fields
	//cb1 - callback on succesful async response
	//cb2 - callback on failed async response	
	this.ajaxCallMethod = function(method,params,cb1,cb2)
	{
		params = params || {};
		params.method = method;
		cb1 = cb1 || function(r){console.log("good async: "+r)};
		cb2 = cb2 || function(r){console.log("bad async: "+r)};
		
		var request = window.XMLHttpRequest ?  new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		var query = this.paramsToQuery(params);
		
		request.cb1 = cb1;
		request.cb2 = cb2;
		
		request.open('POST',this.resourceDir+"async.php",true);
		request.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		request.onreadystatechange=function()
		{
			if(this.readyState==4)
			{
				raw = this.responseText;
				var response;
				try{response = JSON.parse(this.responseText);}
				catch(e){
					response = {code:500,body:this.responseText};	
				}
				if(!response || !response.code)
					var code = 500;
				else
					var code = response.code;
					
				if(code == 200)
					this.cb1(response.body,code);
				else
					this.cb2(response.body,code);
			}
		}
		request.send(query);
	}
	
	this.paramsToQuery = function(params)
	{
		var query = '';
		for(var i in params)
			query += "&"+i+"="+encodeURIComponent(params[i]);
			
		query = query.substr(1);
		return query;
	};
	
	/*XXXXXXXXXXXXXXXXXXXXXXXX
	/* CHECKOUT FUNCTIONS
	/* functions used by or related to the checkout page
	/*XXXXXXXXXXXXXXXXXXXXXXXX
	*/
	
	//destroys the login with amazon session
	this.logoutOfAmazon = function()
	{
		var lander = this;
		this.ajaxCallMethod('logoutAmazon',null,function()
		{
			lander.storeValue('amazonAccessToken','');
			lander.storeValue('amazonOrderId','');
			lander.storeValue('amazonAddressSelected','');
			lander.storeValue('amazonPaymentSelected','');
			lander.storeValue('paySource','CREDITCARD');
			amazon.Login.logout();
			document.location.reload(true);
			
		});
	};
	
	this.displayProgressBar = function(dTitle = 'Order Processing')
	{
		var dBody = "<center><div class='kformProgressBar' style='margin:20px auto'></div></center>";
		this.progressBar = new kdialog(dTitle);
		this.progressBar.display(dBody);
	}
	
	this.hideProgressBar = function()
	{
		this.progressBar.hide();
	}
	
	this.getValue = function(name)
	{
		if(!this.validator)return false;
		return this.validator.getValue(name);
	};
	
	this.setValue = function(name,val)
	{
		if(!this.validator)return false;
		return this.validator.setValue(name,val);
	};
	
	this.storeValue = function(name,val)
	{
		if(!this.validator)return false;
		return this.validator.saveFormValue(name,val);	
	};
	
	this.fetchValue = function(name)
	{
		if(!this.validator)return false;
		return this.validator.fetchFormValue(name);
		
	};
	this.hasInput = function(name)
	{
		if(!this.validator)return false;
		return this.validator.hasInput(name);	
	}
	this.getInput = function(name)
	{
		var input =  this.validator.getInput(name);	
		if(input.node)
			return input.node;
	} 
	
	
	this.getWindowWidth = function()
	{
		var win = typeof window != 'undefined' && window;
		var doc = typeof document != 'undefined' && document;
		var docElem = doc && doc.documentElement;
		return docElem['clientWidth'] < win['innerWidth'] ? win['innerWidth'] : docElem['clientWidth'];
	}
	this.getWindowHeight = function()
	{
		var win = typeof window != 'undefined' && window;
		var doc = typeof document != 'undefined' && document;
		var docElem = doc && doc.documentElement;
		return docElem['clientHeight'];
	}

    /*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
                           SIGNATURE FUNCTIONS
  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

    //This renders a typed signature image
    this.renderTypedSig = function()
    {
        var sigFirstName = this.fetchValue('firstName') ? this.fetchValue('firstName') : '';
        var sigLastName = this.fetchValue('lastName') ? this.fetchValue('lastName') : '';
        if(sigFirstName != '' || sigLastName != '')
        {
            //html2canvas is used to take an image of the signature to be combined with the pdf
            var html = document.createElement("span");
            html.innerHTML = sigFirstName + ' ' + sigLastName;
            html.id = 'signatureTypedInput';
            var sigDisplay = document.getElementById('kform_sigDisplay');
            if(sigDisplay)
                sigDisplay.innerHTML = "";
            else
            {
                sigDisplay = document.createElement("div");
                sigDisplay.id = 'kform_sigDisplay';
                document.body.appendChild(sigDisplay);
            }
            sigDisplay.appendChild(html);
            html2canvas(sigDisplay).then(function (canvas) {
                var image = canvas.toDataURL("image/png");
                if(document.getElementById('kform_sigDisplay'))
                    document.getElementById('kform_sigDisplay').src = image;
                kform.setValue('signature', image);
            });
        }
    }

    this.addClearSigButton = function()
    {
        clearButton = document.getElementById("kform_clearSig");
        if(clearButton)
            clearButton.onclick = function() {
                kform.signaturePad.clear();
                kform.setValue('signature', '');
            };
    }

    this.addTaxButton = function()
    {
        taxButton = document.getElementById("kform_taxBtn");
        if(taxButton)
        {
            taxButton.onclick = function() {
                if((kform.validator.validateField('address1') || kform.validator.validateField('shipAddress1')) && (kform.validator.validateField('shipPostalCode') || kform.validator.validateField('postalCode')))
				{
					kform.cart.getExternalTax();
				}
                else
                    kform.validator.triggerError('Please complete your address.');
            }
        }
    }

}
