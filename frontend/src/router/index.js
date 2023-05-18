import { createRouter, createWebHistory } from 'vue-router';

import IndexInvoiceView from '@/views/invoices/IndexInvoiceView.vue';
import AddInvoiceView from '@/views/invoices/AddInvoiceView.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import ShowInvoiceView from '@/views/invoices/ShowInvoiceView.vue';
import EditInvoiceView from '@/views/invoices/EditInvoiceView.vue';
import IndexProductView from '@/views/products/IndexProductView.vue';
import AddProductView from '@/views/products/AddProductView.vue';
import EditProductView from '@/views/products/EditProductView.vue';
import HelloWorldView from '@/views/HelloWorldView.vue'



const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      component: HelloWorldView
    },
    {
      path: '/invoices',
      component: IndexInvoiceView
    },
    {
      path: '/invoices/add',
      component: AddInvoiceView
    },
    {
      path: '/invoices/show/:id',
      component: ShowInvoiceView
    },
    {
      path: '/invoices/edit/:id',
      component: EditInvoiceView
    },
    {
      path: '/products',
      component: IndexProductView
    },
    {
      path: '/products/add',
      component: AddProductView
    },
    {
      path: '/products/edit/:id',
      component: EditProductView
    },
    {
      path: '/:pathMatch(.*)*',
      component: NotFoundView
    }
  ]
});

export default router;
