<template>
  <div v-if="loading" class="loading-overlay">
    <div class="loading-content">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="loading-text">Loading...</div>
    </div>
  </div>
  <div class="test" v-else>
    <div class="team-registration-container">
      <div v-if="teamRegistered" class="message success">
        <p class="text-center mb-0" style="font-size: 1.5rem">
          Már van csapatod
        </p>
      </div>

      <div v-else>
        <h2>Csapat regisztrálása</h2>
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="teamName">Csapat neve</label>
            <input
              v-model="teamName"
              type="text"
              id="teamName"
              class="form-control"
              placeholder="Add meg a csapat nevét"
              required
            />
          </div>

          <div class="form-group">
            <label for="schoolName">Iskola neve</label>
            <input
              v-model="schoolName"
              type="text"
              id="schoolName"
              class="form-control"
              placeholder="Add meg az iskola nevét"
              required
            />
          </div>

          <label class="mb-2">Csapattagok</label>
          <div
            v-for="(member, index) in teamMembers"
            :key="index"
            class="member-row"
          >
            <span class="icon plus" @click="addMember(index)">＋</span>
            <input
              v-model="member.name"
              type="text"
              class="form-control member-input"
              placeholder="Tag neve"
              required
            />
            <span class="icon remove" @click="removeMember(index)">×</span>
          </div>

          <button
            type="submit"
            class="btn submit-btn"
            :disabled="!teamName || !schoolName || teamMembers.length < 0"
          >
            Csapat regisztrálása
          </button>
        </form>
      </div>
    </div>
  </div>
</template>


<script>
import axios from "axios";
import { BASE_URL } from "@/helpers/baseUrls";
import { useAuthStore } from "@/stores/useAuthStore.js";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
  data() {
    return {
      teamName: "",
      schoolName: "",
      loading: false,
      teamMembers: [{ name: "" }],
      teamRegistered: false,
      stateAuth: useAuthStore(),
    };
  },
  mounted() {
    this.checkIfTeamRegistered();
  },
  methods: {
    addMember(index) {
      this.teamMembers.splice(index + 1, 0, { name: "" });
    },

    async checkIfTeamRegistered() {
      this.loading = true;
      try {
        const response = await axios.get(`${BASE_URL}/teams`, {
          headers: {
            Accept: "application/json",
          },
        });

        const existingTeam = response.data.data.find(
          (team) => team.userId === this.stateAuth.id
        );
        if (existingTeam) {
          this.teamRegistered = true;
        }
      } catch (error) {
        console.error("Hiba történt a csapat ellenőrzésekor:", error);
      }
      this.loading = false;
    },

    addMember() {
      this.teamMembers.push({ name: "" });
    },

    removeMember(index) {
      if (this.teamMembers.length > 1) {
        this.teamMembers.splice(index, 1);
      } else {
        toast.warning("Legalább egy tagot hozzá kell adnod!");
      }
    },

    async submitForm() {
      // if (this.teamRegistered) return;
      this.loading = true;
      const token = this.stateAuth.token;
      const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      };

      try {
        const teamResponse = await axios.post(
          `${BASE_URL}/teams`,
          {
            name: this.teamName,
            school: this.schoolName,
            userId: this.stateAuth.id,
            competitionId: this.stateAuth.currentCompId,
          },
          { headers }
        );

        const teamId = teamResponse.data.id;

        for (const member of this.teamMembers) {
          await axios.post(
            `${BASE_URL}/teammember`,
            {
              teamId,
              name: member.name,
              captain: 0,
            },
            { headers }
          );
        }

        toast("A csapat és a tagok sikeresen regisztrálva!");
        this.teamRegistered = true;
      } catch (error) {
        console.error("Hiba történt a regisztráció során:", error);
        toast("Hiba történt a regisztráció során.");
      }
      this.loading = false;
    },
  },
};
</script>

<style scoped>
.test {
  height: 100vh;
  display: flex;
  justify-content: center;
  padding: 10px;
  align-items: center;
}

.team-registration-container {
  width: 600px;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

h2 {
  font-size: 26px;
  margin-bottom: 20px;
  text-align: center;
  color: #222;
}

.form-group {
  margin-bottom: 20px;
}

label {
  font-weight: 600;
  display: block;
  margin-bottom: 6px;
}

.form-control {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: var(--color);
  outline: none;
}

.members-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}

.member-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.member-input {
  flex: 1;
}

.icon {
  font-size: 20px;
  font-weight: bold;
  cursor: pointer;
  user-select: none;
}

.icon.plus {
  color: #28a745;
}

.icon.plus:hover {
  color: #218838;
}

.icon.remove {
  color: #dc3545;
}

.icon.remove:hover {
  color: #c82333;
}

.btn.submit-btn {
  width: 100%;
  background-color: var(--color);
  border: 0;
  color: #fff;
  font-weight: bold;
  padding: 12px;
  border-radius: 8px;
}

.btn.submit-btn:hover {
  background-color: var(--bg-color);
}

.message.success {
  padding: 15px;
  border-radius: 8px;
  color: var(--color);
  font-weight: bold;
  text-align: center;
}

.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}

.btn {
  background: var(--color);
  color: #fff;
}

.btn:hover {
  background: var(--bg-color);
}
</style>
