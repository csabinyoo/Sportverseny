<template>
  <div>
    <h1 class="text-center">Roles</h1>
    <hr />
    <div v-if="loading" class="loading-overlay">
      <div class="loading-content">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="container pe-0">
      <div class="d-flex justify-content-center mb-3 mt-0 p-0">
        <button
          class="btn btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#modal"
          @click="onClickCreate()"
        >
          Új rang hozzáadása
        </button>
      </div>
      <div class="row d-flex justify-content-center">
        <!-- <div
          class="spinner-border m-0 p-0 text-center"
          role="status"
          v-if="items.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div> -->

        <div class="col-12 col-lg-10 tabla-container pe-0">
          <!-- Táblázat -->
          <div class="d-flex justify-content-between w-100 mb-2">
            <div class="w-100">
              <h2>
                {{ stateAuth.currentCompName }}
              </h2>
            </div>
            <div class="inputBox" v-if="items.length > 0">
              <input type="text" v-model="searchQuery" required="required" />
              <span>keresés</span>
            </div>
          </div>
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <!-- Módosítás -->
              <tr>
                <th v-if="debug">#</th>
                <th>Rang</th>
                <th>Jog</th>
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
                <td data-label="Rang">
                  {{ item.role }}
                </td>
                <td data-label="Jog">
                  {{ item.permission }}
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
  </div>
</template>
    
<script>
// Módosítás
class Item {
  constructor(id = null, role = null, permission = null) {
    this.id = id;
    this.role = role;
    this.permission = permission;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import ErrorMessage from "@/components/ErrorMessage.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ItemForm from "@/components/RoleForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
  components: { ItemForm, OperationsCrud, ErrorMessage },
  data() {
    return {
      modal: null,
      urlBase: BASE_URL,
      urlApi: `${BASE_URL}/roles`,
      stateAuth: useAuthStore(),
      items: [],
      competitions: [],
      searchQuery: "",
      users: [],
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
      const regex = new RegExp(this.searchQuery, "i");
      return this.items.filter(
        (item) => regex.test(item.role) || regex.test(item.permission)
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
        toast.error("Szerver hiba!");
      }
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

      // Módosítás
      const data = {
        role: this.item.role,
        permission: this.item.permission,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
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
        role: this.item.role,
        permission: this.item.permission,
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

    onClickDeleteButton(item) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.role} nevű elemet?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    onClickUpdate(item) {
      this.state = "Update";
      this.title = `Elem módosítása • ${item.role}`;
      this.yes = null;
      this.no = "Mégsem";
      this.size = "lg";
      this.item = { ...item };
    },

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
</style>