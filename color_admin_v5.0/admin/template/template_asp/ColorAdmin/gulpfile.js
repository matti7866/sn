/*
	TASK LIST
	------------------
	01. font-fontawesome
	02. font-bootstrap
	03. js-demo
	04. js-theme
	05. js-vendor
	06. js-app
	07. plugins
	08. css-vendor
	
	09. css-default
	10. css-default-rtl
	11. css-default-theme
	12. css-default-image
	13. watch-default
	14. default
	
	15. css-material
	16. css-material-rtl
	17. css-material-theme
	18. css-material-image
	19. watch-material
	20. material
	
	21. css-apple
	22. css-apple-rtl
	23. css-apple-theme
	24. css-apple-image
	25. watch-apple
	26. apple
	
	27. css-transparent
	28. css-transparent-rtl
	29. css-transparent-theme
	30. css-transparent-image
	31. watch-transparent
	32. transparent
	
	33. css-facebook
	34. css-facebook-rtl
	35. css-facebook-theme
	36. css-facebook-image
	37. watch-facebook
	38. facebook
	
	39. css-google
	40. css-google-rtl
	41. css-google-theme
	42. css-google-image
	43. watch-google
	44. google
*/
var gulp        = require('gulp');
var sass        = require('gulp-sass');
var minifyCSS   = require('gulp-clean-css');
var concat      = require('gulp-concat');
var sourcemaps  = require('gulp-sourcemaps');
var livereload  = require('gulp-livereload');
var connect     = require('gulp-connect');
var download    = require('gulp-download2');
var header      = require('gulp-header');
var uglify      = require('gulp-uglify-es').default;
var merge       = require('merge-stream');
var fileinclude = require('gulp-file-include');
var distPath    = './wwwroot';

// 01. font-fontawesome
gulp.task('font-fontawesome', function() {
  return gulp.src(['node_modules/@fortawesome/fontawesome-free/webfonts/*'])
  	.pipe(gulp.dest(distPath + '/webfonts/'));
});

// 02. font-bootstrap
gulp.task('font-bootstrap', function() {
	return gulp.src(['node_modules/bootstrap-icons/font/fonts/*'])
  	.pipe(gulp.dest(distPath + '/css/fonts/'));
});

// 03. js-demo
gulp.task('js-demo', function(){
	return gulp.src([ 'js/demo/**' ])
		.pipe(gulp.dest(distPath + '/js/demo'));
});

// 04. js-theme
gulp.task('js-theme', function(){
	return gulp.src([ 'js/demo/**' ])
		.pipe(gulp.dest(distPath + '/js/theme'));
});

// 05. js-vendor
gulp.task('js-vendor', function(){
  return gulp.src([
  	'node_modules/pace-js/pace.min.js',
  	'node_modules/jquery/dist/jquery.min.js',
  	'node_modules/jquery-ui-dist/jquery-ui.min.js',
  	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
  	'node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js',
  	'node_modules/js-cookie/src/js.cookie.js'
		])
		.pipe(sourcemaps.init())
		.pipe(concat('vendor.min.js'))
		.pipe(sourcemaps.write())
		.pipe(uglify())
		.pipe(gulp.dest(distPath + '/js/'))
		.pipe(livereload());
});

// 06. js-app
gulp.task('js-app', function(){
  return gulp.src([
  	'./src/js/app.js',
  	])
    .pipe(sourcemaps.init())
    .pipe(concat('app.min.js'))
    .pipe(sourcemaps.write())
    .pipe(uglify())
    .pipe(gulp.dest(distPath + '/js/'))
    .pipe(livereload());
});

