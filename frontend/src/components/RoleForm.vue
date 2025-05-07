<template>
  <form
    @submit.prevent="onClickSubmit"
    class="form-container needs-validation was-validated row g-3"
  >
    <p v-if="debug" class="debug-text">{{ itemForm }}</p>

    <!-- Verseny kiválasztása -->
    <div
      class="form-group mb-0 mt-0 d-flex justify-content-between competition"
    >
      <p for="competitionId" class="form-label">Verseny</p>
      <p for="competitionId" class="form-label">
        <strong>{{ stateAuth.currentCompName }}</strong>
      </p>
    </div>

    <!-- Role -->
    <div class="form-group col-md-6">
      <label for="name" class="form-label">Rang neve:</label>
      <input
        type="text"
        class="form-control"
        id="role"
        required
        v-model="itemForm.role"
      />
      <div class="valid valid-feedback">•</div>
      <div class="valid invalid-feedback">•</div>
    </div>

    <!-- Permission -->
    <div class="form-group col-md-6">
      <label for="userId" class="form-label">Jog:</label>
      <select
        class="form-select"
        id="permission"
        v-model="itemForm.permission"
        required
      >
        <option v-for="num in [0, 1, 2, 3]" :key="num" :value="num">
          {{ num }}
        </option>
      </select>
      <div class="valid valid-feedback">•</div>
      <div class="valid invalid-feedback">•</div>
    </div>

    <!-- Mentés gomb -->
    <button type="submit" class="btn btn-submit">Mentés</button>
  </form>
</template>

<script>
import { useAuthStore } from "@/stores/useAuthStore.js";

export default {
  props: ["itemForm", "debug", "competitions"],
  emits: ["saveItem"],
  data() {
    return {
      stateAuth: useAuthStore(),
    };
  },
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.itemForm);
    },
  },
};
</script>

<style scoped>
/* 1. General Styles */
.form-container {
  background-color: #f9f9f9;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: auto;
  transition: all 0.3s ease;
}

.form-container:hover {
  transform: scale(1.02);
}

.form-group {
  position: relative;
  margin-bottom: 1.5rem;
}

.form-label {
  font-weight: bold;
  font-size: 1.1rem;
  color: #333;
  display: block;
  margin-bottom: 0.5rem;
}

.form-select,
.form-control {
  width: 100%;
  padding: 0.8rem;
  font-size: 1rem;
  border-radius: 5px;
  border: 1px solid #ccc;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-select:focus,
.form-control:focus {
  border-color: #0066cc;
  box-shadow: 0 0 5px rgba(0, 102, 204, 0.5);
}

.btn-submit {
  padding: 0.8rem 2rem;
  font-size: 1.1rem;
  background-color: var(--color);
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-submit:hover {
  background-color: var(--bg-color);
  color: #fff;
  transform: translateY(-2px);
}

.debug-text {
  color: #888;
  font-size: 0.9rem;
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .form-container {
    padding: 1.5rem;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .btn-submit {
    width: 100%;
  }
}

.valid {
  position: absolute;
  top: -15px;
  left: -5px;
  font-size: 2rem;
  width: 10px;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% {
    opacity: 0.6;
    transform: scale(0.8);
  }

  50% {
    opacity: 1;
    transform: scale(1.2);
  }

  100% {
    opacity: 0.6;
    transform: scale(0.8);
  }
}

.competition::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  border-radius: 25px;
  height: 5px;
  width: 100%;
  /* background: var(--color); */
  background: linear-gradient(to right, transparent, var(--color), transparent);
}
</style>
