<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class Registry
{
    private array $files = [];

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getConfiguration(string $className): ?RegistryConfiguration
    {
        return $this->files[$className] ?? null;
    }

    public function addFile(string $className, RegistryConfiguration $dotEnvConfiguration): self
    {
        $this->files[$className] = $dotEnvConfiguration;

        return $this;
    }
}
