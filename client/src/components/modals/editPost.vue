<template>
  <v-dialog v-model="show" dark max-width="1200px" v-if="Object.keys(post).length">
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
        >Edit your title</v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          style="cursor: pointer;"
          @click="e1 = 2"
          :complete="e1 > 2"
          step="2"
        >Edit your post</v-stepper-step>

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
          <v-subheader style="margin-top: 25px;" class="title">Categories</v-subheader>
          <v-divider></v-divider>
          <v-list-tile v-for="category in allCategories" :key="category.id">
            <v-switch
              color="primary darken-3"
              v-model="newCategories"
              :label="category.tag"
              :value="category.id"
            ></v-switch>
          </v-list-tile>
          <!-- v-model="newCategories" -->
          <v-btn flat @click="toggleAddCategoryModal">Update categories</v-btn>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="e1++">Continue</v-btn>
          </v-layout>
        </v-stepper-content>

        <!-- Review post -->
        <v-stepper-content step="5">
          <v-card class="mb-5">
            <v-img class="white--text" height="500px" :src="imageUrl">
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
              </div>
            </v-card-text>
          </v-card>

          <v-layout align-center justify-end row fill-height>
            <v-btn color="primary" @click="edit">Edit Post</v-btn>
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
  name: "editPost",
  components: {
    vueDropzone: vue2Dropzone
  },
  props: ["post"],
  data() {
    return {
      title: this.post.title,
      body: this.post.body,
      imageUrl: this.post.image,
      e1: 0,
      errors: [],
      dropzoneOptions: {
        url: "http://localhost:8000/api/file/",
        thumbnailWidth: 150,
        maxFilesize: 5.0,
        maxFiles: 1,
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` }
      }
      // newCategories: this.post.categories.map(a => a.id)
    };
  },
  mounted() {},
  computed: {
    newCategories: {
      get() {
        console.log(this.post.categories.map(a => a.id));
        return this.post.categories.map(a => a.id);
      },
      set(val) {
        console.log("val", val);
        let temp = [];
        val.forEach(v => {
          let i = this.allCategories.findIndex(x => x.id === v);

          temp.push(this.allCategories[i]);
        });
        this.post.categories = temp;
        return temp;
      }
    },
    categories: {
      get() {
        return this.post.categories;
      },
      set(val) {
        return val;
      }
    },
    allCategories: {
      get() {
        return this.$store.getters.categories;
      }
    },
    posts: {
      get() {
        return this.$store.getters.posts;
      }
    },
    show: {
      get() {
        return this.$store.getters.editPostModalActive;
      },
      set() {
        this.$store.dispatch("toggleEditPostModal");
      }
    }
  },
  methods: {
    fileSuccess(file, response) {
      let payload = {
        type: "success",
        text: "Successfully uploaded."
      };

      this.$store.dispatch("toggleSnackBar", payload);

      // console.log(file, response.path);
      this.imageUrl = response.url;
    },
    fileError(file, message, xhr) {
      let payload = {
        type: "error",
        text: "An error occured while uploading"
      };

      this.$store.dispatch("toggleSnackBar", payload);
    },
    toggleAddCategoryModal() {
      this.$store.commit("toggleAddCategoryModal");
    },
    edit() {
      console.log(this.newCategories);
      const data = {
        title: this.title,
        body: this.body,
        categories: this.newCategories,
        image: this.imageUrl
      };
      this.$http({
        url: `/api/post/${this.post.id}/`,
        crossdomain: true,
        data,
        method: "PUT"
      })
        .then(res => {
          this.$store.dispatch("toggleEditPostModal");

          // this.post = {
          //   id: res.data.data.id,
          //   title: res.data.data.title,
          //   body: res.data.data.body,
          //   categories: res.data.data.categories,
          //   user_id: res.data.data.user_id,
          //   user: res.data.data.user,
          //   image: res.data.data.image
          // };

          // for (let i = 0; i < this.newCategories.length; i++) {
          //   let index = this.allCategories.findIndex(
          //     x => x.id == this.newCategories[i]
          //   );
          //   if (this.allCategories[index].id === this.newCategories[i]) {
          //     this.post.categories.push(this.allCategories[index]);
          //   }
          // }
          // // define payload then trigger snackbar to show user it was successfull
          let payload = {
            type: "success",
            text: "Successfully edited your post"
          };
          this.$store.dispatch("toggleSnackBar", payload);

          // Update global posts array
          let i = this.posts.findIndex(x => x.id == res.data.post.id);
          this.posts[i] = res.data.data;
        })
        .catch(e => {
          // define payload then trigger snackbar to show user it was successfull
          let payload = {
            type: "error",
            text: "An error occured, please try again later."
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
