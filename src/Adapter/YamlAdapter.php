<?php

declare(strict_types=1);

namespace Atomatis\OFM\Adapter;

use Atomatis\OFM\Converter\NameConverter;
use Atomatis\OFM\Converter\TypeConverter;
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
        $this->serializer = new Serializer([$unwrapping, $arrayNormalizer, $this->normalizer], [new YamlEncoder(
            defaultContext: [YamlEncoder::YAML_INLINE => 10]
        )]);
    }

    public function hydrate(object $entityObject, string $path): ?object
    {
        $reflection = new \ReflectionClass($entityObject);
        $data = $this->serializer->decode(file_get_contents($path), 'yaml');

        foreach (ReflectionHelper::getEntityParameters($reflection) as $property => $parameter) {
            if (null !== $parameter->getName()) {
                $data = NameConverter::convertFromData($data, $property, $parameter);
            }

            if (null !== $parameter->getType()) {
                $data = TypeConverter::convertFromData($data, $property, $parameter);
            }
        }

        return $this->serializer->denormalize($data, get_class($entityObject), YamlEncoder::FORMAT);
    }

    public function save(object $entityObject, string $path): void
    {
        $reflection = new \ReflectionClass($entityObject);
        $data = $this->serializer->normalize($entityObject);

        foreach (ReflectionHelper::getEntityParameters($reflection) as $property => $parameter) {
            if (null !== $parameter->getName()) {
                $data = NameConverter::convertFromObject($data, $property, $parameter);
            }
        }

        file_put_contents($path, $this->serializer->encode($data, YamlEncoder::FORMAT));
    }
}
