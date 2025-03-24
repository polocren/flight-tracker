<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-list-alt me-2"></i> Mes vols</h2>
            <router-link to="/flights/new" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter un vol
            </router-link>
        </div>

        <div v-if="loading" class="text-center">
            <p>Chargement des vols...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else-if="flights.length === 0" class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Vous n'avez pas encore
            enregistré de vol. Commencez en ajoutant votre premier vol !
        </div>

        <div v-else>
            <!-- Statistiques des vols -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i
                                    class="fas fa-plane-departure fa-2x mb-2"
                                    style="color: var(--primary-color)"
                                ></i>
                                <h5>Total des vols</h5>
                                <p class="h2">{{ flights.length }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i
                                    class="fas fa-clock fa-2x mb-2"
                                    style="color: var(--secondary-color)"
                                ></i>
                                <h5>Heures de vol totales</h5>
                                <p class="h2">
                                    {{
                                        formatTotalDuration(totalFlightMinutes)
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card">
                                <i
                                    class="fas fa-tachometer-alt fa-2x mb-2"
                                    style="color: var(--accent-color)"
                                ></i>
                                <h5>Durée moyenne</h5>
                                <p class="h2">
                                    {{ formatDuration(averageFlightMinutes) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des vols -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Départ</th>
                            <th>Arrivée</th>
                            <th>Durée</th>
                            <th>Type d'appareil</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="flight in flights" :key="flight.id">
                            <td>{{ formatDate(flight.departure_time) }}</td>
                            <td>{{ flight.departure_airport }}</td>
                            <td>{{ flight.arrival_airport }}</td>
                            <td>
                                {{ formatDuration(flight.flight_duration) }}
                            </td>
                            <td>{{ flight.aircraft_type }}</td>
                            <td>
                                <router-link
                                    :to="`/flights/${flight.id}/edit`"
                                    class="btn btn-sm btn-secondary me-1"
                                >
                                    <i class="fas fa-edit"></i>
                                </router-link>
                                <button
                                    @click="deleteFlight(flight.id)"
                                    class="btn btn-sm btn-danger"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            flights: [],
            loading: true,
            error: null,
        };
    },
    computed: {
        totalFlightMinutes() {
            if (!this.flights.length) return 0;
            return this.flights.reduce(
                (total, flight) => total + flight.flight_duration,
                0
            );
        },
        averageFlightMinutes() {
            if (!this.flights.length) return 0;
            return Math.round(this.totalFlightMinutes / this.flights.length);
        },
    },
    created() {
        this.fetchFlights();
    },
    methods: {
        fetchFlights() {
            this.loading = true;
            this.error = null;

            const token = localStorage.getItem("token");

            if (!token) {
                this.error =
                    "Vous n'êtes pas authentifié. Veuillez vous reconnecter.";
                this.loading = false;
                return;
            }

            fetch("/api/flights", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
                .then((response) => {
                    console.log("Réponse de fetchFlights:", response);
                    if (!response.ok) {
                        throw new Error("Erreur lors du chargement des vols");
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Vols récupérés:", data);
                    this.flights = data;
                })
                .catch((error) => {
                    console.error("Erreur fetchFlights:", error);
                    this.error = error.message;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat("fr-FR", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            }).format(date);
        },
        formatDuration(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return `${hours}h ${mins}min`;
        },
        formatTotalDuration(minutes) {
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;

            if (hours >= 100) {
                return `${hours}h ${mins}min`;
            } else {
                return `${hours}h ${mins}min`;
            }
        },
        deleteFlight(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce vol ?")) {
                this.loading = true;

                const token = localStorage.getItem("token");

                if (!token) {
                    this.error =
                        "Vous n'êtes pas authentifié. Veuillez vous reconnecter.";
                    this.loading = false;
                    return;
                }

                fetch(`/api/flights/delete/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                    .then((response) => {
                        console.log("Réponse de suppression:", response);

                        if (!response.ok) {
                            return response.json().then((data) => {
                                throw new Error(
                                    data.message ||
                                        "Erreur lors de la suppression du vol"
                                );
                            });
                        }

                        return response.json();
                    })
                    .then((data) => {
                        console.log("Suppression réussie:", data);
                        this.fetchFlights();
                    })
                    .catch((error) => {
                        console.error("Erreur deleteFlight:", error);
                        this.error =
                            error.message ||
                            "Erreur lors de la suppression du vol";
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
    },
};
</script>
