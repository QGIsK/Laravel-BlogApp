<template>
  <v-dialog v-model="show" dark max-width="800px">
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 1"
          :complete="e1 > 1"
          step="1"
        >Your profile</v-stepper-step>

        <!-- <v-divider></v-divider>

        <v-stepper-step style="cursor: pointer;" @click="e1 = 2" :complete="e1 > 2" step="2">
          Upload profile picture
          <small>Optional</small>
        </v-stepper-step>-->

        <v-divider></v-divider>

        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 2"
          :complete="e1 > 2"
          step="2"
        >Weekly digest</v-stepper-step>

        <v-divider></v-divider>
        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 3"
          :complete="e1 > 3"
          step="3"
        >Confirm Settings</v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <!-- Profile -->
        <v-stepper-content step="1">
          <v-flex xs12>
            <v-subheader class="title">Personal Data</v-subheader>
            <v-divider class="mb-2"></v-divider>
            <v-text-field label="Name" v-model="user.name" required></v-text-field>
            <v-text-field label="Email" v-model="user.email" disabled></v-text-field>
            <!-- <v-text-field label="About" v-model="user.about" required></v-text-field> -->

            <!-- <v-subheader class="title">Social Media</v-subheader>
            <v-divider class="mb-2"></v-divider>
            <span v-for="media in user.socials" :key="media.name">
              <v-text-field :label="media.name" v-model="media.href"></v-text-field>
            </span>-->
          </v-flex>
          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- Upload profile picture -->
        <!-- <v-stepper-content step="2">
          <v-card class="mb-5" color="grey lighten-1">
            <vue-dropzone
              ref="myVueDropzone"
              id="dropzone"
              :options="dropzoneOptions"
              @vdropzone-success="fileSuccess"
              @vdropzone-error="fileError"
            ></vue-dropzone>
          </v-card>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>-->

        <!-- Weekly digest POST -->
        <v-stepper-content step="2">
          <v-subheader class="title">Weekly Digest</v-subheader>
          <v-subheader class="title">
            <small>Get a weekly email with the latest post from the last week (send on monday)</small>
          </v-subheader>
          <v-divider></v-divider>
          <v-list-tile>
            <v-switch color="primary darken-3" v-model="user.weekly_digest" label="Enabled"></v-switch>
          </v-list-tile>
          <v-subheader style="margin-top: 25px;" class="title">Categories</v-subheader>

          <v-list-tile v-for="category in allCategories" :key="category.id">
            <v-switch
              color="primary darken-3"
              v-model="user.digest_categories"
              :label="category.tag"
              :value="category.id"
            ></v-switch>
          </v-list-tile>
          <v-list-tile></v-list-tile>

          <v-divider></v-divider>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- Review Settings -->
        <v-stepper-content step="3">
          <v-card class="mb-5">
            <v-container fill-height fluid>
              <v-layout fill-height>
                <v-flex xs12 align-end flexbox>
                  <span class="headline">{{user.name}}</span>
                </v-flex>
              </v-layout>
            </v-container>
            <v-card-text>
              <div>
                <span>{{user.email}}</span>
                <span class="grey--text">
                  <br>
                  Joined: {{user.created_at | formatDate}}
                </span>
              </div>
            </v-card-text>
            <v-layout>
              <v-btn color="primary" @click="edit">Edit Profile</v-btn>
            </v-layout>
          </v-card>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </v-dialog>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

export default {
  name: "mySettings",
  data() {
    return {
      e1: 0,
      user: {},
      weeklyEnabled: false
      // dropzoneOptions: {
      //   url: "http://localhost:3000/api/file/upload",
      //   thumbnailWidth: 150,
      //   maxFilesize: 5.0,
      //   maxFiles: 1,
      //   headers: { Authorization: localStorage.getItem("token") }
      // }
    };
  },
  components: {
    vueDropzone: vue2Dropzone
  },
  computed: {
    show: {
      get() {
        return this.$store.getters.mySettingsActive;
      },
      set() {
        this.$store.dispatch("toggleMySettingsModal");
      }
    },
    allCategories: {
      get() {
        return this.$store.getters.categories;
      }
    }
  },
  mounted() {
    this.getUserData();
  },
  methods: {
    edit() {
      const data = {
        name: this.user.name,
        weekly_digest: this.user.weekly_digest ? 1 : 0
      };
      this.$http({
        url: `/api/users/${this.user.id}`,
        method: "put",
        data
      })
        .then(res => {
          this.$store.dispatch("toggleMySettingsModal");
          let payload = {
            type: "success",
            text: "Profile updated"
          };

          this.$store.dispatch("toggleSnackBar", payload);
        })
        .catch(e => console.log(e));
    },
    // fileSuccess(file, response) {
    //   let payload = {
    //     type: "success",
    //     text: "Successfully uploaded."
    //   };

    //   this.$store.dispatch("toggleSnackBar", payload);

    //   // console.log(file, response.path);
    //   this.user.avatar = response.path;
    // },
    // fileError(file, message, xhr) {
    //   let payload = {
    //     type: "error",
    //     text: "An error occured while uploading"
    //   };

    //   this.$store.dispatch("toggleSnackBar", payload);
    // },
    getUserData() {
      this.$http({ url: "/api/user/", method: "GET" })
        .then(res => {
          this.user = res.data;
        })
        .catch(e => {
          let payload = {
            type: "error",
            text: "An error occured while loading user data"
          };

          this.$store.dispatch("toggleSnackBar", payload);
        });
    }
  }
};
</script>

<style scoped>
#dropzone {
  background-color: #111820;
  color: #fff;
  border: none;
}
</style>
