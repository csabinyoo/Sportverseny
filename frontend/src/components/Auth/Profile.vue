<template>
  <div class="container mt-2">
    <Notification ref="notification" />
    <h1 class="title">Profil szerkesztése</h1>
    <hr />

    <!-- Username -->
    <div class="content">
      <div class="card mb-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <p>
            <strong>Felhasználónév:</strong>
            {{ isEditingField === "username" ? "" : user.username }}
          </p>
          <div
            v-if="isEditingField === 'username'"
            class="d-flex align-items-center"
          >
            <input
              type="text"
              class="form-control me-2"
              v-model="updatedField.username"
              placeholder="Enter new username"
            />
            <button class="btn btn-success me-2" @click="saveField('username')">
              Mentés
            </button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn" @click="startEdit('username')">
            Módosítás
          </button>
        </div>
      </div>

      <!-- Name -->
      <div class="card mb-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <p>
            <strong>Felhasználónév:</strong>
            {{ isEditingField === "name" ? "" : user.name }}
          </p>
          <div
            v-if="isEditingField === 'name'"
            class="d-flex align-items-center"
          >
            <input
              type="text"
              class="form-control me-2"
              v-model="updatedField.name"
              placeholder="Enter new name"
            />
            <button class="btn btn-success me-2" @click="saveField('name')">
              Mentés
            </button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn" @click="startEdit('name')">
            Módosítás
          </button>
        </div>
      </div>

      <!-- Email -->
      <div class="card mb-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <p>
            <strong>Email:</strong>
            {{ isEditingField === "email" ? "" : user.email }}
          </p>
          <div
            v-if="isEditingField === 'email'"
            class="d-flex align-items-center"
          >
            <input
              type="email"
              class="form-control me-2"
              v-model="updatedField.email"
              placeholder="Enter new email"
            />
            <button class="btn btn-success me-2" @click="saveField('email')">
              Mentés
            </button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn" @click="startEdit('email')">
            Módosítás
          </button>
        </div>
      </div>

      <!-- Password -->
      <div class="card mb-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <p><strong>Jelszó:</strong> ******* </p>
          <div
            v-if="isEditingField === 'password'"
            class="d-flex align-items-center"
          >
            <input
              type="password"
              class="form-control me-2"
              v-model="updatedField.password"
              placeholder="Enter new password"
            />
            <button class="btn btn-success me-2" @click="saveField('password')">
              Mentés
            </button>
            <button class="btn btn-secondary" @click="cancelEdit">Mégse</button>
          </div>
          <button v-else class="btn" @click="startEdit('password')">
            Módosítás
          </button>
        </div>
      </div>

      <!-- Delete Account -->
      <button class="btn btn-danger mt-3" @click="deleteUser">
        Fiók törlése
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { BASE_URL } from "../../helpers/baseUrls";
import { useAuthStore } from "../../stores/useAuthStore";
import Notification from "../../components/Notification.vue";

export default {
  components: {
    Notification,
  },
  data() {
    return {
      user: {},
      updatedField: {},
      isEditingField: null,
      store: useAuthStore(),
    };
  },
  async created() {
    await this.fetchUserData();
  },
  watch: {
    $route: "fetchUserData",
  },
  methods: {
    async fetchUserData() {
      const userId = this.$route.params.id;
      try {
        if (userId != this.store.id && this.store.roleId === 3) {
          const response = await axios.get(`${BASE_URL}/users/${this.store.id}`, {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          });
          this.user = response.data.data;
          this.$router.push(`/profile/${this.store.id}`);
        } else {
          const response = await axios.get(`${BASE_URL}/users/${userId}`, {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          });
          this.user = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching user profile:", error);
      }
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
      try {
        const updatedUser = { ...this.user, [field]: this.updatedField[field] };
        const userId = this.$route.params.id;

        const response = await axios.patch(
          `${BASE_URL}/users/${userId}`,
          updatedUser,
          {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          }
        );

        if (response.data.message === "This email already exists") {
          this.$refs.notification.showMessage(
            "Error: Ez az e-mail már használatban van.",
            "error"
          );
        } else {
          this.$refs.notification.showMessage(
            `${field} sikeresen frissítve.`,
            "success"
          );
          this.user = response.data.data;

          if (field === "username" && this.store.id.toString() === userId) {
            this.store.setUsername(this.updatedField.username);
          } else if (field === "name" && this.store.id.toString() === userId) {
            this.store.setUser(this.updatedField.name);
          }

          this.cancelEdit();

          if (
            (field === "email" || field === "password") &&
            this.store.id.toString() === userId
          ) {
            this.$refs.notification.showMessage(
              "Kérlek jelentkezz be újra.",
              "info"
            );
            console.log(this.updatedField[field]);
            
            this.store.clearStoredData();
            setTimeout(() => {
              
              this.$router.push("/bejelentkezes");
            }, 1500);
          }
        }
      } catch (error) {
        console.error("Error updating field:", error);
        this.$refs.notification.showMessage(
          "Nem sikerült frissíteni a mezőt. Kérjük, próbálja újra.",
          "error"
        );
      }
    },
    async deleteUser() {
      if (confirm("Biztosan le akarod törölni a fiókodat?")) {
        try {
          const userId = this.$route.params.id;

          await axios.delete(`${BASE_URL}/users/${userId}`, {
            headers: {
              Authorization: `Bearer ${this.store.token}`,
            },
          });
          this.$refs.notification.showMessage(
            "Felhasználó sikeresen törölve",
            "success"
          );
          if (this.store.id.toString() === userId) {
            this.store.clearStoredData();
          }
          this.$router.push("/");
        } catch (error) {
          console.error("Error deleting user:", error);
          this.$refs.notification.showMessage(
            "Nem sikerült letörölni a fiókot. Kérlek próbáld újra.",
            "error"
          );
        }
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
.content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 80vh;
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
  text-align: center;
  padding: 20px;
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
.card .btn {
  margin: 5px 0;
  background: #3498db;
  color: #fff;
  transition: 0.3s;
}
.card .btn:hover {
  background: #035b95;
}
</style>
