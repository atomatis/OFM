<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Entity
{
    const TYPE_DOT_ENV = 'dot-env';
    const TYPE_YAML = 'yaml';
    const TYPE_MAKEFILE = 'makefile';

    public function __construct(
        private string $type,
        private ?string $path = null,
    ){}

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
