<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class CaseHelper
{
    public static function convertDotEnvToLowerCamelCase(string $value): string
    {
        $lowerCamelCase = '';

        $value = strtolower($value);
        $value = explode('_', $value);

        for ($i=0;$i < count($value);$i++) {


            $lowerCamelCase .= 0 === $i ? $value[$i] : ucfirst($value[$i]);
        }

        return $lowerCamelCase;
    }

    public static function convertLowerCamelCaseToDotEnv(string $value): string
    {
        $dotEnv = '';
        $values = preg_split('/(?=[A-Z])/',$value);

        foreach ($values as $value) {
            $dotEnv .= strtoupper($value).'_';
        }

        return substr($dotEnv, 0, -1);
    }
}
