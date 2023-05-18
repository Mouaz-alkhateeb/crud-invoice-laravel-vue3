import { createRouter, createWebHistory } from 'vue-router';


import HomeView from '../views/HomeView.vue';
import NotFoundView from '../views/NotFoundView.vue';
import AddView from '../views/AddView.vue';
import ShowView from '../views/ShowView.vue';
import EditView from '../views/EditView.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: HomeView
    },
    {
      path: '/invoices/add',
      component: AddView
    },
    {
      path: '/invoices/show/:id',
      component: ShowView
    },
    {
      path: '/invoices/edit/:id',
      component: EditView
    },
    {
      path: '/:pathMatch(.*)*',
      component: NotFoundView
    }
  ]
});

export default router;
