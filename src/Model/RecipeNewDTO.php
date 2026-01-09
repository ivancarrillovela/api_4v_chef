<?php
namespace App\Model;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class RecipeNewDTO {
    public function __construct(
        #[Assert\NotBlank] public string $title,
        #[SerializedName('number-diner')] #[Assert\NotBlank] #[Assert\Positive] public int $numberDiner,
        #[SerializedName('type-id')] #[Assert\NotBlank] public int $typeId,
        #[Assert\Count(min: 1)] #[Assert\Valid] /** @var IngredientDTO[] */ public array $ingredients,
        #[Assert\Count(min: 1)] #[Assert\Valid] /** @var StepDTO[] */ public array $steps,
        #[Assert\Valid] /** @var NutrientNewDTO[] */ public array $nutrients
    ) {}
}