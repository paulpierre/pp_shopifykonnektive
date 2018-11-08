kvalidate_config = {};
kvalidate_config.submitErrorPosition = 'dialog';
kvalidate_config.inputErrorDisplayType = 'popup';
kvalidate_config.inputErrorPosition = 'top';
kvalidate_config.placeHolders = {
	firstName:'First Name',
	lastName:'Last Name',
	emailAddress:'Email Address',
	phoneNumber:'Phone Number',
	address1:'Address Line 1',
	address2:'Address Line 2',
	city:'City',
	state:'State',
	country:'Country',
	postalCode:'Postal Code',
	shipAddress1:'Address Line 1',
	shipAddress2:'Address Line 2',
	shipCity:'City',
	shipState:'State',
	shipCountry:'Country',
	shipPostalCode:'Postal Code',
	cardNumber:'Credit Card #',
	cardSecurityCode:'CVV Code',
	achAccountNumber:'Account #',
	achRoutingNumber:'Routing #',
	achAccountHolderType:'Account Holder',
	achAccountType:'Account Type',
	billShipSame:'Billing Same as Shipping Address',
	couponCode:'Coupon Code',
	taxExemption:'Tax Exemption No.'
};

kvalidate_config.requiredErrors = {
	firstName: 'First Name is required',
	lastName: 'Last Name is required',
	emailAddress: 'Email is required',
	phoneNumber: 'Phone Number is required',
	address1: 'Address Line 1 is a Required Value',
	address2: 'Address Line 2 is a Required Value',
	city: 'City is a Required Value',
	state: 'State is a Required Value',
	country: 'Country is a Required Value',
	postalCode: 'Postal Code is a Required Value',
	shipAddress1: 'Address Line 1 is a Required Value',
	shipAddress2: 'Address Line 2 is a Required Value',
	shipCity: 'City is a Required Value',
	shipState: 'State is a Required Value',
	shipCountry: 'Country is a Required Value',
	shipPostalCode: 'Postal Code is a Required Value',
	confirmTOS: 'You Must Agree to the Terms and Conditions',
	cardNumber: 'Card Number is a Required Value',
	cardExpiryDate: 'Expiration Date is a Required Value',
	cardSecurityCode: 'SecurityCode is a Required Value',
	achAccountHolderType: 'Account Holder is a Required Value',
	achAccountType: 'Account Type is a Required Value',
	achAcountNumber: 'Account # is a Required Value',
	achRoutingNumber: 'Routing # is a Required Value'
};

//-message displayed when a field fails validation
kvalidate_config.invalidErrors = {
	emailAddress: 'Email Address is Invalid',
	phoneNumber: 'Phone Number is Invalid',
	cardNumber: 'Card Number is Invalid'
};

//-other error messages
//		the message can also be a function, which allows for some interesting possibilities
//
//		EX. prepaid redirect to straight-sale
//		prepaid: function(){window.location="straight-sale.php";}
//

kvalidate_config.otherErrors = {
	cardExpired: 'You Entered an Expired Date',
	cartEmpty: 'You must select a product to checkout',
	blackList: 'Your account has been blacklisted',
	prepaid: 'Prepaid cards are not allowed',
	debit: 'Debit cards are not allowed',
	trialDedup: 'You have already purchased this product',
	decline: 'Your card has been decline. Please try a new card',
	declineDedup: 'This card has already declined. Please try a new card',
	paymentProcessing: 'Your payment is processing. Please wait'
};	

//Style Rules
//	--styling for all error types set in kform.css in the /resources/css directory
//	--heading "ERROR STYLES"
//	--separate classes for each error type: .kformInlineError / .kformSubmitError / .kformErrorPop
	
