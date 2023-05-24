<?php

declare(strict_types=1);

namespace Kreyu\Bundle\DataTableBundle;

use Kreyu\Bundle\DataTableBundle\Exception\UnexpectedTypeException;
use Kreyu\Bundle\DataTableBundle\Extension\DataTableTypeExtensionInterface;
use Kreyu\Bundle\DataTableBundle\Type\DataTableTypeInterface;
use Kreyu\Bundle\DataTableBundle\Type\ResolvedDataTableTypeFactoryInterface;
use Kreyu\Bundle\DataTableBundle\Type\ResolvedDataTableTypeInterface;

class DataTableRegistry implements DataTableRegistryInterface
{
    /**
     * @var array<string, DataTableTypeInterface>
     */
    private array $types = [];

    /**
     * @var array<ResolvedDataTableTypeInterface>
     */
    private array $resolvedTypes = [];

    /**
     * @var array<string, bool>
     */
    private array $checkedTypes = [];

    /**
     * @var array<string, DataTableTypeExtensionInterface>
     */
    private array $typeExtensions = [];

    /**
     * @param iterable<DataTableTypeInterface>          $types
     * @param iterable<DataTableTypeExtensionInterface> $typeExtensions
     */
    public function __construct(
        iterable $types,
        iterable $typeExtensions,
        private ResolvedDataTableTypeFactoryInterface $resolvedDataTableTypeFactory,
    ) {
        foreach ($types as $type) {
            if (!$type instanceof DataTableTypeInterface) {
                throw new UnexpectedTypeException($type, DataTableTypeInterface::class);
            }

            $this->types[$type::class] = $type;
        }

        foreach ($typeExtensions as $typeExtension) {
            if (!$typeExtension instanceof DataTableTypeExtensionInterface) {
                throw new UnexpectedTypeException($typeExtension, DataTableTypeExtensionInterface::class);
            }

            $this->typeExtensions[$typeExtension::class] = $typeExtension;
        }
    }

    public function getType(string $name): ResolvedDataTableTypeInterface
    {
        if (!isset($this->resolvedTypes[$name])) {
            if (!isset($this->types[$name])) {
                throw new \InvalidArgumentException(sprintf('Could not load type "%s".', $name));
            }

            $this->resolvedTypes[$name] = $this->resolveType($this->types[$name]);
        }

        return $this->resolvedTypes[$name];
    }

    private function resolveType(DataTableTypeInterface $type): ResolvedDataTableTypeInterface
    {
        $fqcn = $type::class;

        if (isset($this->checkedTypes[$fqcn])) {
            $types = implode(' > ', array_merge(array_keys($this->checkedTypes), [$fqcn]));
            throw new \LogicException(sprintf('Circular reference detected for data table type "%s" (%s).', $fqcn, $types));
        }

        $this->checkedTypes[$fqcn] = true;

        $typeExtensions = array_filter(
            $this->typeExtensions,
            fn (DataTableTypeExtensionInterface $extension) => $this->isFqcnExtensionEligible($fqcn, $extension),
        );

        $parentType = $type->getParent();

        try {
            return $this->resolvedDataTableTypeFactory->createResolvedType(
                $type,
                $typeExtensions,
                $parentType ? $this->getType($parentType) : null,
            );
        } finally {
            unset($this->checkedTypes[$fqcn]);
        }
    }

    private function isFqcnExtensionEligible(string $fqcn, DataTableTypeExtensionInterface $extension): bool
    {
        $extendedTypes = $extension::getExtendedTypes();

        if ($extendedTypes instanceof \Traversable) {
            $extendedTypes = iterator_to_array($extendedTypes);
        }

        return in_array($fqcn, $extendedTypes);
    }
}