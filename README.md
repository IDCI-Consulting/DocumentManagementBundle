DocumentManagementBundle
========================

DocumentManagementBundle is a Symfony bundle to manage documents like pdf generation, storage, DMS.

Installation
------------

Add dependencies in your `composer.json` file:
```json
"require": {
    ...
    "idci/document-management-bundle": "~1.0"
},
```

Install these new dependencies in your application using composer:
```sh
$ make composer-update
```

Register needed bundles in your application kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
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

Import the bundle routing:
```yml
# app/config/routng.yml

idci_document_api:
    resource: "@IDCIDocumentManagementBundle/Resources/config/routing.yml"
    prefix: /api
```

That's it, you are ready to use it.

Tests
-----

Install bundle dependencies:
```sh
$ make composer-update
```

To execute unit tests:
```sh
$ make phpunit
```

To execute functional tests:
```sh
$ make phpunit-functional
```
