import { defineStore } from "pinia";
// sessionStorage
export const useAuthStore = defineStore("auth", {
  state: () => ({
    id: Number(sessionStorage.getItem("id")) || null,
    user: sessionStorage.getItem("user") || null,
    username: sessionStorage.getItem("username") || null,
    token: sessionStorage.getItem("currentToken") || null,
    currentCompId: Number(sessionStorage.getItem("currentCompId")) || null,
    currentCompName: sessionStorage.getItem("currentCompName") || null,
    roleId: Number(sessionStorage.getItem("roleId")) || null, // roleId hozzáadása
  }),
  actions: {
    setId(id) {
      sessionStorage.setItem("id", id);
      this.id = id;
    },
    setCurrentCompId(id, name) {
      sessionStorage.setItem("currentCompId", id);
      this.currentCompId = id;
      this.currentCompName = name;
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
    setGuestUser() {
      const uniqueId = Date.now();
      const guestUser = `G_${uniqueId}`;
      const guestUsername = `G_U_${uniqueId}`;
      const guestroleId = 4;
    
      this.user = guestUser;
      this.username = guestUsername;
      this.roleId = guestroleId;
      this.token = null;
      this.id = uniqueId;
    
      // Itt mentjük sessionStorage-ba, hogy újratöltés után is megmaradjon
      sessionStorage.setItem("user", guestUser);
      sessionStorage.setItem("username", guestUsername);
      sessionStorage.setItem("roleId", guestroleId);
      sessionStorage.setItem("id", uniqueId);
    },
    clearStoredData() {
      sessionStorage.removeItem("currentToken");
      sessionStorage.removeItem("user");
      sessionStorage.removeItem("username");
      sessionStorage.removeItem("id");
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("roleId"); // roleId törlése is
      sessionStorage.removeItem("currentCompId");
      sessionStorage.removeItem("currentCompName");
      this.token = null;
      this.user = null;
      this.id = null;
      this.roleId = null;
      this.username = null;
      this.currentCompId = null;
      this.currentCompName = null;
    },
  },
});