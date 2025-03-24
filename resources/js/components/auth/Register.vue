<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Inscription</div>
                <div class="card-body">
                    <div v-if="success" class="alert alert-success">
                        {{ success }}
                    </div>

                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>

                    <form v-if="!success" @submit.prevent="register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                v-model="form.name"
                                required
                            />
                        </div>

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
                            <small class="text-muted"
                                >Le mot de passe doit contenir au moins 8
                                caractères.</small
                            >
                        </div>

                        <div class="mb-3">
                            <label
                                for="password_confirmation"
                                class="form-label"
                                >Confirmer le mot de passe</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                v-model="form.password_confirmation"
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
                                    ? "Inscription en cours..."
                                    : "S'inscrire"
                            }}
                        </button>
                    </form>

                    <div v-if="success" class="mt-3 text-center">
                        <router-link to="/login" class="btn btn-primary me-2"
                            >Se connecter</router-link
                        >
                        <button @click="loginDirect" class="btn btn-secondary">
                            Accéder directement
                        </button>
                    </div>

                    <div v-if="!success" class="mt-3">
                        <p>
                            Déjà un compte?
                            <router-link to="/login"
                                >Connectez-vous</router-link
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
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            error: null,
            success: null,
            loading: false,
            registeredToken: null,
        };
    },
    methods: {
        register() {
            this.loading = true;
            this.error = null;

            console.log("Tentative d'inscription avec:", this.form);

            // Récupérer le jeton CSRF depuis la balise meta
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/api/register", {
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
                    console.log("Inscription réussie:", data);
                    this.success =
                        "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    this.registeredToken = data.token;
                    // Ne pas rediriger automatiquement pour laisser l'utilisateur voir le message
                })
                .catch((error) => {
                    console.error("Erreur d'inscription:", error);
                    this.error = error.message || "Une erreur est survenue";
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        loginDirect() {
            // Utiliser directement le token reçu lors de l'inscription
            if (this.registeredToken) {
                localStorage.setItem("token", this.registeredToken);
                this.$router.push("/flights");
                window.location.reload();
            }
        },
    },
};
</script>
