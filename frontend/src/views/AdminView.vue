<template>
  <div>
    <h1 class="text-center">Felhasználók</h1>
    <div class="inputBox" v-if="users.length > 0">
      <input type="text" v-model="searchQuery" required="required" />
      <span>keresés</span>
    </div>
    <hr />

    <div class="container" v-if="filteredUsers.length > 0">
      <div
        v-for="(users, role) in paginatedUsersByRole"
        :key="role"
        class="mb-5"
      >
        <h2 class="text-center">
          {{ getRoleName(role) }} •
          <span style="color: var(--color); font-weight: bold"
            >{{ users.length * totalPages(role) }}
          </span>
          fő
        </h2>
        <div class="table-responsive">
          <table
            class="table table-bordered table-hover table-striped shadow-sm rounded"
          >
            <thead class="table-dark">
              <tr>
                <th v-if="debug">#</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="user in users"
                :key="user.id"
                @click="onClickTr(user.id)"
                :class="{ active: user.id === selectedRowId }"
              >
                <td v-if="debug">{{ user.id }}</td>
                <td data-label="Username">{{ user.username }}</td>
                <td data-label="Name">{{ user.name }}</td>
                <td data-label="Email">{{ user.email }}</td>
              </tr>
            </tbody>
          </table>

          <div class="d-flex justify-content-center my-3">
            <div class="pagination-container d-flex">
              <div
                v-for="page in totalPages(role)"
                :key="page"
                @click="goToPage(role, page)"
                :class="[
                  'page-box',
                  { 'active-page': currentPage[role] === page },
                ]"
              >
                {{ page }}
              </div>
            </div>
          </div>
        </div>
        <hr />
      </div>
    </div>
    <div class="empty" v-else>
      <div v-if="users.length === 0">
        <div class="spinner-border" role="status">
          <span class="visually-hidden m-0">Loading...</span>
        </div>
      </div>
      <div v-else>
        <p>Nincs találat a keresésben.</p>
      </div>
    </div>
  </div>
</template>

<script>
import { BASE_URL } from "../helpers/baseUrls";
import { DEBUG } from "../helpers/debug";
import { useAuthStore } from "@/stores/useAuthStore.js";
import axios from "axios";
import { reactive } from "vue";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
  data() {
    return {
      urlApi: `${BASE_URL}/users`,
      stateAuth: useAuthStore(),
      users: [],
      searchQuery: "",
      selectedRowId: null,
      debug: DEBUG,
      itemsPerPage: 10,
      currentPage: reactive({
        1: 1, // Admin
        2: 1, // Supervisor
        3: 1, // Student
      }),
    };
  },
  mounted() {
    this.getCollections();
  },
  computed: {
    filteredUsers() {
      if (!this.searchQuery) return this.users;
      const regex = new RegExp(this.searchQuery, "i");
      return this.users.filter(
        (user) =>
          regex.test(user.username) ||
          regex.test(user.name) ||
          regex.test(user.email)
      );
    },
    usersByRole() {
      return this.filteredUsers.reduce((acc, user) => {
        if (!acc[user.roleId]) acc[user.roleId] = [];
        acc[user.roleId].push(user);
        return acc;
      }, {});
    },
    paginatedUsersByRole() {
      const paginated = {};
      for (const role in this.usersByRole) {
        const start = ((this.currentPage[role] || 1) - 1) * this.itemsPerPage;
        paginated[role] = this.usersByRole[role].slice(
          start,
          start + this.itemsPerPage
        );
      }
      return paginated;
    },
  },
  methods: {
    async getCollections() {
      try {
        const response = await axios.get(this.urlApi, {
          headers: { Authorization: `Bearer ${this.stateAuth.token}` },
        });
        this.users = response.data.data;
      } catch (error) {
        toast.error("Szerver hiba!");
      }
    },
    getRoleName(roleId) {
      return roleId == 1
        ? "Admin"
        : roleId == 2
        ? "Supervisor"
        : roleId == 3
        ? "Student"
        : "Egyéb";
    },
    totalPages(role) {
      return Math.ceil(
        (this.usersByRole[role] || []).length / this.itemsPerPage
      );
    },
    goToPage(role, page) {
      this.currentPage[role] = page;
    },
    onClickTr(id) {
      this.selectedRowId = id;
      this.$router.push(`/profile/${id}`);
    },
  },
};
</script>



<style scoped>
.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}
</style>
