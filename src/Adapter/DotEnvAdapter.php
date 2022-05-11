<?php

declare(strict_types=1);

namespace Atomatis\OFM\Adapter;

use Atomatis\OFM\CaseHelper;
use Atomatis\OFM\ReflectionHelper;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class DotEnvAdapter implements AdapterInterface
{
    public function hydrate(object $entityObject, string $path): ?object
    {
        $f = fopen($path, 'r');

        while ($line = fgets($f)) {
            if (PHP_EOL === $line) {continue;}

            // Clean end of line
            $line = str_replace(PHP_EOL, '', $line);
            $line = explode('=', $line);
            $property = CaseHelper::convertDotEnvToLowerCamelCase($line[0]);
            $setter = 'set'.ucfirst($property);
            $properties = ReflectionHelper::getEntityParameters(new \ReflectionClass($entityObject));

            if (key_exists($property, $properties)) {
                $entityObject->$setter($line[1]);
            }
        }

        fclose($f);

        return $entityObject;
    }

    public function save(object $entityObject, string $path): void
    {
        $f = fopen($path, 'w+');

        foreach (ReflectionHelper::getEntityParameters(new \ReflectionClass($entityObject)) as $name => $property) {
            $EnvName = CaseHelper::convertLowerCamelCaseToDotEnv($name);
            $getter = 'get'.$name;

            fputs($f, sprintf('%s=%s%s', $EnvName, $entityObject->$getter(), PHP_EOL));
        }

        fclose($f);
    }
}
