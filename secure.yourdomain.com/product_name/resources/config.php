<?php

KformConfig::setConfig(array(
"isWordpress"=>false,
"apiLoginId"=>"############",
"apiPassword"=>"############",
"authString"=>"############",
"autoUpdate_allowedIps"=>array("############"),
"campaignId"=>1234,
"resourceDir"=>"resources/"));




/* 
!---------------------------------IMPORTANT-----------------------------------!

Documentation:
	
	-Full documentation on landing pages can be found at 

Auto-Update Feature:

	-The auto-update feature will automatically update settings on your landing page
	when you make changes to your campaign within the konnektive CRM. Use this feature
	to keep your landing page up-to-date concerning new coupons / shipping options
	and product changes.

	-To use the campaign auto-update feature, the apache or ngix user 
	(depending on your httpd software) must have write access to this file
	
	-If you are not using the auto-update feature, you will need to manually 
	replace this file after making changes to the campaign	
	
!---------------------------------IMPORTANT-----------------------------------!
*/

class KFormConfig
{
	
	public $isWordpress = false;
	public $apiLoginId = '';
	public $apiPassword = '';
	public $resourceDir;
	public $baseDir;
	
	
	public $mobileRedirectUrl;
	public $desktopRedirectUrl;
	
	
	public $continents;
	public $countries;
	public $coupons;
	public $currencySymbol;
	public $insureShipPrice;
	public $landerType;
	public $offers;
	public $upsells;
	public $products;
	public $shipProfiles;
	public $states;
	public $taxes;
	public $termsOfService;
	public $webPages;
	
	static $instance = NULL;
	static $options;
	static $campaignData;
	// class constructor to set the variable values	
	
	static function setConfig($options)
	{
		self::$options = $options;	
	}
	
	public function __construct()
	{
		if(!empty(self::$instance))
			throw new Exception("cannot recreated KFormConfig");
		
		foreach((array) self::$options as $k=>$v)
			$this->$k = $v;
			
		if($this->isWordpress)
		{
			$options = get_option('konnek_options');
			foreach((array)$options as $k=>$v)
				$this->$k = $v;
		
			$data = json_decode(get_option('konnek_campaign_data'));
			foreach($data as $k=>$v)
				$this->$k = $v;
		}
		elseif(!empty(self::$campaignData))
		{
			$data = (array) json_decode(self::$campaignData);
			foreach($data as $k=>$v)
				$this->$k = $v;	
		}

		self::$instance = $this;
		
	
	}
}

/* 
!---------------------------------IMPORTANT-----------------------------------!

	ABSOLUTELY DO NOT EDIT BELOW THIS LINE
	
!---------------------------------IMPORTANT-----------------------------------!
*/
$requestUri = $_SERVER['REQUEST_URI'];
$baseFile = basename(__FILE__);

