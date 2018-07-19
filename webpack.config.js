var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: './js/Hello.jsx',
    output: { path: `${__dirname}/public/generated/`, filename: 'bundle.js' },
    mode: 'development',
    module: {
        rules: [
            {
                test: /.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['react']
                }
            }
        ]
    },
    devServer: {
        contentBase: path.join(__dirname, 'public'),
        compress: true,
        host: '0.0.0.0',
        port: 8500
    }
};