var gulp = require('gulp');
var sass = require('gulp-sass');

function style() {
    return (
        gulp.src('admin/themes/default/sass/*.scss').pipe(sass()).on('error', sass.logError).pipe(gulp.dest('admin/themes/default/css/'))
        // gulp.src('front/themes/default/sass/*.scss').pipe(sass()).on('error', sass.logError).pipe(gulp.dest('front/themes/default/css/'))
        // gulp.src('modules/navigationModule/admin/themes/default/sass/*.scss').pipe(sass()).on('error', sass.logError).pipe(gulp.dest('modules/navigationModule/admin/themes/default/css/'))
    );
}

function watch() {
    gulp.watch('admin/themes/default/sass/*.scss', style)
    // gulp.watch('modules/navigationModule/admin/themes/default/sass/*.scss', style)
}

exports.style = style;
exports.watch = watch;

// gulp.task('watch', ['sass'], () => {
//     gulp.watch('./*.scss', ['sass']);
// });