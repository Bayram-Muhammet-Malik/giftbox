<?php

namespace gift\core\application\usecases;

use gift\core\domain\entities\Categorie;
use gift\core\domain\entities\Prestation;
use gift\core\domain\entities\CoffretType;
use gift\core\domain\entities\Theme;

class CatalogueService implements CatalogueInterface {

    public function getCategories(): array {
        return Categorie::all()->toArray();
    }

    public function getCategorieById(int $id): array {
        return Categorie::find($id)?->toArray() ?? [];
    }

    public function getPrestationById(string $id): array {
        return Prestation::find($id)?->toArray() ?? [];
    }

    public function getPrestationsByCategorie(int $categ_id): array {
        return Prestation::where('cat_id', $categ_id)->get()->toArray();
    }

    public function getThemesCoffrets(): array {
        return Theme::all()->toArray();
    }

    public function getCoffretById(int $id): array {
        return CoffretType::find($id)?->toArray() ?? [];
    }
}