kvalidator = function(formId,name)
{
	
	//form_top, form_bottom, before_submit, after_submit, dialog
	this.submitErrorPosition = 'after_submit';
	
	//errorPops -- popup, inline
	this.inputErrorDisplayType = 'popup';
	this.inputErrorPosition = 'top'; //(top / left / right / bottom)
	
	
	
	
	
	//Place Holders
	//-this is the text that is displayed in a text input by default (ex. (xxx-xxxx) to indicate a phone number)
	this.usePlaceHolders = true;
	
	if(kvalidate_config && typeof(kvalidate_config) === 'object')
	{
		for(var i in kvalidate_config){
			if(kvalidate_config.hasOwnProperty(i))
			{
				this[i] = kvalidate_config[i];
			}
		}
	}
	

	this.submitErrorPosition = kform.getWindowWidth() < 980 ? 'before_submit'  : 'dialog';	
	
	/*
	*	ADVANCED CONFIGURATIONS BELOW
	*		-please read the documentation
	*/
	
	this.validateEmailAddress = function(val)
	{
		val = val.trim();
		var regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i;
		if(regex.test(val))
			return true;
	};
	
	this.validatePhoneNumber = function(val)
	{
		if(!val.match(/^[0-9()-\s]{7,15}$/))
			return false;
		
		val = val.replace(/[^0-9]/,'');
		if(val.length >= 7)
			return true;
	}
	
	this.validateCardNumber = function(val)
	{
		val = val.replace(/[^0-9]/,'');
		if(val.length >= 15 && val.length < 20)
			return true;
	}
	
	this.validateCardSecurityCode = function(val)
	{
		val = val.replace(/[^0-9]/,'');
		if(val.length == 4 || val.length == 3)
			return true;	
	}
	
	this.validateAchRoutingNumber = function()
	{
		if(this.getValue('EcheckAccountDetails') != '') 
			return true;
			
		var regex = /^[0-9]{9}$/;
		if(regex.test(this.getValue('achRoutingNumber')))
			return true;
		return false;
	};
	this.validateAchAccountNumber = function()
	{
		if(this.getValue('EcheckAccountDetails') != '')
			return true;
			
		var regex = /^[0-9]{10,20}$/;
		if(regex.test(this.getValue('achAccountNumber')))
			return true;
		return false;
	};
		
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXX
	//DO NOT EDIT BELOW THIS LINE
	//XXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	var node;
	if(typeof(formId) == 'string')
		node = document.getElementById(formId);
	else if(typeof(formId) == 'object')
		node = formId;
	
	this.formNode = node;
	this.errors = {};
	this.formNode.validator = this;
	this.formName = name;
	

	
	var p = this.submitErrorPosition;
	
	if(p!='form_top' && p!='form_bottom' && p!='after_submit' && p!='before_submit' && p!='dialog')
	{
		this.submitErrorPosition = 'after_submit';
		console.log("kvalidator: submitErrorPosition is invalid. Changing to default");	
	}
	
	if(this.inputErrorDisplayType == 'inline')
	{
		var p = this.inputErrorPosition;	
		if(p!='before' && p!='after')
		{
			this.inputErrorPosition = 'after';
			console.log("kvalidator: inputErrorPosition is invalid. Changing to default");	
		}
	}
	else if(this.inputErrorDisplayType == 'popup')
	{
		var p = this.inputErrorPosition;	
		if(p!='top' && p!='bottom' && p!='left' && p!='right')
		{
			this.inputErrorPosition = 'after_submit';
			console.log("kvalidator: inputErrorPosition is invalid. Changing to default");	
		}	
	}
	else
	{
		this.inputErrorDisplayType = 'popup';
		this.inputErrorPosition = 'top';	
		
		console.log("kvalidator: inputErrorDisplayType is invalid. Change to default");
	}
	
	this.addSubmitButton = function(node)
	{
		if(!node.isUpgraded)
		{
			node.validator = this;
			node.onclick = function()
			{
				this.validator.lastSubmitClicked = this;
				this.validator.formSubmit();
				return false;
			};
			node.isUpgraded = true;
		}
	}
	
	this.formNode.onsubmit = function(){this.validator.formSubmit();return false;};
	this.lastSubmitClicked = null;
	
	this.formSubmit = function()
	{
		params = this.validate();
		if(this.isValidated)
		{
			if(typeof(this.onSubmit) == 'function')
				this.onSubmit(params);
			else
				this.formNode.submit();			
		}
	}
	
	
	//validate a single field in the form
	this.validateField = function(name,triggerError,displayNow)
	{
		if(triggerError !== false)
			triggerError = true;
		if(displayNow !== true)
			displayNow = false;
		
		if(this.isRequired(name) && this.isEmpty(name) && !this.isHidden(name))
		{
			if(triggerError)
				this.triggerError('required',name,displayNow);
	
			return false;
		}
		
		val = this.getValue(name);
		
		if(val)
		{
			
			//execute against validation function if defined
			var func = 'validate'+name.substring(0,1).toUpperCase()+name.substring(1);
			func = func.replace(/[^a-z0-9_]/i,'');
			
			if(typeof this[func] == 'function')
			{
				val = this[func](val);
				
				if(val === true)
				{
					val = this.getValue(name);
				}
				else if(typeof(val) != 'string')
				{
					if(triggerError)
						this.triggerError('invalid',name,displayNow);
					return false;				
				}
			}
			
		}
		
		return triggerError ? val : true;
	}
	
	this.getValues = function()
	{
		vals = {};
		for(var i in this.inputs)
			vals[this.inputs[i].name] = this.inputs[i].getValue();
		return vals;
	}
	
	//validate the entire form
	this.validate = function(name)
	{
		this.validated = false;
		//clear existing errors
		this.clearErrors();
		
		//final values to submit
		var values = {};
		
		//loop through all inputs to validate and get value
		for(var i in this.inputs)
		{
			var input = this.inputs[i];	
			var name = input.name;
			var val = input.getValue();
			
			if(this.isHidden(name))
				continue;
			
			//validate the field name against field requirements and specific
			//validation functions like: validateShipAddress
			val = this.validateField(name);
			
			//if no error was set on the field, add to the value list
			if(!this.hasInputError(name))	
				values[name] = val;
		}
		
		//execute user defined function if exists
		if(typeof(this.onValidate) == 'function')
			this.onValidate();
		
		if(this.hasError())
			return false;
		else
			this.isValidated = true;
		
		return values;
	}
	
	this.hasError = function()
	{
		if(this.curSubmitError)
			return true;
		
		for(var i in this.errors)
		{
			if(this.errors[i])
				return true;
		}	
	}
	
	this.hasInputError = function(name)
	{
		return typeof this.errors[name] == 'object' && this.errors[name] !== null;
	}
	
	this.triggerError = function(msg,name,displayNow)
	{
		if(displayNow !== false)
			displayNow = true;
		if(typeof(name) != 'string')
			name = null;

		if(name !== null && this.hasInput(name))
		{
			
			if(msg == 'required' && this.requiredErrors[name])
				msg = this.requiredErrors[name];
			else if(msg == 'invalid' && this.invalidErrors[name])
				msg = this.invalidErrors[name];
			else if(this.otherErrors[msg])
				msg = this.otherErrors[msg];
			else
				msg = name+' '+msg;
			
			if(this.inputErrorDisplayType == 'inline')
				this.errors[name] = new this.inlineError(this,name,msg);
			else
				this.errors[name] = new this.errorPop(this,name,msg);
				
			if(displayNow)
				this.errors[name].display();
		}
		else if(name === null)
		{	
			if(typeof(msg) == 'object')
			{
				errMsg = '';
				for(var i in msg)
				{
					errMsg += i+' '+msg[i]+"<br>";
				}
				msg = errMsg;
			}
			
			if(this.otherErrors[msg])
				msg = this.otherErrors[msg];
			
			if(this.submitErrorPosition == 'dialog')
			{
				var dialog = new kdialog('Form Error');
				var dCallback = null;
				if(typeof kform_formSubmitCallback == 'function')
					var dCallback = function(r){kform_formSubmitCallback(r,false);};
				dialog.display('<div class="kformSubmitError">'+msg+'</div>',dCallback);
			}
			else
			{
				var e = new this.submitError(this,msg);
				e.display();
			}
		}
		else
			return;
			
	}
	
	this.hasInput = function(name)
	{
		return typeof(this.inputs[name]) == 'object';	
	}
	
	this.isRequired = function(name)
	{
		if(this.hasInput(name))
			return this.getInput(name).node.hasAttribute('isRequired');
	}
	this.isHidden = function(name)
	{
		if(this.hasInput(name))
		{
			var cur_el = this.getInput(name).node;
			do{
				if(cur_el.style && (cur_el.style.display == 'none' || cur_el.style.visibility == 'hidden'))
					return true;
			}
			while(cur_el = cur_el.parentNode);
		}
		return false;
	}
	
	this.isEmpty = function(name)
	{
		var val = this.getValue(name);
		if(val === true || (typeof(val) == 'string' && val.length > 0))
			return false;
		return true;
		
		//alert('got '+name+' = '+val+"\nintval:"+parseInt(val));
	};
	
	this.getValue = function(name)
	{
		if(this.hasInput(name))
			return this.getInput(name).getValue();
	}
	
	this.setValue = function(name,val)
	{
		if(this.hasInput(name))
		{
			this.getInput(name).setValue(val);	
		}
	};
	
	this.getInput = function(name)
	{
		return this.inputs[name] ? this.inputs[name] : false;
	};
	
	this.addInput = function(node)
	{
		if(node.type == 'submit' || node.type == 'image')
		{
			this.addSubmitButton(node);
			return;
		}
		
		if(!node.hasAttribute('name'))
			return;
		
		if(this.inputs[node.name]){
			if(node.type == 'radio')
				this.inputs[node.name].addRadio(node);
			else
				console.log("warning: you have more than one form elements named: "+node.name);
		}else{
			this.inputs[node.name] = new this.inputEl(node,this);
			this.inputs[node.name].init();
			
		}
	}
	
	this.buildInputs = function()
	{
		this.inputs = {};
		
		nodes = [];
		var inputs = this.formNode.getElementsByTagName('INPUT');
		var selects = this.formNode.getElementsByTagName('SELECT');
		
		for(var i = 0;i<inputs.length;i++)
			nodes.push(inputs[i]);	
		for(var i = 0;i<selects.length;i++)
			nodes.push(selects[i]);	
		
		for(var i=0;i<nodes.length;i++)
		{
			var node = nodes[i];
			this.addInput(node);
			
			var name = node.name;
			
			if(this.usePlaceHolders && this.placeHolders[name])
				node.setAttribute('placeholder',this.placeHolders[name]);
		}
	};
	this.buildLabels = function()
	{
		this.labels = [];	
		var nodes = this.formNode.getElementsByClassName('INPUT');
		for(var i=0;i<nodes.length;i++)
		{
			var node = nodes[i];
			this.labels[node.name] = node;
		}
	}
	

	
	this.clearErrors = function()
	{
		if(this.hasError())
		{
			if(typeof(this.curSubmitError) == 'object')
				this.curSubmitError.remove();
		}
		
		for(var i in this.errors)
		{
			if(this.errors[i])
				this.errors[i].remove();
				
		}
	}
	
	this.saveFormValue = function(key,val)
	{
		try{
			if(!sessionStorage)
				return false;
			
			var varName = 'vals_'+this.formName+'_'+key;
			sessionStorage[varName] = val;
		
			return true;
		}catch(e)
		{
			return false;	
		}
	};
	
	this.fetchFormValue = function(key)
	{
		try{
			if(!sessionStorage)
				return null;
				
			var varName = 'vals_'+this.formName+'_'+key;
				
			if(sessionStorage[varName])
				return sessionStorage[varName];	
			return null;
		}catch(e)
		{
			return null;	
		}
	};
	
	this.buildLabels();
	this.buildInputs();
	
	
};

