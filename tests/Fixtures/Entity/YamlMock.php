<?php

declare(strict_types=1);

namespace Tests\Fixtures\Entity;

use Atomatis\OFM as OFM;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
#[OFM\Entity(OFM\Entity::TYPE_YAML)]
final class YamlMock
{
    #[OFM\Parameter]
    private string $simpleField;

    #[OFM\Parameter]
    private array $arrayField;

    #[OFM\Parameter]
    private array $objectField;

    public function getSimpleField(): string
    {
        return $this->simpleField;
    }

    public function setSimpleField(string $simpleField): self
    {
        $this->simpleField = $simpleField;

        return $this;
    }

    public function getArrayField(): array
    {
        return $this->arrayField;
    }

    public function setArrayField(array $arrayField): self
    {
        $this->arrayField = $arrayField;

        return $this;
    }

    public function getObjectField(): array
    {
        return $this->objectField;
    }

    public function setObjectField(array $objectField): self
    {
        $this->objectField = $objectField;

        return $this;
    }
}
