<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Hydrator;

final class Hydrator implements HydratorInterface
{
    /**
     * @throws \ReflectionException
     * @throws \DateMalformedStringException
     */
    public function hydrate(array $data, string $dtoClass): object
    {
        if (!class_exists($dtoClass)) {
            throw new \RuntimeException("DTO class {$dtoClass} does not exist");
        }

        $reflection = new \ReflectionClass($dtoClass);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $dtoClass();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $arguments[] = $this->resolveParameter($parameter, $data, $dtoClass);
        }

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function resolveParameter(
        \ReflectionParameter $parameter,
        array $data,
        string $dtoClass,
    ): mixed {
        $name = $parameter->getName();

        if (!array_key_exists($name, $data)) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            if ($parameter->allowsNull()) {
                return null;
            }

            throw new \RuntimeException(
                "Missing required field '{$name}' for DTO {$dtoClass}"
            );
        }

        $value = $data[$name];
        $type  = $parameter->getType();

        if ($value === null) {
            return null;
        }

        if ($type instanceof \ReflectionNamedType) {
            return $this->castValue($value, $type, $name, $dtoClass);
        }

        return $value;
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function castValue(
        mixed $value,
        \ReflectionNamedType $type,
        string $field,
        string $dtoClass,
    ): mixed {
        $typeName = $type->getName();

        // scalar → scalar
        if ($type->isBuiltin()) {
            return match ($typeName) {
                'int'    => (int) $value,
                'float'  => (float) $value,
                'string' => (string) $value,
                'bool'   => (bool) $value,
                'array'  => (array) $value,
                default  => $value,
            };
        }

        // DateTimeImmutable
        if ($typeName === \DateTimeImmutable::class) {
            return new \DateTimeImmutable($value);
        }

        // already correct object
        if ($value instanceof $typeName) {
            return $value;
        }

        throw new \RuntimeException(
            "Cannot cast field '{$field}' to {$typeName} in DTO {$dtoClass}"
        );
    }
}
