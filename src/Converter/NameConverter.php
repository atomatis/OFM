<?php

declare(strict_types=1);

namespace Atomatis\OFM\Converter;

use Atomatis\OFM\Parameter;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class NameConverter
{
    public static function convertFromData(array $data, string $property, Parameter $parameter): array
    {
        foreach ($data as $name => $value) {
            if ($name === $parameter->getName()) {
                $data[$property] = $value;
                unset($data[$name]);
                break;
            }
        }

        return $data;
    }

    public static function convertFromObject(array $data, string $property, Parameter $parameter): array
    {
        foreach ($data as $name => $value) {
            if ($name === $property) {
                $data[$parameter->getName()] = $value;
                unset($data[$name]);
                break;
            }
        }

        return $data;
    }
}
