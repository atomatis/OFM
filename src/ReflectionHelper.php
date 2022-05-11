<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class ReflectionHelper
{
    public static function isEntity(\ReflectionClass $reflectionClass): bool
    {
        foreach ($reflectionClass->getAttributes() as $attribute) {
            if ($attribute->getName() === Entity::class) {
                return true;
            }
        }

        return false;
    }

    public static function isParameter(\ReflectionProperty $reflectionProperty): bool
    {
        foreach ($reflectionProperty->getAttributes() as $attribute) {
            if ($attribute->getName() === Parameter::class) {
                return true;
            }
        }

        return false;
    }

    public static function getEntityAttribute(\ReflectionClass $reflectionClass): ?Entity
    {
        foreach ($reflectionClass->getAttributes() as $attribute) {
            if ($attribute->getName() === Entity::class) {
                return $attribute->newInstance();
            }
        }

        return null;
    }

    /** @return Parameter[] */
    public static function getEntityParameters(\ReflectionClass $reflectionClass): array
    {
        $entityProperties = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {

            foreach ($reflectionProperty->getAttributes() as $reflectionAttribute) {
                if ($reflectionAttribute->getName() === Parameter::class) {
                    $entityProperties[$reflectionProperty->getName()] = $reflectionAttribute->newInstance();
                }
            }
        }

        return $entityProperties;
    }
}
