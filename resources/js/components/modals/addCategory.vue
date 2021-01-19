<template>
  <v-dialog v-model="show" dark max-width="600px">
    <v-card>
      <v-card-title class="headline grey darken-4 text-xs-center">Category(s)</v-card-title>
      <form @submit.prevent="add">
        <v-card-text>
          <v-alert v-model="alert" transition="scale-transition" dismissible type="error">{{error}}</v-alert>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field label="Add Category" v-model="tag" required></v-text-field>
              </v-flex>
            </v-layout>
            <v-card class="mt-2 mb-3" v-for="category in allCategories" :key="category.id">
              <v-card-text>
                <div>
                  {{category.tag}}
                  <v-btn flat icon>
                    <v-icon
                      style="font-size: 17.5px;"
                      @click.stop="activeCategory = {id: category.id, tag: category.tag}; editModal = true"
                    >edit</v-icon>
                  </v-btn>
                  <v-btn flat icon>
                    <v-icon
                      style="font-size: 17.5px;"
                      @click.stop="activeCategory = {id: category.id, tag: category.tag}; deleteModal = true"
                    >delete</v-icon>
                  </v-btn>
                </div>
              </v-card-text>
            </v-card>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="darken-1" @click="add()">Add</v-btn>
        </v-card-actions>
      </form>
    </v-card>
    <v-dialog v-model="editModal" dark max-width="500px">
      <v-card>
        <v-card-title class="headline grey darken-4 text-xs-center">Edit Category</v-card-title>
        <v-card-text>
          <v-text-field v-model="activeCategory.tag" :counter="250" label="Edit category" required></v-text-field>
          <v-btn style="margin-left:-5px;" color="darken-1" @click="editCategory()">Edit</v-btn>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteModal" dark max-width="450px">
      <v-card>
        <v-card-title
          class="headline grey darken-4 text-xs-center"
        >Are you sure you wanna delete this category</v-card-title>
        <v-card-text class="text-xs-center">
          <v-btn @click="deleteCategory()" color="red">yes</v-btn>
          <v-btn @click="deleteModal = false">no</v-btn>
          <br>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-dialog>
</template>

<script>
export default {
  name: "loginModal",
  data() {
    return {
      activeCategory: {},
      deleteModal: false,
      editModal: false,
      alert: false,
      error: "",
      tag: this.tag
    };
  },
  computed: {
    show: {
      get() {
        return this.$store.getters.AddCategoryActive;
      },
      set() {
        this.$store.dispatch("toggleAddCategoryModal");
      }
    },
    allCategories: {
      get() {
        return this.$store.getters.categories;
      }
    }
  },

  mounted() {},
  methods: {
    deleteCategory() {
      this.$http({
        url: `/api/category/${this.activeCategory.id}`,
        corssDomain: true,
        method: "DELETE"
      }).then(_ => {
        this.deleteModal = false;
        //define payload for snackbar then toggle snackbar
        let payload = {
          type: "success",
          text: "Successfully deleted category"
        };
        this.$store.dispatch("toggleSnackBar", payload);
        // find deleted comment index then splice off the post.comments array
        let i = this.allCategories.findIndex(
          x => x.id == this.activeCategory.id
        );
        this.allCategories.splice(i, 1);
      });
    },
    editCategory() {
      this.$http({
        url: `/api/category/${this.activeCategory.id}/`,
        data: this.activeCategory,
        crossDomain: true,
        method: "PUT"
      }).then(_ => {
        this.editModal = false;
        //define payload for snackbar then toggle snackbar
        let payload = {
          type: "success",
          text: "Successfully edited category"
        };
        this.$store.dispatch("toggleSnackBar", payload);
        // find deleted comment index then splice off the post.comments array
        let i = this.allCategories.findIndex(
          x => x.id == this.activeCategory.id
        );
        this.allCategories[i].tag = this.activeCategory.tag;
      });
    },
    add() {
      let tag = this.tag;
      this.$http({
        url: "/api/category/",
        crossdomain: true,
        data: {
          tag
        },
        method: "POST"
      })
        .then(res => {
          let payload = {
            type: "success",
            text: "Successfully added category"
          };
          this.$store.dispatch("toggleSnackBar", payload);
          this.tag = "";
          this.allCategories.push(res.data.data);
        })
        .catch(e => {
          this.alert = true;
          this.error = e.response.data.error;
          // console.log(e.response)
        });
    }
  }
};
</script>