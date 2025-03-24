<template>
    <div>
        <h2>Météo aéronautique</h2>

        <div class="card mb-4">
            <div class="card-body">
                <form @submit.prevent="searchWeather">
                    <div class="mb-3">
                        <label for="icao" class="form-label"
                            >Code OACI de l'aéroport</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="icao"
                            v-model="icao"
                            placeholder="Ex: LFPG, KJFK, EGLL"
                            required
                            pattern="[A-Za-z]{4}"
                            maxlength="4"
                        />
                        <small class="form-text text-muted"
                            >Entrez le code OACI à 4 lettres de
                            l'aéroport</small
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        :disabled="loading"
                    >
                        {{
                            loading
                                ? "Recherche en cours..."
                                : "Obtenir la météo"
                        }}
                    </button>
                </form>
            </div>
        </div>

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-if="weatherData">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">METAR - {{ icao.toUpperCase() }}</h5>
                </div>
                <div class="card-body">
                    <p class="font-monospace">{{ weatherData.raw_metar }}</p>

                    <div v-if="weatherData.metar">
                        <hr />
                        <h6>Informations décodées:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li>
                                        <strong>Aéroport:</strong>
                                        {{
                                            weatherData.metar.station ||
                                            icao.toUpperCase()
                                        }}
                                    </li>
                                    <li>
                                        <strong>Heure d'observation:</strong>
                                        {{
                                            formatTime(
                                                weatherData.metar.observed
                                            )
                                        }}
                                    </li>
                                    <li>
                                        <strong>Vent:</strong>
                                        {{
                                            formatWind(
                                                weatherData.metar
                                                    .wind_direction,
                                                weatherData.metar.wind_speed
                                            )
                                        }}
                                    </li>
                                    <li>
                                        <strong>Visibilité:</strong>
                                        {{
                                            formatVisibility(
                                                weatherData.metar.visibility
                                            )
                                        }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li>
                                        <strong>Température:</strong>
                                        {{
                                            weatherData.metar.temperature
                                                ?.celsius || "N/A"
                                        }}°C
                                    </li>
                                    <li>
                                        <strong>Point de rosée:</strong>
                                        {{
                                            weatherData.metar.dewpoint
                                                ?.celsius || "N/A"
                                        }}°C
                                    </li>
                                    <li>
                                        <strong>QNH:</strong>
                                        {{
                                            formatQNH(
                                                weatherData.metar.barometer
                                            )
                                        }}
                                    </li>
                                    <li>
                                        <strong>Nuages:</strong>
                                        {{
                                            formatClouds(
                                                weatherData.metar.clouds
                                            )
                                        }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="weatherData.raw_taf" class="card">
                <div class="card-header">
                    <h5 class="mb-0">TAF - {{ icao.toUpperCase() }}</h5>
                </div>
                <div class="card-body">
                    <p class="font-monospace">{{ weatherData.raw_taf }}</p>
                </div>
            </div>

            <div v-if="weatherData.note" class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                {{ weatherData.note }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            icao: "",
            weatherData: null,
            loading: false,
            error: null,
        };
    },
    methods: {
        searchWeather() {
            this.loading = true;
            this.error = null;
            this.weatherData = null;

            if (!this.icao.match(/^[A-Za-z]{4}$/)) {
                this.error = "Le code OACI doit être composé de 4 lettres";
                this.loading = false;
                return;
            }

            fetch(`/api/weather/airport?icao=${this.icao}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            "Impossible de récupérer les données météo pour cet aéroport"
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Données météo reçues:", data);
                    this.weatherData = data;
                })
                .catch((error) => {
                    console.error("Erreur:", error);
                    this.error = error.message;
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        formatTime(timestamp) {
            if (!timestamp) return "N/A";

            const date = new Date(timestamp * 1000);
            return (
                new Intl.DateTimeFormat("fr-FR", {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    timeZone: "UTC",
                }).format(date) + " UTC"
            );
        },
        formatWind(direction, speed) {
            if (!direction || !speed) return "N/A";

            return `${direction}° à ${speed} kt`;
        },
        formatVisibility(visibility) {
            if (!visibility) return "N/A";

            if (visibility.meters) {
                return `${visibility.meters} mètres`;
            } else if (visibility.miles) {
                return `${visibility.miles} miles`;
            } else {
                return "N/A";
            }
        },
        formatQNH(barometer) {
            if (!barometer) return "N/A";

            if (barometer.hpa) {
                return `${barometer.hpa} hPa`;
            } else if (barometer.inHg) {
                return `${barometer.inHg} inHg`;
            } else {
                return "N/A";
            }
        },
        formatClouds(clouds) {
            if (!clouds || clouds.length === 0) return "Ciel clair";

            return clouds
                .map((cloud) => {
                    let type = "";
                    switch (cloud.code) {
                        case "FEW":
                            type = "Quelques";
                            break;
                        case "SCT":
                            type = "Épars";
                            break;
                        case "BKN":
                            type = "Fragmenté";
                            break;
                        case "OVC":
                            type = "Couvert";
                            break;
                        default:
                            type = cloud.code;
                    }

                    return `${type} à ${cloud.base_feet_agl} pieds`;
                })
                .join(", ");
        },
    },
};
</script>
