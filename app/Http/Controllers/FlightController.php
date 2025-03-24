<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('Récupération des vols', ['user_id' => $request->user()->id]);
        
        try {
            $flights = $request->user()->flights()->orderBy('departure_time', 'desc')->get();
            return response()->json($flights);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des vols', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de la récupération des vols: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Tentative d\'ajout de vol', ['user_id' => $request->user()->id, 'data' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'departure_airport' => 'required|string',
                'arrival_airport' => 'required|string',
                'departure_time' => 'required|date',
                'arrival_time' => 'required|date|after:departure_time',
                'aircraft_type' => 'required|string',
                'registration_number' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // Calculer la durée du vol en minutes
            $departure = new \DateTime($validated['departure_time']);
            $arrival = new \DateTime($validated['arrival_time']);
            $duration = $departure->diff($arrival);
            $minutes = ($duration->days * 24 * 60) + ($duration->h * 60) + $duration->i;

            $flight = $request->user()->flights()->create([
                'departure_airport' => $validated['departure_airport'],
                'arrival_airport' => $validated['arrival_airport'],
                'departure_time' => $validated['departure_time'],
                'arrival_time' => $validated['arrival_time'],
                'flight_duration' => $minutes,
                'aircraft_type' => $validated['aircraft_type'],
                'registration_number' => $validated['registration_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Vol ajouté avec succès', ['flight_id' => $flight->id]);
            
            return response()->json($flight, 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du vol', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'user_id' => $request->user()->id
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du vol: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Flight $flight)
    {
        Log::info('Récupération des détails d\'un vol', ['flight_id' => $flight->id, 'user_id' => $request->user()->id]);
        
        // Vérifier que le vol appartient à l'utilisateur
        if ($flight->user_id !== $request->user()->id) {
            Log::warning('Tentative d\'accès non autorisé à un vol', [
                'flight_id' => $flight->id,
                'user_id' => $request->user()->id,
                'flight_owner_id' => $flight->user_id
            ]);
            
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json($flight);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight)
    {
        Log::info('Tentative de mise à jour d\'un vol', [
            'flight_id' => $flight->id,
            'user_id' => $request->user()->id,
            'data' => $request->all()
        ]);
        
        // Vérifier que le vol appartient à l'utilisateur
        if ($flight->user_id !== $request->user()->id) {
            Log::warning('Tentative de modification non autorisée d\'un vol', [
                'flight_id' => $flight->id,
                'user_id' => $request->user()->id,
                'flight_owner_id' => $flight->user_id
            ]);
            
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'departure_airport' => 'required|string',
                'arrival_airport' => 'required|string',
                'departure_time' => 'required|date',
                'arrival_time' => 'required|date|after:departure_time',
                'aircraft_type' => 'required|string',
                'registration_number' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // Calculer la durée du vol en minutes
            $departure = new \DateTime($validated['departure_time']);
            $arrival = new \DateTime($validated['arrival_time']);
            $duration = $departure->diff($arrival);
            $minutes = ($duration->days * 24 * 60) + ($duration->h * 60) + $duration->i;

            $flight->update([
                'departure_airport' => $validated['departure_airport'],
                'arrival_airport' => $validated['arrival_airport'],
                'departure_time' => $validated['departure_time'],
                'arrival_time' => $validated['arrival_time'],
                'flight_duration' => $minutes,
                'aircraft_type' => $validated['aircraft_type'],
                'registration_number' => $validated['registration_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Vol mis à jour avec succès', ['flight_id' => $flight->id]);
            
            return response()->json($flight);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du vol', [
                'message' => $e->getMessage(),
                'flight_id' => $flight->id,
                'user_id' => $request->user()->id
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du vol: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Flight $flight)
    {
        Log::info('Tentative de suppression d\'un vol', [
            'flight_id' => $flight->id,
            'user_id' => $request->user()->id
        ]);
        
        // Vérifier que le vol appartient à l'utilisateur
        if ($flight->user_id !== $request->user()->id) {
            Log::warning('Tentative de suppression non autorisée d\'un vol', [
                'flight_id' => $flight->id,
                'user_id' => $request->user()->id,
                'flight_owner_id' => $flight->user_id
            ]);
            
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        try {
            $flightId = $flight->id; // Sauvegarder l'ID avant suppression
            
            // Ajouter une transaction pour s'assurer que la suppression se fait correctement
            DB::beginTransaction();
            $flight->delete();
            DB::commit();
            
            Log::info('Vol supprimé avec succès', ['flight_id' => $flightId]);
            
            return response()->json([
                'message' => 'Vol supprimé avec succès',
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la suppression du vol', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'flight_id' => $flight->id,
                'user_id' => $request->user()->id
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de la suppression du vol: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Alternative method to remove a flight using GET request.
     */
    public function destroyAlternative(Request $request, Flight $flight)
    {
        // Même logique que la méthode destroy
        return $this->destroy($request, $flight);
    }
}