//kvalidator.inputEl

kvalidator.prototype.inputEl = function(node,validator)
{
	this.node = node;
	this.validator = validator;
	
	this.init = function()
	{
		var node = this.node;
		var validator = this.validator;
	
		this.type = this.tagName == 'SELECT' ? 'select' : node.type;
		this.name = node.name;
		if(this.type =='radio')
			this.radios = [node];
			
		if(!node.isUpgraded)
		{
			node.isUpgraded = true;
			node.inputError = null;
			node.inputValidator = validator;
			
			var savedVal;
			if(savedVal = validator.fetchFormValue(this.name))
			{
				if(!node.hasAttribute('readonly'))
					this.setValue(savedVal);
			}
			else
			{
				if(this.getValue())
					validator.saveFormValue(this.name,this.getValue());
			}
			if(this.type == 'checkbox')
			{
				node.addEventListener('click',function()
				{
					if(this.inputError)
					{	
						if(typeof(this.inputError.validate) == 'function')
							this.inputError.validate();	
						else
							this.inputError.remove();
					}
					this.inputValidator.saveFormValue(this.name,this.value);
				});
			}
			else if(this.type == 'radio')
			{
				node.addEventListener('click',function()
				{	
					if(this.inputError)
					{	
						if(typeof(this.inputError.validate) == 'function')
							this.inputError.validate();	
						else
							this.inputError.remove();
					}
					this.inputValidator.saveFormValue(this.name,this.value);
				});
			}
			else if(this.type == 'select')
			{
				node.addEventListener('change',function()
				{
					this.inputValidator.saveFormValue(this.name,this.value);	
					if(this.inputError)
					{
						if(typeof(this.inputError.validate) == 'function')
							this.inputError.validate();	
						else
							this.inputError.remove();	
					}
				});
			}
			else
			{
				node.addEventListener('focus',function()
				{
					if(this.inputError)
						this.inputError.display();	
				});
				node.addEventListener('keyup',function()
				{
					if(this.inputError && typeof(this.inputError.validate) == 'function')
						this.inputError.validate();	
					//else if(this.inputValidator)
						//this.inputValidator.validateField(this.name,true,true);
				});
				node.addEventListener('blur',function()
				{
					if(this.inputError == null)
						return;
		
					if(typeof(this.inputError.validate) == 'function')
						this.inputError.validate();
					else
						this.inputError.clear();	
				});
				node.addEventListener('change',function()
				{
					if(!this.noSaveFormValue && !this.hasAttribute('noSaveFormValue'))
						this.inputValidator.saveFormValue(this.name,this.value);
					
					if(this.inputError && typeof(this.inputError.validate) == 'function')
						this.inputError.validate();
					else if(this.inputValidator)
						this.inputValidator.validateField(this.name,true,true);
				});
			}
		}
	};
		
	this.addRadio = function(node)
	{
		this.radios.push(node);
		node.inputValidator = this.validator;
		node.addEventListener('click',function()
		{	
			if(this.inputError)
			{	
				if(typeof(this.inputError.validate) == 'function')
					this.inputError.validate();	
				else
					this.inputError.remove();
			}
			this.inputValidator.saveFormValue(this.name,this.value);
		});
		
		var savedVal;
		if(savedVal = validator.fetchFormValue(node.name))
		{
			if(!node.hasAttribute('readonly') && node.value == savedVal)
				this.setValue(savedVal);
		}
		else
		{
			if(node.checked)
				this.validator.saveFormValue(this.name, node.value);
		}
		
	}
	
	this.getValue = function()
	{
		if(this.type == 'select')
			return this.node.value;
		else if(this.type == 'checkbox')
			return this.node.checked ? true : false;
		else if(this.type == 'radio')
		{
			for(var i in this.radios)
			{
				if(this.radios[i].checked)
					return this.radios[i].value;	
			}
		}
		else
		{
			return this.node.value == null ? null : this.node.value;	
		}
	}
	this.setValue = function(val)
	{
		if(this.type == 'radio')
		{
			for(var i in this.radios)
			{
				this.radios[i].checked = false;
				if(this.radios[i].value == val)
					this.radios[i].checked = true;
			}
		}
		else if(this.type == 'checkbox')
		{
			if(val === 'on' || val === true || val === 'true' || val === '1' || val === 1)
				this.node.checked = true;
			else
				this.node.checked = false;
		}
		else
		{
			this.node.value = val;
		}
	}
}



