const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const globImporter = require('node-sass-glob-importer')

const jsConfig = {
  entry : './scripts/_entry.js',
  target : 'web',
  output : {
    path : path.resolve(__dirname, './scripts'),
    filename : './sttv-js.min.js',
    library : '_st',
    libraryTarget : 'var'
  },
  externals : {
    jquery : 'jQuery'
  },
  mode : 'none',
  module : {
    rules : [
      { test : /\.(js)$/,
        exclude : /node_modules/,
        use : {
          loader: 'babel-loader'
        }
      }
    ]
  }
}

const sassConfig = {
  entry : './styles/sass/_init.sass',
  target : 'web',
  output : {
    path : path.resolve(__dirname, './styles'),
    filename : './main.min.css',
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
}

module.exports = [jsConfig,sassConfig]
