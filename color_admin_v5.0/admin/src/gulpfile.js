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
	
	09. html-default
	10. html-default-startup
	11. css-default
	12. css-default-rtl
	13. css-default-theme
	14. css-default-image
	15. watch-default
	16. webserver-default
	17. webserver-default-startup
	18. default
	19. default-startup
	
	20. html-material
	21. html-material-startup
	22. css-material
	23. css-material-rtl
	24. css-material-theme
	25. css-material-image
	26. watch-material
	27. webserver-material
	28. webserver-material-startup
	29. material
	30. material-startup
	
	31. html-apple
	32. html-apple-startup
	33. css-apple
	34. css-apple-rtl
	35. css-apple-theme
	36. css-apple-image
	37. watch-apple
	38. webserver-apple
	39. webserver-apple-startup
	40. apple
	41. apple-startup
	
	42. html-transparent
	43. html-transparent-startup
	44. css-transparent
	45. css-transparent-rtl
	46. css-transparent-theme
	47. css-transparent-image
	48. watch-transparent
	49. webserver-transparent
	50. webserver-transparent-startup
	51. transparent
	52. transparent-startup
	
	53. html-facebook
	54. html-facebook-startup
	55. css-facebook
	56. css-facebook-rtl
	57. css-facebook-theme
	58. css-facebook-image
	59. watch-facebook
	60. webserver-facebook
	61. webserver-facebook-startup
	62. facebook
	63. facebook-startup
	
	64. html-google
	65. html-google-startup
	66. css-google
	67. css-google-rtl
	68. css-google-theme
	69. css-google-image
	70. watch-google
	71. webserver-google
	72. webserver-google-startup
	73. google
	74. google-startup
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
var distPath    = '../template';

// 01. font-fontawesome
gulp.task('font-fontawesome', function() {
  return gulp.src(['node_modules/@fortawesome/fontawesome-free/webfonts/*'])
  	.pipe(gulp.dest(distPath + '/assets/webfonts/'));
});

// 02. font-bootstrap
gulp.task('font-bootstrap', function() {
	return gulp.src(['node_modules/bootstrap-icons/font/fonts/*'])
  	.pipe(gulp.dest(distPath + '/assets/css/fonts/'));
});

// 03. js-demo
gulp.task('js-demo', function(){
	return gulp.src([ 'js/demo/**' ])
		.pipe(gulp.dest(distPath + '/assets/js/demo'));
});

// 04. js-theme
gulp.task('js-theme', function(){
	return gulp.src([ 'js/demo/**' ])
		.pipe(gulp.dest(distPath + '/assets/js/theme'));
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
		.pipe(gulp.dest(distPath + '/assets/js/'))
		.pipe(livereload());
});

// 06. js-app
gulp.task('js-app', function(){
  return gulp.src([
  	'js/app.js',
  	])
    .pipe(sourcemaps.init())
    .pipe(concat('app.min.js'))
    .pipe(sourcemaps.write())
    .pipe(uglify())
    .pipe(gulp.dest(distPath + '/assets/js/'))
    .pipe(livereload());
});

// 07. plugins
gulp.task('plugins', function() {
	var pluginFiles = [
  	'node_modules/@fortawesome/*',
  	'node_modules/perfect-scrollbar/*',
  	'node_modules/animate.css/*',
  	'node_modules/pace-js/*',
  	'node_modules/jquery/dist/*',
  	'node_modules/jquery-ui-dist/*',
  	'node_modules/bootstrap/dist/js/*',
  	'node_modules/bootstrap-icons/**',
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
	]).pipe(gulp.dest(distPath + '/assets/plugins/countdown/'));
	download([
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/js/jquery.superbox.min.js',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/superbox.min.css'
	]).pipe(gulp.dest(distPath + '/assets/plugins/superbox/'));
	download([
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.eot',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.svg',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.ttf',
		'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.woff'
	]).pipe(gulp.dest(distPath + '/assets/plugins/superbox/font/'));
	download([
		'https://unpkg.com/ionicons@4.2.6/dist/css/ionicons.min.css'
	]).pipe(gulp.dest(distPath + '/assets/plugins/ionicons/css/'));
	download([
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.eot',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff2',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.ttf',
		'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.svg'
	]).pipe(gulp.dest(distPath + '/assets/plugins/ionicons/fonts'));
	download([
		'http://lab.xero.nu/bootstrap_calendar/lib/css/bootstrap_calendar.css'
	]).pipe(gulp.dest(distPath + '/assets/plugins/bootstrap-calendar/css/'));
	download([
		'http://lab.xero.nu/bootstrap_calendar/lib/js/bootstrap_calendar.min.js'
	]).pipe(gulp.dest(distPath + '/assets/plugins/bootstrap-calendar/js/'));
	download([
		'https://jvectormap.com/js/jquery-jvectormap-world-mill.js'
	]).pipe(gulp.dest(distPath + '/assets/plugins/jvectormap-next/'));
	
	return gulp.src(pluginFiles, { base: './node_modules/' })
		.pipe(gulp.dest(distPath + '/assets/plugins'));
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
		.pipe(gulp.dest(distPath + '/assets/css/'))
		.pipe(livereload());
});


