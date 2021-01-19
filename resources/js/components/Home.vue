<template>
  <v-app>
    <sideNav :Posts="Posts"/>
    <v-layout justify-center>
      <v-flex xs16 sm10>
        <v-container fluid grid-list-md>
          <v-layout row wrap v-if="Object.keys(Posts).length">
            <v-flex xs12 v-for="post in Posts.slice(0,12)" :key="post.id">
              <v-card class="mb-5" style="min-width: 100%">
                <v-img
                  class="white--text"
                  style="cursor: pointer;"
                  height="500px"
                  :src="post.image"
                  @click="postRedirect(post.id)"
                >
                  <v-container fill-height fluid>
                    <v-layout fill-height>
                      <v-flex xs12 align-end flexbox>
                        <span class="headline">{{post.title}}</span>
                        <br>
                        <span
                          style="cursor: pointer"
                          v-if="post.user"
                          @click="userRedirect(post.user.id)"
                        >By {{post.user.name}}</span>
                        <br>
                        <span class="grey--text">{{post.lastEdited | formatDate}}</span>
                        <br>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-img>
                <v-card-text class="layout">
                  <div>
                    <span class="grey--text">Tags:</span>
                    <span
                      class="grey--text"
                      v-for="category in post.categories"
                      :key="category.id"
                    >{{category.tag}}&nbsp;</span>
                    <br>
                    <span v-html="post.body.slice(0, 250)"></span>
                    <span @click="postRedirect(post.id)" style="cursor: pointer" class="grey--text">
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

export default {
  name: "home",
  components: {
    sideNav
  },
  data: () => ({
    show: false
  }),
  computed: {
    Posts: {
      get() {
        return this.$store.getters.posts;
      }
    }
  },
  methods: {
    postRedirect(id) {
      this.$router.push(`/post/${id}`);
    },
    userRedirect(id) {
      this.$router.push(`/user/${id}`);
    }
  }
};
</script>
