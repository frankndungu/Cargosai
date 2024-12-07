<template>
  <div class="admin-overview-tabs">
    <button
      v-for="tab in tabs"
      :key="tab"
      :class="['admin-tab-button', { active: activeTab === tab }]"
      @click="setActiveTab(tab)"
    >
      {{ tab }}
    </button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { defineEmits } from "vue";

const tabs = ["Overview", "Sales", "Reports", "Notifications"];
const activeTab = ref(tabs[0]); // Start with "Overview" as active
const emit = defineEmits(["updateActiveTab"]);

function setActiveTab(tab) {
  activeTab.value = tab;
  emit("updateActiveTab", tab);
}
</script>
