<template>
  <div>
    <h1 class="title">Profil szerkesztése</h1>
    <hr />
    <div class="container">
      <div v-if="loading" class="loading-overlay">
        <div class="loading-content">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="loading-text">Loading...</div>
        </div>
      </div>

      <div class="content-profile">
        <div v-for="field in fields" :key="field.key" class="card mb-3">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <p>
              <strong>{{ field.label }}:</strong>
              <span v-if="field.key !== 'password'">
                {{
                  isEditingField === field.key
                    ? ""
                    : " " + (user[field.key] || "Nincs megadva")
                }}
              </span>
              <span v-else>*******</span>
            </p>

            <div
              v-if="isEditingField === field.key"
              class="d-flex flex-column align-items-center"
            >
              <input
                :type="field.type"
                @focus="clearValue(field.key)"
                class="form-control me-2"
                v-model="updatedField[field.key]"
                :placeholder="`Enter new ${field.label.toLowerCase()}`"
              />
              <div class="d-flex justify-content-center gap-2">
                <button class="btn" @click="saveField(field.key)">
                  Mentés
                </button>
                <button class="btn" @click="cancelEdit">Mégse</button>
              </div>
            </div>

            <button
              v-if="user[field.key] && isEditingField !== field.key"
              class="btn"
              @click="startEdit(field.key)"
            >
              Módosítás
            </button>
          </div>
        </div>

        <div class="card mb-3" v-if="store.roleId === 1">
          <div
            class="card-body d-flex justify-content-between align-items-center"
          >
            <p>
              <strong>Jog:</strong>
              {{
                isEditingField === "roleId"
                  ? ""
                  : " " + getRoleName(user.roleId)
              }}
            </p>
            <div
              v-if="isEditingField === 'roleId'"
              class="d-flex flex-column align-items-center"
            >
              <select class="form-control me-2" v-model="updatedField.roleId">
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.role }}
                </option>
              </select>
              <div class="d-flex justify-content-center gap-2">
                <button class="btn btn-success" @click="saveField('roleId')">
                  Mentés
                </button>
                <button class="btn btn-secondary" @click="cancelEdit">
                  Mégse
                </button>
              </div>
            </div>
            <button
              v-if="user.roleId && isEditingField !== 'roleId'"
              class="btn"
              @click="startEdit('roleId')"
            >
              Módosítás
            </button>
          </div>
        </div>

        <button class="btn mt-1 delete" @click="deleteUser">
          Fiók törlése
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";
import { useAuthStore } from "../../stores/useAuthStore";
import { useToast } from "vue-toastification";
const toast = useToast();

