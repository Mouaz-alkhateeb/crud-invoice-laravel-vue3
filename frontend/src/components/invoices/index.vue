
<script setup>

import { onMounted, ref } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router';


let invoices = ref([]);
let searchInvoice = ref([]);
const router = useRouter();

onMounted(async () => {
    getInvoices();
})

const getInvoices = async () => {

  let response = await axios.get('http://localhost:8000/api/get_invoices');
  invoices.value = response.data.invoices;
}

const search = async () => {
    let response = await axios.get('http://localhost:8000/api/search_invoice?s=' + searchInvoice.value);
    invoices.value=response.data.invoices
}

const addInvoice = async () => {

    let form = await axios.get('http://localhost:8000/api/add_invoice');

    router.push('/invoices/add')

}

const onShow = (id) => {
  router.push('/invoices/show/'+id);
}
const onBack = () => {
    router.push('/');
}


</script>

<template>
  <v-container >
    <v-card>
  <v-card-title class="d-flex align-center justify-space-between">
    <div>
      <h2 class="mb-0">Invoices</h2>
    </div>
    <div class="d-flex align-center">
      <v-btn class="mr-3" @click="addInvoice()">New Invoice</v-btn>
      <v-btn class="mr-3" @click="onBack()">Back</v-btn>
    </div>
  </v-card-title>
      <v-row class="mx-2">

        <v-col cols="12" sm="12">
          <v-text-field
          v-model="searchInvoice"
          @keyup="search()"
        label="Search"
        :loading="loading"
        append-inner-icon="mdi-magnify"

      ></v-text-field>
        </v-col>
      </v-row>
  <v-table >
    <thead>
      <tr>
        <th class="text-center">
          Id
        </th>
        <th class="text-center">
          Number
        </th>
        <th class="text-center">
          Customer
        </th>
        <th class="text-center">
          Date
        </th>
        <th class="text-center">
          Due-Date
        </th>
        <th class="text-center">
          Total
        </th>
      </tr>
    </thead>
    <tbody  class="text-center">
      <tr v-for="invoice in invoices" :key="invoice.id">
         <td>
          <a  @click="onShow(invoice.id)">
            #{{ invoice.id }}
          </a>
        </td>
        <td>{{ invoice.number }}</td>
        <td v-if="invoice.customer">{{ invoice.customer.first_name }}</td>
        <td v-else>----</td>
       <td>{{ invoice.date }}</td>
        <td>{{ invoice.due_date }}</td>
        <td>{{ invoice.total }}</td>
      </tr>
    </tbody>
  </v-table>



</v-card>
  </v-container>
</template>









