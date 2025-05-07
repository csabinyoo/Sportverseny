<template>
  <div>
    <h1 class="text-center">Állomások</h1>
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
    <div class="container pe-0">
      <div class="d-flex justify-content-center mb-3 mt-0 p-0">
        <button
          class="btn btn-lg"
          data-bs-toggle="modal"
          data-bs-target="#modal"
          @click="onClickCreate()"
        >
          Új állomás hozzáadása
        </button>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-10 tabla-container pe-0">
          <div class="row">
            <div :class="[teamAtStation ? 'col-9' : 'col-12']">
              <table
                class="table table-bordered table-hover table-striped shadow-sm rounded"
              >
                <thead class="table-dark">
                  <tr>
                    <th v-if="debug">#</th>
                    <th>Név</th>
                    <th>Helyszín</th>
                    <th>Súlyozás</th>
                    <th>többAJobb</th>
                    <th>Típus</th>
                    <th>Felügyelő</th>
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.location }}</td>
                    <td>{{ item.weighting }}</td>
                    <td>{{ item.moreIsBetter === 0 ? "Nem" : "Igen" }}</td>
                    <td>{{ getTypeName(item.typeId) }}</td>
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
            </div>

            <div v-if="teamAtStation" class="col-3 team-list-container">
              <h5 class="text-center mb-3">
                Csapatok ({{ selectedStationName }})
              </h5>
              <transition-group
                name="fade"
                tag="ul"
                class="list-group team-list"
              >
                <li
                  v-for="team in teamsAtStation"
                  :key="team.id"
                  class="list-group-item team-item"
                  @click="fetchTeamMembers(team.id, team.name)"
                >
                  <i class="bi bi-people-fill me-2"></i> {{ team.name }}
                </li>
              </transition-group>
              <div
                v-if="teamsAtStation.length === 0"
                class="text-muted text-center mt-3"
              >
                Nincsenek csapatok.
              </div>
            </div>
          </div>

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
          <div v-if="state == 'Delete'">
            {{ messageYesNo }}
          </div>

          <ItemForm
            v-if="state == 'Create' || state == 'Update'"
            :itemForm="item"
            :debug="debug"
            :competitions="competitions"
            :users="users"
            :types="types"
            @saveItem="saveItemHandler"
          />
        </Modal>

        <!-- Csapattagok modálja -->
        <Modal
          v-if="teamModalVisible"
          title="Csapattagok és pontszámok"
          :no="'Bezárás'"
          :yes="null"
          :size="'md'"
          @yesEvent="() => {}"
          @close="teamModalVisible = false"
        >
          <div>
            <h6 class="mb-3">{{ selectedTeamName }}</h6>
            <ul class="list-group">
              <li
                v-for="member in selectedTeamMembers"
                :key="member.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                {{ member.name }}
                <span class="badge bg-primary rounded-pill">{{
                  member.points
                }}</span>
              </li>
            </ul>
            <div
              v-if="selectedTeamMembers.length === 0"
              class="text-muted mt-3"
            >
              Nincsenek tagok vagy pontok.
            </div>
          </div>
        </Modal>
      </div>
    </div>
  </div>
</template>