export default {
  components: { Notification },
  data() {
    return {
      user: {},
      roles: [],
      loading: false,
      updatedField: {},
      isEditingField: null,
      store: useAuthStore(),
      fields: [
        { key: "username", label: "Username", type: "text" },
        { key: "name", label: "Name", type: "text" },
        { key: "email", label: "Email", type: "email" },
        { key: "password", label: "Password", type: "password" },
      ],
    };
  },
  mounted() {
    // const userIdFromRoute = this.$route.params.id;
    // const isAdmin = this.store.roleId === 1;
    // const isOwnProfile = this.store.id.toString() === userIdFromRoute;

    // if (!isAdmin && !isOwnProfile) {
    //   this.$router.replace(`/profile/${this.store.id}`);
    //   return;
    // }
    this.fetchUserData();
    this.fetchRoles();
  },

  watch: {
    $route: "fetchUserData",
  },
  methods: {
    async fetchUserData() {
      const userIdFromRoute = this.$route.params.id;
      const isAdmin = this.store.roleId === 1;
      const isGuest = this.store.roleId === 4;
      const isOwnProfile = this.store.id.toString() === userIdFromRoute;

      if (isGuest) {
        toast.error("Vengédként nincsen jogosultságod!");
        this.$router.replace(`/`);
        return;
      }

      if (!isAdmin && !isOwnProfile) {
        this.$router.replace(`/profile/${this.store.id}`);
        return;
      }

      try {
        this.loading = true;
        const response = await axios.get(
          `${BASE_URL}/users/${userIdFromRoute}`,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );
        this.user = response.data.data;
      } catch (error) {
        console.error("Error fetching user profile:", error);
      }
      setTimeout(() => {
        this.loading = false;
      }, 1000);
    },
    arValue(key) {
      if (key === "password") {
        this.updatedField[key] = null;
      }
    },
    async fetchRoles() {
      try {
        const response = await axios.get(`${BASE_URL}/roles`, {
          headers: { Authorization: `Bearer ${this.store.token}` },
        });
        this.roles = response.data.data;
      } catch (error) {
        console.error("Error fetching roles:", error);
      }
    },
    getRoleName(roleId) {
      const role = this.roles.find((role) => role.id === roleId);
      return role ? role.role : "Nincs kijelölt jog";
    },
    startEdit(field) {
      this.isEditingField = field;
      this.updatedField[field] = this.user[field];
    },
    cancelEdit() {
      this.isEditingField = null;
      this.updatedField = {};
    },
    async saveField(field) {
      if (this.updatedField[field] === this.user[field]) {
        this.cancelEdit();
        return;
      }

      try {
        this.loading = true;
        const userId = this.$route.params.id;
        const updatedUser = { ...this.user, [field]: this.updatedField[field] };
        const response = await axios.patch(
          `${BASE_URL}/users/${userId}`,
          updatedUser,
          {
            headers: { Authorization: `Bearer ${this.store.token}` },
          }
        );

        this.user = response.data.data;
        toast(`${field} sikeresen frissítve`);

        if (field === "username" && this.store.id.toString() === userId) {
          this.store.setUsername(this.updatedField.username);
        }
        if (field === "name" && this.store.id.toString() === userId) {
          this.store.setUser(this.updatedField.name);
        }
        if (field === "roleId" && this.store.id.toString() === userId) {
          this.store.setRoleId(this.updatedField.roleId);
        }

        if (
          (field === "email" || field === "password") &&
          this.store.id.toString() === userId
        ) {
          toast.warning("Jelentkezz be újra!");

          this.store.clearStoredData();
          setTimeout(() => {
            this.$router.push("/bejelentkezes");
          }, 1500);
        }

        this.cancelEdit();
      } catch (error) {
        console.error("Error updating field:", error);
        this.cancelEdit();
        toast.error("Hiba a frissítéskor!");
      }
      this.loading = false;
    },
    async deleteUser() {
      if (confirm("Biztosan törölni szeretnéd a fiókot?")) {
        try {
          const userId = this.$route.params.id;
          await axios.delete(`${BASE_URL}/users/${userId}`, {
            headers: { Authorization: `Bearer ${this.store.token}` },
          });
          toast("Felhasználó törölve!");
          this.store.clearStoredData();
          this.$router.push("/admin");
        } catch (error) {
          console.error("Error deleting user:", error);
          toast.error("Hiba a törléskor!");
        }
      }
    },
  },
};
</script>


<style scoped>
.container {
  max-width: 600px;
}

.content-profile {
  display: flex;
  flex-direction: column;
}

.card {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 100%;
}

.card-body {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.card-body p {
  margin: 0;
}

button {
  margin-top: 10px;
  width: auto;
}

.title {
  text-align: center;
}

.card input {
  width: 120%;
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

.card:nth-child(1),
.card:nth-child(2),
.card:nth-child(3),
.card:nth-child(4),
.card:nth-child(5),
.delete {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 1s ease-out forwards;
}

/* Animáció definíciója */
@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.card:nth-child(2) {
  animation-delay: 0.3s;
}

.card:nth-child(3) {
  animation-delay: 0.6s;
}

.card:nth-child(4) {
  animation-delay: 0.9s;
}

.card:nth-child(5) {
  animation-delay: 1.2s;
}

.delete {
  animation-delay: 1.5s;
}

.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}
</style>
