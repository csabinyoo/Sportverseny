import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    id: Number(sessionStorage.getItem("id")) || null,
    user: sessionStorage.getItem("user") || null,
    username: sessionStorage.getItem("username") || null,
    token: sessionStorage.getItem("currentToken") || null,
    roleId: Number(sessionStorage.getItem("roleId")) || null, // roleId hozzáadása
  }),
  actions: {
    setId(id) {
      sessionStorage.setItem("id", id);
      this.id = id;
    },
    setUser(user) {
      sessionStorage.setItem("user", user);
      this.user = user;
    }, 
    setUsername(username) {
      sessionStorage.setItem("username", username);
      this.username = username;
    },
    setToken(token) {
      sessionStorage.setItem("currentToken", token);
      this.token = token;
    },
    setRoleId(roleId) {
      // Új metódus a roleId beállításához
      sessionStorage.setItem("roleId", roleId);
      this.roleId = roleId;
    },
    clearStoredData() {
      sessionStorage.removeItem("currentToken");
      sessionStorage.removeItem("user");
      sessionStorage.removeItem("username");
      sessionStorage.removeItem("id");
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("roleId"); // roleId törlése is
      this.token = null;
      this.user = null;
      this.id = null;
      this.roleId = null;
      this.username = null;
    },
  },
});