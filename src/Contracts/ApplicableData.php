<?php

namespace Spatie\LaravelData\Contracts;

interface ApplicableData
{
    public function apply(?array $specificKeys = null, bool $strict = true): static;
    public function applyOnly(string ...$keys): static;
    public function applyExcept(string ...$keys): static;
    public function applyMutable(?array $specificKeys = null, bool $strict = true): static;
    public function previewApply(?array $specificKeys = null, bool $strict = true): array;
    public function hasApplicableData(): bool;
    public function getApplicableKeys(): array;
}
