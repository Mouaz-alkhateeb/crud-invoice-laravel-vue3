<script setup>

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRoute,useRouter } from 'vue-router';


const route = useRoute();
const router = useRouter();
const invoiceId = ref(0);
const form = ref({})
const dateStr = ref('')
const dateCreatedAt =ref('')

onMounted(async () => {
  invoiceId.value = route.params.id;
  getInvoice();
});


const getInvoice = async () => {
  let response = await axios.get(`http://localhost:8000/api/get_invoice/${invoiceId.value}`);

  form.value = response.data.invoice;

  dateStr.value = form.value.created_at;

  const date = new Date(dateStr.value);

  const dateComponents = [
    date.getFullYear(),
    ('0' + (date.getMonth() + 1)).slice(-2),
    ('0' + date.getDate()).slice(-2),
   ];

const timeComponents = [
    ('0' + date.getHours()).slice(-2),
    ('0' + date.getMinutes()).slice(-2),
    ('0' + date.getSeconds()).slice(-2),
  ];

  dateCreatedAt.value = `${dateComponents.join('-')} ${timeComponents.join(':')}`;

}

const print = () => {
  window.print();
  router.push('/').catch(()=>{})
}

const onEdit = (id) => {
  router.push('/invoices/edit/'+id);
}

const onDelete = (id) => {
  axios.get('http://localhost:8000/api/delete_invoice/'+id);
  router.push('/');
}

</script>
<template>
    <div class="container">
      <div class="invoices" >
          <div class="card__header">
            <!-- <div>
                <h2 class="invoice__title">Invoice</h2>
            </div> -->
          <div>
      </div>
      </div>
      <div>
          <!-- <div class="card__header--title ">
              <h1 class="mr-2">#{{ form.id }}</h1>
              <p>{{ dateCreatedAt }} </p>
          </div> -->

          <div>
              <ul  class="card__header-list">
                  <li>
                      <!-- Select Btn Option -->
                      <button class="selectBtnFlat" @click="print()">
                          <i class="fas fa-print"></i>
                          Print
                      </button>
                      <!-- End Select Btn Option -->
                  </li>
                  <li>
                      <!-- Select Btn Option -->
                      <button class="selectBtnFlat" @click="onEdit(form.id)">
                          <i class=" fas fa-reply"></i>
                          Edit
                      </button>
                      <!-- End Select Btn Option -->
                  </li>
                  <li>
                      <!-- Select Btn Option -->
                      <button class="selectBtnFlat " @click="onDelete(form.id)">
                          <i class=" fas fa-pencil-alt"></i>
                          Delete
                      </button>
                      <!-- End Select Btn Option -->
                  </li>

              </ul>
          </div>
      </div>
      <div class="table invoice" style="background-color:beige;">
          <div class="logo">
              <img src="/logo.png" alt="" style="width: 200px;">
          </div>
          <!-- <div class="invoice__header--title">
              <p class="invoice__header--title-1">Invoice</p>

          </div> -->
          <div class="invoice__header--item">
              <div>
                  <h2>Invoice To:</h2>
                  <p v-if="form.customer">{{ form.customer.first_name }}</p>
                  <p v-else>---</p>
              </div>
              <div>
                      <div class="invoice__header--item1">
                          <p>Invoice</p>
                          <span>#{{ form.number }}</span>
                      </div>
                      <div class="invoice__header--item2">
                          <p>Date</p>
                          <span>{{ form.date }}</span>
                      </div>
                      <div class="invoice__header--item2">
                          <p>Due Date</p>
                          <span>{{ form.due_date }}</span>
                      </div>
                      <div class="invoice__header--item2">
                          <p>Reference</p>
                          <span>{{ form.reference }}</span>
                      </div>

              </div>
          </div>

          <div class="table py1">

              <div class="table--heading3">
                  <p >#</p>
                  <p>Item Description</p>
                  <p>Unit Price</p>
                  <p>Qty</p>
                  <p>Total</p>
              </div>

              <!-- item 1 -->
              <div class="table--items3" v-for="(item,index) in form.invoice_items" :key="index">
                  <p >{{ index+1 }}</p>
                  <p >{{ item.product.description }}</p>
                  <p >$ {{ item.unit_price }}</p>
                  <p >{{ item.quantity }}</p>
                  <p> $ {{ item.unit_price * item.quantity }}</p>
              </div>



          </div>

          <div  class="invoice__subtotal">
              <div>
                  <h2>Thank you for your business</h2>
              </div>
              <div>
                  <div class="invoice__subtotal--item1">
                      <p>Sub Total</p>
                      <span> $ {{ form.sub_total }}</span>
                  </div>
                  <div class="invoice__subtotal--item2">
                      <p>Discount</p>
                      <span>$ {{ form.discount }}</span>
                  </div>

              </div>
          </div>

          <div class="invoice__total">
              <div>
                  <h2>Terms and Conditions</h2>
                  <p>{{ form.terms_and_conditions }}</p>
              </div>
              <div>
                  <div class="grand__total" >
                      <div class="grand__total--items">
                          <p>Grand Total</p>
                          <span>$ {{form.total}}</span>
                      </div>
                  </div>
              </div>
          </div>

      </div>
    </div>
    <br>
  </div>
</template>


<style scoped>
@import '@/assets/scss/InvoiceStyle.scss';


@media print {
  /* Hide buttons and other elements that should not be printed */
  .selectBtnFlat {
    display: none;
  }

  /* Adjust font size and margins for printing */
  h2 {
    font-size: 18px;
    margin-bottom: 10px;
  }

  /* Add page breaks before certain elements */


}

</style>
