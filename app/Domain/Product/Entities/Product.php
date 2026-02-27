<?php

namespace App\Domain\Product\Entities;

use App\Domain\Product\ValueObjects\ProductId;
use App\Domain\Product\ValueObjects\ProductName;
use App\Domain\Product\ValueObjects\ProductDescription;
use App\Domain\Product\Events\ProductCreated;
use Illuminate\Support\Collection;

class Product
{
    private ProductId $id;
    private ProductName $name;
    private ProductDescription $description;
    private ?string $preview;
    private int $countryId;
    private int $userId;
    private array $tagIds = [];
    private array $domainEvents = [];
    
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    private function __construct(
        ProductId $id,
        ProductName $name,
        ProductDescription $description,
        ?string $preview,
        int $countryId,
        int $userId,
        array $tagIds = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->preview = $preview;
        $this->countryId = $countryId;
        $this->userId = $userId;
        $this->tagIds = $tagIds;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        
        $this->addDomainEvent(new ProductCreated($this));
    }

    public static function create(
        ProductName $name,
        ProductDescription $description,
        ?string $preview,
        int $countryId,
        int $userId,
        array $tagIds = []
    ): self {
        return new self(
            new ProductId(0),
            $name,
            $description,
            $preview,
            $countryId,
            $userId,
            $tagIds
        );
    }

    public static function reconstruct(
        int $id,
        string $name,
        ?string $description,
        ?string $preview,
        int $countryId,
        int $userId,
        array $tagIds,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ): self {
        $product = new self(
            new ProductId($id),
            new ProductName($name),
            new ProductDescription($description),
            $preview,
            $countryId,
            $userId,
            $tagIds
        );
        
        $product->createdAt = $createdAt;
        $product->updatedAt = $updatedAt;
        $product->domainEvents = []; 
        
        return $product;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getName(): ProductName
    {
        return $this->name;
    }

    public function getDescription(): ProductDescription
    {
        return $this->description;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function getCountryId(): int
    {
        return $this->countryId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTagIds(): array
    {
        return $this->tagIds;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateName(ProductName $name): void
    {
        $this->name = $name;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateDescription(ProductDescription $description): void
    {
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updatePreview(?string $preview): void
    {
        $this->preview = $preview;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateCountry(int $countryId): void
    {
        $this->countryId = $countryId;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addTag(int $tagId): void
    {
        if (!in_array($tagId, $this->tagIds, true)) {
            $this->tagIds[] = $tagId;
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function removeTag(int $tagId): void
    {
        $this->tagIds = array_diff($this->tagIds, [$tagId]);
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addDomainEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }

    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }
}