/*
SUBMIT ERRORS
-these are general errors that apply to the entire form
-only one can be displayed at a time
*/
kvalidator.prototype.submitError = function(validator,msg)
{
	this.validator = validator;
	this.errorText = msg;
	validator.curSubmitError = this;
	this.errorNode = document.createElement('DIV');
	
	this.display = function()
	{
	
		//clear existing error
		if(validator.curSubmitError)
			validator.curSubmitError.clear();

		//create new errorDiv
		var formNode = this.validator.formNode;
		var div = this.errorNode;
		div.className = 'kformSubmitErrorContainer';
		
		var pad = document.createElement('DIV');
		pad.className = 'kformSubmitError';
		
		pad.innerHTML = this.errorText;
		
		div.appendChild(pad);
		//insert into form in different locations depending on configured position
		var pos = validator.submitErrorPosition;
		switch(pos)
		{
			case 'form_top':
				formNode.insertBefore(div,formNode.firstChild);	
				break;
			case 'form_bottom':
				formNode.appendChild(div);	
				break;
			case 'before_submit':
				validator.lastSubmitClicked.parentNode.insertBefore(div,validator.lastSubmitClicked);
				break;
			case 'after_submit':
				validator.lastSubmitClicked.parentNode.insertBefore(div,validator.lastSubmitClicked.nextSibling);
				break; 
		}
	
	}
	
	//clear the displayed error
	this.clear = function()
	{
		if(this.errorNode.parentNode)
			this.errorNode.parentNode.removeChild(this.errorNode);
		
	}
	
	this.remove = function()
	{
		this.clear();
		this.validator.curSubmitError = null;
	}
}

