<template>
  <v-dialog v-model="show" dark max-width="700px">
    <v-stepper v-model="e1">
      <v-alert
        v-for="error in errors"
        :key="error.msg"
        v-model="alert"
        transition="scale-transition"
        dismissible
        type="error"
      >{{error.msg}}</v-alert>
      <v-stepper-header>
        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 1"
          :complete="e1 > 1"
          step="1"
        >Enter your Name & Email</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 2"
          :complete="e1 > 2"
          step="2"
        >Set a password</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step style="cursor: pointer;" @click="e1 = 3" :complete="e1 > 3" step="4">Confirm</v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <!-- Email -->
        <v-stepper-content step="1">
          <v-flex xs12>
            <v-text-field label="Name" v-model="name" required></v-text-field>
            <v-text-field label="Email" v-model="email" required></v-text-field>
          </v-flex>
          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>
        <!-- Password -->
        <v-stepper-content step="2">
          <v-flex xs12>
            <v-text-field label="Password" v-model="password" type="password" required></v-text-field>
            <v-text-field
              label="Confirm Password"
              v-model="password_confirmation"
              type="password"
              required
            ></v-text-field>
          </v-flex>
          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>
        <!-- Confirm -->
        <v-stepper-content step="3">
          <v-flex xs12>
            <v-subheader>
              Please confirm this is your email&nbsp;
              <b>{{email}}</b>
            </v-subheader>
          </v-flex>
          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="register">Register</v-btn>
          </v-layout>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </v-dialog>
</template>


<script>
export default {
  name: "registerModal",
  data() {
    return {
      e1: 0,
      alert: false,
      errors: [],
      name: this.name,
      email: this.email,
      password: this.password,
      password_confirmation: this.password_confirmation
    };
  },
  computed: {
    show: {
      get() {
        return this.$store.getters.registerActive;
      },
      set() {
        this.$store.dispatch("toggleRegisterModal");
      }
    }
  },
  methods: {
    register() {
      let name = this.name;
      let email = this.email;
      let password = this.password;
      let password_confirmation = this.password_confirmation;
      this.$store
        .dispatch("register", {
          name,
          email,
          password,
          password_confirmation
        })
        .then(() => {
          this.$store.commit("toggleRegisterModal");
          this.$router.push("/");
        })
        .catch(err => {
          this.alert = true;
          this.errors = err.response.data.errors;
        });
    },
    openLogin() {
      this.$store.commit("toggleRegisterModal");
      this.$store.commit("toggleLoginModal");
    }
  }
};
</script>

<style scoped>
#dropzone {
  background-color: #111820;
  color: #fff;
  border: none;
  height: 200px;
}
</style>