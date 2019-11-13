<template>
  <v-layout mt-5 mb-5 v-if="post">
    <v-flex xs12 sm10 offset-sm1>
      <v-card class="mb-5">
        <v-img class="white--text" height="500px" :src="post.image">
          <v-container fill-height fluid>
            <v-layout fill-height>
              <v-flex xs12 align-end flexbox>
                <span class="headline">{{post.title}}</span>
                <br>
                <span class="grey--text">{{post.lastEdited | formatDate}}</span>
                <br>
              </v-flex>
            </v-layout>
          </v-container>
        </v-img>
        <v-card-text class="layout justify-center">
          <div>
            <span class="grey--text">Tags:</span>
            <span
              class="grey--text"
              v-for="category in post.categories"
              :key="category.id"
            >{{category.tag}}&nbsp;</span>
            <br>
            <span v-html="post.body"></span>
          </div>
          <span
            style="cursor: pointer"
            v-if="post.user"
            @click="userRedirect(post.user.id)"
          >By {{post.user.name}}</span>
        </v-card-text>
        <v-card-actions>
          <v-btn v-if="isEditor" flat icon>
            <v-icon @click="editPost(post.id)">edit</v-icon>
            <editPost :post="post"/>
          </v-btn>
          <v-btn v-if="isEditor" flat icon>
            <v-icon @click="deleteModal = true">delete</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
      <span>
        <span class="title" mb-5>Comments</span>
        <v-card v-if="isLoggedIn" mt-5 mb-5>
          <v-card-text>
            <v-text-field v-model="commentBody" :counter="500" label="Add Comment" required></v-text-field>
            <v-btn style="margin-left:-5px;" color="darken-1" @click="postComment()">Post</v-btn>
          </v-card-text>
        </v-card>
        <v-card class="mt-4 mb-5" v-if="!isComment">
          <v-card-text>
            Theres no comments yet.
            <br>
          </v-card-text>
        </v-card>
        <v-card class="mt-4 mb-3" v-for="comment in comments" :key="comment.id">
          <v-card-text>
            <div>
              {{comment.body}}
              <br>
              <span class="grey--text">By {{comment.user.name}}</span>
            </div>
            <span style="margin-left: -17.5px;">
              <v-btn
                v-if="isCommentOwner(comment.user.id)"
                flat
                icon
                @click="editCommentModal = true; editComment = {id: comment.id, body: comment.body, }"
              >
                <v-icon style="font-size: 17.5px;">edit</v-icon>
              </v-btn>
              <v-btn
                style="margin-left: -12.5px;"
                v-if="isCommentOwner(comment.user.id)"
                flat
                icon
                @click="commentDeleteModal = true; deleteComment = comment.id"
              >
                <v-icon style="font-size: 17.5px;">delete</v-icon>
              </v-btn>
            </span>
          </v-card-text>
        </v-card>
      </span>
    </v-flex>
    <v-dialog v-model="editCommentModal" dark max-width="500px">
      <v-card>
        <v-card-title class="headline grey darken-4 text-xs-center">Edit Comment</v-card-title>
        <v-card-text>
          <v-text-field v-model="editComment.body" :counter="500" label="Edit Comment" required></v-text-field>
          <v-btn style="margin-left:-5px;" color="darken-1" @click="postEditComment()">Edit</v-btn>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-if="isLoggedIn" v-model="commentDeleteModal" dark max-width="450px">
      <v-card>
        <v-card-title
          class="headline grey darken-4 text-xs-center"
        >Are you sure you wanna delete this comment?</v-card-title>
        <v-card-text class="text-xs-center">
          <v-btn @click="DeleteComment()" color="red">yes</v-btn>
          <v-btn @click="commentDeleteModal = false">no</v-btn>
          <br>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-if="isEditor" v-model="deleteModal" dark max-width="450px">
      <v-card>
        <v-card-title
          class="headline grey darken-4 text-xs-center"
        >Are you sure you wanna delete this post?</v-card-title>
        <v-card-text class="text-xs-center">
          <v-btn @click="deletePost()" color="red">yes</v-btn>
          <v-btn @click="deleteModal = false">no</v-btn>
          <br>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>



