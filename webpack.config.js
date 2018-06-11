var path = require('path');

module.exports = {
  entry : './single-courses.js',
  mode: 'development',
  output : {
    path: path.resolve(__dirname, 's/'),
    filename: './single-courses.bundle.js'
  }
};
