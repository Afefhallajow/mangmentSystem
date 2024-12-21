import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import axios from "axios";
import store from "../store/index";

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/LoginPage.vue')
  },
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { requiresAuth: true }
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/project',
    name: 'project',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Project/project.vue')
  },
  {
    path: '/project/add',
    name: 'addProject',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Project/addProject.vue')
  },
  {
    path: '/project/edit/:id',
    name: 'editProject',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Project/addProject.vue')
  },
  {
    path: '/task',
    name: 'task',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Task/task.vue')
  },
  {
    path: '/task/add',
    name: 'addTask',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Task/addTask.vue')
  },
  {
    path: '/task/edit/:id',
    name: 'editTask',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/projectMangment/Task/addTask.vue')
  },
{
    path: '/Inventory/products',
    name: 'products',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/invetoryMangment/ProductMangment/products.vue')
  },
  {
    path: '/Inventory/product/:id',
    name: 'showProduct',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/invetoryMangment/ProductMangment/ShowProduct.vue')
  },
  {
    path: '/Inventory/products/save',
    name: 'saveProduct',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/invetoryMangment/ProductMangment/addProduct.vue')
  },
  {
    path: '/Inventory/products/edit/:id',
    name: 'editProduct',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/invetoryMangment/ProductMangment/addProduct.vue')
  },
  {
    path: '/market',
    name: 'market',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/market.vue')
  },
  {
    path: '/cart',
    name: 'cart',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/CartPage.vue')
  }

]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('auth_token'); // Example: Check token
  if (!isAuthenticated) {
    // Prevent infinite redirection by checking if the user is already on the login page
    if (to.name !== 'login') {
      next('/login');  // Redirect to the login page
    } else {
      next();  // Stay on the login page
    }
  } else {
    try {
      // Fetch user permissions
      const response = await axios.get('/permission/get');
      store.commit('setPermissions', response.data.data); // Save to Vuex store or global state
      // Proceed to the next route
      next();
    } catch (error) {
      console.error('Error fetching permissions:', error);
      next(false); // Cancel navigation if there's an error
    }
  }
});

export default router
