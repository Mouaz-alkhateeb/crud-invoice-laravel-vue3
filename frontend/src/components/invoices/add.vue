<script setup>

import { onMounted, ref } from "vue";
import axios from 'axios';
import { stringifyQuery } from "vue-router";
import { useRouter } from 'vue-router'

const router = useRouter()
let form = ref([])
let customers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])
const showModal = ref(false)
const hideModal = ref(true)
let listProducts=ref([])

onMounted(async () => {
    indexForm();
  getCustomers();
  getProducts();
})

const indexForm = async () => {
    let response = await axios.get('http://localhost:8000/api/add_invoice');
    form.value= response.data
}

const getCustomers = async () => {
   let response = await axios.get('http://localhost:8000/api/get_customers');
    customers.value = response.data.customers
}

const getProducts = async () => {
    let response = await axios.get('http://localhost:8000/api/get_products');
    listProducts.value= response.data.products
}

const addCart = (item) => {
  const itemCart = {
    id: item.id,
    item_code: item.item_code,
    description: item.description,
    unit_price: item.unit_price,
    quantity: item.quantity
  }
  listCart.value.push(itemCart)
  closeModal()
}

const openModal = () => {
 showModal.value = !showModal.value
}
const closeModal = () => {
 showModal.value = !hideModal.value
}

const removeItem = (index) => {
  listCart.value.splice(index,1)
}

const SubTotal = () => {
  let total = 0;
  listCart.value.map((data) => {
    total = total + (data.quantity * data.unit_price);
  });
  return total;
}

const Total = () => {
  return SubTotal() - form.value.discount
}

const onSave = () => {
  if (listCart.value.length >= 1) {

    let subtotal = 0;
    subtotal = SubTotal()

    let total = 0;
    total = Total()

    let formData = new FormData()
    formData.append('invoice_items', JSON.stringify(listCart.value))
    formData.append('customer_id', customer_id.value)
    formData.append('date', form.value.date)
    formData.append('due_date', form.value.due_date)
    formData.append('number',form.value.number)
    formData.append('reference', form.value.reference)
    formData.append('discount', form.value.discount)
    formData.append('subtotal', subtotal)
    formData.append('total', total)
    formData.append('terms_and_conditions', form.value.terms_and_conditions)


    axios.post('http://localhost:8000/api/store_invoice', formData);


    listCart.value = [];
    router.push('/')
  }
}


</script>



<template>
  <div class="container">
      <div class="invoices">

      <div class="card__header">
          <div>
              <h2 class="invoice__title">New Invoice</h2>
          </div>
          <div>

          </div>
      </div>

      <div class="card__content">
          <div class="card__content--header">
              <div>
                  <p class="my-1">Customer</p>
                  <select name="customers" id="customers"  class="input" v-model="customer_id">
                      <option value="" disabled>Select Customer</option>
                      <option :value="customer.id" v-for="customer in customers" :key="customer.id">{{ customer.first_name }}</option>
                  </select>
              </div>
              <div>
                  <p class="my-1">Date</p>
                  <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                  <p class="my-1">Due Date</p>
                  <input id="due_date" type="date" class="input"  v-model="form.due_date">
              </div>
              <div>
                  <p class="my-1">Numero</p>
                  <input type="text" class="input" v-model="form.number">
                  <p class="my-1">Reference(Optional)</p>
                  <input type="text" class="input" v-model="form.reference">
              </div>
          </div>
          <br><br>
          <div class="table">

              <div class="table--heading2">
                  <p>Item Description</p>
                  <p>Unit Price</p>
                  <p>Qty</p>
                  <p>Total</p>
                  <p></p>
              </div>

              <!-- item 1 -->
              <div class="table--items2" v-for="(itemCart,index) in listCart" :key="index">
                  <p>#{{ itemCart.item_code }} {{ itemCart.description }} </p>
                  <p>
                      <input type="text" class="input" v-model="itemCart.unit_price">
                  </p>
                  <p>
                      <input type="text" class="input" v-model="itemCart.quantity">
                  </p>
                  <p v-if="itemCart.quantity">
                      $ {{ (itemCart.quantity)*(itemCart.unit_price) }}
                  </p>
                  <p v-else></p>
                  <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(index)">
                      &times;
                  </p>
              </div>
              <div style="padding: 10px 30px !important;">
                  <button class="btn btn-sm btn__open--modal" @click="openModal()">
                    Add New Line
                  </button>
              </div>
          </div>

          <div class="table__footer">
              <div class="document-footer" >
                  <p>Terms and Conditions</p>
                  <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions" ></textarea>
              </div>
              <div>
                  <div class="table__footer--subtotal">
                      <p>Sub Total</p>
                      <span>$ {{ SubTotal() }}</span>
                  </div>
                  <div class="table__footer--discount">
                      <p>Discount</p>
                      <input type="text" class="input" v-model="form.discount">
                  </div>
                  <div class="table__footer--total">
                      <p>Grand Total</p>
                      <span>$ {{ Total() }}</span>
                  </div>
              </div>
          </div>


      </div>
      <div class="card__header" style="margin-top: 40px;">
          <div>

          </div>
          <div>
              <a class="btn btn-secondary" @click="onSave()">
                  Save
              </a>
          </div>
      </div>

  </div>
  <!--==================== add modal items ====================-->
  <div class="modal main__modal " :class="{ show : showModal }">
      <div class="modal__content">
          <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
          <h3 class="modal__title">Add Item</h3>
          <hr><br>
          <div class="modal__items">
              <ul style="list-style: none;">
                <li v-for="(product,index) in listProducts" :key="index" style="display: grid; grid-template-columns: 30px 350px 15px;align-items: center; padding-bottom:5px;">
                  <p>{{ index+1 }}</p>
                  <a href="#">{{ product.item_code }} {{ product.description }}</a>
                  <button class="customBtn" @click="addCart(product)"> + </button>
              </li>
              </ul>
          </div>
          <br><hr>
          <div class="model__footer">
              <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                  Cancel
              </button>
              <button class="btn btn-light btn__close--modal " >Save</button>
          </div>
      </div>
  </div>
  </div>
</template>

<style scoped>
@import '@/assets/scss/InvoiceStyle.scss';


.customBtn {
  background-color: #ccc;
  color: #100f0f;
  border-radius: 50%;
  padding: 8px;
  border: none;
  cursor: pointer;
  width: 35px;
  height: 35px;
}

.customBtn:hover {
  background-color: #999;
}




</style>
