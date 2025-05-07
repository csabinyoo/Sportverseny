<template>
  <div>
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="/logo.png" alt="logo" />
      </div>
      <ul class="sidebar-links mt-3 p-0">
        <h4>
          <span>Main Menu</span>
          <div class="menu-separator"></div>
        </h4>
        <li>
          <RouterLink to="/" class="nav-link">
            <span class="material-symbols-outlined"> home </span
            >Home</RouterLink
          >
        </li>
        <div v-if="stateAuth.user && stateAuth.roleId !== 4">
          <li>
            <RouterLink :to="`/profile/${stateAuth.id}`" class="nav-link">
              <span class="material-symbols-outlined"> person </span
              >Profil</RouterLink
            >
          </li>
        </div>
        <div v-if="stateAuth.roleId === 3 || stateAuth.roleId === 1">
          <li>
            <RouterLink to="/registerteam" class="nav-link">
              <span class="material-symbols-outlined"> group_add </span> Csapat
              regisztrálása
            </RouterLink>
          </li>
        </div>
        <div v-if="!stateAuth.user">
          <h4>
            <span>Login</span>
            <div class="menu-separator"></div>
          </h4>
          <li>
            <RouterLink to="/login" class="nav-link">
              <span class="material-symbols-outlined"> login</span
              >Login</RouterLink
            >
          </li>
          <li>
            <RouterLink to="/register" class="nav-link">
              <span class="material-symbols-outlined"> how_to_reg </span
              >Register</RouterLink
            >
          </li>
        </div>
        <div v-if="stateAuth.roleId === 1">
          <h4 class="mt-3">
            <span>Admin menu</span>
            <div class="menu-separator"></div>
          </h4>
          <li>
            <RouterLink to="/tables" class="nav-link">
              <span class="material-symbols-outlined"> tabs </span>Táblák
              szerkesztése</RouterLink
            >
          </li>
          <li v-if="route.path === '/admin'">
            <RouterLink to="/admin" class="nav-link">
              <span class="material-symbols-outlined"> dashboard </span>Admin
              Felület</RouterLink
            >
          </li>
          <li v-if="route.path === '/teams'">
            <RouterLink to="/teams" class="nav-link">
              <span class="material-symbols-outlined"> groups </span> Teams
            </RouterLink>
          </li>
          <li v-if="route.path === '/roles'">
            <RouterLink to="/roles" class="nav-link">
              <span class="material-symbols-outlined"> done_all </span> Roles
            </RouterLink>
          </li>
          <li v-if="route.path === '/stations'">
            <RouterLink to="/stations" class="nav-link">
              <span class="material-symbols-outlined"> simulation </span>
              Stations
            </RouterLink>
          </li>
          <li v-if="route.path === '/competitions'">
            <RouterLink to="/competitions" class="nav-link">
              <span class="material-symbols-outlined"> trophy </span>
              Competitions
            </RouterLink>
          </li>
        </div>
      </ul>
      <div class="user-account">
        <div class="user-profile">
          <img src="/profile-img.jpg" alt="Profile Image" />
          <div class="user-detail">
            <h3 class="m-0">{{ truncate(stateAuth.user, 15) }}</h3>
            <span class="m-0">{{ truncate(stateAuth.username, 15) }}</span>

            <hr class="m-0" />
            <p
              class="m-0 text-center mt-1"
              style="
                font-size: 12px;
                color: var(--color);
                font-weight: bold;
                border: 1px solid var(--border-color);
              "
            >
              {{
                stateAuth.roleId === 1
                  ? "Admin"
                  : stateAuth.roleId === 2
                  ? "Supervisor"
                  : stateAuth.roleId === 3
                  ? "Student"
                  : "Guest"
              }}
            </p>
          </div>
        </div>
        <span class="material-symbols-outlined" @click="Logout()">
          logout
        </span>
      </div>
    </aside>
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
import { useRoute } from "vue-router";
import { useToast } from "vue-toastification";
const toast = useToast();

export default {
  components: { Notification },
  data() {
    return {
      stateAuth: useAuthStore(),
    };
  },
  setup() {
    const route = useRoute();
    return { route };
  },
  mounted() {
    this.getCurrentComp();
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
        toast("Sikeres kijelentkezés!");
        this.stateAuth.clearStoredData();
        this.$router.push("/");
      } catch (error) {
        console.error("Error:", error);
        toast("Sikertelen kijelentkezés!");
      }
    },
    async getCurrentComp() {
      const url = `${BASE_URL}/getCurrentComp`;
      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, { headers });
        const data = response.data.data;
        if (data.length > 0) {
          this.stateAuth.setCurrentCompId(data[0].id, data[0].name);
        } else {
          this.stateAuth.setCurrentCompId(null, null);
        }
      } catch (error) {
        console.error("Error:", error);
      }
    },
    truncate(text, length) {
      if (!text) return "";
      return text.length > length ? text.substring(0, length) + "..." : text;
    },
  },
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.content {
  margin-left: 85px;
  height: 100vh;
  width: calc(100% - 85px);
  transition: margin-left 0.4s ease, width 0.4s ease;
}
.sidebar:hover ~ .content {
  margin-left: 260px;
  width: calc(100% - 260px);
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 85px;
  display: flex;
  overflow-x: hidden;
  flex-direction: column;
  background: #161a2d;
  padding: 25px 20px;
  transition: all 0.4s ease;
}

