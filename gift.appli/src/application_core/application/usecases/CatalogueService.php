<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\domain\entities\Categorie;
use gift\core\domain\entities\Prestation;
use gift\core\domain\entities\CoffretType;
use gift\core\domain\entities\Theme;
use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CatalogueService implements CatalogueInterface
{
    public function getCategories(): array
    {
        return Categorie::all()->toArray();
    }

    public function getCategorieById(int $id): array {
        try {
            return Categorie::findOrFail($id)->toArray();
        } catch (ModelNotFoundException) {
            throw new NotFoundException("Catégorie non trouvée dans la base de donnée.");
        } catch (QueryException) {
            throw new DataErrorException("ID de catégorie invalide.");
        }
    }

    public function getPrestationById(string $id): array {
        try {
            return Prestation::findOrFail($id)->toArray();
        } catch (ModelNotFoundException) {
            throw new NotFoundException("Prestation non trouvée dans la base de donnée.");
        } catch (QueryException) {
            throw new DataErrorException("ID de prestation invalide.");
        }
    }

    public function getPrestationsByCategorie(int $categ_id): array {
        return Prestation::where('cat_id', $categ_id)->get()->toArray();
    }

    public function getThemesCoffrets(): array {
        return Theme::all()->toArray();
    }

    public function getCoffretById(int $id): array {
        try {
            return CoffretType::findOrFail($id)->toArray();
        } catch (ModelNotFoundException) {
            throw new NotFoundException("Coffret non trouvé dans la base de donnée.");
        } catch (QueryException) {
            throw new DataErrorException("ID de coffret invalide.");
        }
    }
}
