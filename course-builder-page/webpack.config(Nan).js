const webpack = require('webpack');
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
// const ExtractTextPlugin = require('extract-text-webpack-plugin');

const VENDOR_LIBS = [
  'react', 'faker', 'react-dom', 'redux',
  'react-redux', 'redux-form', 'lodash', 'react-iframe', 'react-materialize'
];

module.exports = {
  entry: {
    bundle: './src/index.js',
    vendor: VENDOR_LIBS
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].js'
  },
  module: {
    rules: [
      {
        use: {
          loader: 'babel-loader'
        },
        test: /\.js$/,
        exclude: /node_modules/
      },
      {
        use: ['style-loader', 'css-loader'],
        test: /\.css$/
      },
      {
        test: /\.js$/,
        use: ['source-map-loader'],
        enforce: 'pre',
        exclude: /node_modules/
      }
    ]
  },
  resolve: {
    extensions: [
      '*', '.js', '.jsx', '.json', '.sass', '.woff', 'ttf'
    ],
    modules: [
      'node_modules',
      path.resolve(__dirname, 'dist'),
    ]
  },
  devtool: '#eval-source-map',
  plugins: [
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor'
    }),
    new HtmlWebpackPlugin({
      template: './src/index.html'
    })
  ]
};
