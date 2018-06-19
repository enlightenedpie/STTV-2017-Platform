var path = require('path');

module.exports = {
  entry : {
    'single-courses' : './single-courses.js',
    'site-object' : './s/modules/base/_sss.js'
  },
  mode: 'development',
  output : {
    path: path.resolve(__dirname, './s'),
    filename: './[name].bundle.js'
  }
};
