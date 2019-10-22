<template>
  <v-layout justify-center>
    <shareModal />
    <editPost :post="post" />
    <v-dialog v-model="deleteModal" dark max-width="450px">
      <v-card>
        <v-card-title
          class="headline grey darken-4 text-xs-center"
        >Are you sure you wanna delete this post?</v-card-title>
        <v-card-text class="text-xs-center">
          <v-btn @click="deletePost(delPost)" color="red">yes</v-btn>
          <v-btn @click="deleteModal = false">no</v-btn>
          <br />
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-flex xs16 sm10>
      <v-container fluid grid-list-md mt-2>
        <v-layout row wrap v-if="Object.keys(Posts).length">
          <v-flex v-for="post in Posts" :key="post._id" v-bind="{ [`${post._id}`]: true }">
            <v-card class="my-1 mx-1">
              <v-img
                style="cursor: pointer"
                :src="post.image"
                height="250px"
                @click="postRedirect(post._id)"
              >
                <v-container fill-height fluid pa-2>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline white--text" v-text="post.title"></span>
                      <br />
                      <span class="grey--text">{{post.createdAt | formatDate}}</span>
                      <br />
                      <span
                        class="grey--text"
                        v-for="category in post.categories"
                        :key="category._id"
                      >{{category.name}}&nbsp;</span>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-img>
              <v-card-actions>
                <v-btn flat icon>
                  <v-icon @click="toggleEditPostModal(post)">edit</v-icon>
                </v-btn>
                <v-btn flat icon>
                  <v-icon @click="deleteModal = true; toggleDeleteModal(post)">delete</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn icon>
                  <!-- <v-icon>bookmark</v-icon> -->
                </v-btn>
                <v-btn icon>
                  <!-- <v-icon>bookmark</v-icon> -->
                </v-btn>
                <v-btn icon>
                  <v-icon @click="toggleShareModal(post._id)">share</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-flex>
  </v-layout>
</template>


<script>
import shareModal from "../modals/share";
import editPost from "../modals/editPost";

export default {
    name: "myposts",
    components: {
        shareModal,
        editPost,
    },
    data: () => ({
        deleteModal: false,
        // Posts: [],
        post: {},
        delPost: {},
    }),
    mounted() {
        // this.getUsersPost();
    },
    computed: {
        Posts: {
            get() {
                return this.$store.getters.posts.filter(post => post.user._id === this.$store.getters.userId);
            },
        },
        allCategories: {
            get() {
                return this.$store.getters.categories;
            },
        },
    },
    methods: {
        toggleDeleteModal(post) {
            console.log("clicked");
            this.delPost = post;
            // this.deleteModal != this.deleteModal;
        },
        deletePost(post) {
            this.$http({
                url: `/api/post/${post._id}/delete`,
                crossdomain: true,
                method: "GET",
            })
                .then(res => {
                    this.deleteModal = false;

                    // Update global
                    let i = this.Posts.findIndex(x => x._id == this.delPost._id);
                    this.Posts.splice(i, 1);

                    // define payload then trigger snackbar to show user it was successfull
                    let payload = {
                        type: "success",
                        text: "Successfully deleted the post.",
                    };

                    this.$store.dispatch("toggleSnackBar", payload);
                })
                .catch(e => {
                    let payload = {
                        type: "error",
                        text: "An error occured, Please try again later.",
                    };

                    this.$store.dispatch("toggleSnackBar", payload);
                });
        },
        toggleEditPostModal(post) {
            console.log(post);
            this.post = post;
            this.$store.dispatch("toggleEditPostModal");
        },
        toggleModal(type) {
            this.$store.dispatch(type);
        },
        getUsersPost() {
            this.$http({
                url: "/api/post/my",
                crossdomain: true,
                method: "GET",
            }).then(res => {
                this.Posts = res.data.posts;
            });
        },
        postRedirect(id) {
            this.$router.push(`/post/${id}`);
        },
        toggleShareModal(post) {
            this.$store.dispatch("toggleShareModal", {
                post,
            });
        },
    },
};
</script>
