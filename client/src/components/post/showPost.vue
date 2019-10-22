<template>
  <v-layout mt-5 mb-5 v-if="Object.keys(post).length">
    <shareModal/>

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
              <v-flex xs-12 align-start flexbox>
                <v-btn icon outlined color="blue-grey darken-3">
                  <v-icon @click="toggleShareModal(post._id)">share</v-icon>
                </v-btn>
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
              :key="category._id"
            >{{category.name}}&nbsp;</span>
            <br>
            <span v-html="post.body"></span>
          </div>
          <span
            style="cursor: pointer"
            v-if="post.user"
            @click="userRedirect(post.user._id)"
          >By {{post.user.name}}</span>
        </v-card-text>
        <v-card-actions>
          <v-btn v-if="isOwner" flat icon>
            <v-icon @click="editPost(post._id)">edit</v-icon>
            <editPost :post="post"/>
          </v-btn>
          <v-btn v-if="isOwner" flat icon>
            <v-icon @click="deleteModal = true">delete</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
      <span v-if="!post.allowComments">
        <v-card class="mt-4 mb-5" v-if="!isComment">
          <v-card-text>
            Comments are disabled
            <br>
          </v-card-text>
        </v-card>
      </span>
      <span v-else>
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
        <v-card class="mt-4 mb-3" v-for="comment in post.comments" :key="comment._id">
          <v-card-text>
            <div>
              {{comment.body}}
              <br>
              <span class="grey--text">By {{comment.user.name}}</span>
            </div>
            <span style="margin-left: -17.5px;">
              <v-btn
                v-if="isCommentOwner(comment.user._id)"
                flat
                icon
                @click="editCommentModal = true; editComment = {_id: comment._id, body: comment.body, }"
              >
                <v-icon style="font-size: 17.5px;">edit</v-icon>
              </v-btn>
              <v-btn
                style="margin-left: -12.5px;"
                v-if="isCommentOwner(comment.user._id)"
                flat
                icon
                @click="commentDeleteModal = true; deleteComment = comment._id"
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

    <v-dialog v-model="commentDeleteModal" dark max-width="450px">
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
    <v-dialog v-model="deleteModal" dark max-width="450px">
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
import shareModal from "../modals/share";
export default {
    name: "ShowPost",
    props: ["Posts"],
    components: {
        editPost,
        shareModal,
    },
    data() {
        return {
            deleteModal: false,
            deleteComment: null,
            editCommentModal: false,
            commentDeleteModal: false,
            post: {},
            commentBody: this.commentBody,
            editComment: {
                _id: null,
                body: "",
            },
        };
    },
    mounted() {
        this.getPost(this.$route.params.id);
    },
    computed: {
        isOwner() {
            if (!this.post.user) return false;
            return this.post.user._id == this.$store.getters.userId;
        },
        isLoggedIn() {
            return this.$store.getters.isLoggedIn;
        },
        user() {
            return JSON.parse(localStorage.getItem("user"));
        },
        isComment: {
            get() {
                if (this.post.comments && this.post.comments.length > 0) return true;
            },
            set() {
                return false;
            },
        },
    },
    methods: {
        deletePost() {
            this.$http({
                url: `/api/post/${this.post._id}/delete`,
                crossdomain: true,
                method: "GET",
            })
                .then(res => {
                    this.deleteModal = false;
                    this.$router.push("/");
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
        postEditComment() {
            this.$http({
                url: `/api/post/${this.post._id}/comment/${this.editComment._id}/edit`,
                data: this.editComment,
                crossDomain: true,
                method: "POST",
            })
                .then(res => {
                    this.editCommentModal = false;
                    // Find in post comments array then edit and reset edit comment variables
                    let i = this.post.comments.findIndex(x => x._id == this.editComment._id);
                    this.post.comments[i].body = this.editComment.body;
                    this.editComment = {
                        _id: null,
                        body: "",
                    };
                    // define payload then trigger snackbar to show user it was successfull
                    let payload = {
                        type: "success",
                        text: "Successfully edited your comment",
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                })
                .catch(e => {
                    // define payload then trigger snackbar to show user there was an error
                    let payload = {
                        type: "error",
                        text: e.response.data.error,
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                });
        },
        DeleteComment() {
            // console.log(this.deleteComment)
            this.$http({
                url: `/api/post/${this.post._id}/comment/${this.deleteComment}/delete`,
                crossdomain: true,
                method: "GET",
            })
                .then(_ => {
                    //  turn off delete modal
                    this.commentDeleteModal = false;
                    //define payload for snackbar then toggle snackbar
                    let payload = {
                        type: "success",
                        text: "Successfully deleted comment",
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                    // find deleted comment index then splice off the post.comments array
                    let i = this.post.comments.findIndex(x => x._id == this.deleteComment);
                    this.post.comments.splice(i, 1);
                })
                .catch(e => console.log(e));
        },
        isCommentOwner(id) {
            return id == this.$store.getters.userId;
        },
        toggleShareModal(post) {
            this.$store.dispatch("toggleShareModal", {
                post,
            });
        },
        getPost(id) {
            this.$http({
                url: `/api/post/${id}`,
                crossdomain: true,
                method: "GET",
            })
                .then(res => {
                    // this.post = res.data.post;
                    this.post = {
                        title: res.data.post.title,
                        body: res.data.post.body,
                        lastEdited: res.data.post.lastEdited,
                        image: res.data.post.image,
                        user: res.data.post.user,
                        categories: res.data.post.categories,
                        _id: res.data.post._id,
                        comments: res.data.comments,
                        allowComments: res.data.post.allowComments,
                    };
                })
                .catch(e => {
                    let payload = {
                        type: "error",
                        text: e.response.data.error,
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                    this.$router.push("/");
                });
        },
        userRedirect(id) {
            this.$router.push(`/user/${id}`);
        },
        editPost(id) {
            this.$store.dispatch("toggleEditPostModal");
        },
        postComment() {
            let postId = this.$route.params.id;
            let data = {
                body: this.commentBody,
            };
            this.$http({
                url: `/api/post/${postId}/comment/new`,
                crossdomain: true,
                method: "POST",
                data,
            })
                .then(res => {
                    this.commentBody = "";
                    this.post.comments.push(res.data.returnComment);
                    // define payload then trigger snackbar to show user it was successfull
                    let payload = {
                        type: "success",
                        text: "Successfully posted your comment",
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                })
                .catch(e => {
                    console.log(e);
                    // define payload then trigger snackbar to show user there was an error
                    let payload = {
                        type: "error",
                        text: "An error occured please try again later",
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                });
        },
    },
};
</script>