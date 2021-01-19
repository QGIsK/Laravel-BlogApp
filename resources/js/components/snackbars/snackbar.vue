<template>
  <div>
    <v-card>
      <v-snackbar
        v-model="show"
        :color="type"
        right="right"
        :timeout="timeout"
        top="top"
        :vertical="mode === 'vertical'"
      >
        {{ text }}
        <v-btn color flat @click="toggleSnackBar">Close</v-btn>
      </v-snackbar>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "snackbar",
  data() {
    return {
      mode: "",
      color: "success",
      timeout: 3000
    };
  },
  computed: {
    show: {
      get() {
        return this.$store.getters.snackbarActive;
      },
      set() {
        let payload = {
          type: "",
          text: ""
        };
        this.$store.dispatch("toggleSnackBar", payload);
      }
    },
    text: {
      get() {
        return this.$store.getters.snackbarText;
      }
    },
    type: {
      get() {
        return this.$store.getters.snackbarType;
      }
    }
  },
  methods: {
    toggleSnackBar() {
      let payload = {
        type: "",
        text: ""
      };
      this.$store.dispatch("toggleSnackBar", payload);
    }
  }
};
</script>
