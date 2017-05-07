# Magento2 Product Sale
Special price product indexer

## Install with Composer as you go

1. Go to Magento2 root folder

2. Enter following commands to install module:

    ```bash
    composer require faonni/module-product-sale
    ```
   Wait while dependencies are updated.

3. Enter following commands to enable module:

    ```bash
	php bin/magento setup:upgrade
	php bin/magento setup:static-content:deploy
