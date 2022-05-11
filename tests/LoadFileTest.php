<?php

declare(strict_types=1);

namespace Tests;

use Atomatis\OFM\EntityManager;
use Atomatis\OFM\Registry;
use Atomatis\OFM\RegistryConfiguration;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Entity\YamlMock;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class LoadFileTest extends TestCase
{
    public function testLoadYamlFile(): void
    {
        $registry = new Registry();
        $registry->addFile(YamlMock::class, (new RegistryConfiguration())->setPath(__DIR__.'/Fixtures/assets/mock.yaml'));

        $entityManager = new EntityManager($registry);
        /** @var YamlMock $yamlMock */
        $yamlMock = $entityManager->load(YamlMock::class);

        self::assertEquals('a text value', $yamlMock->getSimpleField());
        self::assertEquals(['value1', 'value2'], $yamlMock->getArrayField());
        self::assertEquals([
            'property1' => 'a text value',
            'property2' => ['value1', 'value2'],
            'property3' => [
                'property1' => 'a text value',
            ]
        ], $yamlMock->getObjectField());
    }
}
