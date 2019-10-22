/* eslint-disable */
import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
if (!process.env.NODE_ENV || process.env.NODE_ENV == "development") {
	axios.defaults.baseURL = "http://localhost:3000";
}
Vue.use(Vuex);
export default new Vuex.Store({
	state: {
		status: "",
		token: localStorage.getItem("token") || "",
		user: JSON.parse(localStorage.getItem("user")) || {},
		posts: [],
		categories: [],
		mySetingsModal: false,
		loginModal: false,
		registerModal: false,
		editPostModal: false,
		addCategoryModal: false,
		filterModal: false,
		shareModal: false,
		sharePost: "",
		newPostModal: false,
		snackbarActive: false,
		snackbarText: "",
		snackbarType: "",
	},
	mutations: {
		getPosts(state, { posts }) {
			state.posts = posts;
		},
		getCategories(state, { categories }) {
			state.categories = categories;
		},
		auth_request(state) {
			state.status = "loading";
		},
		auth_success(state, { token, user }) {
			state.status = "success";
			state.token = token;
			state.user = user;
		},
		auth_error(state) {
			state.status = "error";
		},
		logout(state) {
			state.status = "";
			state.token = "";
			state.user = {};
		},
		toggleMySettingsModal(state) {
			state.mySetingsModal = !state.mySetingsModal;
		},
		toggleLoginModal(state) {
			state.loginModal = !state.loginModal;
		},
		toggleRegisterModal(state) {
			state.registerModal = !state.registerModal;
		},
		toggleFilterModal(state) {
			state.filterModal = !state.filterModal;
		},
		toggleShareModal(state, { post }) {
			state.shareModal = !state.shareModal;
			state.sharePost = post;
		},
		toggleAddCategoryModal(state) {
			state.addCategoryModal = !state.addCategoryModal;
		},
		toggleNewPostModal(state) {
			state.newPostModal = !state.newPostModal;
		},
		toggleEditPostModal(state) {
			state.editPostModal = !state.editPostModal;
		},
		toggleSnackBar(state, { type, text }) {
			state.snackbarActive = !state.snackbarActive;
			state.snackbarText = text;
			state.snackbarType = type;
		},
	},
	actions: {
		getPosts({ commit }) {
			axios({ url: "/api/post", crossDomain: true, method: "GET" })
				.then(res => {
					commit("getPosts", { posts: res.data.posts });
				})
				.catch(e => {
					console.log(e);
					let payload = {
						type: "error",
						text: "An error occured",
					};
					commit("toggleSnackBar", payload);
				});
		},
		getCategories({ commit }) {
			axios({
				url: "/api/category",
				crossdomain: true,
				method: "GET",
			}).then(res => {
				commit("getCategories", { categories: res.data.categories });
			});
		},
		toggleLoginModal({ commit }) {
			commit("toggleLoginModal");
		},
		toggleMySettingsModal({ commit }) {
			commit("toggleMySettingsModal");
		},
		toggleRegisterModal({ commit }) {
			commit("toggleRegisterModal");
		},
		toggleFilterModal({ commit }) {
			commit("toggleFilterModal");
		},
		toggleAddCategoryModal({ commit }) {
			commit("toggleAddCategoryModal");
		},
		toggleShareModal({ commit }, { post }) {
			commit("toggleShareModal", {
				post,
			});
		},
		toggleNewPostModal({ commit }) {
			commit("toggleNewPostModal");
		},
		toggleEditPostModal({ commit }) {
			commit("toggleEditPostModal");
		},
		toggleSnackBar({ commit }, { type, text }) {
			commit("toggleSnackBar", {
				type,
				text,
			});
		},
		login({ commit }, user) {
			return new Promise((resolve, reject) => {
				commit("auth_request");
				axios({
					url: "/api/auth/login",
					data: {
						email: user.email,
						password: user.password,
					},
					crossdomain: true,
					method: "POST",
				})
					.then(resp => {
						const token = resp.data.token;
						const user = {
							id: resp.data.user._id,
							name: resp.data.user.name,
							email: resp.data.user.email,
							socials: resp.data.user.socials,
							isEditor: resp.data.user.isEditor,
							isAdmin: resp.data.user.isAdmin,
						};

						let payload = {
							type: "success",
							text: "Successfully logged in",
						};

						localStorage.setItem("token", token);
						localStorage.setItem("user", JSON.stringify(user));
						axios.defaults.headers.common["Authorization"] = token;
						commit("toggleSnackBar", payload);
						commit("auth_success", {
							token,
							user,
						});
						resolve(resp);
					})
					.catch(err => {
						commit("auth_error", err);
						localStorage.removeItem("token");
						reject(err);
					});
			});
		},
		register({ commit }, user) {
			return new Promise((resolve, reject) => {
				commit("auth_request");
				axios({
					url: "/api/auth/register",
					crossdomain: true,
					data: user,
					method: "POST",
				})
					.then(resp => {
						const token = resp.data.token;
						const user = {
							id: resp.data.user._id,
							name: resp.data.user.name,
							email: resp.data.user.email,
							socials: resp.data.user.socials,
							editor: resp.data.user.isEditor,
							admin: resp.data.user.isAdmin,
						};

						let payload = {
							type: "success",
							text: "Successfully registered",
						};

						localStorage.setItem("token", token);
						localStorage.setItem("user", JSON.stringify(user));

						axios.defaults.headers.common["Authorization"] = token;
						commit("auth_success", {
							token,
							user,
						});
						commit("toggleSnackBar", payload);
						resolve(resp);
					})
					.catch(err => {
						commit("auth_error", err);
						localStorage.removeItem("user");
						localStorage.removeItem("token");
						reject(err);
					});
			});
		},
		logout({ commit }) {
			return new Promise((resolve, reject) => {
				let payload = {
					type: "success",
					text: "Successfully logged out",
				};

				localStorage.removeItem("token");
				localStorage.removeItem("user");
				delete axios.defaults.headers.common["Authorization"];
				commit("toggleSnackBar", payload);
				commit("logout");
				resolve();
			});
		},
	},
	getters: {
		posts: state => state.posts,
		categories: state => state.categories,
		isLoggedIn: state => !!state.token,
		userId: state => state.user.id,
		authStatus: state => state.status,
		loginActive: state => state.loginModal,
		registerActive: state => state.registerModal,
		filterActive: state => state.filterModal,
		newPostActive: state => state.newPostModal,
		editPostModalActive: state => state.editPostModal,
		AddCategoryActive: state => state.addCategoryModal,
		mySettingsActive: state => state.mySetingsModal,
		sharePost: state => state.sharePost,
		shareActive: state => state.shareModal,
		snackbarActive: state => state.snackbarActive,
		snackbarText: state => state.snackbarText,
		snackbarType: state => state.snackbarType,
		isAdmin: state => {
			if (!state.user.isAdmin) return false;
			return state.user.isAdmin;
		},
		isEditor: state => {
			if (!state.user.isEditor) return false;
			return state.user.isEditor;
		},
	},
});
