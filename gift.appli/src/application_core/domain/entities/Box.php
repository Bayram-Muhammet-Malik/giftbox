<?php
declare(strict_types=1);

namespace gift\core\domain\entities;

use gift\core\application\exceptions\DataErrorException;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'box';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function prestation()
    {
        return $this->belongsToMany(Prestation::class, 'box2presta', 'box_id', 'presta_id')
            ->withPivot('quantite');
    }

    public function validate(): bool
    {
        if ($this->statut !== 1) {
            throw new DataErrorException("La box doit être en état créée");
        }

        if ($this->prestation->count() < 2) {
            throw new DataErrorException("La box doit contenir au moins 2 prestations");
        }

        $this->statut = 2;
        return true;
    }

    public function canBeModified(): bool
    {
        if ($this->statut !== 1) {
            throw new DataErrorException("Impossible de modifier une box non créée");
        }

        return true;
    }

    public function preparePrestationsForSync(int $presta_id, int $quantite): array
    {
        if ($quantite < 1) {
            throw new DataErrorException("Quantité invalide");
        }

        $this->canBeModified();

        $prestations = [];

        foreach ($this->prestation as $p) {
            $prestations[$p->id] = ['quantite' => $p->pivot->quantite];
        }

        if (isset($prestations[$presta_id])) {
            $prestations[$presta_id]['quantite'] += $quantite;
        } else {
            $prestations[$presta_id] = ['quantite' => $quantite];
        }

        return $prestations;
    }

    public function recalculateMontant(): float
    {
        $total = 0;

        foreach ($this->prestation as $p) {
            $total += $p->tarif * $p->pivot->quantite;
        }

        $this->montant = $total;
        return $total;
    }

    public function deliver(string $token): bool
    {
        if ($this->statut !== 2) {
            throw new DataErrorException("La box doit être validée avant livraison");
        }

        $this->token = $token;
        $this->statut = 3;

        return true;
    }

    public function markAsUsed(): bool
    {
        if ($this->statut !== 3) {
            throw new DataErrorException("Box déjà utilisée ou non livrée");
        }

        $this->statut = 4;

        return true;
    }
}
