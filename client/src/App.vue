<template>
  <v-app :dark="true">
    <snackbar/>
    <v-toolbar color="gray" dense fixed clipped-left app>
      <!-- <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon> -->
      <v-toolbar-title class="mr-5 align-center">
        <v-list-tile-title style="cursor:pointer" @click="redirect('/')">Blog App</v-list-tile-title>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-toolbar-items v-if="isLoggedIn">
        <v-menu transition="slide-y-transition" bottom offset-y v-if="isEditor">
          <template v-slot:activator="{ on }">
            <v-btn dark flat v-on="on">Post</v-btn>
          </template>
          <v-list>
            <v-list-tile v-if="isAdmin">
              <v-list-tile-title
                style="cursor:pointer"
                @click.stop="changeModal('toggleAddCategoryModal')"
              >New Category</v-list-tile-title>
              <addCategoryModal/>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-title
                style="cursor:pointer"
                @click.stop="changeModal('toggleNewPostModal')"
              >New Post</v-list-tile-title>
              <newPost/>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-title style="cursor:pointer" @click="redirect('/post/my')">My Post(s)</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
        <v-menu transition="slide-y-transition" bottom offset-y v-if="isLoggedIn">
          <template v-slot:activator="{ on }">
            <v-btn dark flat v-on="on">{{user.name}}</v-btn>
          </template>
          <v-list>
            <v-list-tile>
              <v-list-tile-title style="cursor:pointer" @click="userProfile">Profile</v-list-tile-title>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-title style="cursor:pointer" @click="logout">Logout</v-list-tile-title>
            </v-list-tile>
            <v-list-tile>
              <v-list-tile-title
                style="cursor:pointer"
                @click.stop="changeModal('toggleMySettingsModal')"
              >Settings</v-list-tile-title>
              <mySettingsModal/>
            </v-list-tile>
          </v-list>
        </v-menu>
      </v-toolbar-items>

      <v-toolbar-items v-else>
        <v-btn flat @click="changeModal('toggleLoginModal')">Login</v-btn>
        <v-btn flat @click="changeModal('toggleRegisterModal')">Register</v-btn>
        <loginModal/>
        <registerModal/>
      </v-toolbar-items>
      <v-spacer></v-spacer>
    </v-toolbar>
    <v-content>
      <router-view></router-view>
    </v-content>
  </v-app>
</template>


<script>
import axios from "Axios";
import loginModal from "./components/modals/loginModal";
import mySettingsModal from "./components/modals/mySettings";
import newPost from "./components/modals/newPost";
import registerModal from "./components/modals/registerModal";
import addCategoryModal from "./components/modals/addCategory.vue";
import snackbar from "./components/snackbars/snackbar.vue";

export default {
    name: "App",
    components: {
        loginModal,
        registerModal,
        newPost,
        addCategoryModal,
        mySettingsModal,
        snackbar,
    },
    data() {
        const that = this;
        return {
            nameRegister: "",
            emailRegister: "",
            passwordRegister: "",
            password2Register: "",
            isDark: true,
        };
    },
    computed: {
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        },
        user() {
            return JSON.parse(localStorage.getItem("user"));
        },
        isEditor() {
            return this.$store.getters.isEditor;
        },
        isAdmin() {
            return this.$store.getters.isAdmin;
        },
        categories: {
            get() {
                return this.$store.getters.categories;
            },
        },
        posts: {
            get() {
                return this.$store.getters.posts;
            },
        },
    },
    mounted() {
        this.$store.dispatch("getPosts");
        this.$store.dispatch("getCategories");
    },
    methods: {
        redirect(route) {
            this.$router.push(route);
        },
        logout() {
            this.$store.dispatch("logout").then(() => {
                this.$router.push("/");
            });
        },
        postRedirect(id) {
            this.$router.push(`/post/${id}`);
        },
        userRedirect(id) {
            this.$router.push(`/user/${id}`);
        },
        changeModal(type) {
            this.$store.dispatch(type);
        },
        userProfile() {
            this.$router.push(`/user/${this.$store.getters.userId}`);
        },
    },
};
</script>
