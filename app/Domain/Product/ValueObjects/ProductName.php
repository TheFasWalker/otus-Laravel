<?php

namespace App\Domain\Product\ValueObjects;

class ProductName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        
        if (empty($value)) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
        
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Product name cannot exceed 255 characters');
        }
        
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}