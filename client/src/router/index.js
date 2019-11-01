/* eslint-disable */

import Vue from "vue";
import Router from "vue-router";
import store from "../store/";

import Home from "../components/Home.vue";
import showPost from "../components/post/showPost.vue";
import myPosts from "../components/post/myPosts.vue";
import UserProfile from "../components/user/Profile.vue";
import FilterPosts from "../components/post/Filter.vue";

Vue.use(Router);

let router = new Router({
	mode: "history",
	routes: [
		{
			path: "/",
			name: "Home",
			component: Home,
		},
		{
			path: "/post/filter",
			name: "FilterPosts",
			component: FilterPosts,
		},
		{
			path: "/post/my",
			name: "myPosts",
			component: myPosts,
		},
		{
			path: "/post/:id",
			name: "ShowPost",
			component: showPost,
		},
		{
			path: "/user/:id",
			name: "UserProfile",
			component: UserProfile,
		},
	],
});

router.beforeEach((to, from, next) => {
	if (to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters.isLoggedIn) {
			next();
			return;
		}
		next("/login");
	} else {
		next();
	}
});

export default router;
