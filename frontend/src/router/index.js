import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TheaterView from "@/views/TheaterView.vue";
import MovieView from "@/views/MovieView.vue";
import ReportingView from "@/views/ReportingView.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
        },
        {
            path: '/reporting',
            name: 'reporting',
            component: ReportingView
        },
        {
            path: '/theaters',
            name: 'theaters',
            component: TheaterView
        },
        {
            path: '/movies',
            name: 'movies',
            component: MovieView
        }
        // {
        //   path: '/about',
        //   name: 'about',
        //   // route level code-splitting
        //   // this generates a separate chunk (About.[hash].js) for this route
        //   // which is lazy-loaded when the route is visited.
        //   component: () => import('../views/AboutView.vue'),
        // },
    ],
})

export default router
