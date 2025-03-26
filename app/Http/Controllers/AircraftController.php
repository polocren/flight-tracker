<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    public function index()
    {
        $aircraft = Aircraft::all();
        return response()->json($aircraft);
    }

    public function show(Aircraft $aircraft)
    {
        return response()->json($aircraft);
    }
    /**
     * Voici les étapes pour mettre à jour votre projet sur GitHub :
     * 
     * 1. Ajouter les fichiers modifiés :
     *    git add .
     * 
     * 2. Créer un commit avec un message descriptif :
     *    git commit -m "Description des modifications"
     * 
     * 3. Récupérer les derniers changements du dépôt distant (git pull) :
     *    - Cette commande permet de synchroniser votre dépôt local avec GitHub
     *    - Elle récupère les modifications faites par d'autres développeurs
     *    - Évite les conflits avant d'envoyer vos propres modifications
     *    git pull origin main
     * 
     * 4. Envoyer vos modifications sur GitHub :
     *    git push origin main
     * 
     * Note: Remplacez 'main' par le nom de votre branche si différent
     * Important: Toujours faire un pull avant un push pour éviter les conflits
     */
}