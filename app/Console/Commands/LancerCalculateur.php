<?php

namespace App\Console\Commands;

use App\Services\CalculateurPrix;
use Illuminate\Console\Command;

class LancerCalculateur extends Command
{
    protected $signature = 'calculateur:lancer
        {prixHT : Prix hors taxe}
        {taux : Taux de taxe en décimal (ex: 0.15)}
        {remise : Remise en pourcentage (ex: 10)}
        {seuil : Seuil minimum à vérifier}';

    protected $description = 'Teste les méthodes du CalculateurPrix';

    public function handle(CalculateurPrix $calculateur): void
    {
        $prixHT = (float) $this->argument('prixHT');
        $taux = (float) $this->argument('taux');
        $remise = (float) $this->argument('remise');
        $seuil = (float) $this->argument('seuil');

        $this->info("--- CalculateurPrix ---");
        $this->line("Prix HT : $prixHT $");

        try {
            $prixTTC = $calculateur->calculerAvecTaxe($prixHT, $taux);
            $this->info("Prix TTC ($taux) : $prixTTC $");
        } catch (\InvalidArgumentException $e) {
            $this->error("Taxe invalide : " . $e->getMessage());
        }

        try {
            $prixRemise = $calculateur->appliquerRemise($prixHT, $remise);
            $this->info("Après remise $remise% : $prixRemise $");
        } catch (\InvalidArgumentException $e) {
            $this->error("Remise invalide : " . $e->getMessage());
        }

        try {
            $respecte = $calculateur->respecteSeuilMinimum($prixHT, $seuil);
            $statut = $respecte ? "respecté" : "non respecté";
            $this->info("Seuil minimum $seuil $ : $statut");
        } catch (\InvalidArgumentException $e) {
            $this->error("Seuil invalide : " . $e->getMessage());
        }
    }
}
