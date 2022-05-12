<?php

declare(strict_types=1);

namespace Atomatis\OFM\Adapter;

use Atomatis\OFM\ReflectionHelper;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

/** @author Alexandre Tomatis <alexandre.tomatis@gmail.com> */
final class YamlAdapter implements AdapterInterface
{
    private ObjectNormalizer $normalizer;
    private Serializer $serializer;

    public function __construct()
    {
        $unwrapping = new UnwrappingDenormalizer();
        $arrayNormalizer = new ArrayDenormalizer();
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfoExtractor = new PropertyInfoExtractor(
            listExtractors: [
                new SerializerExtractor(new ClassMetadataFactory(new LoaderChain([new AnnotationLoader()]))),
                $reflectionExtractor,
            ],
            typeExtractors: [$reflectionExtractor],
            accessExtractors: [$reflectionExtractor],
            initializableExtractors: [$reflectionExtractor]
        );

        $this->normalizer = new ObjectNormalizer(propertyTypeExtractor: $propertyInfoExtractor);
        $this->serializer = new Serializer([$unwrapping, $arrayNormalizer, $this->normalizer], [new YamlEncoder()]);
    }

    public function hydrate(object $entityObject, string $path): ?object
    {
        $reflection = new \ReflectionClass($entityObject);
        $entityObject = $this->serializer->deserialize(file_get_contents($path), get_class($entityObject), YamlEncoder::FORMAT);

        foreach (ReflectionHelper::getEntityParameters($reflection) as $name => $parameter) {
            if (null !== $parameter->getType()) {
                $getter = 'get'.$name;
                $setter = 'set'.$name;
                $values = [];

                foreach ($entityObject->$getter() as $value) {
                    $values[] = $this->normalizer->denormalize($value, $parameter->getType());
                }

                $entityObject->$setter($values);
            }
        }

        return $entityObject;
    }

    public function save(object $entityObject, string $path): void
    {
        $yaml = $this->serializer->serialize($entityObject, YamlEncoder::FORMAT);
        // Yaml encode/decode for better format.
        // (yes, better is possible, i know).
        $yaml = Yaml::parse($yaml);
        file_put_contents($path, Yaml::dump($yaml));
    }
}
