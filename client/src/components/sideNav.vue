<template>
  <v-navigation-drawer v-model="drawer" fixed clipped app>
    <v-list dense>
      <span>
        <v-list-tile v-for="item in sideBarItems" :key="item.text" @click="item.method">
          <v-list-tile-action>
            <v-icon>{{item.icon}}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{item.text}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </span>
    </v-list>
    <v-list dense>
      <span>
        <v-subheader class="mt-5 gray--text font-weight-bold text--darken-1">LATEST POSTS</v-subheader>
        <span v-if="Object.keys(Posts).length">
          <v-list-tile v-for="post in Posts.slice(0,8)" v-bind:key="post.id" class="my-2">
            <v-list-tile-avatar style="cursor: pointer" @click="postRedirect(post.id)">
              <img :src="post.image">
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title
                style="cursor: pointer"
                @click="postRedirect(post.id)"
              >{{ post.title }}</v-list-tile-title>
              <v-list-tile-sub-title
                style="cursor: pointer"
                @click="userRedirect(post.user.id)"
              >By {{post.user.name}}</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </span>
        <span v-else>
          <v-list-tile class="mb-2">
            <v-list-tile-avatar>
              <img>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>There are no posts</v-list-tile-title>
              <v-list-tile-sub-title>Be the first!</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </span>
      </span>
      <v-flex xs-12 align-nd flexbox>
        <v-list-tile>&copy; {{ new Date().getFullYear() }} All rights reserved</v-list-tile>
      </v-flex>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "sideNav",
  components: {},
  data() {
    const self = this;
    return {
      drawer: null,
      sideBarItems: [
        {
          icon: "sort",
          text: "Filter Posts",
          method() {
            self.$router.push("/post/filter");
          }
        }
      ]
    };
  },
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
    },
    redirect(link) {
      this.$router.push(link);
    }
  }
};
</script>
