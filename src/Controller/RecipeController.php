<?php
namespace App\Controller;

use App\Entity\Rating;
use App\Model\RecipeNewDTO;
use App\Repository\{RecipeRepository, RatingRepository};
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\HttpKernel\Attribute\{MapQueryParameter, MapRequestPayload};
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class RecipeController extends AbstractController
{
    #[Route('/recipes', name: 'get_recipes', methods: ['GET'])]
    public function list(RecipeRepository $repo, #[MapQueryParameter] ?int $type): JsonResponse
    {
        return $this->json($repo->findActiveRecipes($type));
    }

    #[Route('/recipes', name: 'create_recipe', methods: ['POST'])]
    public function create(#[MapRequestPayload] RecipeNewDTO $dto, RecipeService $service): JsonResponse
    {
        return $this->json($service->createRecipe($dto), 201);
    }

    #[Route('/recipes/{id}', name: 'delete_recipe', methods: ['DELETE'])]
    public function delete(int $id, RecipeRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $recipe = $repo->find($id);
        if (!$recipe || $recipe->isDeleted()) return $this->json(['error' => 'No encontrada'], 404);

        $recipe->setDeleted(true);
        $em->flush();
        return $this->json(['message' => 'Eliminada']);
    }

    #[Route('/recipes/{id}/rating/{rate}', name: 'rate_recipe', methods: ['POST'])]
    public function rate(int $id, int $rate, Request $req, RecipeRepository $rr, RatingRepository $ratR, EntityManagerInterface $em): JsonResponse
    {
        if ($rate < 0 || $rate > 5) return $this->json(['error' => 'Voto inválido'], 400);
        
        $recipe = $rr->find($id);
        if (!$recipe || $recipe->isDeleted()) return $this->json(['error' => 'No existe'], 404);

        $ip = $req->getClientIp();
        if ($ratR->findOneBy(['recipe' => $recipe, 'ipAddress' => $ip])) {
            return $this->json(['error' => 'Ya votaste'], 400);
        }

        $rating = new Rating();
        $rating->setScore($rate);
        $rating->setIpAddress($ip);
        $rating->setRecipe($recipe);
        
        $em->persist($rating);
        $em->flush();

        return $this->json($recipe);
    }
}