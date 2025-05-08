<template>
  <div>
    <!-- Módosítás -->
    <h1 class="text-center">Versenyek</h1>
    <div class="inputBox" v-if="items.length > 0">
      <input type="text" v-model="searchQuery" required="required" />
      <span>keresés</span>
    </div>
    <hr />
    <div v-if="loading" class="loading-overlay">
      <div class="loading-content">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="container pe-0" v-if="filteredItems.length > 0">
      <div class="d-flex justify-content-center mb-3 mt-0 p-0">
        <!-- Módosítás -->
        <button
          class="btn btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#modal"
          @click="onClickCreate()"
        >
          Új verseny hozzáadása
        </button>
      </div>
      <div class="row d-flex justify-content-center">
        <div
          class="spinner-border m-0 p-0 text-center"
          role="status"
          v-if="items.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div>

        <div class="col-12 col-lg-10 tabla-container pe-0">
          <!-- Táblázat -->
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <!-- Módosítás -->
              <tr>
                <th v-if="debug">#</th>
                <th>Név</th>
                <th>Dátum</th>
                <th>Helyszín</th>
                <th>Regisztráció</th>
                <th>Regisztráció</th>
                <th>Aktuális</th>
                <th v-if="edit">Műveletek</th>
              </tr>
            </thead>
            <tbody>
              <!-- Módosítás -->
              <tr
                v-for="item in paginatedCollections"
                :key="item.id"
                @click="onClickTr(item.id)"
                :class="{
                  updating: loading,
                  active: item.id === selectedRowId,
                }"
              >
                <td data-label="ID" v-if="debug">{{ item.id }}</td>
                <td data-label="Név">{{ item.name }}</td>
                <td data-label="Dátum">{{ item.date }}</td>
                <td data-label="Helyszín">{{ item.location }}</td>
                <td data-label="Regisztráció (tól)">{{ item.registerFrom }}</td>
                <td data-label="Regisztráció (ig)">{{ item.registerTo }}</td>
                <td data-label="Aktuális">
                  <div
                    class="form-check d-flex justify-content-center align-items-center"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      v-model="item.currentComp"
                      @click="setCurrentComp(item.id, item.name)"
                    />
                  </div>
                </td>

                <!-- CRUD gombok component -->
                <td data-label="Műveletek" v-if="edit">
                  <OperationsCrud
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdate="onClickUpdate"
                    :data="item"
                  />
                </td>
              </tr>
            </tbody>
          </table>

          <div class="justify-content-center">
            <div class="pagination-container d-flex">
              <div
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)"
                :class="['page-box', { 'active-page': currentPage === page }]"
              >
                {{ page }}
              </div>
            </div>
          </div>
        </div>

        <Modal
          :title="title"
          :yes="yes"
          :no="no"
          :size="size"
          @yesEvent="yesEventHandler"
        >
          <!-- Igen/Nem válasz -->
          <div v-if="state == 'Delete'">
            {{ messageYesNo }}
          </div>

          <!-- Beviteli form -->
          <ItemForm
            v-if="state == 'Create' || state == 'Update'"
            :itemForm="item"
            :debug="debug"
            @saveItem="saveItemHandler"
          />
        </Modal>
      </div>
    </div>
    <div class="empty" v-else>
      <div v-if="items.length === 0">
        <p>Adatok betöltése...</p>
      </div>
      <div v-else>
        <p>Nincs találat a keresésben.</p>
      </div>
    </div>
  </div>
</template>
      
  <script>
