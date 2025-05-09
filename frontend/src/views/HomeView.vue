<template>
  <div>
    <div class="container">
      <!-- Check if user is not logged in -->
      <div v-if="!stateAuth.user" class="card">
        <p class="mb-0">Üdvözlünk! Kérlek, jelentkezz be, vagy regisztrálj!</p>
        <div class="btn-group">
          <router-link to="/login" class="btn">Bejelentkezés</router-link>
          <router-link to="/register" class="btn">Regisztráció</router-link>
        </div>

        <div class="btn-group-vertical mt-2">
          <button @click="setGuest" class="btn">Folytatás vendégként</button>
        </div>
      </div>

      <div v-else-if="stateAuth.user" class="card">
        <img src="/profile-img.jpg" alt="" class="avatar" />
        <p class="mb-0">
          Üdvözlünk, <strong>{{ stateAuth.user }}</strong> (<strong>{{ stateAuth.username }}</strong>)!
        </p>
        <p class="mb-0">
          Jog:
          <strong>
            {{ stateAuth.roleId === 1
              ? "Admin"
              : stateAuth.roleId === 2
                ? "Supervisor"
                : stateAuth.roleId === 3
                  ? "Student"
                  : "Guest" }}
          </strong>
        </p>
        <p class="mb-0">Aktív verseny: <strong>{{ stateAuth.currentCompName }}</strong></p>
        <div class="btn-group-vertical">
          <router-link to="/competitions" class="btn mt-2" v-if="stateAuth.roleId < 3 && stateAuth.roleId > 0">Versenyek
            kezelése</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "@/stores/useAuthStore.js";
import { useToast } from "vue-toastification";
import axios from "axios";
const toast = useToast();

export default {
  data() {
    return {
      urlBase: BASE_URL,
      stateAuth: useAuthStore(),
      competitions: [],
    };
  },
  methods: {
    setGuest() {
      try {
        this.stateAuth.setGuestUser(); // Beállítjuk a vendég felhasználót
        this.getCurrentComp();
        toast("Sikeres bejelentkezés!");
      } catch (error) {
        toast("Sikertelen bejelentkezés!");
      }
    },
    async getCompetitions() {
      const url = this.urlCompetitions;
      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, headers);
        this.competitions = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!")
      }
    },
    async getCurrentComp() {
      const url = `${BASE_URL}/getCurrentComp`;
      const headers = {
        Accept: "application/json"
      };
      try {
        const response = await axios.get(url, { headers });
        const data = response.data.data;
        
        if (data.length > 0) {
          this.stateAuth.setCurrentCompId(data[0].id, data[0].name)
        } else {
          this.stateAuth.setCurrentCompId(null, null)
        }
      } catch (error) {
        console.error("Error:", error);
      }
    }
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
  border: 4px solid var(--color);
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
  background: var(--color) !important;
  color: white !important;
  font-weight: 600 !important;
  border: 1px solid var(--color) !important;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s;
}

.btn:hover {
  background: var(--bg-color) !important;
  color: #fff;
  border-color: transparent !important;
}
</style>
