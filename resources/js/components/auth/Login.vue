<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Connexion</div>
                <div class="card-body">
                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>

                    <form @submit.prevent="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                v-model="form.email"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label"
                                >Mot de passe</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                v-model="form.password"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="loading"
                        >
                            {{
                                loading
                                    ? "Connexion en cours..."
                                    : "Se connecter"
                            }}
                        </button>
                    </form>

                    <div class="mt-3">
                        <p>
                            Pas encore de compte?
                            <router-link to="/register"
                                >Inscrivez-vous</router-link
                            >
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
            form: {
                email: "",
                password: "",
            },
            error: null,
            loading: false,
        };
    },
    methods: {
        login() {
            this.loading = true;
            this.error = null;

            console.log("Tentative de connexion avec:", this.form);

            // Récupérer le jeton CSRF depuis la balise meta
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
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
                    console.log("Connexion réussie:", data);
                    // Stocker le token
                    localStorage.setItem("token", data.token);

                    // Utiliser un délai court pour s'assurer que le token est bien enregistré
                    setTimeout(() => {
                        // Forcer la redirection directement en utilisant window.location
                        window.location.href = "/flights";
                    }, 500);
                })
                .catch((error) => {
                    console.error("Erreur de connexion:", error);
                    this.error = error.message || "Identifiants incorrects";
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>