.sidebar:hover {
  width: 260px;
}

.sidebar .sidebar-header {
  display: flex;
  align-items: center;
  max-height: 50px;
}

.sidebar .sidebar-header img {
  width: 50px;
  /* border-radius: 50%; */
  transition: 0.5s;
}

.sidebar:hover .sidebar-header img {
  width: 150px;
  left: 50%;
  transform: translate(25%, 0%);
  /* margin: auto; */
}

.sidebar-links h4 {
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  margin: 10px 0;
  position: relative;
  font-size: 18px;
}

.sidebar-links h4 span {
  opacity: 0;
}

.sidebar:hover .sidebar-links h4 span {
  opacity: 1;
}

.sidebar-links .menu-separator {
  position: absolute;
  left: 0;
  top: 50%;
  width: 100%;
  height: 1px;
  transform: scaleX(1);
  transform: translateY(-50%);
  background: var(--color);
  transform-origin: right;
  transition-delay: 0.2s;
  transition: 0.3s;
}

.sidebar:hover .sidebar-links .menu-separator {
  transition-delay: 0s;
  transform: scaleX(0);
}

.sidebar-links {
  list-style: none;
  margin-top: 20px;
  height: 80%;
  overflow-y: auto;
  scrollbar-width: none;
}

.sidebar-links::-webkit-scrollbar {
  display: none;
}

.sidebar-links li .nav-link {
  display: flex;
  align-items: center;
  gap: 0 20px;
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  padding: 15px 10px;
  text-decoration: none;
  transition: 0.2s ease;
}

.sidebar-links li .nav-link:hover {
  color: #161a2d;
  background: #fff;
  /* border-radius: 4px; */
}

/* .sidebar-links li .nav-link.router-link-active {
  border-left: 4px solid var(--color);
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  position: relative;
  overflow: hidden;
} */

.sidebar-links li .nav-link.router-link-active {
  border-left: 3px solid var(--color);
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  position: relative;
  overflow: hidden;
  transition: 0.3s;
}

.sidebar-links li .nav-link.router-link-active:hover {
  border-color: #fff;
}

.user-account {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 5px 10px;
  margin-top: 40px;
  margin-left: -10px;
  border-radius: 4px;
}

.user-account .material-symbols-outlined {
  display: none;
  transition: 0.3s;
}

.sidebar:hover .user-account .material-symbols-outlined {
  display: block;
  font-size: 24px;
  margin-left: 10px;
  color: #fff;
  cursor: pointer;
}

.sidebar:hover .user-account .material-symbols-outlined:hover {
  transform: rotate(180deg);
  /* color: #e63946; */
  color: var(--color);
}

.user-profile {
  display: flex;
  padding: 10px 0 10px 0;
  align-items: center;
  color: #fff;
}

.user-profile img {
  width: 42px;
  border-radius: 50%;
  border: 2px solid var(--color);
}

.user-profile h3 {
  font-size: 0.9rem;
}

.user-profile span {
  font-size: 0.775rem;
  font-weight: 600;
}

.user-detail {
  display: none;
}

.sidebar:hover .user-detail {
  display: block;
  margin-left: 15px;
  white-space: nowrap;
}

.sidebar:hover .user-account {
  border: 1px solid var(--border-color);
  border-radius: 4px;
}

.text {
  display: none;
}

@media (max-width: 600px) {
  .sidebar:hover {
    width: 85px;
  }
  .sidebar:hover ~ .content {
    margin-left: 85px;
    width: calc(100% - 85px);
  }
  .sidebar:hover .sidebar-links h4 span {
    opacity: 0;
  }
  .sidebar:hover .sidebar-links .menu-separator {
    transform: scaleX(1);
  }
  .sidebar:hover .sidebar-header img {
    width: 50px;
    transform: translate(0%, 0%);
  }
  .sidebar:hover .user-detail {
    display: none;
  }
  .sidebar:hover .user-account {
    border: 0;
  }
  .user-account .material-symbols-outlined {
    position: absolute;
    bottom: 10px;
    margin-left: 10px;
    display: flex;
    color: #fff;
  }
}
</style>