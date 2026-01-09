<?php
namespace App\Controller;

use App\Repository\{RecipeTypeRepository, NutrientTypeRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class HelperController extends AbstractController
{
    #[Route('/recipe-types', methods: ['GET'])]
    public function getRecipeTypes(RecipeTypeRepository $repo): JsonResponse {
        return $this->json($repo->findAll());
    }

    #[Route('/nutrient-types', methods: ['GET'])]
    public function getNutrientTypes(NutrientTypeRepository $repo): JsonResponse {
        return $this->json($repo->findAll());
    }
}