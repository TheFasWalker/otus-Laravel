<?php

namespace App\Domain\Product\ValueObjects;

class ProductId
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Product ID must be positive');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(ProductId $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}