<template>
    <div>
        <div class="home-hero">
            <i class="fas fa-plane airplane-icon"></i>
            <h1 class="display-4">Bienvenue sur FlightTracker</h1>
            <p class="lead">
                Suivez vos heures de vol, consultez les informations sur les
                avions et obtenez la météo aéronautique en temps réel.
            </p>
            <div>
                <router-link
                    to="/flights"
                    class="btn btn-light me-2"
                    v-if="isLoggedIn"
                >
                    <i class="fas fa-list-alt me-1"></i> Mes Vols
                </router-link>
                <router-link to="/login" class="btn btn-light me-2" v-else>
                    <i class="fas fa-sign-in-alt me-1"></i> Connexion
                </router-link>
                <router-link to="/aircraft" class="btn btn-outline-light me-2">
                    <i class="fas fa-plane me-1"></i> Avions
                </router-link>
                <router-link to="/weather" class="btn btn-outline-light">
                    <i class="fas fa-cloud-sun me-1"></i> Météo
                </router-link>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i
                            class="fas fa-plane-departure fa-4x mb-3"
                            style="color: var(--primary-color)"
                        ></i>
                        <h3>Suivez vos vols</h3>
                        <p>
                            Enregistrez vos heures de vol et gardez une trace
                            précise de votre expérience aéronautique.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i
                            class="fas fa-info-circle fa-4x mb-3"
                            style="color: var(--secondary-color)"
                        ></i>
                        <h3>Informations avions</h3>
                        <p>
                            Consultez les spécifications techniques des avions
                            légers les plus populaires.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i
                            class="fas fa-cloud-sun fa-4x mb-3"
                            style="color: var(--accent-color)"
                        ></i>
                        <h3>Météo aéronautique</h3>
                        <p>
                            Obtenez les METARs et TAFs des aéroports du monde
                            entier en temps réel.
                        </p>
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
            isLoggedIn: false,
        };
    },
    created() {
        this.isLoggedIn = !!localStorage.getItem("token");
    },
};
</script>