// Módosítás
class Item {
  constructor(
    id = null,
    name = null,
    date = null,
    location = null,
    registerFrom = null,
    registerTo = null,
    currentComp = null
  ) {
    this.id = id;
    this.name = name;
    this.date = date;
    this.location = location;
    this.registerFrom = registerFrom;
    this.registerTo = registerTo;
    this.currentComp = currentComp;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ItemForm from "@/components/CompetitionForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
  components: { ItemForm, OperationsCrud },
  data() {
    // Módosítás
    return {
      modal: null,
      urlBase: BASE_URL,
      urlApi: `${BASE_URL}/competitions`,
      stateAuth: useAuthStore(),
      items: [],
      competitions: [],
      searchQuery: "",
      users: [],
      currentComp: null,
      loading: false,
      currentPage: 1,
      itemsPerPage: 10,
      item: {},
      selectedRowId: null,
      messageYesNo: null,
      state: "Read",
      title: null,
      yes: null,
      no: null,
      size: null,
      debug: DEBUG,
      edit: false,
    };
  },
  mounted() {
    this.getCollections();
    this.edit = false;
    setTimeout(() => {
      this.modal = new bootstrap.Modal("#modal", {
        keyboard: false,
      });
      this.edit = true;
    }, 1500);
  },
  computed: {
    paginatedCollections() {
      const filtered = this.filteredItems;
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return filtered.slice(start, end);
    },
    filteredItems() {
      if (!this.searchQuery) return this.items;
      // Módosítás
      const regex = new RegExp(this.searchQuery, "i");
      return this.items.filter(
        (item) =>
          regex.test(item.name) ||
          regex.test(item.date) ||
          regex.test(item.location)
      );
    },
    totalPages() {
      return Math.ceil(this.filteredItems.length / this.itemsPerPage);
    },
  },

  methods: {
    async getCollections() {
      const url = this.urlApi;
      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, headers);
        this.items = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
    },

    async deleteItemById() {
      this.loading = true;
      const id = this.selectedRowId;
      const token = this.stateAuth.token;

      const url = `${this.urlApi}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      try {
        const response = await axios.delete(url, { headers });
        this.getCollections();
      } catch (error) {
        toast.error("Nem törölhető!");
      }
      this.loading = false;
    },

    async updateItem() {
      this.loading = true;
      const id = this.selectedRowId;
      const url = `${this.urlApi}/${id}`;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${this.stateAuth.token}`,
      };

      // Módosłtás
      const data = {
        name: this.item.name,
        date: this.item.date,
        location: this.item.location,
        registerFrom: this.item.registerFrom,
        registerTo: this.item.registerTo,
        currentComp: this.item.currentComp,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.getCollections();
      } catch (error) {
        toast.error("A módosítás nem sikerült!");
      }
      this.loading = false;
      this.state = "Read";
    },

    async createItem() {
      this.loading = true;
      const token = this.stateAuth.token;
      const url = this.urlApi;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      // Módosítás
      const data = {
        name: this.item.name,
        date: this.item.date,
        location: this.item.location,
        registerFrom: this.item.registerFrom,
        registerTo: this.item.registerTo,
        currentComp: false,
      };
      try {
        const response = await axios.post(url, data, { headers });
        // this.items.push(response.data.data);
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.state = "Read";
    },

    // További CRUD műveletek és metódusok
    yesEventHandler() {
      if (this.state == "Delete") {
        this.deleteItemById();
        this.goToPage(1);
      }
    },

    // Módosítás
    onClickDeleteButton(item) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.name} nevű elemet?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    // Módosítás
    onClickUpdate(item) {
      this.state = "Update";
      this.title = `Elem módosítása • ${item.name}`;
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.item = { ...item };
    },

    // Módosítás
    onClickCreate() {
      this.title = "Új adat bevitele";
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.state = "Create";
      this.item = new Item();
    },

    onClickTr(id) {
      this.selectedRowId = id;
    },

    saveItemHandler() {
      if (this.state === "Update") {
        this.updateItem();
      } else if (this.state === "Create") {
        this.createItem();
      }
      this.modal.hide();
    },

    async setCurrentComp(id, name) {
      const token = this.stateAuth.token;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      try {
        let url = `${this.urlBase}/competitionCurrentCompToFalse`;
        let data = {};
        let response = await axios.patch(url, data, { headers });
        url = `${this.urlBase}/competitions/${id}`;
        data = {
          currentComp: true,
        };
        response = await axios.patch(url, data, { headers });
        this.stateAuth.setCurrentCompId(id, name);
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
    },

    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>
      
  <style scoped>
.container {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.row {
  width: 100%;
}

.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}

.tabla-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.btn {
  background: var(--color) !important;
  color: white !important;
  font-weight: 600 !important;
  border: 1px solid var(--color) !important;
  border-radius: 5px;
  text-decoration: none;
  transition: background 0.3s;
}

.btn:hover {
  background: var(--bg-color) !important;
  color: #fff;
  border-color: transparent !important;
}

.form-check-input {
  height: 1.5em;
  width: 1.5em;
}
</style>