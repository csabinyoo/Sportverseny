<template>
  <div class="container">
    <!-- Check if user is not logged in -->
    <div v-if="!user" class="card">
      <p>Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
      <div class="btn-group">
        <router-link to="/login" class="btn">Bejelentkezés</router-link>
        <router-link to="/register" class="btn">Regisztráció</router-link>
      </div>
    </div>

    <!-- Check if user is logged in and has a valid role -->
    <div v-else-if="roleId > 0" class="card">
      <img src="/avatar.gif" alt="" class="avatar">
      <p class="mb-0">Üdvözlünk, <strong>{{ user }} ({{ username }})</strong>!</p>
      <div class="btn-group">
        <router-link to="/versenyek" class="btn">Versenyek</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";

export default {
  setup() {
    const store = useAuthStore();

    // Use computed properties for reactivity
    const user = store.user;
    const username = store.username;
    const roleId = store.roleId;

    // Return all reactive data
    return {
      user,
      username,
      roleId,
    };
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 90vh;
}

.avatar {
  border-radius: 50%;
  width: 100px;
  height: 100px;
  margin: auto;
  border: 4px solid #007bff;
  margin-bottom: 10px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
  max-width: 400px;
  width: 100%;
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 15px;
} 

.btn {
  background: #007bff;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s;
}

.btn:hover {
  background: #0056b3;
  color: #fff;
}
</style>
