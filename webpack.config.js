var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: './s/checkout/base.js',
    output: {
        path: path.resolve(__dirname, 's/'),
        filename: 'checkout.min.js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    stats: {
        colors: true
    },
    devtool: 'source-map',
    context: __dirname,
    resolve: {
        extensions: [ '.js', '.jsx', '.json' ],
        modules: [ 'node_modules', path.resolve(__dirname, 's'), path.resolve(__dirname, 's/checkout'), path.resolve(__dirname, 's/checkout/modules') ]
    }
};
