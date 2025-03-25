<template>
  <div>
    <h1 class="text-center my-4">Csapatok</h1>
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
          v-if="teams.length == 0"
        >
          <span class="visually-hidden m-0">Loading...</span>
        </div>

        <div class="col-12 col-lg-12 tabla-container" v-if="teams.length > 0">
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Verseny</th>
                <th>Csapatnév</th>
                <th>Helyszín</th>
                <th>Csapatkapitány</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="team in paginatedCollections"
                :key="team.id"
                @click="onClickTr(team.id)"
                :class="{
                  updating: loading,
                  active: team.id === selectedRowId,
                }"
              >
                <td data-label="ID">{{ team.id }}</td>
                <td data-label="Username">{{ team.competitionId }}</td>
                <td data-label="Name">{{ team.name }}</td>
                <td data-label="Email">{{ team.school }}</td>
                <td data-label="Role">{{ team.userId }}</td>

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
          <div v-if="state == 'Delete'">
            {{ messageYesNo }}
          </div>
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
class User {
  constructor(
    id = null,
    name = null,
    email = null,
    password = null,
    roleId = null
  ) {
    this.id = id;
    this.name = name;
    this.email = email;
    this.password = password;
    this.roleId = roleId;
  }
}
import { BASE_URL } from "../helpers/baseUrls";
import { useAuthStore } from "@/stores/useAuthStore.js";
import ErrorMessage from "@/components/ErrorMessage.vue";
import axios from "axios";
import * as bootstrap from "bootstrap";
export default {
  components: { ErrorMessage },
  data() {
    return {
      urlApi: `${BASE_URL}/teams`,
      stateAuth: useAuthStore(),
      items: [],
      loading: false,
      modal: null,
      currentPage: 1,
      itemsPerPage: 10,
      user: new User(),
      selectedRowId: null,
      messageYesNo: null,
      state: "Read", //CRUD: Create, Read, Update, Delete
      title: null,
      yes: null,
      no: null,
      size: null,
      errorMessages: null,
      teams: [],
    };
  },
  mounted() {
    this.getCollections();
    this.modal = new bootstrap.Modal("#modal", {
      keyboard: false,
    });
  },
  computed: {
    paginatedCollections() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.teams.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.teams.length / this.itemsPerPage);
    },
  },
  methods: {
    async getCollections() {
      const url = this.urlApi;
      const token = this.stateAuth.token;

      const headers = {
        Accept: "application/json",
      };
      try {
        const response = await axios.get(url, { headers });
        this.teams = response.data.data;

        this.loading = false;
      } catch (error) {
        this.errorMessages = "Szerver hiba";
      }
    },
    
    getRoleName(roleId) {
      return roleId === 1
        ? "Admin"
        : roleId === 2
        ? "Supervisor"
        : roleId === 3
        ? "Student"
        : "";
    },

    goToPage(page) {
      this.currentPage = page;
    },

    onClickTr(id) {
      // if (this.selectedRowId === id) {
      //   this.selectedRowId = null;
      // } else {
      //   this.selectedRowId = id;
      // }
      this.selectedRowId = id;
      // this.$router.push(`/profile/${id}`);
    },
  },
};
</script>
  
  <style scoped>
.active {
  --bs-table-bg: rgba(0, 0, 255, 0.1) !important;
}

.tabla-container {
  max-height: 600px;
  overflow: auto;
}

.table th,
.table td {
  vertical-align: middle;
  overflow: hidden;
}

.table-hover tbody tr:hover {
  background-color: #f1f1f1;
  cursor: pointer;
}

.table th,
.table td {
  text-align: center;
}

.table-dark th {
  background-color: #343a40;
  color: white;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f8f9fa;
}

.shadow-sm {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.rounded {
  border-radius: 8px;
}

h1 {
  font-size: 2.5rem;
  font-weight: bold;
}

.updating {
  pointer-events: none;
  opacity: 0.6;
}

.pagination-container {
  display: flex;
  max-width: 1000px;
  overflow-x: auto;
  gap: 5px;
}

.page-box {
  min-width: 40px;
  line-height: 40px;
  margin-bottom: 10px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  background-color: #f8f9fa;
  font-weight: bold;
  transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
}

.page-box:hover {
  background-color: #58c2ff;
  background-image: -webkit-linear-gradient(
    45deg,
    rgba(255, 255, 255, 0.4) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255, 255, 255, 0.4) 50%,
    rgba(255, 255, 255, 0.4) 75%,
    transparent 75%,
    transparent
  );
  color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.active-page {
  background-color: #58c2ff;
  color: white;
  transition: 0.3s;
  background-image: -webkit-linear-gradient(
    45deg,
    rgba(255, 255, 255, 0.4) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255, 255, 255, 0.4) 50%,
    rgba(255, 255, 255, 0.4) 75%,
    transparent 75%,
    transparent
  );
}

@media (max-width: 991px) {
  table {
    display: block;
    width: 100%;
    overflow-x: auto;
    border: 0;
  }

  thead {
    display: none;
  }

  tbody {
    display: block;
    width: 100%;
  }

  tbody tr {
    display: block;
    margin-bottom: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    background-color: #f8f9fa;
  }

  tbody tr:hover {
    background-color: #e9ecef;
  }

  td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    padding: 5px 10px;
    border-bottom: 1px solid #222 !important;
  }

  td:last-child {
    border-bottom: 0 !important;
  }

  td:before {
    content: attr(data-label);
    font-weight: bold;
    text-transform: capitalize;
    color: #6c757d;
  }

  td span {
    text-align: right;
    /* color: #212529; */
  }
}

.password span {
  font-size: 12px;
}
</style>