var path = require('path');
 var webpack = require('webpack');

 module.exports = {
     entry: './src/js/main.js',
     output: {
         path: path.resolve(__dirname, 'static/js'),
         filename: 'main.js'
     },
     stats: {
         colors: true
     }
 };
