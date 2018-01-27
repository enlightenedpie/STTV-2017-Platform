var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var include = require("gulp-include");
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

gulp.task('styles', function() {
    gulp.src('styles/styles.min.sass')
		.pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./'));

	//gulp.watch('styles/styles.min.sass',['styles']);
});

gulp.task('scripts', function(){
	gulp.src('s/sttv-js.js')
	.pipe(sourcemaps.init())
	.pipe(include())
	.on('error', console.log)
	.pipe(gulp.dest('./'))
	.pipe(rename('sttv-js.min.js'))
    .pipe(uglify())
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest('./'))
});

gulp.task('noform', function() {
    gulp.src('material/materialize-src/sass/materialize-noform.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
		.pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./'));
});

// gulp.task('noform', function() {
//   console.log('gulp compiling');
//   gulp.src([
//     './material/materialize-src/sass/components/**',
//     '!./material/materialize-src/sass/components/{forms,forms/**}'
//   ])
//   .pipe(gulp.dest('output'))
// });
