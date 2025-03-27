<template>
  <div>
    <h1 class="text-center">Csapatok</h1>
    <hr />
    <ErrorMessage
      :errorMessages="errorMessages"
      @close="onClickCloseErrorMessage"
    />
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div
          class="spinner-border m-0 p-0 text-center"
          role="status"
          v-if="items.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div>

        <div class="col-12 col-lg-10 tabla-container" v-if="items.length > 0">
          <!-- Táblázat -->
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <!-- Módosítás -->
              <tr>
                <th v-if="debug">#</th>
                <th>Verseny</th>
                <th>Csapatnév</th>
                <th>Iskola</th>
                <th>Csapatkapitány</th>
                <th>Műveletek</th>
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
                <td data-label="Verseny">
                  {{ getCompetitionName(item.competitionId) }}
                </td>
                <td data-label="Csapatnév">
                  {{ item.name }}
                  <span
                    class="spinner-border text-primary spinner-border-sm m-0 p-0"
                    role="status"
                    v-if="item.id === selectedRowId && loading"
                  >
                    <span class="visually-hidden m-0">Loading...</span>
                  </span>
                </td>
                <td data-label="Iskola">{{ item.school }}</td>
                <td data-label="Kapitány">{{ getUserName(item.userId) }}</td>

                <!-- CRUD gombok component -->
                <td data-label="Műveletek">
                  <OperationsCrud
                    @onClickDeleteButton="onClickDeleteButton"
                    @onClickUpdate="onClickUpdate"
                    @onClickCreate="onClickCreate"
                    :data="item"
                  />
                </td>
              </tr>
            </tbody>
          </table>
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
            :competitions="competitions"
            :users="users"
            @saveItem="saveItemHandler"
          />
        </Modal>
      </div>
      <div class="d-flex justify-content-center my-3">
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
  </div>
</template>
    
<script>
// Módosítás
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
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import ErrorMessage from "@/components/ErrorMessage.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ItemForm from "@/components/TeamForm.vue";
import OperationsCrud from "@/components/OperationsCrud.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";

export default {
  components: { ItemForm, OperationsCrud, ErrorMessage },
  data() {
    return {
      urlBase: BASE_URL,
      urlApi: `${BASE_URL}/teams`,
      urlCompetitions: `${BASE_URL}/competitions`,
      urlUsers: `${BASE_URL}/users`,
      stateAuth: useAuthStore(),
      items: [],
      competitions: [],
      users: [],
      loading: false,
      modal: null,
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
      errorMessages: null,
      debug: DEBUG,
    };
  },
  mounted() {
    this.getCollections();
    this.getCompetitions(); // Versenyek lekérése
    this.getUsers(); // Felhasználók lekérése
    this.modal = new bootstrap.Modal("#modal", { keyboard: false });
  },
  computed: {
    paginatedCollections() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.items.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
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
        this.loading = false;
      } catch (error) {
        this.errorMessages = "Szerver hiba";
      }
    },

    // Versenyek lekérése
    async getCompetitions() {
      const url = this.urlCompetitions;
      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, headers);
        this.competitions = response.data.data;
      } catch (error) {
        this.errorMessages = "A versenyek betöltése nem sikerült.";
      }
    },

    // Felhasználók lekérése
    async getUsers() {
      const url = this.urlUsers;
      const token = this.stateAuth.token;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };
      try {
        // const response = await axios.get(url, headers);
        const response = await axios.get(url, { headers });
        this.users = response.data.data;
        console.log(response.data.data);
      } catch (error) {
        this.errorMessages = "A felhasználók betöltése nem sikerült.";
      }
    },

    // A versenyek és felhasználók nevének megjelenítése
    getCompetitionName(competitionId) {
      const competition = this.competitions.find(
        (comp) => comp.id === competitionId
      );
      return competition ? competition.name : "Nincs verseny";
    },

    getUserName(userId) {
      const user = this.users.find((user) => user.id === userId);
      return user ? user.name : "Nincs felhasználó";
    },

    async deleteItemById() {
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
        this.errorMessages =
          "A sport nem törölhető, mert már ilyet sportolnak a diákok.";
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

      // Módosłtás
      const data = {
        competitionId: this.item.competitionId,
        name: this.item.name,
        school: this.item.school,
        userId: this.item.userId,
      };
      try {
        const response = await axios.patch(url, data, { headers });
        this.getCollections();
      } catch (error) {
        this.errorMessages = "A módosítás nem sikerült.";
        console.log(error);
      }
      this.state = "Read";
    },

    async createItem() {
      const token = this.stateAuth.token;
      const url = this.urlApi;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      // Módosítás
      const data = {
        competitionId: this.item.competitionId,
        name: this.item.name,
        school: this.item.school,
        userId: this.item.userId,
      };
      try {
        const response = await axios.post(url, data, { headers });
        // this.items.push(response.data.data);
        this.getCollections();
      } catch (error) {
        this.errorMessages = "A bővítés nem sikerült.";
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
      this.messageYesNo = `Valóban törölni akarod a(z) ${item.name} nevű elemet?`;
      this.yes = "Igen";
      this.no = "Nem";
      this.size = null;
    },

    onClickUpdate(item) {
      this.state = "Update";
      this.title = `Elem módosítása | ${item.name}`;
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

    onClickCloseErrorMessage() {
      this.errorMessages = null;
      this.loading = false;
      this.state = "Read";
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
</style>