<template>
  <div class="button-container">
    <router-link :to="{ path: 'invoices' }" class="button">Invoices</router-link>

    <router-link :to="{ path: 'products' }" class="button">Products</router-link>
  </div>
</template>

<style scoped>
.button-container {
  display: flex;
  justify-content: center; /* Center the buttons horizontally */
  align-items: center; /* Center the buttons vertically */
  margin-top: 20px; /* Add space between buttons and the top of the page */
}

.button {
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  background-color: #505150;
  color: white;
  margin-right: 10px; /* Add space between the buttons */
}

.button:last-child {
  margin-right: 0; /* Remove the right margin from the last button */
}
</style>
