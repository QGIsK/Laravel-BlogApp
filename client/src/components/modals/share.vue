<template>
  <v-dialog v-model="show" dark max-width="300px">
    <v-card>
      <v-card-title class="headline grey darken-4 text-xs-center">Share Post</v-card-title>
      <v-card-text class>
        <v-layout justify-center>
          <div>
            <!-- <v-flex xs12> -->
            <v-text-field :value="makeUrl(post)" label="URL" readonly></v-text-field>
            <!-- </v-flex> -->
            <v-btn
              round
              dark
              v-clipboard="makeUrl(post)"
              color="indigo accent-3"
              @click="Copy()"
              style="width:175px"
            >
              <i class="fa fa-globe"></i>&nbsp;COPY URL
            </v-btn>
          </div>
        </v-layout>
        <v-layout justify-center>
          <social-sharing :url="makeUrl(post)" inline-template>
            <div>
              <network network="reddit">
                <v-btn round dark color="indigo darken-4" style="width:175px">
                  <i class="fa fa-reddit"></i>&nbsp;Reddit
                </v-btn>
              </network>
              <v-spacer></v-spacer>
              <network network="twitter">
                <v-btn round dark color="blue darken-2" style="width:175px">
                  <i class="fa fa-twitter"></i>&nbsp;Twitter
                </v-btn>
              </network>
              <v-spacer></v-spacer>
              <network network="telegram">
                <v-btn round dark color="blue lighten-2" style="width:175px">
                  <i class="fa fa-telegram"></i>&nbsp;Telegram
                </v-btn>
              </network>
            </div>
          </social-sharing>
        </v-layout>
      </v-card-text>
      <v-card-actions>
        <v-btn flat v-clipboard="shortUrl" @click="Copy()">Prefer a shorter url?</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
    name: "share",
    data() {
        return { shortUrl: "" };
    },
    computed: {
        show: {
            get() {
                if (this.$store.getters.shareActive) this.shortenUrl(this.$store.getters.sharePost);
                return this.$store.getters.shareActive;
            },
            set() {
                this.$store.dispatch("toggleShareModal", {
                    post: "",
                });
            },
        },
        post() {
            return this.$store.getters.sharePost;
        },
    },
    mounted() {},
    methods: {
        shortenUrl(post) {
            this.$http({
                url: "https://we-link.herokuapp.com/api/url/shorten",
                crossDomain: true,
                data: {
                    longUrl: `${window.location.protocol}${window.location.host}/post/${post}`,
                },
                method: "POST",
            })
                .then(res => {
                    this.shortUrl = res.data.shortUrl;
                })
                .catch(e => {
                    console.log(e);
                    let payload = {
                        type: "error",
                        text: "An error occured shortening the link, please try again later.",
                    };
                    this.$store.dispatch("toggleSnackBar", payload);
                });
        },
        Copy() {
            let payload = {
                type: "success",
                text: "Successfully coppied to clipboard",
            };
            this.$store.dispatch("toggleSnackBar", payload);
        },
        makeUrl(post) {
            return `${window.location.protocol}${window.location.host}/post/${post}`;
        },
    },
};
</script>
