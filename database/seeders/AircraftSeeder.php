<?php

namespace Database\Seeders;

use App\Models\Aircraft;
use Illuminate\Database\Seeder;

class AircraftSeeder extends Seeder
{
    public function run(): void
    {
        $aircraft = [
            [
                'name' => 'Cessna 172 Skyhawk',
                'model' => '172',
                'manufacturer' => 'Cessna',
                'takeoff_speed' => 55,
                'max_weight' => 1157,
                'cruise_speed' => 122,
                'range' => 640,
                'description' => 'Le Cessna 172 Skyhawk est l\'avion d\'aviation générale le plus produit de l\'histoire. C\'est un avion monomoteur à ailes hautes, avec un train d\'atterrissage fixe.',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/a/ae/Cessna_172S_Skyhawk_SP%2C_Private_JP6817606.jpg',
            ],
            [
                'name' => 'Piper PA-28 Cherokee',
                'model' => 'PA-28',
                'manufacturer' => 'Piper',
                'takeoff_speed' => 60,
                'max_weight' => 1055,
                'cruise_speed' => 115,
                'range' => 465,
                'description' => 'Le Piper PA-28 Cherokee est une famille d\'avions légers à quatre places, monomoteurs, à ailes basses. La production a commencé en 1961 et se poursuit encore aujourd\'hui.',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/17/G-AVRZ_Piper_PA-28-180_Cherokee_C_at_Northrepps.jpg',
            ],
            [
                'name' => 'Cirrus SR22',
                'model' => 'SR22',
                'manufacturer' => 'Cirrus',
                'takeoff_speed' => 70,
                'max_weight' => 1633,
                'cruise_speed' => 183,
                'range' => 1049,
                'description' => 'Le Cirrus SR22 est un avion monomoteur composite américain à 4 places produit par Cirrus Aircraft. Il est équipé d\'un parachute balistique de cellule comme équipement standard.',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/9f/Cirrus_SR-22_G3_GTS_AN1594917.jpg',
            ],
            [
                'name' => 'Diamond DA40',
                'model' => 'DA40',
                'manufacturer' => 'Diamond',
                'takeoff_speed' => 57,
                'max_weight' => 1198,
                'cruise_speed' => 130,
                'range' => 720,
                'description' => 'Le Diamond DA40 Diamond Star est un avion léger monomoteur à quatre places. Il est fabriqué en Autriche et au Canada par Diamond Aircraft Industries.',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/1/1f/DiamondDA40C-GSPB.jpg',
            ],
            [
                'name' => 'Beechcraft Bonanza',
                'model' => 'G36',
                'manufacturer' => 'Beechcraft',
                'takeoff_speed' => 60,
                'max_weight' => 1656,
                'cruise_speed' => 176,
                'range' => 920,
                'description' => 'Le Beechcraft Bonanza est un avion léger civil produit par Beechcraft depuis 1947. C\'est l\'un des avions monomoteurs les plus luxueux et les plus rapides de sa catégorie.',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/3/3a/Beech.bonanza.takeoff.arp.jpg',
            ],
        ];

        foreach ($aircraft as $planeData) {
            Aircraft::create($planeData);
        }
    }
}