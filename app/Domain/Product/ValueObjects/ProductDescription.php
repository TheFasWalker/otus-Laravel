<?php

namespace App\Domain\Product\ValueObjects;

class ProductDescription
{
    private ?string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ? trim($value) : null;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}