<template>
  <div>
    <div v-if="loading" class="loading-overlay">
      <div class="loading-content">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="container pt-2">
      <h1>Állomások</h1>
      <hr class="mb-5">
      <div class="station-list">
        <div v-for="item in items" :key="item.id" class="station-card">
          <h2 class="text-center">{{ item.name }}</h2>
          <hr class="m-0" />
          <p><strong>Helyszín:</strong> {{ item.location }}</p>
          <p><strong>Súlyozás:</strong> {{ item.weighting }}</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import { useAuthStore } from "@/stores/useAuthStore.js";
import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
  data() {
    return {
      urlApi: `${BASE_URL}/stations`,
      stateAuth: useAuthStore(),
      debug: DEBUG,
      loading: false,
      items: [],
    };
  },
  mounted() {
    this.getCollections();
  },
  methods: {
    async getCollections() {
      this.loading = true;
      try {
        const response = await axios.get(this.urlApi, {
          headers: { Accept: "application/json" },
        });
        this.items = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
        console.log(error);
      }
      this.loading = false;
    },
  },
};
</script>

<style scoped>
.container {
  padding: 40px 20px;
  min-height: 100vh;
}

h1 {
  text-align: center;
  color: var(--color);
  /* margin-bottom: 40px; */
  font-size: 32px;
}

.station-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.station-card {
  border-radius: 16px;
  padding: 24px;
  width: 300px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.station-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
}

.station-card h2 {
  margin-bottom: 12px;
  color: var(--color);
  font-size: 20px;
}

.station-card p {
  margin: 6px 0;
  color: #000;
  font-size: 16px;
}

.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}

</style>