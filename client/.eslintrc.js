// https://eslint.org/docs/user-guide/configuring

// module.exports = {
// 	root: true,
// 	parserOptions: {
// 		parser: "babel-eslint",
// 	},
// 	env: {
// 		browser: true,
// 		es6: true,
// 		node: true,
// 	},
// 	extends: [
// 		"airbnb-base",
// 		"prettier",
// 		"plugin:vue/recommended",
// 		// https://github.com/vuejs/eslint-plugin-vue#priority-a-essential-error-prevention
// 		// consider switching to `plugin:vue/strongly-recommended` or `plugin:vue/recommended` for stricter rules.
// 		// https://github.com/standard/standard/blob/master/docs/RULES-en.md
// 		"standard",
// 	],
// 	// required to lint *.vue files
// 	plugins: ["prettier", "vue"],
// 	// add your custom rules here
// 	rules: {
// 		"prettier/prettier": ["error"],
// 		// allow async-await
// 		"generator-star-spacing": "off",
// 		// allow debugger during development
// 		"no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
// 		//ingore qoutes
// 		quotes: "off",
// 		//ignore semi
// 		semi: "off",
// 	},
// };

module.exports = {
	root: true,
	env: {
		browser: true,
		es6: true,
		node: true,
	},
	extends: ["plugin:vue/essential", "eslint:recommended", "airbnb", "prettier"],
	plugins: ["prettier"],
	rules: {
		"prettier/prettier": ["error"],
		"no-console": process.env.NODE_ENV === "production" ? "error" : "off",
		"no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
	},
	parserOptions: {
		parser: "babel-eslint",
	},
};
