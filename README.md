# SEO Suite extension for Magento 2
The core purpose of this extension is to provide on-page SEO functionality that are missing in original Magento packages.

## Features
- Use hreflang to tell search engines about the variations of your content;

## Compatibility
- Open Source >= 2.4.0
- Commerce On Prem (EE) >= 2.4.0
- Commerce On Cloud (ECE) >= 2.4.0

## Installation
Using composer

```
composer require softcommerceltd/module-url-rewrite-generator
```

## Post Installation

```sh
# Enable the module
bin/magento module:enable SoftCommerce_UrlRewriteGenerator
```

In production mode:
```sh
# compile & generate static files
bin/magento deploy:mode:set production
```

In development mode:
```
bin/magento setup:di:compile
```

## Usage

### Generate URL rewrites for Category

Command options:

```
bin/magento url_rewrite:category:generate [id|-i]
```

Example:

```sh
# Regenerate URL rewrites for all categories:
bin/magento url_rewrite:category:generate

# Generate URL rewrites for particular categories with IDs 25 & 26:
bin/magento url_rewrite:category:generate -i 25,26
```

### Generate URL rewrites for Product

> Please note, products with visibility *__Not Visible Individually__* [id: 1] are excluded from URL rewrite generation.

Command options:

``
bin/magento url_rewrite:product:generate [id|-i]
``

```sh
# Regenerate URL rewrites for all products:
bin/magento url_rewrite:product:generate

# Generate URL rewrites for particular products with IDs 25 & 26:
bin/magento url_rewrite:product:generate -i 25,26
```

### Delete URL rewrites

Command options:

``
bin/magento url_rewrite:delete [entity|-e || store|-s]
``

```sh
# Delete URL rewrites for entity: product with store IDs: 1 and 2
bin/magento url_rewrite:delete -e product -s 1,2

# Delete URL rewrites for product and category entities with store IDs 1, 2 and 3
bin/magento url_rewrite:delete -e product,category -s 1,2,3
```

## Support
Soft Commerce Ltd <br />
support@softcommerce.io

## License
Each source file included in this package is licensed under OSL 3.0.

[Open Software License (OSL 3.0)](https://opensource.org/licenses/osl-3.0.php).
Please see `LICENSE.txt` for full details of the OSL 3.0 license.

## Thanks for dropping by

<p align="center">
    <a href="https://softcommerce.co.uk" target="_blank">
        <img src="https://softcommerce.co.uk/pub/media/banner/logo.svg" width="200" alt="Soft Commerce Ltd" />
    </a>
    <br />
    <a href="https://softcommerce.co.uk/" target="_blank">https://softcommerce.io/</a>
</p>
