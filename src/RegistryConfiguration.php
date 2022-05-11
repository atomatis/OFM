<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class RegistryConfiguration
{
    private ?string $path;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
