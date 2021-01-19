<template>
  <v-dialog v-model="show" dark max-width="600px">
    <v-card>
      <v-card-title class="headline grey darken-4 text-xs-center">Login</v-card-title>
      <form @submit="login">
        <v-card-text>
          <v-alert v-model="alert" transition="scale-transition" dismissible type="error">{{error}}</v-alert>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field label="Email" v-model="email" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field label="Password" v-model="password" type="password" required></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="darken-1" flat @click="openRegister()">Don't have an account yet?</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="darken-1" @click="login()">Login</v-btn>
        </v-card-actions>
      </form>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
    name: "loginModal",
    data() {
        return {
            e1: 0,
            alert: false,
            error: "",
            password: this.password,
            email: this.email,
        };
    },
    computed: {
        show: {
            get() {
                return this.$store.getters.loginActive;
            },
            set() {
                this.$store.dispatch("toggleLoginModal");
            },
        },
    },
    methods: {
        login() {
            let email = this.email;
            let password = this.password;
            this.$store
                .dispatch("login", {
                    email,
                    password,
                })
                .then(() => {
                    this.$store.commit("toggleLoginModal");
                    if (this.$store.getters.isEditor) return this.$router.push("/post/my");
                    this.$router.push("/");
                })
                .catch(e => {
                    this.alert = true;
                    this.error = e.response.data.error;
                });
        },
        openRegister() {
            this.$store.commit("toggleLoginModal");
            this.$store.commit("toggleRegisterModal");
        },
    },
};
</script>
