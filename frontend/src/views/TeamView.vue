<template>
  <div>
    <h1 class="text-center">Csapatok</h1>
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
          Új csapat hozzáadása
        </button>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-10 tabla-container pe-0">
          <div class="d-flex justify-content-between w-100 mb-2">
            <div class="w-100">
              <h2>{{ stateAuth.currentCompName }}</h2>
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
              <tr>
                <th v-if="debug">#</th>
                <th>Csapatnév</th>
                <th>Iskola</th>
                <th>Csapatkapitány</th>
                <th v-if="edit">Műveletek</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in paginatedCollections"
                :key="item.id"
                @click="onClickTr(item.id)"
                :class="{
                  updating: loading,
                  active: item.id === selectedRowId,
                }"
              >
                <td v-if="debug">{{ item.id }}</td>
                <td @click="onClickTeam(item.id)">{{ item.name }}</td>
                <td>{{ item.school }}</td>
                <td>{{ getUserName(item.userId) }}</td>
                <td v-if="edit">
                  <OperationsCrud
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdate="onClickUpdate"
                    :data="item"
                  />
                </td>
              </tr>
            </tbody>
          </table>

          <div class="pagination-container d-flex justify-content-center">
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

        <Modal
          :title="title"
          :yes="yes"
          :no="no"
          :size="size"
          @yesEvent="yesEventHandler"
        >
          <div v-if="state == 'Delete'">
            {{ messageYesNo }}
          </div>
          <ItemForm
            v-if="state == 'Create' || state == 'Update'"
            :itemForm="item"
            :debug="debug"
            :competitions="competitions"
            :users="users"
            @saveItem="saveItemHandler"
          />
        </Modal>

        <div
          class="modal fade"
          id="teamMembersModal"
          tabindex="-1"
          aria-labelledby="teamMembersModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="teamMembersModalLabel">
                  Csapattagok
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div v-if="teamMembers.length === 0" class="text-center">
                  <p>Nincs adat</p>
                </div>
                <div v-else class="row">
                  <div
                    v-for="member in teamMembers"
                    :key="member.id"
                    class="col-md-6 col-lg-4 mb-3"
                  >
                    <div class="card shadow-sm h-100">
                      <div class="card-body">
                        <h5 class="card-title">{{ member.name }}</h5>
                        <p class="card-text">
                          <strong>Tag ID:</strong> {{ member.id }}<br />
                          <strong>Csapat ID:</strong> {{ member.teamId }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  Bezárás
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ItemForm from "@/components/TeamForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
import { useToast } from "vue-toastification";

const toast = useToast();

class Item {
  constructor(
    id = null,
    competitionId = null,
    name = null,
    school = null,
    userId = null
  ) {
    this.id = id;
    this.competitionId = competitionId;
    this.name = name;
    this.school = school;
    this.userId = userId;
  }
}

export default {
  components: { ItemForm, OperationsCrud },
  data() {
    return {
      modal: null,
      urlBase: BASE_URL,
      urlApi: `${BASE_URL}/teams`,
      urlCompetitions: `${BASE_URL}/competitions`,
      urlUsers: `${BASE_URL}/users`,
      stateAuth: useAuthStore(),
      items: [],
      competitions: [],
      users: [],
      teamMembers: [],
      item: {},
      loading: false,
      searchQuery: "",
      currentPage: 1,
      itemsPerPage: 10,
      selectedRowId: null,
      messageYesNo: null,
      state: "Read",
      title: null,
      yes: null,
      no: null,
      size: null,
      debug: DEBUG,
      edit: true,
    };
  },
  computed: {
    paginatedCollections() {
      const filtered = this.filteredItems;
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return filtered.slice(start, start + this.itemsPerPage);
    },
    filteredItems() {
      if (!this.searchQuery) return this.items;
      const regex = new RegExp(this.searchQuery, "i");
      return this.items.filter(
        (item) =>
          regex.test(item.name) ||
          regex.test(item.school) ||
          regex.test(this.getUserName(item.userId))
      );
    },
    totalPages() {
      return Math.ceil(this.filteredItems.length / this.itemsPerPage);
    },
  },
  mounted() {
    this.loadInitialData();
    this.modal = new bootstrap.Modal("#modal", { keyboard: false });
  },
  methods: {
    async loadInitialData() {
      await Promise.all([
        this.getUsers(),
        this.getCompetitions(),
        this.getCollections(),
      ]);
    },
    async getCollections() {
      try {
        const response = await axios.get(this.urlApi);
        this.items = response.data.data;
      } catch (err) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
    },
    async getUsers() {
      const headers = {
        Authorization: `Bearer ${this.stateAuth.token}`,
        Accept: "application/json",
      };
      try {
        const response = await axios.get(this.urlUsers, { headers });
        this.users = response.data.data;
      } catch {
        toast.error("Szerver hiba");
      }
    },
    async getCompetitions() {
      try {
        const response = await axios.get(this.urlCompetitions);
        this.competitions = response.data.data;
      } catch {
        toast.error("Szerver hiba");
      }
    },
    getUserName(userId) {
      const user = this.users.find((u) => String(u.id) === String(userId));
      return user ? user.name : "Ismeretlen felhasználó";
    },
    async getTeamMembers(teamId) {
      try {
        const response = await axios.get(`${this.urlBase}/teammember`);
        this.teamMembers = response.data.data.filter(
          (m) => m.teamId === teamId
        );
      } catch {
        toast.error("Szerver hiba");
      }
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
        toast.error("Szerver hiba");
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
        competitionId: this.stateAuth.currentCompId,
        name: this.item.name,
        school: this.item.school,
        userId: this.item.userId,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba");
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
        competitionId: this.stateAuth.currentCompId,
        name: this.item.name,
        school: this.item.school,
        userId: this.item.userId,
      };
      try {
        const response = await axios.post(url, data, { headers });
        // this.items.push(response.data.data);
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba");
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

    goToPage(page) {
      this.currentPage = page;
    },

    onClickDeleteButton(item) {
      this.state = "Delete";
      this.title = "Törlés";
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.name} nevű elemet?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    onClickUpdate(item) {
      this.state = "Update";
      this.title = `Elem módosítása • ${item.name}`;
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

    async onClickTeam(id) {
      if (!this.users.length) {
        await this.getUsers();
      }
      await this.getTeamMembers(id);
      const modal = new bootstrap.Modal(
        document.getElementById("teamMembersModal")
      );
      modal.show();
    },

    saveItemHandler() {
      if (this.state === "Update") {
        this.updateItem();
      } else if (this.state === "Create") {
        this.createItem();
      }
      this.modal.hide();
    },
    async onClickTr(id) {
      this.selectedRowId = id;
    },
  },
};
</script>

<style scoped>
.card {
  border-radius: 1rem;
  transition: transform 0.2s ease-in-out;
}
.card:hover {
  transform: translateY(-5px);
}

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
