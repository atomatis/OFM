<?php

declare(strict_types=1);

namespace Tests;

use Atomatis\OFM\EntityManager;
use Atomatis\OFM\Registry;
use Atomatis\OFM\RegistryConfiguration;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Entity\YamlMock;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class FlushFileTest extends TestCase
{
    public function testFlushYamlFile(): void
    {
        $tmpMock = tmpfile();
        fwrite($tmpMock, file_get_contents(__DIR__.'/Fixtures/assets/mock.yaml'));
        $path = stream_get_meta_data($tmpMock)['uri'];

        $registry = new Registry();
        $registry->addFile(YamlMock::class, (new RegistryConfiguration())->setPath($path));
        $entityManager = new EntityManager($registry);

        /** @var YamlMock $yamlMock */
        $yamlMock = $entityManager->load(YamlMock::class);
        $yamlMock->setSimpleField('modified field');
        $entityManager->flush($yamlMock);

        self::assertEquals(file_get_contents(__DIR__.'/Fixtures/assets/flushed_mock.yaml'), file_get_contents($path));
    }
}
