<template>
  <div class="spinner-overlay">
    <div class="spinner"></div>
  </div>
</template>

<style scoped>
.spinner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
  padding-top: 100px;
}

.spinner {
  border: 8px solid var(--background-color);
  border-top: 8px solid var(--accent-color);
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
