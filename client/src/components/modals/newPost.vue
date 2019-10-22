<template>
  <v-dialog v-model="show" dark max-width="1200px">
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
        >Name your post</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 2"
          :complete="e1 > 2"
          step="2"
        >Write your post</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step style="cursor: pointer;" @click="e1 = 3" :complete="e1 > 3" step="3">
          Add a Header
          <small>Optional</small>
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 4"
          :complete="e1 > 4"
          step="4"
        >Configure Post</v-stepper-step>

        <v-divider></v-divider>
        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 5"
          :complete="e1 > 5"
          step="5"
        >Review post</v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <!-- TITLE -->
        <v-stepper-content step="1">
          <v-flex xs12>
            <v-text-field label="Title" v-model="title" required></v-text-field>
          </v-flex>
          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- WRITE POST -->
        <v-stepper-content step="2">
          <v-card class="mb-5">
            <v-flex xs12>
              <wysiwyg id="body" v-model="body"/>
            </v-flex>
          </v-card>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- HEADER -->
        <v-stepper-content step="3">
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
        </v-stepper-content>

        <!-- CONFIGURE -->
        <v-stepper-content step="4">
          <v-subheader class="title">Type of post</v-subheader>
          <v-divider></v-divider>
          <v-list-tile>
            <v-switch
              v-model="allowComments"
              color="primary darken-3"
              label="Allow Comments. Default: False"
            ></v-switch>
          </v-list-tile>

          <v-subheader style="margin-top: 25px;" class="title">Categories</v-subheader>
          <v-divider></v-divider>
          <v-list-tile v-for="category in allCategories" :key="category._id">
            <v-switch
              color="primary darken-3"
              v-model="categories"
              :label="category.name"
              :value="category._id"
            ></v-switch>
          </v-list-tile>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- Review post -->
        <v-stepper-content step="5">
          <v-card class="mb-5">
            <v-img class="white--text" height="500px" :src="imagePath">
              <v-container fill-height fluid>
                <v-layout fill-height>
                  <v-flex xs12 align-end flexbox>
                    <span class="headline">{{title}}</span>
                    <br>
                    <span class="grey--text">{{new Date | formatDate}}</span>
                    <br>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-img>
            <v-card-text class="layout justify-center">
              <div>
                <span v-html="body"></span>
                <br>
                <span class="grey--text">
                  Allow Comments
                  <b>{{allowComments}}</b>
                </span>
              </div>
            </v-card-text>
          </v-card>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="newPost">Post</v-btn>
          </v-layout>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </v-dialog>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

import "vue-wysiwyg/dist/vueWysiwyg.css";

export default {
    name: "NewPost",
    components: {
        vueDropzone: vue2Dropzone,
    },
    data() {
        return {
            e1: 0,
            title: this.title,
            body: this.body,
            imagePath: "/public/images/header.jpg",
            allowComments: false,
            errors: [],
            categories: [],
            dropzoneOptions: {
                url: "http://localhost:3000/api/file/upload",
                thumbnailWidth: 150,
                maxFilesize: 5.0,
                maxFiles: 1,
                headers: { Authorization: localStorage.getItem("token") },
            },
        };
    },
    mounted() {},
    computed: {
        allCategories: {
            get() {
                return this.$store.getters.categories;
            },
        },
        show: {
            get() {
                return this.$store.getters.newPostActive;
            },
            set() {
                this.$store.dispatch("toggleNewPostModal");
            },
        },
        posts: {
            get() {
                return this.$store.getters.posts;
            },
        },
        isAdmin() {
            return this.$store.getters.isAdmin;
        },
    },
    methods: {
        fileSuccess(file, response) {
            let payload = {
                type: "success",
                text: "Successfully uploaded.",
            };

            this.$store.dispatch("toggleSnackBar", payload);

            // console.log(file, response.path);
            this.imagePath = response.path;
        },
        fileError(file, message, xhr) {
            let payload = {
                type: "error",
                text: "An error occured while uploading",
            };

            this.$store.dispatch("toggleSnackBar", payload);
        },
        togglePreviewModal() {
            this.$store.dispatch("togglePreviewModal", {
                post: {
                    title: this.title,
                    body: this.body,
                },
            });
        },
        toggleAddCategoryModal() {
            this.$store.dispatch("toggleAddCategoryModal");
        },
        async newPost() {
            const data = {
                title: this.title,
                body: this.body,
                categories: this.categories,
                imageUrl: this.imagePath,
                allowComments: this.allowComments,
            };
            await this.$http({
                url: "/api/post/new",
                crossdomain: true,
                data,
                method: "POST",
            })

                .then(res => {
                    this.$store.dispatch("toggleNewPostModal");
                    this.file = "";
                    this.title = "";
                    this.body = "";
                    this.allowComments = false;
                    this.imagePath = "/public/images/header.jpg";
                    this.categories = [];
                    this.e1 = 1;

                    let payload = {
                        type: "success",
                        text: "Successfully posted.",
                    };

                    this.posts.unshift(res.data.post);
                    this.$store.dispatch("toggleSnackBar", payload);
                })
                .catch(err => {
                    console.log(err);
                    this.alert = true;
                    this.errors = err.response.data.errors;
                    let payload = {
                        type: "error",
                        text: err.response.data.errors,
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                });
        },
    },
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
