<template>
    <div>
        <div v-if="loading" class="text-center">
            <p>Chargement des informations...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else>
            <div class="row">
                <div class="col-md-6">
                    <img
                        v-if="aircraft.image_url"
                        :src="aircraft.image_url"
                        :alt="aircraft.name"
                        class="img-fluid rounded mb-4"
                    />

                    <h2>{{ aircraft.name }}</h2>
                    <h5 class="text-muted mb-4">
                        {{ aircraft.manufacturer }} {{ aircraft.model }}
                    </h5>

                    <p>{{ aircraft.description }}</p>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Spécifications techniques</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Vitesse de décollage</th>
                                        <td>
                                            {{ aircraft.takeoff_speed }} nœuds
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Poids maximal</th>
                                        <td>{{ aircraft.max_weight }} kg</td>
                                    </tr>
                                    <tr>
                                        <th>Vitesse de croisière</th>
                                        <td>
                                            {{ aircraft.cruise_speed }} nœuds
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Autonomie</th>
                                        <td>
                                            {{ aircraft.range }} miles nautiques
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <router-link to="/aircraft" class="btn btn-secondary"
                    >Retour à la liste</router-link
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            aircraft: {},
            loading: true,
            error: null,
        };
    },
    created() {
        this.fetchAircraftDetails();
    },
    methods: {
        fetchAircraftDetails() {
            const aircraftId = this.$route.params.id;

            fetch(`/api/aircraft/${aircraftId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            "Erreur lors du chargement des détails de l'avion"
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    this.aircraft = data;
                })
                .catch((error) => {
                    this.error = error.message;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>
