# Shopify + Konnektive Integration Hack
- - -

### Bypass Shopfiy and checkout with Konnektive, no APIs needed


![Shopify Konnektive](http://paulpierre.com/img/shopifykonnektive.jpg)

built by [http://paulpierre.com](http://paulpierre.com) in 2018

My partner and I did not like the limitations of Shopifys merchant processing so I developed an integration that allows you to leverage Shopify's front-end but securely complete the purchase on Konnektive which allows you to charge in any currency with no problem or let you use any merchant processor. 

### Checkout looks just like Shopify
![Shopify Konnektive](https://i.imgur.com/raJUl6Z.png)

### Customize your own upsell w/ Konnektive
![Shopify Konnektive](https://i.imgur.com/Ny8lYru.png)


------------
How it works
------------

* Shopify retains your cart information accessible by any script originating from your domain in the form
of your domain.com/cart.json
* You create a campaign within Konnektive, including all upsells, etc.
* Create a landing page within Konnektive, download the accompanything zip file once this is setup
* Extract the corresponding files in the /product_name folder, the path to your new funnel will be secure.mydomain.com/product_name
* Simply setup secure.mydomain.com as a CNAME pointing to this server, and the normal www.mydomain.com or mydomain.com will still point to shopify
* Intercept your checkout button by inserting javascript in your product page via event.preventDefault() and redirect the user to secure.mydomain.com. The template provided looks just like Shopify's checkout flow.
* MAKE SURE you copy product_name/resources/js/checkout.js (this is not included in Konnektive, this is my custom script)
* Modify Checkout.js product_map variable so it matches Shopify product IDs with Konnektive IDs 
* Boom.


![Built with](https://i.imgur.com/AgWbVwT.png) 


### MIT License
- - -

Copyright (c) 2019 Paul Pierre

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in allcopies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

