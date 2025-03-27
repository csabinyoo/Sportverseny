<template>
  <form
    @submit.prevent="onClickSubmit"
    class="row g-4 needs-validation was-validated"
  >
    <p v-if="debug">{{ itemForm }}</p>

    <!-- Verseny kiválasztása -->
    <div class="col-md-5 position-relative">
      <label for="competitionId" class="form-label">Verseny:</label>
      <select
        class="form-select"
        id="competitionId"
        v-model="itemForm.competitionId"
        required
      >
        <option
          v-for="competition in competitions"
          :key="competition.id"
          :value="competition.id"
        >
          {{ competition.name }}
        </option>
      </select>
    </div>

    <!-- Csapatnév -->
    <div class="col-md-4 position-relative">
      <label for="name" class="form-label">Csapatnév:</label>
      <input
        type="text"
        class="form-control"
        id="name"
        required
        v-model="itemForm.name"
      />
    </div>

    <!-- Kapitány kiválasztása (csak azok, akiknek roleId === 3) -->
    <div class="col-md-4 position-relative">
      <label for="userId" class="form-label">Kapitány:</label>
      <select
        class="form-select"
        id="userId"
        v-model="itemForm.userId"
        required
      >
        <option v-for="user in filteredUsers" :key="user.id" :value="user.id">
          {{ user.name }}
        </option>
      </select>
    </div>

    <!-- Iskola -->
    <div class="col-md-8 position-relative">
      <label for="school" class="form-label">Iskola:</label>
      <input
        type="text"
        class="form-control"
        id="school"
        required
        v-model="itemForm.school"
      />
    </div>

    <!-- Mentés gomb -->
    <button type="submit" class="btn btn-success">Mentés</button>
  </form>
</template>
  
  <script>
export default {
  props: ["itemForm", "debug", "competitions", "users"],
  emits: ["saveItem"],
  computed: {
    filteredUsers() {
      return this.users.filter((user) => user.roleId === 3);
    },
  },
  methods: {
    onClickSubmit() {
      this.$emit("saveItem", this.itemForm);
    },
  },
};
</script>
  
  <style scoped>
</style>
  