const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
// Paths // 
const SRC_DIR =  path.resolve(__dirname, 'src');
const JS_DIR = path.resolve(__dirname, '.src/js');
const CSS_DIR = path.resolve(__dirname, '.src/scss');
const LIB_DIR = path.resolve( __dirname, 'src/library' );
const BUILD_DIR = path.resolve(__dirname, 'build');


module.exports = {
    ...defaultConfig,
    entry: {
        ...defaultConfig.entry(),
        index: './src/index.js',
        main: './src/js/main.js',
		bootstrap: './src/js/bootstrap.js',
    },
    	output: {
		path: path.resolve( BUILD_DIR ),
		filename: '[name].js',
		clean: true,
	},
    	optimization: {
		...defaultConfig.optimization,
		splitChunks: false,
	},
    	resolve: {
		...defaultConfig.resolve,		
	},
};