/*
INLINE ERRORS
-this error text inserted next to a field input
*/
kvalidator.prototype.inlineError = function(validator,name,msg)
{
	
	this.validator = validator;
	this.fieldName = name;
	this.errorText = msg;
	
	var input = validator.getInput(name);
	this.inputNode = input.node;
	this.inputNode.inputError = this;
	this.errorNode = document.createElement('DIV');
	this.errorNode.className = 'kformInlineError';
	this.errorNode.innerHTML = this.errorText;
	
	this.display = function()
	{
		if(this.errorNode.parentNode)
			this.clear();
			
		//insert error in the correct place
		var input =  this.inputNode;
		
		var pos = validator.inputErrorPosition;
		if(pos != 'after' && pos != 'before')
			pos = 'after'
			
		if(pos == 'after')
			input.parentNode.insertBefore(this.errorNode,input.nextSibling);
		else if(pos == 'before')
			input.parentNode.insertBefore(this.errorNode,input);

	}
	
	this.clearOutline = function()
	{
		var className = this.inputNode.className.replace(/ kformErrorOutline/,'');
		this.inputNode.className = className;
	}
	this.showOutline = function()
	{
		var className = this.inputNode.className.replace(/ kformErrorOutline/,'');
		className += ' kformErrorOutline';
		this.inputNode.className = className;
	}
	
	this.clear = function()
	{
		if(this.errorNode.parentNode)
			this.errorNode.parentNode.removeChild(this.errorNode);
	}
	
	this.validate = function()
	{
		if(this.validator.validateField(this.fieldName,false) === true)
			this.remove();
	}
	
	this.remove = function()
	{
		this.clear();
		this.clearOutline();
		this.inputNode.inputError = null;
		this.validator.errors[this.fieldName] = null;
	}
	
	this.showOutline();
}

