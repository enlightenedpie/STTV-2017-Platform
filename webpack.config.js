var path = require('path');
var webpack = require('webpack');
// var sassConfig = require('webpack.sass-config');
// var merge = require('webpack-merge');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

var common = {
  entry: ['./s/checkout/base.js', './styles/styles.min.sass'],
  output: {
    path: path.resolve(__dirname, 's/'),
    filename: 'checkout.min.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        use: 'babel-loader'
      },
      { // css / sass / scss loader for webpack
        test: /\.(css|sass|scss)$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
                loader: 'css-loader',
                options: {
                    url: false
                }
            },
            {
                loader: 'sass-loader'
            }
          ],
        })
      }
   ]
 },
  plugins: [new ExtractTextPlugin({ // define where to save the file
      filename: 'styles.css',
      allChunks: true,
    })],
  stats: {
    colors: true
  },
  devtool: 'source-map',
  context: __dirname,
  resolve: {
    extensions: [
      '*','.js', '.jsx', '.json','.sass','.woff','ttf'
    ],
    modules: [
      'node_modules',
      path.resolve(__dirname, 's'),
      path.resolve(__dirname, 's/checkout'),
      path.resolve(__dirname, 's/checkout/modules')
    ]
  }
};

module.exports = common;
