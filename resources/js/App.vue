<template>
    <div>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <router-link class="navbar-brand" to="/">
                    <i class="fas fa-plane-departure me-2"></i>FlightTracker
                </router-link>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/"
                                ><i class="fas fa-home me-1"></i>
                                Accueil</router-link
                            >
                        </li>
                        <li class="nav-item" v-if="isLoggedIn">
                            <router-link class="nav-link" to="/flights"
                                ><i class="fas fa-list-alt me-1"></i> Mes
                                Vols</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/aircraft"
                                ><i class="fas fa-plane me-1"></i>
                                Avions</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/weather"
                                ><i class="fas fa-cloud-sun me-1"></i>
                                Météo</router-link
                            >
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <template v-if="isLoggedIn">
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#"
                                    @click.prevent="logout"
                                    ><i class="fas fa-sign-out-alt me-1"></i>
                                    Déconnexion</a
                                >
                            </li>
                        </template>
                        <template v-else>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/login"
                                    ><i class="fas fa-sign-in-alt me-1"></i>
                                    Connexion</router-link
                                >
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/register"
                                    ><i class="fas fa-user-plus me-1"></i>
                                    Inscription</router-link
                                >
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4 fade-in">
            <router-view></router-view>
        </div>

        <!-- Le footer a été supprimé ici -->
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
        // Vérifier si un token existe
        this.isLoggedIn = !!localStorage.getItem("token");
        console.log("État de connexion:", this.isLoggedIn);
    },
    methods: {
        logout() {
            // Récupérer le token d'authentification
            const token = localStorage.getItem("token");

            // Appeler l'API de déconnexion
            fetch("/api/logout", {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            })
                .then(() => {
                    // Supprimer le token et rediriger
                    localStorage.removeItem("token");
                    this.isLoggedIn = false;
                    window.location.href = "/login";
                })
                .catch((error) =>
                    console.error("Erreur lors de la déconnexion:", error)
                );
        },
    },
};
</script>
