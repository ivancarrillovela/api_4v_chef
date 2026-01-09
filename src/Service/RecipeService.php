<?php
namespace App\Service;

use App\Entity\{Recipe, Ingredient, Step, RecipeNutrient};
use App\Model\RecipeNewDTO;
use App\Repository\{RecipeTypeRepository, NutrientTypeRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RecipeService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RecipeTypeRepository $recipeTypeRepo,
        private NutrientTypeRepository $nutrientTypeRepo
    ) {}

    public function createRecipe(RecipeNewDTO $dto): Recipe
    {
        $type = $this->recipeTypeRepo->find($dto->typeId);
        if (!$type) throw new BadRequestHttpException("Tipo de receta no encontrado");

        $recipe = new Recipe();
        $recipe->setTitle($dto->title);
        $recipe->setNumDiners($dto->numberDiner);
        $recipe->setType($type);
        // El campo deleted ya es false por defecto en la entidad

        foreach ($dto->ingredients as $i) {
            $ing = new Ingredient();
            $ing->setName($i->name);
            $ing->setQuantity($i->quantity);
            $ing->setUnit($i->unit);
            $recipe->addIngredient($ing);
        }

        foreach ($dto->steps as $s) {
            $step = new Step();
            $step->setOrderStep($s->order);
            $step->setDescription($s->description);
            $recipe->addStep($step);
        }

        foreach ($dto->nutrients as $n) {
            $nutType = $this->nutrientTypeRepo->find($n->typeId);
            if (!$nutType) throw new BadRequestHttpException("Nutriente no encontrado");
            
            $rn = new RecipeNutrient();
            $rn->setNutrientType($nutType);
            $rn->setQuantity($n->quantity);
            $recipe->addRecipeNutrient($rn);
        }

        $this->em->persist($recipe);
        $this->em->flush();

        return $recipe;
    }
}