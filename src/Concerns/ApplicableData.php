<?php

namespace Spatie\LaravelData\Concerns;

use ReflectionClass;
use ReflectionProperty;

trait ApplicableData
{

    public function apply(?array $specificKeys = null, bool $strict = true): static
    {
        $additionalData = $this->getAdditionalData();

        if (empty($additionalData)) {
            return $this;
        }

        if ($specificKeys !== null) {
            $additionalData = array_intersect_key($additionalData, array_flip($specificKeys));
        }

        $currentData = $this->toArray();

        $currentData = array_diff_key($currentData, $additionalData);

        if ($strict) {
            $additionalData = $this->filterExistingProperties($additionalData);
        }

        $mergedData = array_merge($currentData, $additionalData);

        return static::from($mergedData);
    }

    public function applyOnly(string ...$keys): static
    {
        return $this->apply($keys, true);
    }

    public function applyExcept(string ...$keys): static
    {
        $additionalData = $this->getAdditionalData();
        $keysToApply = array_diff(array_keys($additionalData), $keys);

        return $this->apply($keysToApply, true);
    }

    public function applyLoose(?array $specificKeys = null): static
    {
        return $this->apply($specificKeys, false);
    }


    public function applyMutable(?array $specificKeys = null, bool $strict = true): static
    {
        $additionalData = $this->getAdditionalData();

        if (empty($additionalData)) {
            return $this;
        }

        if ($specificKeys !== null) {
            $additionalData = array_intersect_key($additionalData, array_flip($specificKeys));
        }

        if ($strict) {
            $additionalData = $this->filterExistingProperties($additionalData);
        }

        foreach ($additionalData as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->_additional = array_diff_key($this->_additional, $additionalData);

        return $this;
    }

    protected function filterExistingProperties(array $data): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $propertyNames = array_map(fn($prop) => $prop->getName(), $properties);
        $propertyNames = array_diff($propertyNames, ['_additional', '_dataContext']);

        return array_intersect_key($data, array_flip($propertyNames));
    }

    public function hasApplicableData(): bool
    {
        return !empty($this->getAdditionalData());
    }

    public function getApplicableKeys(): array
    {
        $additionalData = $this->getAdditionalData();
        $existingProperties = $this->filterExistingProperties($additionalData);

        return array_keys($existingProperties);
    }

    public function previewApply(?array $specificKeys = null, bool $strict = true): array
    {
        $additionalData = $this->getAdditionalData();

        if ($specificKeys !== null) {
            $additionalData = array_intersect_key($additionalData, array_flip($specificKeys));
        }

        if ($strict) {
            $additionalData = $this->filterExistingProperties($additionalData);
        }

        return [
            'current' => $this->toArray(),
            'additional' => $additionalData,
            'result' => array_merge($this->toArray(), $additionalData)
        ];
    }
}
