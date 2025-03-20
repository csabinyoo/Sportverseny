<template>
  <div class="container">
    <h1 class="title">Kezdőlap</h1>

    <div v-if="!user" class="card">
      <p>Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
      <div class="btn-group">
        <router-link to="/login" class="btn">Bejelentkezés</router-link>
        <router-link to="/register" class="btn">Regisztráció</router-link>
      </div>
    </div>

    <div v-else-if="roleId > 0" class="card">
      <img src="/avatar-5.png" alt="" class="avatar">
      <p class="mb-0">Üdvözlünk, <strong>{{ user }} ({{ username }})</strong>! </p> <p class="mt-0 p-0">Itt kezelheted a felhasználókat és a tartalmakat.</p>
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
    return {
      user: store.user,
      username: store.username,
      roleId: store.roleId,
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
  height: 80vh;
  padding: 20px;
}

.title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.avatar {
  border-radius: 50%;
  width: 120px;
  height: 120px;
  margin-bottom: 20px;
  object-fit: cover;
  border: 4px solid #007bff;
  animation: pulseAvatar 1.5s infinite alternate;
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

.card img {
  border-radius: 50%;
  height: 75px;
  width: 75px;
  margin: auto;
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