<script>
class Item {
  constructor(
    id = null,
    name = null,
    location = null,
    weighting = null,
    moreIsBetter = null,
    typeId = null,
    userId = null,
    competitionId = null
  ) {
    this.id = id;
    this.name = name;
    this.location = location;
    this.weighting = weighting;
    this.moreIsBetter = moreIsBetter;
    this.typeId = typeId;
    this.userId = userId;
    this.competitionId = competitionId;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import ErrorMessage from "@/components/ErrorMessage.vue";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ItemForm from "@/components/StationForm.vue";
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
      urlApi: `${BASE_URL}/stations`,
      urlCompetitions: `${BASE_URL}/competitions`,
      urlUsers: `${BASE_URL}/users`,
      urlTypes: `${BASE_URL}/results`,
      stateAuth: useAuthStore(),
      items: [],
      competitions: [],
      types: [],
      searchQuery: "",
      selectedStationName: "",
      users: [],
      loading: false,
      teamAtStation: false,
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
      teamsAtStation: [],
      teamModalVisible: false,
      selectedTeamMembers: [],
      selectedTeamName: "",
    };
  },
  mounted() {
    this.getTypes();
    this.getCollections();
    this.getCompetitions();
    this.getUsers();
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
  methods: {
    async getCollections() {
      try {
        const response = await axios.get(this.urlApi, {
          headers: { Accept: "application/json" },
        });
        this.items = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
    },
    async fetchTeamsAtStation(stationId) {
      this.teamAtStation = true;
      try {
        const response = await axios.get(
          `${this.urlBase}/teamAtStation/${stationId}`,
          {
            headers: { Accept: "application/json" },
          }
        );
        this.teamsAtStation = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
    },
    async fetchTeamMembers(teamId, teamName) {
      try {
        const response = await axios.get(
          `${this.urlBase}/membersResultsAtStation`
        );
        this.teamMembers = response.data.data.filter(
          (m) => m.teamId === teamId
        );
        this.selectedTeamMembers = response.data.data;
        this.selectedTeamName = teamName;
        this.teamModalVisible = true;
      } catch (error) {
        toast.error("Szerver hiba");
      }
    },
    async getCompetitions() {
      try {
        const response = await axios.get(this.urlCompetitions);
        this.competitions = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
    },
    async getTypes() {
      try {
        const response = await axios.get(this.urlTypes);
        this.types = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
    },
    async getUsers() {
      try {
        const response = await axios.get(this.urlUsers, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `Bearer ${this.stateAuth.token}`,
          },
        });
        this.users = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
    },
    getUserName(userId) {
      const user = this.users.find((u) => u.id === userId);
      return user ? user.name : "Nincs felhasználó";
    },
    getTypeName(typeId) {
      const type = this.types.find((t) => t.id === typeId);
      return type ? type.type : "Nincs típus";
    },
    async deleteItemById() {
      this.loading = true;
      try {
        await axios.delete(`${this.urlApi}/${this.selectedRowId}`, {
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `Bearer ${this.stateAuth.token}`,
          },
        });
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
    },
    async updateItem() {
      this.loading = true;
      try {
        await axios.patch(
          `${this.urlApi}/${this.selectedRowId}`,
          {
            name: this.item.name,
            location: this.item.location,
            weighting: this.item.weighting,
            moreIsBetter: this.item.moreIsBetter,
            typeId: this.item.typeId,
            userId: this.item.userId,
            competitionId: this.item.competitionId,
          },
          {
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
              Authorization: `Bearer ${this.stateAuth.token}`,
            },
          }
        );
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = false;
      this.state = "Read";
    },
    async createItem() {
      this.loading = true;
      try {
        await axios.post(
          this.urlApi,
          {
            name: this.item.name,
            location: this.item.location,
            weighting: this.item.weighting,
            moreIsBetter: this.item.moreIsBetter,
            typeId: this.item.typeId,
            userId: this.item.userId,
            competitionId: this.stateAuth.currentCompId,
          },
          {
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
              Authorization: `Bearer ${this.stateAuth.token}`,
            },
          }
        );
        this.getCollections();
      } catch (error) {
        toast.error("Szerver hiba!");
      }
      this.loading = true;

      this.state = "Read";
    },
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
      this.fetchTeamsAtStation(id);
      const selectedStation = this.items.find((item) => item.id === id);
      if (selectedStation) {
        this.selectedStationName = selectedStation.name;
      }
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

.team-list-container {
  background-color: #f8f9fa;
  border-left: 1px solid #dee2e6;
  padding: 15px;
  border-radius: 0 0.375rem 0.375rem 0;
  min-height: 300px;
  box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
}

.team-list {
  padding: 0;
  list-style: none;
}

.team-item {
  background: #ffffff;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  padding: 8px 12px;
  margin-bottom: 8px;
  transition: transform 0.2s ease, background-color 0.3s ease;
  cursor: default;
}

.team-item:hover {
  background-color: #e9f5ff;
  transform: scale(1.02);
}

.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