// 09. html-default
gulp.task('html-default', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'default'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_html'))
    .pipe(livereload());
});

// 10. html-default-startup
gulp.task('html-default-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'default'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_html_startup'))
    .pipe(livereload());
});

// 11. css-default
gulp.task('css-default', function(){
  return gulp.src([
			'scss/default/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest(distPath + '/assets/css/default/'))
		.pipe(livereload());
});

// 12. css-default-rtl
gulp.task('css-default-rtl', function(){
	return gulp.src([
			'scss/default/styles.scss'
		])
		.pipe(header('$enable-rtl: true;'))
		.pipe(sass())
		.pipe(concat('app-rtl.min.css'))
		.pipe(minifyCSS())
		.pipe(gulp.dest(distPath + '/assets/css/default/'))
		.pipe(livereload());
});

// 13. css-default-theme
gulp.task('css-default-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/default/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/default/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 14. css-default-image
gulp.task('css-default-image', function(){
	return gulp.src([ 'scss/default/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/default/images'));
});

// 15. watch-default
gulp.task('watch-default', function () {
	livereload.listen();
	
  gulp.watch('html/**/**/*.html', gulp.series(gulp.parallel(['html-default'])));
  gulp.watch('html-startup/**/*.html', gulp.series(gulp.parallel(['html-default-startup'])));
  gulp.watch('scss/default/**/*.scss', gulp.series(gulp.parallel(['css-default', 'css-default-theme'])));
  gulp.watch('js/**/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 16. webserver-default
gulp.task('webserver-default', function() {
	connect.server({
		name: 'Color Admin',
		root: [distPath, distPath + '/template_html/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 17. webserver-default-startup
gulp.task('webserver-default-startup', function() {
	connect.server({
		name: 'Color Admin',
		root: [distPath, distPath + '/template_html_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 18. default
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
	'css-default-image', 
	'html-default', 
	'html-default-startup', 
	'webserver-default', 
	'watch-default'
])));

// 19. default-startup
gulp.task('default-startup', gulp.series(gulp.parallel([
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
	'css-default-image', 
	'html-default', 
	'html-default-startup', 
	'webserver-default-startup', 
	'watch-default'
])));



// 20. html-material
gulp.task('html-material', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'material'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_material'))
    .pipe(livereload());
});

// 21. html-material-startup
gulp.task('html-material-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'material'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_material_startup'))
    .pipe(livereload());
});

// 22. css-material
gulp.task('css-material', function(){
  return gulp.src([
			'scss/material/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/assets/css/material/'))
		.pipe(livereload());
});

// 23. css-material-rtl
gulp.task('css-material-rtl', function(){
	return gulp.src([
		'scss/material/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/assets/css/material/'));
});

// 24. css-material-theme
gulp.task('css-material-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/material/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/material/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 25. css-material-image
gulp.task('css-material-image', function(){
	return gulp.src([ 'scss/material/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/material/images'));
});

// 26. watch-material
gulp.task('watch-material', function () {
	livereload.listen();
  gulp.watch('html/**/**.html', gulp.series(gulp.parallel(['html-material'])));
  gulp.watch('html-startup/**/**.html', gulp.series(gulp.parallel(['html-material-startup'])));
  gulp.watch('scss/**/**.scss', gulp.series(gulp.parallel(['css-material', 'css-material-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 27. webserver-material
gulp.task('webserver-material', function() {
	connect.server({
		name: 'Color Admin Material',
		root: [distPath, distPath + '/template_material/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 28. webserver-material-startup
gulp.task('webserver-material-startup', function() {
	connect.server({
		name: 'Color Admin Material',
		root: [distPath, distPath + '/template_material_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 29. material
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
	'css-material-image', 
	'html-material', 
	'html-material-startup', 
	'webserver-material', 
	'watch-material'
])));

// 30. material-startup
gulp.task('material-startup', gulp.series(gulp.parallel([
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
	'css-material-image', 
	'html-material', 
	'html-material-startup', 
	'webserver-material-startup', 
	'watch-material'
])));



// 31. html-apple
gulp.task('html-apple', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'apple'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_apple'))
    .pipe(livereload());
});

// 32. html-apple-startup
gulp.task('html-apple-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'apple'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_apple_startup'))
    .pipe(livereload());
});

// 33. css-apple
gulp.task('css-apple', function(){
  return gulp.src([
			'scss/apple/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/assets/css/apple/'))
		.pipe(livereload());
});

// 34. css-apple-rtl
gulp.task('css-apple-rtl', function(){
	return gulp.src([
		'scss/apple/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/assets/css/apple/'));
});

// 35. css-apple-theme
gulp.task('css-apple-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/apple/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/apple/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 36. css-apple-image
gulp.task('css-apple-image', function(){
	return gulp.src([ 'scss/apple/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/apple/images'));
});

// 37. watch-apple
gulp.task('watch-apple', function () {
	livereload.listen();
  gulp.watch('html/**/**.html', gulp.series(gulp.parallel(['html-apple'])));
  gulp.watch('html-startup/**/**.html', gulp.series(gulp.parallel(['html-apple-startup'])));
  gulp.watch('scss/**/**.scss', gulp.series(gulp.parallel(['css-apple', 'css-apple-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 38. webserver-apple
gulp.task('webserver-apple', function() {
	connect.server({
		name: 'Color Admin Apple',
		root: [distPath, distPath + '/template_apple/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 39. webserver-apple-startup
gulp.task('webserver-apple-startup', function() {
	connect.server({
		name: 'Color Admin Apple',
		root: [distPath, distPath + '/template_apple_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 40. apple
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
	'css-apple-image', 
	'html-apple', 
	'html-apple-startup', 
	'webserver-apple', 
	'watch-apple'
])));

// 41. apple-startup
gulp.task('apple-startup', gulp.series(gulp.parallel([
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
	'css-apple-image', 
	'html-apple', 
	'html-apple-startup', 
	'webserver-apple-startup', 
	'watch-apple'
])));



// 42. html-transparent
gulp.task('html-transparent', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'transparent'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_transparent'))
    .pipe(livereload());
});

// 43. html-transparent-startup
gulp.task('html-transparent-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'transparent'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_transparent_startup'))
    .pipe(livereload());
});

// 44. css-transparent
gulp.task('css-transparent', function(){
  return gulp.src([
			'scss/transparent/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/assets/css/transparent/'))
		.pipe(livereload());
});

// 45. css-transparent-rtl
gulp.task('css-transparent-rtl', function(){
	return gulp.src([
		'scss/transparent/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/assets/css/transparent/'));
});

// 46. css-transparent-theme
gulp.task('css-transparent-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/transparent/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/transparent/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 47. css-transparent-image
gulp.task('css-transparent-image', function(){
	return gulp.src([ 'scss/transparent/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/transparent/images'));
});

// 48. watch-transparent
gulp.task('watch-transparent', function () {
	livereload.listen();
  gulp.watch('html/**/**.html', gulp.series(gulp.parallel(['html-transparent'])));
  gulp.watch('html-startup/**/**.html', gulp.series(gulp.parallel(['html-transparent-startup'])));
  gulp.watch('scss/**/**.scss', gulp.series(gulp.parallel(['css-transparent', 'css-transparent-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 49. webserver-transparent
gulp.task('webserver-transparent', function() {
	connect.server({
		name: 'Color Admin Transparent',
		root: [distPath, distPath + '/template_transparent/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 50. webserver-transparent-startup
gulp.task('webserver-transparent-startup', function() {
	connect.server({
		name: 'Color Admin Transparent',
		root: [distPath, distPath + '/template_transparent_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 51. transparent
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
	'css-transparent-image', 
	'html-transparent', 
	'html-transparent-startup', 
	'webserver-transparent', 
	'watch-transparent'
])));

// 52. transparent-startup
gulp.task('transparent-startup', gulp.series(gulp.parallel([
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
	'css-transparent-image', 
	'html-transparent', 
	'html-transparent-startup', 
	'webserver-transparent-startup', 
	'watch-transparent'
])));



// 53. html-facebook
gulp.task('html-facebook', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'facebook'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_facebook'))
    .pipe(livereload());
});

// 54. html-facebook-startup
gulp.task('html-facebook-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'facebook'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_facebook_startup'))
    .pipe(livereload());
});

// 55. css-facebook
gulp.task('css-facebook', function(){
  return gulp.src([
			'scss/facebook/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/assets/css/facebook/'))
		.pipe(livereload());
});

// 56. css-facebook-rtl
gulp.task('css-facebook-rtl', function(){
	return gulp.src([
		'scss/facebook/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/assets/css/facebook/'));
});

// 57. css-facebook-theme
gulp.task('css-facebook-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/facebook/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/facebook/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 58. css-facebook-image
gulp.task('css-facebook-image', function(){
	return gulp.src([ 'scss/facebook/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/facebook/images'));
});

// 59. watch-facebook
gulp.task('watch-facebook', function () {
	livereload.listen();
  gulp.watch('html/**/**.html', gulp.series(gulp.parallel(['html-facebook'])));
  gulp.watch('html-startup/**/**.html', gulp.series(gulp.parallel(['html-facebook-startup'])));
  gulp.watch('scss/**/**.scss', gulp.series(gulp.parallel(['css-facebook', 'css-facebook-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 60. webserver-facebook
gulp.task('webserver-facebook', function() {
	connect.server({
		name: 'Color Admin Facebook',
		root: [distPath, distPath + '/template_facebook/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 61. webserver-facebook-startup
gulp.task('webserver-facebook-startup', function() {
	connect.server({
		name: 'Color Admin Facebook',
		root: [distPath, distPath + '/template_facebook_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 62. facebook
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
	'css-facebook-image', 
	'html-facebook', 
	'html-facebook-startup', 
	'webserver-facebook', 
	'watch-facebook'
])));

// 63. facebook-startup
gulp.task('facebook-startup', gulp.series(gulp.parallel([
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
	'css-facebook-image', 
	'html-facebook', 
	'html-facebook-startup', 
	'webserver-facebook-startup', 
	'watch-facebook'
])));



// 64. html-google
gulp.task('html-google', function() {
  return gulp.src(['./html/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'google'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_google'))
    .pipe(livereload());
});

// 65. html-google-startup
gulp.task('html-google-startup', function() {
  return gulp.src(['./html-startup/*.html'])
    .pipe(fileinclude({
      prefix: '@@',
      basepath: '@file',
      rootPath: './',
      context: {
      	theme: 'google'
      }
    }))
    .pipe(gulp.dest(distPath + '/template_google_startup'))
    .pipe(livereload());
});

// 66. css-google
gulp.task('css-google', function(){
  return gulp.src([
			'scss/google/styles.scss'
		])
		.pipe(sass())
		.pipe(concat('app.min.css'))
		.pipe(gulp.dest(distPath + '/assets/css/google/'))
		.pipe(livereload());
});

// 67. css-google-rtl
gulp.task('css-google-rtl', function(){
	return gulp.src([
		'scss/google/styles.scss'
	])
	.pipe(header('$enable-rtl: true;'))
	.pipe(sass())
	.pipe(concat('app-rtl.min.css'))
	.pipe(minifyCSS())
	.pipe(gulp.dest(distPath + '/assets/css/google/'));
});

// 68. css-google-theme
gulp.task('css-google-theme', function(){
	var colorList = ['red','pink','orange','yellow','lime','green','teal','cyan','blue','purple','indigo','black'];
	
	var tasks = colorList.map(function (color) {
		return gulp.src([ 'scss/google/theme.scss' ])
			.pipe(header('$primary-color: \''+ color +'\';'))
			.pipe(sass())
			.pipe(concat(color +'.min.css'))
			.pipe(minifyCSS())
			.pipe(gulp.dest(distPath + '/assets/css/google/theme/'));
  });
	console.log('Generating the css files. Please wait...');
  return merge(tasks);
});

// 69. css-google-image
gulp.task('css-google-image', function(){
	return gulp.src([ 'scss/google/images/**' ])
		.pipe(gulp.dest(distPath + '/assets/css/google/images'));
});

// 70. watch-google
gulp.task('watch-google', function () {
	livereload.listen();
  gulp.watch('html/**/**.html', gulp.series(gulp.parallel(['html-google'])));
  gulp.watch('html-startup/**/**.html', gulp.series(gulp.parallel(['html-google-startup'])));
  gulp.watch('scss/**/**.scss', gulp.series(gulp.parallel(['css-google', 'css-google-theme'])));
  gulp.watch('js/*.js', gulp.series(gulp.parallel(['js-app', 'js-demo'])));
});

// 71. webserver-google
gulp.task('webserver-google', function() {
	connect.server({
		name: 'Color Admin Google',
		root: [distPath, distPath + '/template_google/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 72. webserver-google-startup
gulp.task('webserver-google-startup', function() {
	connect.server({
		name: 'Color Admin Google',
		root: [distPath, distPath + '/template_google_startup/'],
		port: 8000,
		livereload: true,
		fallback: 'index.html'
	});
});

// 73. google
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
	'css-google-image', 
	'html-google', 
	'html-google-startup', 
	'webserver-google', 
	'watch-google'
])));

// 74. google-startup
gulp.task('google-startup', gulp.series(gulp.parallel([
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
	'css-google-image', 
	'html-google', 
	'html-google-startup', 
	'webserver-google-startup', 
	'watch-google'
])));