<template>
  <v-layout justify-center>
    <v-navigation-drawer permanent fixed clipped app>
      <v-toolbar flat>
        <v-list>
          <v-list-tile>
            <v-list-tile-title class="sub-title">Filter</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-list dense>
        <span>
          <v-list-tile v-for="category in allCategories" :key="category.id">
            <v-switch
              color="indigo darken-3"
              v-model="activeFilters"
              :label="category.tag"
              :value="category.id"
            ></v-switch>
          </v-list-tile>
        </span>
      </v-list>
    </v-navigation-drawer>

    <v-flex xs16 sm10>
      <v-container fluid grid-list-md mt-2>
        <v-layout row wrap v-if="Object.keys(allPosts).length">
          <v-flex v-for="post in allPosts" :key="post.id" v-bind="{ [`${post.id}`]: true }">
            <v-card class="my-1 mx-1">
              <v-img
                style="cursor: pointer"
                :src="post.image"
                height="250px"
                @click="postRedirect(post.id)"
              >
                <v-container fill-height fluid pa-2>
                  <v-layout fill-height>
                    <v-flex xs12 align-end flexbox>
                      <span class="headline white--text" v-text="post.title"></span>
                      <br>
                      <span class="grey--text">{{post.createdAt | formatDate}}</span>
                      <br>
                        <!-- <span
                          class="grey--text"
                          v-for="category in post.categories"
                          :key="category.id"
                        >{{category.tag}}&nbsp;</span>-->
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-img>
              <v-card-actions v-if="post.categories.length > 0">
                Tags:&nbsp;
                <span
                  class="grey--text"
                  v-for="category in post.categories"
                  :key="category.id"
                >{{category.tag}}&nbsp;</span>
                <!-- <v-spacer></v-spacer>
                <v-btn icon>
                  <v-icon>favorite</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>bookmark</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>bookmark</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>bookmark</v-icon>
                </v-btn>-->
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "FilterPosts",
  components: {},
  data() {
    const self = this;
    return {
      activeFilters: []
    };
  },
  computed: {
    allPosts: {
      get() {
        if (this.activeFilters.length)
          return this.$store.getters.posts.filter(post =>
            post.categories.find(categorie =>
              this.activeFilters.includes(categorie.id)
            )
          );
        return this.$store.getters.posts;
      }
    },
    allCategories: {
      get() {
        return this.$store.getters.categories;
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
