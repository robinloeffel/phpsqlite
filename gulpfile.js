let gulp = require('gulp'),
    del = require('del'),
    runSequence = require('run-sequence'),
    changed = require('gulp-changed'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    connect = require('gulp-connect-php'),
    livereload = require('gulp-livereload'),
    devEnv = process.env.NODE_ENV === 'dev';

gulp.task('clean', () => {
    return del('dist/');
});

gulp.task('serve', () => {
    return connect.server({
        port: 8080,
        base: 'dist/'
    });
});

gulp.task('sass', () => {
    return gulp.src('./src/scss/*.scss')
        .pipe(changed('./dist/css/', {
            extension: '.css'
        }))
        .pipe(plumber())
        .pipe(sass.sync({
            outputStyle: 'expanded'
        }))
        .pipe(gulp.dest('./dist/css/'))
        .pipe(livereload());
});

gulp.task('js', () => {
    return gulp.src('./src/js/*.js')
        .pipe(changed('./dist/js/'))
        .pipe(plumber())
        .pipe(gulp.dest('./dist/js/'));
});

gulp.task('api', () => {
    return gulp.src('./api/*')
        .pipe(gulp.dest('./dist/api/'));
});

gulp.task('copy', () => {
    return gulp.src([
            './src/index.html'
        ])
        .pipe(gulp.dest('./dist/'));
});

gulp.task('vendor', () => {
    return gulp.src([
            './node_modules/axios/dist/axios.min.js',
            './node_modules/vue/dist/vue.min.js'
        ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('./dist/js/'));
});

gulp.task('watch', () => {
    livereload.listen();
    gulp.watch('./src/index.html', ['copy']);
    gulp.watch('./api/*', ['api']);
    gulp.watch('./src/scss/*.scss', ['sass']);
    gulp.watch('./src/js/*.js', ['js']);
});

gulp.task('default', (callback) => {
    runSequence('clean', 'build', 'serve', 'watch', callback);
});

gulp.task('build', (callback) => {
    runSequence('clean', ['sass', 'js', 'api', 'copy', 'vendor'], callback);
});
