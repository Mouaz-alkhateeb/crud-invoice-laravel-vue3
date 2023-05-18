

<script setup>

import { onMounted, ref } from "vue";
import axios from 'axios';
import { useRouter } from "vue-router";


let products = ref([])
let router = useRouter()

onMounted(async () => {
    getProducts()
})

const getProducts = async () => {
    let response = await axios.get('http://localhost:8000/api/get_all_products');
    products.value = response.data.products;
}

const ourImage = (img) => {
    return "http://localhost:8000/upload/"+img
}
const newProduct = () => {
   router.push("/products/add")
}
const onEdit = (productId) => {
    router.push("/products/edit/"+productId)
}

const onDelete = (productId) => {
    Swal.fire({
        title: "Are You Sure ..!!",
        text: "You Can't go Back",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonText: "Cancel",
        confirmButtonText:"Yes,Delete it..!!"
    })
        .then((result) => {
            if (result.value) {
                axios.get('http://localhost:8000/api/delete_product/' + productId)
                    .then(() => {
                        Swal.fire("Success", "Products Deleted Successfully", "success");
                        getProducts();
                    })
                    .catch(() => {
                        Swal.fire("Failed", "There Is Some Wrong.", "warning")
                    })
}
  })
}
const onBack = () => {
    router.push('/');
}
</script>

<template>
   <div class="container">

      <div class="products__list table  my-3">

          <div class="customers__titlebar dflex justify-content-between align-items-center">
              <div class="customers__titlebar--item">
                  <h1 class="my-1">Products</h1>
              </div>
              <div class="customers__titlebar--item">
                  <button class="btn btn-secondary my-1" @click="newProduct()">
                      Add Product
                  </button>
                  <button class="btn btn-secondary ml-1" @click="onBack">
                        Back
                    </button>
              </div>
          </div>

          <div class="table--heading mt-2 products__list__heading " style="padding-top: 20px;background:#FFF" >
              <!-- <p class="table--heading--col1">&#32;</p> -->
              <p class="table--heading--col1">image</p>
              <p class="table--heading--col2">
                  Product
              </p>
              <p class="table--heading--col4">Type</p>
              <p class="table--heading--col3">
                  Inventory
              </p>
              <!-- <p class="table--heading--col5">&#32;</p> -->
              <p class="table--heading--col5">actions</p>
          </div>

          <!-- product 1 -->
          <div v-if="products.length > 0">
            <div  class="table--items products__list__item" v-for="product in products" :key="product.id">
              <div class="products__list__item--imgWrapper">
                  <img class="products__list__item--img" :src="ourImage(product.photo)"  v-if="product.photo" style="height: 40px;">
              </div>
              <p href="# " class="table--items--col2">
                 {{ product.name }}
              </p>
              <p class="table--items--col2">
                  {{ product.type }}
              </p>
              <p class="table--items--col3">
                  {{ product.quantity }}
              </p>
              <div>
                  <button class="btn-icon btn-icon-success" style="margin-right: 5px;" @click="onEdit(product.id)">
                      <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button class="btn-icon btn-icon-danger" @click="onDelete(product.id)">
                      <i class="far fa-trash-alt"></i>
                  </button>
              </div>
          </div>
        </div>

          <div v-else class="table--items products__list__item" ><p>Products Not Found</p></div>

      </div>

  </div>
</template>

<style scoped>
@import '@/assets/scss/ProductStyle.scss';
</style>
