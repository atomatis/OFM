<?php

declare(strict_types=1);

namespace Atomatis\OFM;

use Atomatis\OFM\Adapter\AdapterInterface;
use Atomatis\OFM\Adapter\DotEnvAdapter;
use Atomatis\OFM\Adapter\MakefileAdapter;
use Atomatis\OFM\Adapter\YamlAdapter;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class EntityManager
{
    private Registry $registry;

    /** @var AdapterInterface[]  */
    private array $adapters;

    public function __construct(Registry $registry = null) {
        $this->registry = null === $registry ? new Registry() : $registry;

        $this->adapters = [
            Entity::TYPE_DOT_ENV => new DotEnvAdapter(),
            Entity::TYPE_YAML => new YamlAdapter(),
            Entity::TYPE_MAKEFILE => new MakefileAdapter(),
        ];
    }

    public function getPath($className): string
    {
        return $this->registry->getConfiguration($className)->getPath();
    }

    public function load(string $className): ?object
    {
        $reflection = new \ReflectionClass($className);

        if (!ReflectionHelper::isEntity($reflection)) {
            throw new \Exception($className.' is not a OFM\Entity. Maybe you forget add OFM\Entity attribute in your class?');
        }

        $entityAttribute = ReflectionHelper::getEntityAttribute($reflection);
        $entityObject = new $className();
        $adapter = $this->adapters[$entityAttribute->getType()];
        $path = $this->registry->getConfiguration($className)->getPath();

        if (!file_exists($path)) {
            file_put_contents($path, '');
        }

        return $adapter->hydrate($entityObject, $path);
    }

    public function flush(object $entityObject): void
    {
        $reflection = new \ReflectionClass($entityObject);

        if (!ReflectionHelper::isEntity($reflection)) {
            throw new \Exception($reflection->getName().' is not a OFM\Entity. Maybe you forget add OFM\Entity attribute in your class?');
        }

        $entityAttribute = ReflectionHelper::getEntityAttribute($reflection);
        $adapter = $this->adapters[$entityAttribute->getType()];
        $path = $this->registry->getConfiguration($reflection->getName())->getPath();
        $adapter->save($entityObject, $path);
    }
}
