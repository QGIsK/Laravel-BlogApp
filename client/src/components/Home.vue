<template>
  <v-app>
    <sideNav :Posts="Posts"/>
    <shareModal/>
    <v-layout justify-center>
      <v-flex xs16 sm10>
        <v-container fluid grid-list-md>
          <v-layout row wrap v-if="Object.keys(Posts).length">
            <v-flex xs12 v-for="post in Posts.slice(0,12)" :key="post._id">
              <v-card class="mb-5" style="min-width: 100%">
                <v-img
                  class="white--text"
                  style="cursor: pointer;"
                  height="500px"
                  :src="post.image"
                  @click="postRedirect(post._id)"
                >
                  <v-container fill-height fluid>
                    <v-layout fill-height>
                      <v-flex xs12 align-end flexbox>
                        <span class="headline">{{post.title}}</span>
                        <br>
                        <span
                          style="cursor: pointer"
                          v-if="post.user"
                          @click="userRedirect(post.user._id)"
                        >By {{post.user.name}}</span>
                        <br>
                        <span class="grey--text">{{post.lastEdited | formatDate}}</span>
                        <br>
                      </v-flex>
                      <!-- <v-flex xs-12 align-start flexbox>
                        <v-btn icon>
                          <v-icon @click="toggleShareModal(post._id)">share</v-icon>
                        </v-btn>
                      </v-flex>-->
                    </v-layout>
                  </v-container>
                </v-img>
                <v-card-text class="layout">
                  <div>
                    <span class="grey--text">Tags:</span>
                    <span
                      class="grey--text"
                      v-for="category in post.categories"
                      :key="category._id"
                    >{{category.name}}&nbsp;</span>
                    <br>
                    <span v-html="post.body.slice(0, 250)"></span>
                    <span
                      @click="postRedirect(post._id)"
                      style="cursor: pointer"
                      class="grey--text"
                    >
                      <br>Read more...
                    </span>
                  </div>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>
    </v-layout>
  </v-app>
</template>

<script>
import sideNav from "./sideNav";
import shareModal from "./modals/share";

export default {
    name: "home",
    components: {
        sideNav,
        shareModal,
    },
    data: () => ({
        show: false,
        // Posts: [],
    }),
    mounted() {
        // this.getAllPosts();
    },
    computed: {
        Posts: {
            get() {
                return this.$store.getters.posts;
            },
        },
    },
    methods: {
        getAllPosts() {
            this.$http({
                url: "/api/post/?amount=16",
                crossdomain: true,
                method: "GET",
            }).then(res => {
                this.Posts.push(res.data.posts);
            });
        },
        postRedirect(id) {
            this.$router.push(`/post/${id}`);
        },
        userRedirect(id) {
            this.$router.push(`/user/${id}`);
        },
        toggleShareModal(post) {
            this.$store.dispatch("toggleShareModal", {
                post,
            });
        },
    },
};
</script>
