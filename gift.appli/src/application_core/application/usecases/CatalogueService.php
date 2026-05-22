<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\domain\entities\Categorie;
use gift\core\domain\entities\Prestation;
use gift\core\domain\entities\CoffretType;
use gift\core\domain\entities\Theme;
use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use Illuminate\Database\QueryException;

class CatalogueService implements CatalogueInterface
{
    public function getCategories(): array
    {
        return Categorie::all()->toArray();
    }

    public function getCategorieById(int $id): array
    {
        try {
            $categorie = Categorie::find($id);
            if (!$categorie) throw new NotFoundException("Catégorie non trouvée dans la base de donnée.");
            return $categorie->toArray();

        } catch (QueryException $e) {
            throw new DataErrorException("ID de catégorie invalide.");
        }
    }

    public function getPrestationById(string $id): array
    {
        try {
            $presta = Prestation::find($id);

            if (!$presta) throw new NotFoundException("Prestation non trouvée dans la base de donnée");

            return $presta->toArray();

        } catch (QueryException $e) {
            throw new DataErrorException("ID de prestation invalide.");
        }
    }

    public function getPrestationsByCategorie(int $categ_id): array
    {
        return Prestation::where('cat_id', $categ_id)->get()->toArray();
    }

    public function getThemesCoffrets(): array
    {
        return Theme::all()->toArray();
    }

    public function getCoffretById(int $id): array
    {
        try {
            $coffret = CoffretType::find($id);

            if (!$coffret) throw new NotFoundException("Coffret non trouvé dans la base de donnée");

            return $coffret->toArray();

        } catch (QueryException $e) {
            throw new DataErrorException("ID de coffret invalide.");
        }
    }
}
