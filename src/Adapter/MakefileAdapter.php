<?php

declare(strict_types=1);

namespace Atomatis\OFM\Adapter;

use Atomatis\OFM\CaseHelper;
use Atomatis\OFM\ReflectionHelper;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class MakefileAdapter implements AdapterInterface
{
    const TYPE_ENV = 'env';
    const TYPE_INCLUDE = 'include';

    public function hydrate(object $entityObject, string $path): ?object
    {
        throw new \Exception('TODO: code this if needed.');
    }

    public function save(object $entityObject, string $path): void
    {
        $f = fopen($path, 'w+');

        foreach (ReflectionHelper::getEntityParameters(new \ReflectionClass($entityObject)) as $name => $parameter) {
            $getter = 'get'.$name;
            $value =  $entityObject->$getter();

            if (null === $value) {continue;}

            switch ($parameter->getType()) {
                case MakefileAdapter::TYPE_ENV; fputs($f, $this->getEnv($name, $value));break;
                case MakefileAdapter::TYPE_INCLUDE; fputs($f, $this->getInclude($value));break;
            }
        }

        fclose($f);
    }

    private function getEnv($name, $value): string
    {
        $envName = CaseHelper::convertLowerCamelCaseToDotEnv($name);

        return sprintf('%s=%s%s', $envName, $value, PHP_EOL);
    }

    private function getInclude($value): string
    {
        return sprintf('include %s%s',  $value, PHP_EOL);
    }
}
