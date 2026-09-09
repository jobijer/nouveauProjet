<?php

namespace App\Services;

class CalculateurPrix
{
    public function calculerAvecTaxe(float $prixHT, float $tauxTaxe): float
    {
        if ($tauxTaxe < 0 || $prixHT < 0) {
            throw new \InvalidArgumentException(
                'Le taux de taxe ne peut pas être négatif.'
            );
        }

        return round($prixHT * (1 + $tauxTaxe), 2);
    }

    public function appliquerRemise(float $prix, float $remisePourcentage): float
    {
        if ($remisePourcentage < 0 || $prix < 0) {
            throw new \InvalidArgumentException(
                'La remise ne peut pas être négative.'
            );
        }

        $prixApresRemise = $prix - ($prix * $remisePourcentage / 100);

        return max(0, round($prixApresRemise, 2));
    }

    public function respecteSeuilMinimum(float $prix, float $seuilMinimum): bool
    {
        if ($seuilMinimum < 0 || $prix < 0) {
            throw new \InvalidArgumentException(
                'Le seuil minimum ne peut pas être négatif.'
            );
        }

        return $prix >= $seuilMinimum;
    }
}
