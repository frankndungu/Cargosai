<template>
  <div class="vendor-info-section">
    <div class="vendor-info-header" @click="isOpen = !isOpen">
      <span>Vendor</span>
      <i
        :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'"
        class="chevron"
      ></i>
    </div>
    <div v-if="isOpen" class="vendor-info-content">
      <p>Here goes vendor information...</p>
    </div>
    <hr class="separator" />
  </div>
</template>

<script setup>
import { ref } from "vue";

const isOpen = ref(false);
</script>
