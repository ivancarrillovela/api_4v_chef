<?php
namespace App\Model;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class NutrientNewDTO {
    public function __construct(
        #[SerializedName('type-id')] #[Assert\NotBlank] public int $typeId,
        #[Assert\NotBlank] #[Assert\Positive] public float $quantity
    ) {}
}