<script>
import editPost from "../modals/editPost";
export default {
  name: "ShowPost",
  components: {
    editPost
  },
  data() {
    return {
      deleteModal: false,
      deleteComment: null,
      editCommentModal: false,
      commentDeleteModal: false,
      comments: [],
      commentBody: this.commentBody,
      editComment: {
        id: null,
        body: ""
      }
    };
  },
  computed: {
    isEditor() {
      // if (!this.post.user) return false;
      // return this.post.user.id == this.$store.getters.userId;
      return this.$store.getters.isEditor;
    },
    isLoggedIn() {
      return this.$store.getters.isLoggedIn;
    },
    user() {
      return JSON.parse(localStorage.getItem("user"));
    },
    isComment: {
      get() {
        if (this.comments && this.comments.length > 0) return true;
      },
      set() {
        return false;
      }
    },
    Posts: {
      get() {
        return this.$store.getters.posts;
      }
    },
    post: {
      get() {
        const post = this.$store.getters.posts.filter(post => {
          return String(post.id) === this.$route.params.id;
        });
        if (post[0]) this.getComments(this.$route.params.id);
        return post[0];
      }
    }
  },
  methods: {
    getComments(id) {
      this.$http({
        url: `/api/comment/${id}`,
        method: "GET"
      }).then(res => {
        if (res.data.data.length <= 0) {
          this.comments = [];
          return;
        }
        for (let i = 0; i < res.data.data.length; i++) {
          this.comments.push(res.data.data[i]);
        }
      });
    },
    deletePost() {
      this.$http({
        url: `/api/post/${this.post.id}`,
        crossdomain: true,
        method: "DELETE"
      })
        .then(res => {
          this.deleteModal = false;
          this.$router.push("/");

          let i = this.Posts.findIndex(x => x.id == this.post.id);
          this.Posts.splice(i, 1);

          // define payload then trigger snackbar to show user it was successfull
          let payload = {
            type: "success",
            text: "Successfully deleted the post."
          };
          this.$store.dispatch("toggleSnackBar", payload);
        })
        .catch(e => {
          let payload = {
            type: "error",
            text: "An error occured, Please try again later."
          };
          this.$store.dispatch("toggleSnackBar", payload);
        });
    },
    postEditComment() {
      this.$http({
        url: `/api/comment/${this.editComment.id}/`,
        data: this.editComment,
        crossDomain: true,
        method: "PUT"
      })
        .then(res => {
          this.editCommentModal = false;
          // Find in post comments array then edit and reset edit comment variables
          let i = this.comments.findIndex(x => x.id == this.editComment.id);
          this.comments[i].body = this.editComment.body;
          this.editComment = {
            id: null,
            body: ""
          };
          // define payload then trigger snackbar to show user it was successfull
          let payload = {
            type: "success",
            text: "Successfully edited your comment"
          };
          this.$store.dispatch("toggleSnackBar", payload);
        })
        .catch(e => {
          // define payload then trigger snackbar to show user there was an error
          let payload = {
            type: "error",
            text: e.response.data.error
          };
          this.$store.dispatch("toggleSnackBar", payload);
        });
    },
    DeleteComment() {
      // console.log(this.deleteComment)
      this.$http({
        url: `/api/comment/${this.deleteComment}`,
        crossdomain: true,
        method: "DELETE"
      })
        .then(_ => {
          //  turn off delete modal
          this.commentDeleteModal = false;
          //define payload for snackbar then toggle snackbar
          let payload = {
            type: "success",
            text: "Successfully deleted comment"
          };
          this.$store.dispatch("toggleSnackBar", payload);
          // find deleted comment index then splice off the comments array
          let i = this.comments.findIndex(x => x.id == this.deleteComment);
          this.comments.splice(i, 1);
        })
        .catch(e => console.log(e));
    },
    isCommentOwner(id) {
      return id == this.$store.getters.userId;
    },
    // getPost(id) {
    //   this.$http({
    //     url: `/api/post/${id}`,
    //     crossdomain: true,
    //     method: "GET"
    //   })
    //     .then(res => {
    //       this.post = {
    //         title: res.data.data.title,
    //         body: res.data.data.body,
    //         lastEdited: res.data.data.lastEdited,
    //         image: res.data.data.image,
    //         user: res.data.data.user,
    //         id: res.data.data.id
    //       };
    //     })
    //     .catch(e => {
    //       let payload = {
    //         type: "error",
    //         text: e.response.data.error
    //       };
    //       this.$store.dispatch("toggleSnackBar", payload);
    //       this.$router.push("/");
    //     });
    // },
    userRedirect(id) {
      this.$router.push(`/user/${id}`);
    },
    editPost(id) {
      this.$store.dispatch("toggleEditPostModal");
    },
    postComment() {
      let postId = this.$route.params.id;
      let data = {
        body: this.commentBody
      };
      this.$http({
        url: `/api/comment/${postId}`,
        crossdomain: true,
        method: "POST",
        data
      })
        .then(res => {
          this.commentBody = "";
          this.comments.push(res.data.data);
          // define payload then trigger snackbar to show user it was successfull
          let payload = {
            type: "success",
            text: "Successfully posted your comment"
          };
          this.$store.dispatch("toggleSnackBar", payload);
        })
        .catch(e => {
          console.log(e);
          // define payload then trigger snackbar to show user there was an error
          let payload = {
            type: "error",
            text: "An error occured please try again later"
          };
          this.$store.dispatch("toggleSnackBar", payload);
        });
    }
  }
};
</script>