// 07. plugins
gulp.task('plugins', function() {
	var pluginFiles = [
  	'node_modules/pace-js/*',
  	'node_modules/jquery/dist/*',
  	'node_modules/jquery-ui-dist/*',
  	'node_modules/bootstrap/dist/js/*',
  	'node_modules/js-cookie/src/*',
		'node_modules/apexcharts/dist/**',
		'node_modules/lity/dist/**',
		'node_modules/x-editable-bs4/dist/**',
		'node_modules/dropzone/dist/**',
  	'node_modules/@fullcalendar/**',
		'node_modules/chart.js/dist/**',
		'node_modules/raphael/raphael.min.js',
		'node_modules/tag-it/css/**',
		'node_modules/tag-it/js/**',
		'node_modules/jquery-migrate/dist/**',
		'node_modules/jquery-mockjax/dist/**',
		'node_modules/x-editable-bs4/dist/**',
		'node_modules/blueimp-file-upload/**',
		'node_modules/blueimp-canvas-to-blob/**',
		'node_modules/blueimp-gallery/**',
		'node_modules/blueimp-load-image/**',
		'node_modules/blueimp-tmpl/**',
		'node_modules/abpetkov-powerange/dist/**',
		'node_modules/bootstrap3-wysihtml5-bower/dist/**',
		'node_modules/summernote/dist/**',
		'node_modules/parsleyjs/dist/**',
		'node_modules/flot/**',
		'node_modules/ckeditor/**',
		'node_modules/jvectormap-next/jquery-jvectormap.css',
		'node_modules/jvectormap-next/jquery-jvectormap.min.js',
		'node_modules/moment/**',
		'node_modules/d3/d3.min.js',
		'node_modules/nvd3/build/**',
		'node_modules/simple-line-icons/css/**',
		'node_modules/simple-line-icons/fonts/**',
		'node_modules/jquery-knob/dist/**',
		'node_modules/sweetalert/dist/**',
		'node_modules/clipboard/dist/**',
		'node_modules/jstree/dist/**',
		'node_modules/gritter/css/**',
		'node_modules/gritter/images/**',
		'node_modules/gritter/js/**',
		'node_modules/datatables.net/js/**',
		'node_modules/datatables.net-bs4/css/**',
		'node_modules/datatables.net-bs4/js/**',
		'node_modules/datatables.net-responsive/js/**',
		'node_modules/datatables.net-responsive-bs4/css/**',
		'node_modules/datatables.net-responsive-bs4/js/**',
		'node_modules/datatables.net-autofill/js/**',
		'node_modules/datatables.net-autofill-bs4/css/**',
		'node_modules/datatables.net-autofill-bs4/js/**',
		'node_modules/datatables.net-buttons/js/**',
		'node_modules/datatables.net-buttons-bs4/css/**',
		'node_modules/datatables.net-buttons-bs4/js/**',
		'node_modules/datatables.net-colreorder/js/**',
		'node_modules/datatables.net-colreorder-bs4/css/**',
		'node_modules/datatables.net-colreorder-bs4/js/**',
		'node_modules/datatables.net-fixedcolumns/js/**',
		'node_modules/datatables.net-fixedcolumns-bs4/css/**',
		'node_modules/datatables.net-fixedcolumns-bs4/js/**',
		'node_modules/datatables.net-fixedheader/js/**',
		'node_modules/datatables.net-fixedheader-bs4/css/**',
		'node_modules/datatables.net-fixedheader-bs4/js/**',
		'node_modules/datatables.net-keytable/js/**',
		'node_modules/datatables.net-keytable-bs4/css/**',
		'node_modules/datatables.net-keytable-bs4/js/**',
		'node_modules/datatables.net-rowreorder/js/**',
		'node_modules/datatables.net-rowreorder-bs4/css/**',
		'node_modules/datatables.net-rowreorder-bs4/js/**',
		'node_modules/datatables.net-scroller/js/**',
		'node_modules/datatables.net-scroller-bs4/css/**',
		'node_modules/datatables.net-scroller-bs4/js/**',
		'node_modules/datatables.net-select/js/**',
		'node_modules/datatables.net-select-bs4/css/**',
		'node_modules/datatables.net-select-bs4/js/**',
		'node_modules/pdfmake/build/**',
		'node_modules/jszip/dist/**',
		'node_modules/bootstrap-datepicker/dist/**',
		'node_modules/bootstrap-timepicker/css/**',
		'node_modules/bootstrap-timepicker/js/**',
		'node_modules/isotope-layout/dist/**',
		'node_modules/lightbox2/dist/**',
		'node_modules/bootstrap-datetime-picker/css/**',
		'node_modules/bootstrap-datetime-picker/js/**',
		'node_modules/masonry-layout/dist/**',
		'node_modules/select2/dist/**',
		'node_modules/select-picker/dist/**',
		'node_modules/jvectormap-next/**',
		'node_modules/spectrum-colorpicker2/dist/*',
		'node_modules/jquery.maskedinput/src/**',
		'node_modules/ion-rangeslider/css/**',
		'node_modules/ion-rangeslider/js/**',
		'node_modules/bootstrap-daterangepicker/daterangepicker.css',
		'node_modules/bootstrap-daterangepicker/daterangepicker.js',
		'node_modules/flag-icon-css/css/**',
		'node_modules/flag-icon-css/flags/**',
		'node_modules/jquery-sparkline/jquery.sparkline.min.js',
		'node_modules/bootstrap-social/bootstrap-social.css',
		'node_modules/intro.js/minified/**',
		'node_modules/angular/**',
		'node_modules/angular-ui-router/release/**',
		'node_modules/angular-ui-bootstrap/dist/**',
		'node_modules/oclazyload/dist/**',
		'node_modules/swiper/*',
		'node_modules/switchery/dist/*',
		'node_modules/lightbox2/dist/**',
		'node_modules/@highlightjs/**'
	];
	
	download([
		'https://raw.githubusercontent.com/kbwood/countdown/master/dist/js/jquery.plugin.min.js',
		'https://raw.githubusercontent.com/kbwood/countdown/master/dist/js/jquery.countdown.min.js',
		'https://raw.githubusercontent.com/kbwood/countdown/master/dist/css/jquery.countdown.css'
	]).pipe(gulp.dest(distPath + '/lib/countdown/'));
	download([
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/js/jquery.superbox.min.js',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/superbox.min.css'
	]).pipe(gulp.dest(distPath + '/lib/superbox/'));
	download([
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.eot',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.svg',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.ttf',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.woff'
	]).pipe(gulp.dest(distPath + '/lib/superbox/font/'));
	download([
		'https://unpkg.com/ionicons@4.2.6/dist/css/ionicons.min.css'
	]).pipe(gulp.dest(distPath + '/lib/ionicons/css/'));
	download([
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.eot',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff2',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.ttf',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.svg'
	]).pipe(gulp.dest(distPath + '/lib/ionicons/fonts'));
	download([
		'http://lab.xero.nu/bootstrap_calendar/lib/css/bootstrap_calendar.css'
	]).pipe(gulp.dest(distPath + '/lib/bootstrap-calendar/css/'));
	download([
		'http://lab.xero.nu/bootstrap_calendar/lib/js/bootstrap_calendar.min.js'
	]).pipe(gulp.dest(distPath + '/lib/bootstrap-calendar/js/'));
	download([
		'https://jvectormap.com/js/jquery-jvectormap-world-mill.js'
	]).pipe(gulp.dest(distPath + '/lib/jvectormap-next/'));
	
	return gulp.src(pluginFiles, { base: './node_modules/' })
		.pipe(gulp.dest(distPath + '/lib'));
});