/*
ERROR POPS
-these are floating error text areas that appear next to the invalid field
-only one error can display its error text at a time
-There are some redundancies from the inlineError class that possibly could be avoided with class extensions
*/
kvalidator.prototype.errorPop = function(validator,name,msg)
{		
	
	//instance of kvalidator
	this.validator = validator;
	
	//the inputElement that is triggering this error
	this.inputNode = validator.getInput(name).node;
	this.inputNode.inputError = this;
	this.fieldName = name;
	
	this.position = validator.inputErrorPosition;
	this.errorText = msg;
	this.timeoutIds = []; //timeouts related to popup hover states
	
	
	this.display = function()
	{
		
		if(this.validator.curErrorPop)
			this.validator.curErrorPop.clear();

		this.validator.curErrorPop = this;
		
		var inputNode = this.inputNode;
		
		var errorNode = document.createElement('DIV');
		errorNode.className = 'kformErrorPopContainer';
		errorNode.instance = this;
		this.errorNode = errorNode;
		
		var popup = this;
		
		//set alertText div width (same size as input)
		
		var cWidth = inputNode.offsetWidth;
		
		var style = window.getComputedStyle(inputNode)
		
		var padLeft = style.getPropertyValue('padding-left');
		var padRight = style.getPropertyValue('padding-right');
		var borderLeft = style.getPropertyValue('border-left-width');
		var borderRight = style.getPropertyValue('border-right-width');
		var marginLeft = style.getPropertyValue('margin-left');
		var marginRight = style.getPropertyValue('margin-right');
		
		
		
		if(padLeft)
		{
			padLeft = parseInt(padLeft.replace(/px/,''));	
			if(!isNaN(padLeft))
				cWidth -= padLeft;
		}
		if(padRight)
		{
			padRight = parseInt(padRight.replace(/px/,''));	
			if(!isNaN(padRight))
				cWidth -= padRight;
		}
		if(borderLeft)
		{
			borderLeft = parseInt(borderLeft.replace(/px/,''));	
			if(!isNaN(borderLeft))
				cWidth -= borderLeft;
		}
		if(borderRight)
		{
			borderLeft = parseInt(borderRight.replace(/px/,''));	
			if(!isNaN(borderRight))
				cWidth -= borderRight;
		}
		if(marginLeft)
		{
			borderLeft = parseInt(marginLeft.replace(/px/,''));	
			if(!isNaN(marginLeft))
				cWidth -= marginLeft;
		}
		if(marginRight)
		{
			marginRight = parseInt(marginRight.replace(/px/,''));	
			if(!isNaN(marginRight))
				cWidth -= marginRight;
		}

		
		
		cWidth = cWidth > 200 ? cWidth : 200;
		
		
	
		
		//errorNode.style.width = cWidth +'px';
		
		
		var div = document.createElement('DIV');
		div.className = 'xOut';
		div.onclick = function(){popup.clear()};
		errorNode.appendChild(div);
		
		console.log(cWidth);
		//create inner div for error container
		var div = document.createElement('DIV');
		div.className = 'kformErrorPop';
		div.style.width= cWidth+'px';
		div.innerHTML = this.errorText;
	
		if(this.backgroundColor)
			div.style.backgroundColor = this.backgroundColor;
		if(this.fontColor)
			div.style.color = this.fontColor;
		if(this.borderColor)
			div.style.borderColor = this.borderColor;	
	
		errorNode.appendChild(div);
		errorNode.style.display = 'block';
		//curInput.parentNode.insertBefore(alertText,curInput.nextSibling);
		
		var coords = this.getOffsets(inputNode);
		
		var offsetParent = coords[0];
		var x = coords[1];
		var y = coords[2];
		
		offsetParent.appendChild(errorNode);
		
		if(this.position)
			position = this.position;
		
		if(inputNode.hasAttribute('inputErrorPos'))
			position = inputNode.getAttribute('inputErrorPos');
		
		if(position != 'top' && position != 'bottom' && position != 'left' && position != 'right')
			position = 'top';
		
		var gutter_y = 10;
		var gutter_x = 20;
		
		if(position == 'top')
		{
			var top = y - errorNode.offsetHeight - gutter_y;
			var left = x;
		}
		else if(position == 'bottom')
		{
			var top = y + errorNode.offsetHeight + gutter_y;
			var left = x;
		}
		else if(position == 'left')
		{
			var top = y;
			var left = x - errorNode.offsetWidth - gutter_x;
		}
		else if(position == 'right')
		{
			var top = y - 2;
			var left = x + errorNode.offsetWidth + gutter_x;
		}

		errorNode.style.top = top+'px';
		errorNode.style.left = left+'px';
		errorNode.addEventListener('click',function()
		{
			errorNode.instance.clear();
		});
	};
	
	

	this.validate = function()
	{
		if(this.validator.validateField(this.fieldName,false) === true)
		{
			this.remove();
		}
	}
	
	this.clearOutline = function()
	{
		var className = this.inputNode.className.replace(/ kformErrorOutline/,'');
		this.inputNode.className = className;
	}
	this.showOutline = function()
	{
		
		var className = this.inputNode.className.replace(/ kformErrorOutline/,'');
		className += ' kformErrorOutline';
		this.inputNode.className = className;
	}
	
	this.clear = function()
	{
		if(this.errorNode && this.errorNode.parentNode)
			this.errorNode.parentNode.removeChild(this.errorNode);
	}
	this.remove = function()
	{
		this.clear();
		this.clearOutline();
		this.inputNode.inputError = null;
		this.validator.curErrorPop = null;
		this.validator.errors[this.fieldName] = null;
	}
	this.getOffsets = function() 
	{
		cur_el = this.inputNode;
		curoffsets = {x:cur_el.offsetLeft,y:cur_el.offsetTop};
		while(cur_el.offsetParent)
		{
			cur_el = cur_el.offsetParent;
			var pos = window.getComputedStyle(cur_el).getPropertyValue('position');
			if(pos == 'absolute' || pos == 'relative' || pos == 'fixed')
				break;
				
			curoffsets.x += cur_el.offsetLeft;
			curoffsets.y += cur_el.offsetTop;
		}
		return [cur_el,curoffsets.x,curoffsets.y];
	}
	this.showOutline();
	
	
	if(this.inputNode.tagName == 'INPUT' && this.inputNode.type == 'checkbox')
	{
		this.display();
	}
	
};



