# Magento Invoice PDF Generator

[![Build Status](https://travis-ci.org/EaDesgin/magento2-pdf-generator2.svg?branch=master)](https://travis-ci.org/EaDesgin/magento2-pdf-generator2)

**Magento 2 Invoice PDF Generator** -  helps you to customize the pdf templates for Magento 2. 
If you have an enabled template and a default template for the store you need your template the system will print the pdf template. 

# How to use the module 
Add a new template from the "Add new template" button. This will prompt you with a set of fields. 

* Enable template - you need to enable the template in order to use it;
* Default template - make the template as default for the current store;
* Template name - this is for your own information as well as the template description;
* Template for website - here you select the website you need the template for;
* The template body, header and footer is where you can add the html that will be transformed into the PDF body;
* The template CSS filed allows you to create styles for the html like "h1 {color:red;} h2 {color:blue}", do not use the style tag, it is not need. In the body you can also specify html like in the email templates;
* The template settings are used to shape the template as you need. The "Template file name" can be made from variables as long as they are ok for file naming {{var invoice.increment_id}}-{{var invoice.id}}-file-invoice. The "Template paper orientation" is used to set the pdf as landscape or portrait.  If you chose to use the custom format the "Custom height" and "Custom width" in millimeters will be used. The paper orientation and the "Template paper format" will be ignored in this case. If the template has standard format the "Template paper format" will allow you to set the paper in a few formats (A4,A5,A3,Letter and Legal). The other settings are the margins (in millimeters) for the top, right, left and bottom. If the header or footer overlaps over your body increase the top and bottom margins to fix this. 

The extension will allow you to harness all the power of the email template system and more. You can add phtml files to your template for very advanced configurations `({Error in template processing}` and `{Error in template processing})`.  You can add your own item processing layout so you can output taxes item prices as you want `({{layout area="frontend" handle="sales_email_order_invoice_items" invoice=$invoice order=$order}})`.

You can also localize your template using the trans directive. 
``` php
{{trans "Thank you for your order from %store_name." store_name=$store.getFrontendName()}}{{trans "Once your package ships we will send you a tracking number."}}
```

Using the extension you are able to change the invoice PDF as you desire. The PDF Generator has multiple features as follows:

* change the Magento invoice PDF to meet your needs;
* add custom CSS to your template to further personalize the PDF;
* add templates for each store with different design and features;
* change the file name of the PDF file using variables;
* you can send the invoice as PDF attachment to the invoice mail;
* you can disable enable the PDF from the system configuration area.

For the variable system you can read the [Magento domentation here](http://devdocs.magento.com/guides/v2.0/frontend-dev-guide/templates/template-email.html). 
We use the exact same system to generate the variables.

# Supported Versions

* Magento 2.1.*
* Magento 2.2.*

# Installation

You can install the module via composer or manually by adding it to the app/code directory. The module is available on [packagist.org](https://packagist.org/packages/eadesignro/module-pdfgenerator)

Via composer:

``` bash
composer config repositories.magento2-pdf-generator2 git git@github.com:EaDesgin/magento2-pdf-generator2.git;
```

``` bash
composer require eadesignro/module-pdfgenerator;
```

``` bash 
php bin/magento setup:upgrade;
```

# Requirements

* ~5.6.0|7.0.2|7.0.4|~7.0.6|~7.1.0
* https://github.com/mpdf/mpdf - the library for pdf generation.

# Video install and use

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/-O4qhzL9_SM/0.jpg)](https://www.youtube.com/watch?v=-O4qhzL9_SM)