// 08. css-vendor
gulp.task('css-vendor', function(){
  return gulp.src([
		'node_modules/animate.css/animate.min.css',
		'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
		'node_modules/jquery-ui-dist/jquery-ui.min.css',
		'node_modules/pace-js/themes/black/pace-theme-flash.css',
		'node_modules/perfect-scrollbar/css/perfect-scrollbar.css'
		])
		.pipe(concat('vendor.min.css'))
		.pipe(minifyCSS({debug: true}, (details) => {
      console.log(`${details.name}: ${details.stats.originalSize}`);
      console.log(`${details.name}: ${details.stats.minifiedSize}`);
    }))
		.pipe(gulp.dest(distPath + '/css/'))
		.pipe(livereload());
});


// 09. css-default
gulp.task('css-default', function(){
  return gulp.src([
			'./src/scss/default/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest(distPath + '/css/default/'))
		.pipe(livereload());
});

// 10. css-default-rtl
gulp.task('css-default-rtl', function(){
	return gulp.src([
			'./src/scss/default/styles.scss'
		])
		.pipe(header('$enable-rtl: true;'))
		.pipe(sass())
		.pipe(concat('app-rtl.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest(distPath + '/css/default/'))
		.pipe(livereload());
});

// 11. css-default-theme
gulp.task('css-default-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/default/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/default/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 12. css-default-image
gulp.task('css-default-image', function(){
	return gulp.src([ './src/scss/default/images/**' ])
		.pipe(gulp.dest(distPath + '/css/default/images'));
});

// 13. watch-default
gulp.task('watch-default', function () {
	livereload.listen();
	
  gulp.watch('./src/scss/default/**/*.scss', gulp.series(gulp.parallel(['css-default', 'css-default-theme'])));
  gulp.watch('js/**/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 14. default
gulp.task('default', gulp.series(gulp.parallel([
	'font-fontawesome', 
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-default',
	'css-default-rtl',
	'css-default-theme',
	'css-default-image'
])));



// 15. css-material
gulp.task('css-material', function(){
  return gulp.src([
			'./src/scss/material/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/css/material/'))
		.pipe(livereload());
});

// 16. css-material-rtl
gulp.task('css-material-rtl', function(){
	return gulp.src([
		'./src/scss/material/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/css/material/'));
});

// 17. css-material-theme
gulp.task('css-material-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/material/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/material/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 18. css-material-image
gulp.task('css-material-image', function(){
	return gulp.src([ './src/scss/material/images/**' ])
		.pipe(gulp.dest(distPath + '/css/material/images'));
});

// 19. watch-material
gulp.task('watch-material', function () {
	livereload.listen();
  gulp.watch('./src/scss/**/**.scss', gulp.series(gulp.parallel(['css-material', 'css-material-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 20. material
gulp.task('material', gulp.series(gulp.parallel([
	'font-fontawesome',
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-material',
	'css-material-rtl',
	'css-material-theme',
	'css-material-image'
])));


// 21. css-apple
gulp.task('css-apple', function(){
  return gulp.src([
			'./src/scss/apple/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/css/apple/'))
		.pipe(livereload());
});

// 22. css-apple-rtl
gulp.task('css-apple-rtl', function(){
	return gulp.src([
		'./src/scss/apple/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/css/apple/'));
});

// 23. css-apple-theme
gulp.task('css-apple-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/apple/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/apple/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 24. css-apple-image
gulp.task('css-apple-image', function(){
	return gulp.src([ './src/scss/apple/images/**' ])
		.pipe(gulp.dest(distPath + '/css/apple/images'));
});

// 25. watch-apple
gulp.task('watch-apple', function () {
	livereload.listen();
  gulp.watch('./src/scss/**/**.scss', gulp.series(gulp.parallel(['css-apple', 'css-apple-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 26. apple
gulp.task('apple', gulp.series(gulp.parallel([
	'font-fontawesome',
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-apple',
	'css-apple-rtl',
	'css-apple-theme',
	'css-apple-image'
])));


// 27. css-transparent
gulp.task('css-transparent', function(){
  return gulp.src([
			'./src/scss/transparent/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/css/transparent/'))
		.pipe(livereload());
});

// 28. css-transparent-rtl
gulp.task('css-transparent-rtl', function(){
	return gulp.src([
		'./src/scss/transparent/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/css/transparent/'));
});

// 29. css-transparent-theme
gulp.task('css-transparent-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/transparent/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/transparent/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 30. css-transparent-image
gulp.task('css-transparent-image', function(){
	return gulp.src([ './src/scss/transparent/images/**' ])
		.pipe(gulp.dest(distPath + '/css/transparent/images'));
});

// 31. watch-transparent
gulp.task('watch-transparent', function () {
	livereload.listen();
  gulp.watch('./src/scss/**/**.scss', gulp.series(gulp.parallel(['css-transparent', 'css-transparent-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 32. transparent
gulp.task('transparent', gulp.series(gulp.parallel([
	'font-fontawesome',
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-transparent',
	'css-transparent-rtl',
	'css-transparent-theme',
	'css-transparent-image'
])));


// 33. css-facebook
gulp.task('css-facebook', function(){
  return gulp.src([
			'./src/scss/facebook/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/css/facebook/'))
		.pipe(livereload());
});

// 34. css-facebook-rtl
gulp.task('css-facebook-rtl', function(){
	return gulp.src([
		'./src/scss/facebook/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/css/facebook/'));
});

// 35. css-facebook-theme
gulp.task('css-facebook-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/facebook/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/facebook/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 36. css-facebook-image
gulp.task('css-facebook-image', function(){
	return gulp.src([ './src/scss/facebook/images/**' ])
		.pipe(gulp.dest(distPath + '/css/facebook/images'));
});

// 37. watch-facebook
gulp.task('watch-facebook', function () {
	livereload.listen();
  gulp.watch('./src/scss/**/**.scss', gulp.series(gulp.parallel(['css-facebook', 'css-facebook-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 38. facebook
gulp.task('facebook', gulp.series(gulp.parallel([
	'font-fontawesome',
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-facebook',
	'css-facebook-rtl',
	'css-facebook-theme',
	'css-facebook-image'
])));


// 39. css-google
gulp.task('css-google', function(){
  return gulp.src([
			'./src/scss/google/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/css/google/'))
		.pipe(livereload());
});

// 40. css-google-rtl
gulp.task('css-google-rtl', function(){
	return gulp.src([
		'./src/scss/google/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/css/google/'));
});

// 41. css-google-theme
gulp.task('css-google-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ './src/scss/google/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/css/google/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 42. css-google-image
gulp.task('css-google-image', function(){
	return gulp.src([ './src/scss/google/images/**' ])
		.pipe(gulp.dest(distPath + '/css/google/images'));
});

// 43. watch-google
gulp.task('watch-google', function () {
	livereload.listen();
  gulp.watch('./src/scss/**/**.scss', gulp.series(gulp.parallel(['css-google', 'css-google-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 44. google
gulp.task('google', gulp.series(gulp.parallel([
	'font-fontawesome',
	'font-bootstrap',
	'js-demo', 
	'js-theme', 
	'js-vendor', 
	'js-app', 
	'css-vendor', 
	'css-google',
	'css-google-rtl',
	'css-google-theme',
	'css-google-image'
])));