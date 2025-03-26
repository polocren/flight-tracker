<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $fillable = [
        'name',
        'manufacturer',
        'model',
        'description',
        'image_url'
    ];
}

    /**
     * Modèle Aircraft (Avion)
     * 
     * Gère les données des avions avec les attributs :
     * - name : Nom de l'avion
     * - manufacturer : Fabricant
     * - model : Modèle
     * - description : Description
     * - image_url : URL de l'image
     * 
     * Utilise les fonctionnalités Eloquent de Laravel pour
     * les requêtes et relations avec la base de données.
     */

