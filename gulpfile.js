let gulp = require('gulp'),
    del = require('del'),
    runSequence = require('run-sequence'),
    stylish = require('jshint-stylish'),
    changed = require('gulp-changed'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    connect = require('gulp-connect-php'),
    livereload = require('gulp-livereload'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpIf = require('gulp-if'),
    cleanCss = require('gulp-clean-css'),
    babel = require('gulp-babel'),
    jshint = require('gulp-jshint'),
    devEnv = process.argv[2] === '--dev' || process.argv[3] === '--dev';

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
        .pipe(gulpIf(devEnv, sourcemaps.init()))
        .pipe(sass.sync({
            outputStyle: 'expanded'
        }))
        .pipe(gulpIf(!devEnv, cleanCss()))
        .pipe(gulpIf(devEnv, sourcemaps.write('.')))
        .pipe(gulp.dest('./dist/css/'))
        .pipe(livereload());
});

gulp.task('js', () => {
    return gulp.src('./src/js/*.js')
        .pipe(changed('./dist/js/'))
        .pipe(plumber())
        .pipe(jshint())
        .pipe(jshint.reporter(stylish))
        .pipe(gulpIf(devEnv, sourcemaps.init()))
        .pipe(babel())
        .pipe(gulpIf(!devEnv, uglify()))
        .pipe(gulpIf(devEnv, sourcemaps.write('.')))
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
    let srcConfig = devEnv ? [
        './node_modules/axios/dist/axios.js',
        './node_modules/vue/dist/vue.js'
    ] : [
        './node_modules/axios/dist/axios.min.js',
        './node_modules/vue/dist/vue.min.js'
    ];

    return gulp.src(srcConfig)
        .pipe(concat('vendor.js'))
        .pipe(gulpIf(!devEnv, uglify()))
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
