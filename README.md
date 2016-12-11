# Doctrine Data Manager (not ready for production yet!)
Easily dumps data from DB or loads to DB across Doctrine Entities
This version do only YAML dump and load, but feel free to contribute more, if needed

`Tested only with symfony3+ and php7!`

### Installation
```console
$ composer require mikopet/doctrine-data-manager-bundle --dev
```
And register it in AppKernel
```php
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            // ...
            $bundles[] = new Mikopet\DoctrineDataManagerBundle\DoctrineDataManagerBundle();
            // ...
        }
```
Then we need only configure the commands:
```yaml
# app/config/config.yml
imports:
    - { resource: "@DoctrineDataManagerBundle/Resources/config/services.yml" }
```

### Use
After installing the package you got two commands (dump/load).
With these commands you can do exactly what the name means.
Just run it without arguments, or add the entity name what you want to manage

```console
$ php bin/console doctrine:data:dump
$ php bin/console doctrine:data:load User
```

Pay attention to the names! This package uses conventional entity names/setters/getters!


### Donation
If you like my software, and want to say thanks, just donate a few coin for me on this bitcoin address: `3J3madHEPfqN2jcZN3A5iYMFMkktETkbLX`

Thank you!