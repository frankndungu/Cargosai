<template>
  <div class="success-container">
    <div v-if="loading" class="loading-wrapper">
      <p class="loading-text">Loading order confirmation...</p>
    </div>
    <div v-else class="success-card">
      <div class="success-icon">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <h1 class="success-title">Thank You for Your Purchase!</h1>
      <p class="success-subtitle">
        We appreciate your support and trust in our products.
      </p>
      <p class="email-notice">
        Order updates will be sent to your email. Please check your inbox or
        spam folder for detailed information.
      </p>
      <button @click="goToHomePage" class="btn btn-continue">
        Continue Shopping
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

const toast = useToast();
const loading = ref(false);

const router = useRouter();

const goToHomePage = () => {
  router.push("/shop");
};

onMounted(() => {
  loading.value = false;
  // Show thank you toast
  toast.success("Thank You for Your Purchase!", {
    position: "top-right",
    duration: 3000,
  });
});
</script>

<style scoped>
.success-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: var(--background-color);
  padding: 20px;
}

.success-card {
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  padding: 40px;
  text-align: center;
  max-width: 500px;
  width: 100%;
  transform: translateY(-20px);
  opacity: 0;
  animation: fadeInUp 0.8s forwards;
  margin: 0 auto;
}

@keyframes fadeInUp {
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.loading-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: var(--dark-color);
  font-size: 1.2rem;
}

.success-icon {
  width: 100px;
  height: 100px;
  margin: 0 auto 20px;
  color: var(--green-color);
  stroke-width: 3;
  animation: popIn 0.6s ease-out;
}

.success-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--green-color);
  margin-bottom: 10px;
}

.success-subtitle,
.email-notice {
  color: var(--dark-color);
  margin-bottom: 20px;
}

.btn-continue {
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 15px 20px;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all var(--transition-speed) ease;
}

.btn-continue:hover {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

@media (max-width: 600px) {
  .success-card {
    padding: 20px;
    margin: 0 10px;
  }
}
</style>
