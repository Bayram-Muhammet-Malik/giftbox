<?php

namespace gift\core\application\usecases;

use gift\core\domain\entities\Categorie;
use gift\core\domain\entities\Prestation;
use gift\core\domain\entities\CoffretType;
use gift\core\domain\entities\Theme;
use gift\core\application\exceptions\CatalogueException;
use Illuminate\Database\QueryException;

class CatalogueService implements CatalogueInterface
{

    public function getCategories(): array
    {
        try {
            return Categorie::all()->toArray();
        } catch (QueryException $e) {
            throw new CatalogueException("Impossible de récupérer les catégories.", 0, $e);
        }
    }

    public function getCategorieById(int $id): array
    {
        try {
            return Categorie::find($id)?->toArray() ?? [];
        } catch (QueryException $e) {
            throw new CatalogueException(
                "Impossible de récupérer la catégorie.",
                0,
                $e
            );
        }
    }

    public function getPrestationById(string $id): array
    {
        try {
            return Prestation::find($id)?->toArray() ?? [];
        } catch (QueryException $e) {
            throw new CatalogueException("Impossible de récupérer la prestation.", 0, $e);
        }
    }

    public function getPrestationsByCategorie(int $categ_id): array
    {
        try {
            return Prestation::where('cat_id', $categ_id)->get()->toArray();
        } catch (QueryException $e) {
            throw new CatalogueException(
                "Impossible de récupérer les prestations de cette catégorie.",
                0,
                $e
            );
        }
    }

    public function getThemesCoffrets(): array
    {
        try {
            return Theme::all()->toArray();
        } catch (QueryException $e) {
            throw new CatalogueException(
                "Impossible de récupérer les thèmes.",
                0,
                $e
            );
        }
    }

    public function getCoffretTypes(): array
    {
        try {
            return CoffretType::orderBy('theme_id')
                ->get()
                ->groupBy('theme_id')
                ->toArray();
        } catch (QueryException $e) {
            throw new CatalogueException("Impossible de récupérer les coffrets types.",0,$e);
        }
    }

    public function getCoffretById(int $id): array
    {
        try {
            return CoffretType::find($id)?->toArray() ?? [];
        } catch (QueryException $e) {
            throw new CatalogueException(
                "Impossible de récupérer le coffret.",
                0,
                $e
            );
        }
    }
}