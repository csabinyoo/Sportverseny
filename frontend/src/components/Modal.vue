<template>
  <div class="modal" id="modal" tabindex="-1">
    <div
      class="modal-dialog modal-dialog-centered"
      :class="{
        'modal-xl': size == 'xl',
        'modal-lg': size == 'lg',
        'modal-sm': size == 'sm',
      }"
    >
      <div class="modal-content">
        <div class="modal-header text-center">
          <h1 class="modal-title fs-5 w-100" id="exampleModalLabel">{{ title }}</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
            v-if="no"
          >
            {{ no }}
          </button>
          <button
            type="button"
            class="btn btn-primary"
            v-if="yes"
            @click="onClickYesButton()"
            data-bs-dismiss="modal"
          >
            {{ yes }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: ["title", "yes", "no", "size"],
  emits: ["yesEvent"],

  methods: {
    onClickYesButton() {
      this.$emit("yesEvent");
    },
  },
};
</script>
  
<style scoped>
.btn {
  background: var(--color);
  border: 0;
}
.modal-body {
  max-height: 600px;
  overflow: auto;
}
</style>