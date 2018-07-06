const webpack = require('webpack')
const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const globImporter = require('node-sass-glob-importer')

module.exports = {
  entry : ['./s/_entry.js','./styles/_init.sass'],
  output : {
    path : path.resolve(__dirname, './assets'),
    filename : './sttv-js.min.js',
    library : 'sttvjs',
    libraryTarget : 'umd',
    umdNamedDefine : true
  },
  externals : {
    jquery : 'jQuery'
  },
  mode : 'none',
  module : {
    rules : [
      { test : /\.sass$/,
        use : ExtractTextPlugin.extract({
          fallback : 'style-loader',
          use : [
            {
              loader : 'css-loader',
              options : {
                url: false,
                minimize: true,
                sourceMap: true
              }
            },
            {
              loader : 'sass-loader',
              options : {
                sourceMap: true,
                importer : globImporter()
              }
            }
          ]
        })
      },
      { test : /\.(js)$/,
        exclude : /node_modules/,
        use : {
          loader: 'babel-loader'
        }
      }
    ]
  },
  plugins : [
    new ExtractTextPlugin({
      filename : './main.min.css',
      disable : false,
      allChunks : true
    })
  ]
};
