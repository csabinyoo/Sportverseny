<template>
  <div>
    <div :class="{'sidebar': true, 'active': sidebarActive}">
      <div class="logo">
        <RouterLink to="/">Sportverseny</RouterLink>
        <hr>
      </div>

      <nav class="navbar navbar-dark">
        <ul class="navbar-nav">
          <li class="nav-item">
            <RouterLink to="/" class="nav-link">Kezdőlap</RouterLink>
          </li>
          <li v-if="stateAuth.user && stateAuth.roleId === 4" class="nav-item">
            <RouterLink to="/admin" class="nav-link">Admin Felület</RouterLink>
          </li>
          <li v-if="!stateAuth.user" class="nav-item">
            <RouterLink to="/login" class="nav-link">Bejelentkezés</RouterLink>
          </li>
          <li v-if="!stateAuth.user" class="nav-item">
            <RouterLink to="/register" class="nav-link">Regisztráció</RouterLink>
          </li>
          <li v-if="stateAuth.user" class="nav-item">
            <RouterLink class="nav-link" to="/" @click="Logout()">Kijelentkezés</RouterLink>
          </li>
          <li v-if="stateAuth.user" class="nav-item">
            <RouterLink class="nav-link" to="/profile">Profil</RouterLink>
          </li>
          <li v-if="stateAuth.user" class="nav-item nav-link">
            <i class="bi bi-person"></i>
            <span v-if="stateAuth.user"> {{ stateAuth.user }}</span>
          </li>
        </ul>
      </nav>

      <button class="toggle-btn" @click="toggleSidebar">
        {{ sidebarActive ? '<<' : '>>' }}
      </button>
    </div>

    <RouterView />
  </div>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";
import { RouterLink, RouterView } from "vue-router";
import axios from "axios";
import { BASE_URL } from "@/helpers/baseUrls";

export default {
  data() {
    return {
      stateAuth: useAuthStore(),
      sidebarActive: false,
    };
  },
  methods: {
    async Logout() {
      const url = `${BASE_URL}/users/logout`;
      const headers = {
        Accept: "application/json",
        Authorization: `Bearer ${this.stateAuth.token}`,
      };
      try {
        await axios.post(url, null, { headers });
      } catch (error) {
        console.error("Error:", error);
      }

      this.stateAuth.clearStoredData();
      window.location.reload();
    },
    toggleSidebar() {
      this.sidebarActive = !this.sidebarActive;
    },
  },
};
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
}

.sidebar {
  position: fixed;
  top: 0;
  left: -250px;
  width: 250px;
  height: 100%;
  background-color: #2c3e50;
  transition: transform 0.3s ease;
  z-index: 1000;
}

hr {
  margin-top: 10px;
  color: #fff;
}

.sidebar.active {
  transform: translateX(250px);
}

.sidebar .logo a {
  font-family: 'Cinzel', serif;
  font-size: 32px;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  padding: 20px;
}

.navbar-nav {
  display: flex;
  flex-direction: column;
  padding-top: 20px;
}

.nav-item {
  margin-bottom: 10px;
}

.nav-link {
  color: #ecf0f1;
  text-decoration: none;
  font-size: 16px;
  padding: 12px;
  border-radius: 4px;
  transition: color 0.3s ease;
  position: relative;
  overflow: hidden;
}

.nav-link:before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(to right, #3498db, #2c3e50, #2c3e50);
  transition: left 0.3s ease;
  z-index: -1;
}

.nav-link:hover {
  color: white;
}

.nav-link:hover:before {
  left: 0;
}

.toggle-btn {
  position: absolute;
  top: 20px;
  left: 250px;
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
  z-index: 2000;
}

.toggle-btn:hover {
  background-color: #2980b9;
}
</style>
