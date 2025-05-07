<template>
  <div class="wrapper">
    <div v-if="loading" class="loading-overlay">
      <div class="loading-content">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loading-text">Loading...</div>
      </div>
    </div>
    <div class="container">
      <h1 class="title">Táblák listája</h1>
      <ul class="table-list">
        <li
          v-for="(table, index) in tables"
          :key="index"
          class="table-item"
          v-show="visibleTables.includes(index)"
        >
          <a :href="table.path" class="table-link">
            <span class="material-symbols-outlined icon">{{ table.icon }}</span>
            <span class="table-text">{{ table.name }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tables: [
        { name: "Felhasználók", icon: "dashboard", path: "/admin" },
        { name: "Versenyek", icon: "trophy", path: "/competitions" },
        { name: "Csapatok", icon: "groups", path: "/teams" },
        { name: "Roles", icon: "done_all", path: "/roles" },
        { name: "Állomások", icon: "simulation", path: "/stations" }
        // { name: "Eredménytípusok", icon: "sports_score", path: "/competitions" }
        // { name: "Csapattagok", icon: "reduce_capacity", path: "/teamMembers" },
        // { name: "CsapatAzÁllomáson", icon: "emoji_people", path: "/competitions" },
        // { name: "CsapatEredményeiAzÁllomáson", icon: "leaderboard", path: "/competitions"}
      ],
      visibleTables: [],
      loading: false,
    };
  },
  mounted() {
    this.showTablesWithDelay();
  },
  methods: {
    showTablesWithDelay() {
      this.loading = true;
      let totalTables = this.tables.length;
      let loadedTables = 0;

      this.tables.forEach((_, index) => {
        setTimeout(() => {
          this.visibleTables.push(index);
          loadedTables++;

          if (loadedTables === totalTables) {
            this.loading = false;
          }
        }, index * 100);
      });
    },
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/icon?family=Material+Icons");

.wrapper {
  display: flex;
  align-items: center;
  width: 80%;
  height: 100vh;
  margin: 0 auto;
}

.container {
  max-width: 600px;
  padding: 20px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  text-align: center;
  font-family: Arial, sans-serif;
}

.title {
  position: relative;
  font-size: 26px;
  color: #222;
  margin-bottom: 20px;
  font-weight: bold;
}

.title::after {
  content: "";
  position: absolute;
  width: 0;
  height: 5px;
  bottom: -5px;
  left: 50%;
  transform: translate(-50%, -50%);
  border-radius: 15px;
  background: var(--color);
  animation: myWidth 3s linear forwards;
}

@keyframes myWidth {
  from{width: 0;}
  to{width: 50%;}
}

.table-list {
  list-style: none;
  padding: 0;
}

.table-item {
  margin: 12px 0;
}

.table-link {
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 18px;
  color: black;
  padding: 10px 15px;
  border-radius: 8px;
  background: #f0f0f0;
  transition: background 0.3s;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table-link:hover {
  background: var(--table-hover);
  color: black;
}

.icon {
  margin-right: 8px;
  font-size: 20px;
  color: var(--color);
  transition: 0.3s;
}

.table-text {
  color: black;
  transition: 0.3s;
}

.table-link:hover .icon {
  transform: rotateY(180deg);
}

@media (max-width: 768px) {
  .container {
    padding: 15px;
  }

  .title {
    font-size: 22px;
  }

  .table-link {
    font-size: 16px;
    padding: 8px 12px;
  }

  .icon {
    font-size: 18px;
  }
}

.spinner-border {
  width: 4rem;
  height: 4rem;
  border-width: 0.5em;
  color: var(--color);
}
</style>