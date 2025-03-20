<template>
  <div v-if="message" class="notification" :class="messageType">
    <p class="mb-0">{{ message }}</p>
  </div>
</template>
  
  <script>
export default {
  data() {
    return {
      message: "",
      messageType: "success", // 'error', 'info', 'warning'
    };
  },
  methods: {
    showMessage(message, type = "success") {
      this.message = message;
      this.messageType = type;
      setTimeout(() => {
        this.message = "";
      }, 3000);
    },
  },
};
</script>
  
  <style scoped>
.notification {
  position: fixed;
  top: 20px;
  right: 10px;
  background-color: #4caf50;
  color: white;
  padding: 10px 10px;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.3s ease;
}

.notification.error {
  background-color: #f44336;
}

.notification.warning {
  background-color: #ff9800;
}

.notification.info {
  background-color: #2196f3;
}
</style>
  