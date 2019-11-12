<template>
  <v-layout mt-5>
    <v-flex xs12 sm4 offset-sm1>
      <v-card class="mb-5">
        <v-img class="white--text" height="400px" :src="user.avatar">
          <v-container fill-height fluid>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <span class="headline">
                  {{user.name}}
                  <span v-if="user.isEditor">/</span>
                  <br>
                </span>
              </v-flex>
            </v-layout>
          </v-container>
        </v-img>
        <v-card-text class="layout justify-center">
          <div>
            <span>{{user.about}}</span>
            <span class="grey--text">
              <br>
              Joined: {{user.created_at | formatDate}}
            </span>
          </div>
        </v-card-text>
      </v-card>
    </v-flex>
    <v-flex xs12 sm4 offset-sm1 mb-5>
      <v-card v-if="Object.keys(userPosts).length">
        <v-subheader
          class="grey darken-4 gray--text font-weight-bold text--darken-1"
        >USERS LATEST POSTS</v-subheader>

        <v-list-tile
          v-for="post in userPosts.slice(0, 8)"
          @click="postRedirect(post.id)"
          v-bind:key="post.id"
          class="my-4"
        >
          <v-list-tile-avatar>
            <img :src="post.image">
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{ post.title }}</v-list-tile-title>
            <v-list-tile-sub-title class="grey--text">Posted {{post.updated_at | formatDate}}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "UserProfile",
  data() {
    const self = this;
    return {
      user: {},
      userPosts: {},
      currentUserId: self.$store.getters.userId
    };
  },
  mounted() {
    this.getUser(this.$route.params.id);
    // this.getUserPosts(this.$route.params.id);
  },
  computed: {
    isUser() {
      return this.currentUserId === this.user.id;
    }
  },
  methods: {
    getUser(id) {
      this.$http({
        url: `/api/users/${id}`,
        crossdomain: true,
        method: "GET"
      }).then(res => {
        this.user = res.data.user;
        this.userPosts = res.data.posts;
      });
    },
    // getUserPosts(id) {
    //   this.$http({
    //     url: `/api/user/${id}/posts`,
    //     crossdomain: true,
    //     method: "GET"
    //   }).then(res => {
    //     this.userPosts = res.data.posts;
    //   });
    // },
    postRedirect(id) {
      this.$router.push(`/post/${id}`);
    }
  }
};
</script>
