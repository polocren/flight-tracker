import { createRouter, createWebHistory } from "vue-router";

// Importation des composants
import Home from "../components/Home.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";
import FlightList from "../components/flights/FlightList.vue";
import FlightForm from "../components/flights/FlightForm.vue";
import AircraftList from "../components/aircraft/AircraftList.vue";
import AircraftDetail from "../components/aircraft/AircraftDetail.vue";
import WeatherSearch from "../components/weather/WeatherSearch.vue";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/register",
        name: "register",
        component: Register,
    },
    {
        path: "/flights",
        name: "flights",
        component: FlightList,
        meta: { requiresAuth: true },
    },
    {
        path: "/flights/new",
        name: "new-flight",
        component: FlightForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/flights/:id/edit",
        name: "edit-flight",
        component: FlightForm,
        meta: { requiresAuth: true },
    },
    {
        path: "/aircraft",
        name: "aircraft",
        component: AircraftList,
    },
    {
        path: "/aircraft/:id",
        name: "aircraft-detail",
        component: AircraftDetail,
    },
    {
        path: "/weather",
        name: "weather",
        component: WeatherSearch,
    },
];

const router = createRouter({
    history: createWebHistory("/"),
    routes,
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const isLoggedIn = !!localStorage.getItem("token");

    if (to.meta.requiresAuth && !isLoggedIn) {
        next("/login");
    } else {
        next();
    }
});

export default router;
