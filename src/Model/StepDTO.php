<?php
namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class StepDTO {
    public function __construct(
        #[Assert\NotBlank] public int $order,
        #[Assert\NotBlank] public string $description
    ) {}
}