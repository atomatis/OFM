<?php

declare(strict_types=1);

namespace Atomatis\OFM\Adapter;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
interface AdapterInterface
{
    public function hydrate(object $entityObject, string $path): ?object;
    public function save(object $entityObject, string $path): void;
}