kdialog = function(title)
{
	if(typeof(window._kdialog) == 'undefined'){
		window.addEventListener('resize',function(){try{window._kdialog.setOffsets();}catch(e){}});
		window.addEventListener('scroll',function(){try{window._kdialog.setOffsets();}catch(e){}});
	}
	try{window._kdialog.hide();}catch(e){};
	window._kdialog = this;
	this.title=title;
}
kdialog.prototype.display = function(dMsg,dCallback)
{
	this.node = document.createElement('DIV');
	this.node.className = 'kdialogWrap';
	if(this.title){this.title = "<div class='kdialogTitle'>"+this.title+"</div>";}

	

	this.node.innerHTML = "<div class='kdialogBackground'></div><div class='kdialogContent'><div class='kdialogXOut'></div>" + this.title + dMsg + "</div></div>";
	var dialog = this;
	this.node.getElementsByClassName('kdialogXOut')[0].addEventListener('click',function(){
		dialog.hide();
		if(typeof dCallback == 'function')
			dCallback(dMsg);
	});
	document.body.appendChild(this.node);
	
	if(this.isConfirm)
	{
		var content = this.node.getElementsByClassName('kdialogContent')[0];
		
		var div = document.createElement('DIV');
		div.className = 'kdialogConfirmOptions';
		
		var opt = document.createElement('INPUT');
		opt.type = 'button';
		opt.value = 'No';
		opt.onclick = function(){dialog.hide()};
		div.appendChild(opt);
		
		var opt = document.createElement('INPUT');
		opt.type = 'button';
		opt.value = 'Yes';
		opt.onclick = function(){dialog.onConfirm();dialog.hide();};
		div.appendChild(opt);
		
		content.appendChild(div);
	
	}

	var nodes = this.node.getElementsByClassName('kdialogClose');
	for(var i=0;i<nodes.length;i++)
		nodes[i].addEventListener('click',function(){
			dialog.hide();
			if(typeof dCallback == 'function')
				dCallback(dMsg);
		});
	
	this.setOffsets();
}