if($_SERVER['REQUEST_METHOD']=='POST' && strstr($requestUri,$baseFile))
{
	
	$authString = filter_input(INPUT_POST,'authString',FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
	if(empty($authString))
		die(); //exit silently, don't want people to know that this file processes api requests if they are just sending random posts at it
	
	
	$remoteIp = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		  $remoteIp =  $_SERVER["HTTP_CF_CONNECTING_IP"];
	
	$allowedIps = KFormConfig::$options['autoUpdate_allowedIps'];
	if($authString == KFormConfig::$options['authString'] && in_array($remoteIp,$allowedIps))
	{
		$data = filter_input(INPUT_POST,'data');
		$data = trim($data);
		$data = utf8_encode($data);
		$decoded = json_decode($data);
		if($decoded != NULL)
		{
			$file = fopen(__FILE__,'r');
			if(empty($file))
				die("ERROR: File not writable");
			
			$new_file = '';
			
			while($line = fgets($file))
			{
				$new_file .= $line;
				
				if(strpos($line,"/*[DYNAMIC-DATA-TOKEN]") === 0)
					break;
			}
			fclose($file);
			
			$new_file .= "KFormConfig::\$campaignData = '$data';".PHP_EOL;
			$ret = file_put_contents(__FILE__,$new_file);

			
			if(is_int($ret))
				die("SUCCESS");
			else
				die("ERROR: File not writable");
		}
		else
		{
			die("ERROR: what data");	
		}
	}
	else
	{
		die("ERROR: invalid credentials");	
	}
}

/*[DYNAMIC-DATA-TOKEN] do not remove */

KFormConfig::$campaignData = '{
    "countries": {
        "US": "United States",
        "AU": "Australia",
        "CA": "Canada",
        "GB": "United Kingdom"
    },
    "states": {
        "GB": {
            "ABE": "Aberdeen City",
            "ABD": "Aberdeenshire",
            "ANS": "Angus",
            "ANT": "Antrim",
            "ARD": "Ards",
            "AGB": "Argyll and Bute",
            "ARM": "Armagh",
            "BLA": "Ballymena",
            "BLY": "Ballymoney",
            "BNB": "Banbridge",
            "BDG": "Barking and Dagenham",
            "BNE": "Barnet",
            "BNS": "Barnsley",
            "BAS": "Bath and North East Somerset",
            "BBO": "Bedford Borough",
            "BDF": "Bedfordshire",
            "BFS": "Belfast",
            "BEX": "Bexley",
            "BIR": "Birmingham",
            "BBD": "Blackburn with Darwen",
            "BPL": "Blackpool",
            "BGW": "Blaenau Gwent",
            "BOL": "Bolton",
            "BMH": "Bournemouth",
            "BRC": "Bracknell Forest",
            "BRD": "Bradford",
            "BEN": "Brent",
            "BGE": "Bridgend",
            "BNH": "Brighton and Hove",
            "BST": "Bristol, City of",
            "BRY": "Bromley",
            "BUC": "Buckingham",
            "BKM": "Buckinghamshire",
            "BUR": "Bury",
            "CAY": "Caerphilly",
            "CLD": "Calderdale",
            "CAM": "Cambridgeshire",
            "CMD": "Camden",
            "CRF": "Cardiff",
            "CMN": "Carmarthenshire",
            "CKF": "Carrickfergus",
            "CSR": "Castlereagh",
            "CGN": "Ceredigion",
            "CHS": "Cheshire",
            "CHE": "Cheshire East",
            "CWC": "Cheshire West and Chester",
            "CLK": "Clackmannanshire",
            "CLR": "Coleraine",
            "CWY": "Conwy",
            "CKT": "Cookstown",
            "CON": "Cornwall",
            "COV": "Coventry",
            "CGV": "Craigavon",
            "CRY": "Croydon",
            "CMA": "Cumbria",
            "DAL": "Darlington",
            "DEN": "DenbighshireD",
            "DER": "Derby",
            "DBY": "Derbyshire",
            "DRY": "Derry",
            "DEV": "Devon",
            "DNC": "Doncaster",
            "DOR": "Dorset",
            "DOW": "Down",
            "DUD": "Dudley",
            "DGY": "Dumfries and Galloway",
            "DND": "Dundee City",
            "DGN": "Dungannon",
            "DUR": "Durham",
            "EAL": "Ealing",
            "EAY": "East Ayrshire",
            "EDU": "East Dunbartonshire",
            "ELN": "East Lothian",
            "ERW": "East Renfrewshire",
            "ERY": "East Riding of Yorkshire",
            "ESX": "East Sussex",
            "EDH": "Edinburgh, City of",
            "ELS": "Eilean Siar",
            "ENF": "Enfield",
            "ESS": "Essex",
            "FAL": "Falkirk",
            "FER": "Fermanagh",
            "FIF": "Fife",
            "FLN": "Flintshire",
            "GAT": "Gateshead",
            "GLG": "Glasgow City",
            "GLS": "Gloucestershire",
            "GLO": "Greater London",
            "GRE": "Greenwich",
            "GGY": "Guernsey",
            "GWN": "Gwynedd",
            "HCK": "Hackney",
            "HAL": "Halton",
            "HMF": "Hammersmith and Fulham",
            "HAM": "Hampshire",
            "HRY": "Haringey",
            "HRW": "Harrow",
            "HPL": "Hartlepool",
            "HAV": "Havering",
            "HEF": "Herefordshire, County of",
            "HTF": "Hertfordshire",
            "HRT": "Hertfordshire",
            "HLD": "Highland",
            "HIL": "Hillingdon",
            "HNS": "Hounslow",
            "IVC": "Inverclyde",
            "AGY": "Isle of Anglesey",
            "IOM": "Isle of Man",
            "IOW": "Isle of Wight",
            "IOS": "Isles of Scilly",
            "ISL": "Islington",
            "JEY": "Jersey",
            "KEC": "Kensington and Chelsea",
            "KEN": "Kent",
            "KHL": "Kingston upon Hull, City of",
            "KTT": "Kingston upon Thames",
            "KIR": "Kirklees",
            "KWL": "Knowsley",
            "LBH": "Lambeth",
            "LAN": "Lancashire",
            "LRN": "Larne",
            "LDS": "Leeds",
            "LCE": "Leicester",
            "LEC": "Leicestershire",
            "LEW": "Lewisham",
            "LMV": "Limavady",
            "LIN": "Lincolnshire",
            "LSB": "Lisburn",
            "LIV": "Liverpool",
            "LND": "London, City of",
            "LUT": "Luton",
            "MFT": "Magherafelt",
            "MAN": "Manchester",
            "MDW": "Medway",
            "MTY": "Merthyr Tydfil",
            "MRT": "Merton",
            "MDB": "Middlesbrough",
            "MLN": "Midlothian",
            "MIK": "Milton Keynes",
            "MON": "Monmouthshire",
            "MRY": "Moray",
            "MYL": "Moyle",
            "NTL": "Neath Port Talbot",
            "NET": "Newcastle upon Tyne",
            "NWM": "Newham",
            "NWP": "Newport",
            "NYM": "Newry and Mourne",
            "NTA": "Newtownabbey",
            "NFK": "Norfolk",
            "NAY": "North Ayrshire",
            "NDN": "North Down",
            "NEL": "North East Lincolnshire",
            "NLK": "North Lanarkshire",
            "NLN": "North Lincolnshire",
            "NSM": "North Somerset",
            "NTY": "North Tyneside",
            "NYK": "North Yorkshire",
            "NTH": "Northamptonshire",
            "NBL": "Northumberland",
            "NGM": "Nottingham",
            "NTT": "Nottinghamshire",
            "OLD": "Oldham",
            "OMH": "Omagh",
            "ORK": "Orkney Islands",
            "OXF": "Oxfordshire",
            "PEM": "Pembrokeshire",
            "PKN": "Perth and Kinross",
            "PTE": "Peterborough",
            "PLY": "Plymouth",
            "POL": "Poole",
            "POR": "Portsmouth",
            "POW": "Powys",
            "RDG": "Reading",
            "RDB": "Redbridge",
            "RCC": "Redcar and Cleveland",
            "RFW": "Renfrewshire",
            "RCT": "Rhondda, Cynon, Ta",
            "RIC": "Richmond upon Thames",
            "RCH": "Rochdale",
            "ROT": "Rotherham",
            "RUT": "Rutland",
            "SLF": "Salford",
            "SAW": "Sandwell",
            "SCB": "Scottish Borders, The",
            "SFT": "Sefton",
            "SHF": "Sheffield",
            "ZET": "Shetland Islands",
            "SHR": "Shropshire",
            "SLG": "Slough",
            "SOL": "Solihull",
            "SOM": "Somerset",
            "SAY": "South Ayrshire",
            "SGC": "South Gloucestershire",
            "SLK": "South Lanarkshire",
            "STY": "South Tyneside",
            "SYK": "South Yorkshire",
            "STH": "Southampton",
            "SOS": "Southend-on-Sea",
            "SWK": "Southwark",
            "SHN": "St. Helens",
            "STS": "Staffordshire",
            "STG": "Stirling",
            "SKP": "Stockport",
            "STT": "Stockton-on-Tees",
            "STE": "Stoke-on-Trent",
            "STB": "Strabane",
            "SFK": "Suffolk",
            "SND": "Sunderland",
            "SRY": "Surrey",
            "STN": "Sutton",
            "SWA": "Swansea",
            "SWD": "Swindon",
            "TAM": "Tameside",
            "TFW": "Telford and Wrekin",
            "THR": "Thurrock",
            "TOB": "Torbay",
            "TOF": "Torfaen",
            "TWH": "Tower Hamlets",
            "TRF": "Trafford",
            "VGL": "Vale of Glamorgan, T",
            "WKF": "Wakefield",
            "WLL": "Walsall",
            "WFT": "Waltham Forest",
            "WND": "Wandsworth",
            "WRT": "Warrington",
            "WAR": "Warwickshire",
            "WBK": "West Berkshire",
            "WDU": "West Dunbartonshire",
            "WLN": "West Lothian",
            "WMD": "West Midlands",
            "WSX": "West Sussex",
            "WYK": "West Yorkshire",
            "WSM": "Westminster",
            "WGN": "Wigan",
            "WIL": "Wiltshire",
            "WNM": "Windsor and Maidenhead",
            "WRL": "Wirral",
            "WOK": "Wokingham",
            "WLV": "Wolverhampton",
            "WOC": "Worcester",
            "WOR": "Worcestershire",
            "WRX": "Wrexham",
            "YOR": "York"
        },
        "CA": {
            "AB": "Alberta",
            "BC": "British Columbia",
            "MB": "Manitoba",
            "NB": "New Brunswick",
            "NL": "Newfoundland and Labrador",
            "NT": "Northwest Territories",
            "NS": "Nova Scotia",
            "NU": "Nunavut",
            "ON": "Ontario",
            "PE": "Prince Edward Island",
            "QC": "Quebec",
            "SK": "Saskatchewan",
            "YT": "Yukon"
        },
        "AU": {
            "ACT": "Australian Capital Territory",
            "NSW": "New South Wales",
            "NT": "Northern Territory",
            "QLD": "Queensland",
            "SA": "South Australia",
            "TAS": "Tasmania",
            "VIC": "Victoria",
            "WA": "Western Australia"
        },
        "US": {
            "AL": "Alabama",
            "AK": "Alaska",
            "AZ": "Arizona",
            "AR": "Arkansas",
            "CA": "California",
            "CO": "Colorado",
            "CT": "Connecticut",
            "DE": "Delaware",
            "DC": "District of Columbia",
            "FL": "Florida",
            "GA": "Georgia",
            "HI": "Hawaii",
            "ID": "Idaho",
            "IL": "Illinois",
            "IN": "Indiana",
            "IA": "Iowa",
            "KS": "Kansas",
            "KY": "Kentucky",
            "LA": "Louisiana",
            "ME": "Maine",
            "MD": "Maryland",
            "MA": "Massachusetts",
            "MI": "Michigan",
            "MN": "Minnesota",
            "MS": "Mississippi",
            "MO": "Missouri",
            "MT": "Montana",
            "NE": "Nebraska",
            "NV": "Nevada",
            "NH": "New Hampshire",
            "NJ": "New Jersey",
            "NM": "New Mexico",
            "NY": "New York",
            "NC": "North Carolina",
            "ND": "North Dakota",
            "OH": "Ohio",
            "OK": "Oklahoma",
            "OR": "Oregon",
            "PA": "Pennsylvania",
            "RI": "Rhode Island",
            "SC": "South Carolina",
            "SD": "South Dakota",
            "TN": "Tennessee",
            "TX": "Texas",
            "UT": "Utah",
            "VT": "Vermont",
            "VA": "Virginia",
            "WA": "Washington",
            "WV": "West Virginia",
            "WI": "Wisconsin",
            "WY": "Wyoming",
            "AS": "American Samoa",
            "FM": "Federated States of Micronesia",
            "GU": "Guam",
            "MP": "Northern Mariana Islands",
            "PR": "Puerto Rico",
            "MH": "Republic of Marshall Islands",
            "VI": "Virgin Islands of the U.S.",
            "AE": "Armed Forces Middle East",
            "AA": "Armed Forces Americas",
            "AP": "Armed Forces Pacific"
        }
    },
    "currencySymbol": "$",
    "shipOptions": [],
    "coupons": [],
    "products": [],
    "webPages": {
        "catalogPage": {
            "disableBack": 0,
            "url": "https:\/\/secure.product_domain.com\/product_name\/catalog.php"
        },
        "checkoutPage": {
            "disableBack": 0,
            "url": "https:\/\/secure.product_domain.com\/product_name\/checkout.php",
            "autoImportLead": 1,
            "productId": null,
            "requireSig": 0,
            "sigType": 0,
            "cardinalAuth": 0
        },
        "thankyouPage": {
            "disableBack": 0,
            "url": "https:\/\/secure.product_domain.com\/product_name\/thankyou.php",
            "createAccountDialog": 0,
            "reorderUrl": null,
            "allowReorder": 0
        },
        "upsellPage1": {
            "disableBack": 1,
            "url": "https:\/\/secure.product_domain.com\/product_name\/upsell1.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 148,
            "replaceProductId": null
        },
        "upsellPage2": {
            "disableBack": 1,
            "url": "https:\/\/secure.product_domain.com\/product_name\/upsell2.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 143,
            "replaceProductId": null
        },
        "upsellPage3": {
            "disableBack": 1,
            "url": "https:\/\/secure.product_domain.com\/product_name\/upsell3.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 149,
            "replaceProductId": null
        },
        "upsellPage4": {
            "disableBack": 1,
            "url": "https:\/\/secure.product_domain.com\/product_name\/upsell4.php",
            "createAccountDialog": 0,
            "requirePayInfo": 0,
            "productId": 153,
            "replaceProductId": null
        },
        "productDetails": {
            "url": "product-details.php"
        }
    },
    "landerType": "CART",
    "googleTrackingId": null,
    "enableFraudPlugin": 0,
    "autoTax": 0,
    "taxServiceId": null,
    "companyName": "click_gamma_inc",
    "offers": {
        "137": {
            "productId": 137,
            "name": "(13) 1 Product Name ($89 \/each)",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imagePath": "product.png",
            "imageId": null,
            "price": "89.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "138": {
            "productId": 138,
            "name": "(17) Buy 3 Product Name, GET 2 FREE ($267 \/t",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "267.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "139": {
            "productId": 139,
            "name": "(18) Buy 2 Product Name, GET 1 FREE ($177 \/t",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "177.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "140": {
            "productId": 140,
            "name": "(14) 2 Product Name ($70 \/each)",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "70.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "141": {
            "productId": 141,
            "name": "(15) 4 Product Name ($55 \/each)",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "220.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "142": {
            "productId": 142,
            "name": "(16) 10 Product Name ($46 \/each)",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "460.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        }
    },
    "upsells": {
        "143": {
            "productId": 143,
            "name": "(19) Product Name - Upsell - USB Adapter x 1",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "9.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "144": {
            "productId": 144,
            "name": "(20) Product Name - Upsell - USB Adapter x 2",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "18.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "145": {
            "productId": 145,
            "name": "(21) Product Name - Upsell - USB Adapter x 3",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "27.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "146": {
            "productId": 146,
            "name": "(22) Product Name - Upsell - USB Adapter x 4",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "36.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "147": {
            "productId": 147,
            "name": "Additional 1 Product Name",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "89.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "148": {
            "productId": 148,
            "name": "(31) Product Name Lifetime Protection",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "14.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "149": {
            "productId": 149,
            "name": "(23) Product Name - Upsell - LED Mood Light ",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "14.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "150": {
            "productId": 150,
            "name": "(24) Product Name - Upsell - LED Mood Light ",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "151": {
            "productId": 151,
            "name": "(25) Product Name - Upsell - LED Mood Light ",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "152": {
            "productId": 152,
            "name": "(26) Product Name - Upsell - LED Mood Light ",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "153": {
            "productId": 153,
            "name": "(27) Product Name - Upsell - Car Adapter x 1",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "154": {
            "productId": 154,
            "name": "(20) Product Name - Upsell - USB Adapter x 2",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "155": {
            "productId": 155,
            "name": "(29) Product Name - Upsell - Car Adapter x 3",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        },
        "156": {
            "productId": 156,
            "name": "(30) Product Name - Upsell - Car Adapter x 4",
            "description": "*No description available",
            "imagePath": "https://product_name/img/product.png",
            "imageId": null,
            "price": "28.00",
            "shipPrice": "0.00",
            "category": "E-Commerce"
        }
    },
    "shipProfiles": [],
    "continents": {
        "AU": "AU",
        "CA": "NA",
        "GB": "EU",
        "US": "NA"
    }
}';