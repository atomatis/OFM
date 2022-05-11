# Object File Mapper

Object file manipulator inspired by doctrine/ORM package

Warning. I write this for personal use. Documentation is really poor. 
If you are interested for use this package, contact me at alexandre.tomatis@gmail.com for get better documentation.

## Installation

```shell 
composer req atomatis/OFM
```

## Usage

**Create entity**

Example with yaml file
```php 
<?php

declare(strict_types=1);

namespace App\Entity;

use Atomatis\OFM as OFM;

#[OFM\Entity(OFM\Entity::TYPE_YAML)]
final class Configuration
{
    #[OFM\Parameter]
    private ?string $foo;

    #[OFM\Parameter]
    private ?array $bar;
    
    ...
    
    // getter/setter
    
    ...
```

**Configuration**

```php 
// init registry with Entity file path.
$registry = new Registry();
$registry->addFile(Configuration::class, (new RegistryConfiguration())->setPath('my/file/path/configuration.yaml'));
$entityManager = new EntityManager($registry);
```

**Load file**

```yaml 
# my/file/path/configuration.yaml
foo: 'hello here'
```

```php 
$configuration = $entityManager->load(Configuration::class);

$configuration->getFoo(); // return 'hello here'
$configuration->getBar(); // return null
```

**Flush file**

```yaml 
# my/file/path/configuration.yaml
foo: 'hello here'
```

```php 
$configuration = $entityManager->load(Configuration::class);
$configuration->setFoo('see you later');
$entityManager->flush($configuration);
```

```yaml 
# my/file/path/configuration.yaml
foo: 'see you later'
```