kdialog.prototype.setOffsets = function()
{
	try{var content = this.node.getElementsByClassName('kdialogContent')[0];}catch(e){return false;}
	
	var win = typeof window != 'undefined' && window;
	var doc = typeof document != 'undefined' && document;
	var docElem = doc && doc.documentElement;
	var winWidth = kform.getWindowWidth();
	var winHeight = kform.getWindowHeight();
	
	var maxWidth = (winWidth - 250);
	if(maxWidth < 150)
		maxWidth = 150;
	
	//shrink the dialog if on a small width device (mobile phone)
	content.style.maxWidth = maxWidth;	
	
	var boxW = content.offsetWidth
	var boxH = content.offsetHeight;
	
	var doc = document.documentElement;
	var scrollLeft = (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0);
	
	var left = Math.floor(winWidth / 2);
	left = left + scrollLeft - Math.floor(boxW / 2);
	var top = Math.floor(winHeight / 2);
	top = top - Math.floor(boxH / 2);
	content.style.left = left+'px';
	content.style.top = top+'px';

	var wrapperTop = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
	this.node.style.top = wrapperTop + 'px';
	
	this.node.style.width=winWidth+'px';
	this.node.style.height = winHeight+'px';
}
kdialog.prototype.hide = function()
{
	window._kdialog = null;
	if(this.node.parentNode){
		this.node.parentNode.removeChild(this.node);}
	if(typeof this.onHide == 'function'){
		this.onHide();}

}

kdialog_alertError = function(msg)
{
	var dialog = new kdialog("ERROR");	
	var msg = "<div class='kformSubmitError'>"+msg+"</div><br><center><input type='button' value='Ok' onclick='_kdialog.hide()'></center>";
	dialog.display(msg);
}


