<?php

declare(strict_types=1);

namespace Atomatis\OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Parameter
{
    public function __construct(
        private ?string $type = null,
        private ?string $name = null,
    ){}

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
