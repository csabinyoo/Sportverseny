<template>
  <div class="layout">
    <div class="sidebar">
      <div class="logo">
        <RouterLink to="/">Sportverseny</RouterLink>
        <hr />
      </div>

      <nav class="navbar navbar-dark">
        <ul class="navbar-nav">
          <li class="nav-item">
            <RouterLink to="/" class="nav-link">Kezdőlap</RouterLink>
          </li>
          <li v-if="stateAuth.user && stateAuth.roleId === 1" class="nav-item">
            <RouterLink to="/admin" class="nav-link">Admin Felület</RouterLink>
          </li>
          <li v-if="!stateAuth.user" class="nav-item">
            <RouterLink to="/login" class="nav-link">Bejelentkezés</RouterLink>
          </li>
          <li v-if="!stateAuth.user" class="nav-item">
            <RouterLink to="/register" class="nav-link"
              >Regisztráció</RouterLink
            >
          </li>
          <li v-if="stateAuth.user" class="nav-item">
            <RouterLink class="nav-link" :to="`/profile/${stateAuth.id}`">Profil</RouterLink>
          </li>
          <li v-if="stateAuth.user" class="nav-item">
            <RouterLink class="nav-link" to="/" @click="Logout()"
              >Kijelentkezés</RouterLink
            >
          </li>
        </ul>
      </nav>
      <div v-if="stateAuth.user" class="user-info">
        <i class="bi bi-person"></i>
        <span>{{ stateAuth.user }} | {{ stateAuth.username }}</span>
        <p>
          (
          {{
            stateAuth.roleId === 1
              ? "Admin"
              : stateAuth.roleId === 2
              ? "Supervisor"
              : stateAuth.roleId === 3
              ? "Student"
              : "Guest"
          }}
          )
        </p>
      </div>
    </div>
    <div class="content">
      <RouterView />
    </div>
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
  },
};
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.layout {
  display: flex;
  height: 100vh;
}

.sidebar {
  width: 250px;
  height: 100vh;
  background-color: #2c3e50;
  /* padding: 20px 0 20px 0; */
  padding: 0 20px 0 20px;
  color: white;
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;
  overflow-y: auto;
  transition: transform 0.3s ease;
}

.content {
  margin-left: 250px;
  width: calc(100% - 250px);
  padding: 20px;
  overflow-y: auto;
}

.sidebar .logo a {
  font-family: "Cinzel", serif;
  font-size: 32px;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  display: block;
  text-align: center;
}

.navbar-nav {
  list-style: none;
  padding: 0;
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
  display: block;
  transition: background 0.3s ease;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.2);
}

.user-info {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  width: 100%;
}

.user-info::before {
  content: "";
  position: absolute;
  width: 100%;
  height: 2px;
  left: 0;
  top: -10px;
  background: #fff;
}

@media (max-width: 768px) {
  .layout {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }

  .content {
    margin-left: 0;
    width: 100%;
  }

  .navbar-nav {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
  }

  .nav-item {
    margin-bottom: 5px;
  }

  .user-info {
    display: none;
  }
}
</style>