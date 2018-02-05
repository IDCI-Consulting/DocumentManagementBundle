DocumentManagementBundle
========================

DocumentManagementBundle is a Symfony bundle to manage documents like pdf generation, storage, DMS.

Installation
------------

Add dependencies in your `composer.json` file:
```json
"require": {
    ...
    "idci/document-management-bundle": "dev-master"
},
```

Install these new dependencies in your application using composer:
```sh
$ php composer.phar update
```

Register needed bundles in your application kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IDCI\Bundle\DocumentManagementBundle\IDCIDocumentManagementBundle(),
    );
}
```

Import the bundle configuration:
```yml
# app/config/config.yml

imports:
    - { resource: @IDCIDocumentManagementBundle/Resources/config/config.yml }
```

That's it, you are ready to use it.

