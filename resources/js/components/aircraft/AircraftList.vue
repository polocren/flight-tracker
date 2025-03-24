<template>
    <div>
        <h2 class="mb-4">
            <i class="fas fa-plane me-2"></i> Avions légers populaires
        </h2>

        <div v-if="loading" class="text-center">
            <p>Chargement des informations...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else-if="aircraft.length === 0" class="alert alert-info">
            Aucune information sur les avions disponible pour le moment.
        </div>

        <div v-else class="row mt-4">
            <div
                v-for="plane in aircraft"
                :key="plane.id"
                class="col-md-6 col-lg-4 mb-4"
            >
                <div class="card h-100">
                    <img
                        v-if="plane.image_url"
                        :src="plane.image_url"
                        class="card-img-top"
                        :alt="plane.name"
                        style="height: 200px; object-fit: cover"
                        @error="handleImageError"
                    />
                    <div class="card-body">
                        <h5 class="card-title">{{ plane.name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ plane.manufacturer }} {{ plane.model }}
                        </h6>
                        <div class="card-text mb-3">
                            <p class="text-truncate">{{ plane.description }}</p>
                        </div>
                        <router-link
                            :to="`/aircraft/${plane.id}`"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-info-circle me-1"></i> Voir les
                            détails
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            aircraft: [],
            loading: true,
            error: null,
        };
    },
    created() {
        this.fetchAircraft();
    },
    methods: {
        fetchAircraft() {
            fetch("/api/aircraft")
                .then((response) => {
                    console.log("Réponse API aircraft:", response);
                    if (!response.ok) {
                        throw new Error(
                            "Erreur lors du chargement des informations sur les avions"
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Données des avions:", data);
                    this.aircraft = data;
                })
                .catch((error) => {
                    console.error("Erreur:", error);
                    this.error = error.message;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        handleImageError(event) {
            // Remplacer par une image par défaut
            event.target.src =
                "https://via.placeholder.com/400x300?text=Avion+non+disponible";
        },
    },
};
</script>
