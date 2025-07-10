import LoginView from "@/Pages/Auth/LoginView.vue";
import RegisterView from "@/Pages/Auth/RegisterView.vue";
import AddProduct from "@/Pages/AddProduct.vue";
import EditProductPage from "@/Pages/EditProductPage.vue";
import ErrorPage from "@/Pages/ErrorPage.vue";
import HomePage from "@/Pages/HomePage.vue";



const routes = [
    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: { guest: true },
    },
    { path: "/", name: "home", component: HomePage, meta: { auth: true } },
    {
        path: "/add-product",
        name: "add-product",
        component: AddProduct,
        meta: { auth: true },
    },
    {
        path: "/product/:id",
        name: "product",
        component: EditProductPage,
        meta: { auth: true },
    },
    {
        path: "/register",
        name: "register",
        component: RegisterView,
        meta: { guest: true },
    },
    {
        path: "/error",
        name: "error",
        component: ErrorPage,
        props: (route) => ({
            errorStatus: Number(route.query.status) || 404,
            errorMessage: route.query.message,
        }),
    },
    {
        path: "/:pathMatch(.*)*",
        name: "not-found",
        component: ErrorPage,
        props: { errorStatus: 404 },
    },
];



export default routes;
