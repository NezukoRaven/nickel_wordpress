const path = require('path');

module.exports = {
  entry: './index.js',
  output: {
    path: path.resolve(__dirname, 'assets/js/frontend/'),
    filename: 'block.js'
  },
  externals: {
    '@wordpress/element': ['wp', 'element'],
    '@wordpress/i18n': ['wp', 'i18n'],
    '@woocommerce/blocks-registry': ['wc', 'wcBlocksRegistry'],
    '@wordpress/html-entities': ['wp', 'htmlEntities'],
    '@woocommerce/settings': ['wc', 'wcSettings']
  },
  module: {
    rules: [
      {
        test: /\.js$/, // Compila archivos JavaScript
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader' // Utiliza babel-loader
        }
      }
    ]
  }
};