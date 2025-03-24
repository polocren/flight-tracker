<template>
    <div>
        <h2>{{ isEditing ? "Modifier le vol" : "Ajouter un nouveau vol" }}</h2>

        <div v-if="loading" class="text-center">
            <p>Chargement...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <form v-else @submit.prevent="saveFlight" class="mt-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="departure_airport" class="form-label"
                        >Aéroport de départ</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="departure_airport"
                        v-model="form.departure_airport"
                        required
                    />
                    <small class="form-text text-muted"
                        >Code OACI ou nom de l'aéroport</small
                    >
                </div>

                <div class="col-md-6">
                    <label for="arrival_airport" class="form-label"
                        >Aéroport d'arrivée</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="arrival_airport"
                        v-model="form.arrival_airport"
                        required
                    />
                    <small class="form-text text-muted"
                        >Code OACI ou nom de l'aéroport</small
                    >
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="departure_time" class="form-label"
                        >Date et heure de départ</label
                    >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="departure_time"
                        v-model="form.departure_time"
                        required
                    />
                </div>

                <div class="col-md-6">
                    <label for="arrival_time" class="form-label"
                        >Date et heure d'arrivée</label
                    >
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="arrival_time"
                        v-model="form.arrival_time"
                        required
                    />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="aircraft_type" class="form-label"
                        >Type d'appareil</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="aircraft_type"
                        v-model="form.aircraft_type"
                        required
                    />
                    <small class="form-text text-muted"
                        >Par exemple: Cessna 172, Piper PA-28</small
                    >
                </div>

                <div class="col-md-6">
                    <label for="registration_number" class="form-label"
                        >Immatriculation</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        id="registration_number"
                        v-model="form.registration_number"
                    />
                    <small class="form-text text-muted">Optionnel</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea
                    class="form-control"
                    id="notes"
                    v-model="form.notes"
                    rows="3"
                ></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <router-link to="/flights" class="btn btn-secondary"
                    >Annuler</router-link
                >
                <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="saving"
                >
                    {{ saving ? "Sauvegarde en cours..." : "Sauvegarder" }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                departure_airport: "",
                arrival_airport: "",
                departure_time: "",
                arrival_time: "",
                aircraft_type: "",
                registration_number: "",
                notes: "",
            },
            loading: false,
            saving: false,
            error: null,
            isEditing: false,
        };
    },
    created() {
        // Déterminer si nous sommes en mode édition
        const flightId = this.$route.params.id;
        this.isEditing = !!flightId;

        if (this.isEditing) {
            this.fetchFlightDetails(flightId);
        }
    },
    methods: {
        fetchFlightDetails(id) {
            this.loading = true;

            // Récupérer le token d'authentification
            const token = localStorage.getItem("token");

            if (!token) {
                this.error =
                    "Vous n'êtes pas authentifié. Veuillez vous reconnecter.";
                this.loading = false;
                return;
            }

            fetch(`/api/flights/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            "Erreur lors du chargement des détails du vol"
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    // Formater les dates pour l'input datetime-local
                    this.form = {
                        ...data,
                        departure_time: this.formatDateTimeForInput(
                            data.departure_time
                        ),
                        arrival_time: this.formatDateTimeForInput(
                            data.arrival_time
                        ),
                    };
                })
                .catch((error) => {
                    this.error = error.message;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        formatDateTimeForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16);
        },
        saveFlight() {
            this.saving = true;
            this.error = null;

            console.log("Tentative de sauvegarde du vol:", this.form);

            const url = this.isEditing
                ? `/api/flights/${this.$route.params.id}`
                : "/api/flights";
            const method = this.isEditing ? "PUT" : "POST";

            // Récupérer le token d'authentification
            const token = localStorage.getItem("token");
            // Récupérer le jeton CSRF
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            if (!token) {
                this.error =
                    "Vous n'êtes pas authentifié. Veuillez vous reconnecter.";
                this.saving = false;
                return;
            }

            fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(this.form),
            })
                .then((response) => {
                    console.log("Réponse reçue:", response);
                    if (!response.ok) {
                        return response.json().then((errorData) => {
                            console.error("Erreur détaillée:", errorData);
                            throw errorData;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Vol enregistré avec succès:", data);
                    // Rediriger vers la liste des vols avec une méthode plus fiable
                    window.location.href = "/flights";
                })
                .catch((error) => {
                    console.error(
                        "Erreur lors de la sauvegarde du vol:",
                        error
                    );
                    this.error =
                        error.message ||
                        "Une erreur est survenue lors de la sauvegarde du vol";
                })
                .finally(() => {
                    this.saving = false;
                });
        },
    },
};
</script>
