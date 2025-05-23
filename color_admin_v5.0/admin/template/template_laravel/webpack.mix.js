const mix = require('laravel-mix');
require('laravel-mix-dload');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// vendor css
mix.styles([
	'node_modules/animate.css/animate.min.css',
	'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
	'node_modules/jquery-ui-dist/jquery-ui.min.css',
	'node_modules/pace-js/themes/black/pace-theme-flash.css',
	'node_modules/perfect-scrollbar/css/perfect-scrollbar.css'
], 'public/assets/css/vendor.min.css');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts/', 'public/assets/webfonts/');


// core css
var theme = 'default';
mix.sass('resources/scss/'+ theme +'/styles.scss', 'public/assets/css/app.min.css');
mix.copy('resources/scss/'+ theme +'/images/', 'public/assets/css/images/');


// vendor js
mix.combine([
	'node_modules/pace-js/pace.min.js',
	'node_modules/jquery/dist/jquery.min.js',
	'node_modules/jquery-ui-dist/jquery-ui.min.js',
	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
	'node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js',
	'node_modules/js-cookie/src/js.cookie.js'
], 'public/assets/js/vendor.min.js');


// core js
mix.combine([
	'resources/js/app.js'
], 'public/assets/js/app.min.js');


// plugins
mix.copy('node_modules/pace-js', 'public/assets/plugins/pace-js');
mix.copy('node_modules/jquery/dist', 'public/assets/plugins/jquery/dist');
mix.copy('node_modules/jquery-ui-dist', 'public/assets/plugins/jquery-ui-dist');
mix.copy('node_modules/bootstrap/dist', 'public/assets/plugins/bootstrap/dist');
mix.copy('node_modules/js-cookie/src', 'public/assets/plugins/js-cookie/src');
mix.copy('node_modules/apexcharts/dist', 'public/assets/plugins/apexcharts/dist');
mix.copy('node_modules/lity/dist', 'public/assets/plugins/lity/dist');
mix.copy('node_modules/x-editable-bs4/dist', 'public/assets/plugins/x-editable-bs4/dist');
mix.copy('node_modules/dropzone/dist', 'public/assets/plugins/dropzone/dist');
mix.copy('node_modules/@fullcalendar', 'public/assets/plugins/@fullcalendar');
mix.copy('node_modules/chart.js/dist', 'public/assets/plugins/chart.js/dist');
mix.copy('node_modules/raphael', 'public/assets/plugins/raphael');
mix.copy('node_modules/tag-it', 'public/assets/plugins/tag-it');
mix.copy('node_modules/jquery-migrate/dist', 'public/assets/plugins/jquery-migrate/dist');
mix.copy('node_modules/jquery-mockjax/dist', 'public/assets/plugins/jquery-mockjax/dist');
mix.copy('node_modules/x-editable-bs4/dist', 'public/assets/plugins/x-editable-bs4/dist');
mix.copy('node_modules/blueimp-file-upload', 'public/assets/plugins/blueimp-file-upload');
mix.copy('node_modules/blueimp-canvas-to-blob', 'public/assets/plugins/blueimp-canvas-to-blob');
mix.copy('node_modules/blueimp-gallery', 'public/assets/plugins/blueimp-gallery');
mix.copy('node_modules/blueimp-load-image', 'public/assets/plugins/blueimp-load-image');
mix.copy('node_modules/blueimp-tmpl', 'public/assets/plugins/blueimp-tmpl');
mix.copy('node_modules/abpetkov-powerange/dist', 'public/assets/plugins/abpetkov-powerange/dist');
mix.copy('node_modules/bootstrap3-wysihtml5-bower/dist', 'public/assets/plugins/bootstrap3-wysihtml5-bower/dist');
mix.copy('node_modules/summernote/dist', 'public/assets/plugins/summernote/dist');
mix.copy('node_modules/parsleyjs/dist', 'public/assets/plugins/parsleyjs/dist');
mix.copy('node_modules/flot', 'public/assets/plugins/flot');
mix.copy('node_modules/ckeditor', 'public/assets/plugins/ckeditor');
mix.copy('node_modules/jvectormap-next', 'public/assets/plugins/jvectormap-next');
mix.copy('node_modules/moment', 'public/assets/plugins/moment');
mix.copy('node_modules/d3', 'public/assets/plugins/d3');
mix.copy('node_modules/nvd3/build', 'public/assets/plugins/nvd3/build');
mix.copy('node_modules/simple-line-icons', 'public/assets/plugins/simple-line-icons');
mix.copy('node_modules/jquery-knob/dist', 'public/assets/plugins/jquery-knob/dist');
mix.copy('node_modules/sweetalert/dist', 'public/assets/plugins/sweetalert/dist');
mix.copy('node_modules/clipboard/dist', 'public/assets/plugins/clipboard/dist');
mix.copy('node_modules/jstree/dist', 'public/assets/plugins/jstree/dist');
mix.copy('node_modules/gritter', 'public/assets/plugins/gritter');
mix.copy('node_modules/datatables.net', 'public/assets/plugins/datatables.net');
mix.copy('node_modules/datatables.net-bs4', 'public/assets/plugins/datatables.net-bs4');
mix.copy('node_modules/datatables.net-responsive', 'public/assets/plugins/datatables.net-responsive');
mix.copy('node_modules/datatables.net-responsive-bs4', 'public/assets/plugins/datatables.net-responsive-bs4');
mix.copy('node_modules/datatables.net-autofill', 'public/assets/plugins/datatables.net-autofill');
mix.copy('node_modules/datatables.net-autofill-bs4', 'public/assets/plugins/datatables.net-autofill-bs4');
mix.copy('node_modules/datatables.net-buttons', 'public/assets/plugins/datatables.net-buttons');
mix.copy('node_modules/datatables.net-buttons-bs4', 'public/assets/plugins/datatables.net-buttons-bs4');
mix.copy('node_modules/datatables.net-colreorder', 'public/assets/plugins/datatables.net-colreorder');
mix.copy('node_modules/datatables.net-colreorder-bs4', 'public/assets/plugins/datatables.net-colreorder-bs4');
mix.copy('node_modules/datatables.net-fixedcolumns', 'public/assets/plugins/datatables.net-fixedcolumns');
mix.copy('node_modules/datatables.net-fixedcolumns-bs4', 'public/assets/plugins/datatables.net-fixedcolumns-bs4');
mix.copy('node_modules/datatables.net-fixedheader', 'public/assets/plugins/datatables.net-fixedheader');
mix.copy('node_modules/datatables.net-fixedheader-bs4', 'public/assets/plugins/datatables.net-fixedheader-bs4');
mix.copy('node_modules/datatables.net-keytable', 'public/assets/plugins/datatables.net-keytable');
mix.copy('node_modules/datatables.net-keytable-bs4', 'public/assets/plugins/datatables.net-keytable-bs4');
mix.copy('node_modules/datatables.net-rowreorder', 'public/assets/plugins/datatables.net-rowreorder');
mix.copy('node_modules/datatables.net-rowreorder-bs4', 'public/assets/plugins/datatables.net-rowreorder-bs4');
mix.copy('node_modules/datatables.net-scroller', 'public/assets/plugins/datatables.net-scroller');
mix.copy('node_modules/datatables.net-scroller-bs4', 'public/assets/plugins/datatables.net-scroller-bs4');
mix.copy('node_modules/datatables.net-select', 'public/assets/plugins/datatables.net-select');
mix.copy('node_modules/datatables.net-select-bs4', 'public/assets/plugins/datatables.net-select-bs4');
mix.copy('node_modules/pdfmake/build', 'public/assets/plugins/pdfmake/build');
mix.copy('node_modules/jszip/dist/', 'public/assets/plugins/jszip/dist/');
mix.copy('node_modules/bootstrap-datepicker/dist', 'public/assets/plugins/bootstrap-datepicker/dist');
mix.copy('node_modules/bootstrap-timepicker', 'public/assets/plugins/bootstrap-timepicker');
mix.copy('node_modules/isotope-layout/dist', 'public/assets/plugins/isotope-layout/dist');
mix.copy('node_modules/lightbox2/dist', 'public/assets/plugins/lightbox2/dist');
mix.copy('node_modules/bootstrap-datetime-picker', 'public/assets/plugins/bootstrap-datetime-picker');
mix.copy('node_modules/masonry-layout/dist', 'public/assets/plugins/masonry-layout/dist');
mix.copy('node_modules/select2/dist', 'public/assets/plugins/select2/dist');
mix.copy('node_modules/select-picker/dist', 'public/assets/plugins/select-picker/dist');
mix.copy('node_modules/jvectormap-next', 'public/assets/plugins/jvectormap-next');
mix.copy('node_modules/spectrum-colorpicker2/dist', 'public/assets/plugins/spectrum-colorpicker2/dist');
mix.copy('node_modules/jquery.maskedinput/src', 'public/assets/plugins/jquery.maskedinput/src');
mix.copy('node_modules/ion-rangeslider', 'public/assets/plugins/ion-rangeslider');
mix.copy('node_modules/bootstrap-daterangepicker', 'public/assets/plugins/bootstrap-daterangepicker');
mix.copy('node_modules/flag-icon-css', 'public/assets/plugins/flag-icon-css');
mix.copy('node_modules/jquery-sparkline', 'public/assets/plugins/jquery-sparkline');
mix.copy('node_modules/bootstrap-social', 'public/assets/plugins/bootstrap-social');
mix.copy('node_modules/intro.js/minified', 'public/assets/plugins/intro.js/minified');
mix.copy('node_modules/angular', 'public/assets/plugins/angular');
mix.copy('node_modules/angular-ui-router', 'public/assets/plugins/angular-ui-router');
mix.copy('node_modules/angular-ui-bootstrap', 'public/assets/plugins/angular-ui-bootstrap');
mix.copy('node_modules/oclazyload/dist', 'public/assets/plugins/oclazyload/dist');
mix.copy('node_modules/switchery/dist', 'public/assets/plugins/switchery/dist');
mix.copy('node_modules/lightbox2/dist', 'public/assets/plugins/lightbox2/dist');
mix.copy('node_modules/@highlightjs', 'public/assets/plugins/@highlightjs');
mix.download({
	enabled: true,
	urls: [{
		'url': 'https://raw.githubusercontent.com/kbwood/countdown/master/dist/js/jquery.plugin.min.js', 
		'dest': 'public/assets/plugins/countdown/'
	},{
		'url': 'https://raw.githubusercontent.com/kbwood/countdown/master/dist/js/jquery.countdown.min.js', 
		'dest': 'public/assets/plugins/countdown/'
	},{
		'url': 'https://raw.githubusercontent.com/kbwood/countdown/master/dist/css/jquery.countdown.css', 
		'dest': 'public/assets/plugins/countdown/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/js/jquery.superbox.min.js', 
		'dest': 'public/assets/plugins/superbox/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/superbox.min.css', 
		'dest': 'public/assets/plugins/superbox/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.eot', 
		'dest': 'public/assets/plugins/superbox/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.svg', 
		'dest': 'public/assets/plugins/superbox/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.ttf', 
		'dest': 'public/assets/plugins/superbox/'
	},{
		'url': 'https://raw.githubusercontent.com/seyDoggy/superbox/master/css/font/superboxicons.woff', 
		'dest': 'public/assets/plugins/superbox/font/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/css/ionicons.min.css', 
		'dest': 'public/assets/plugins/ionicons/css/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.eot', 
		'dest': 'public/assets/plugins/ionicons/fonts/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff2', 
		'dest': 'public/assets/plugins/ionicons/fonts/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.woff', 
		'dest': 'public/assets/plugins/ionicons/fonts/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.ttf', 
		'dest': 'public/assets/plugins/ionicons/fonts/'
	},{
		'url': 'https://unpkg.com/ionicons@4.2.6/dist/fonts/ionicons.svg', 
		'dest': 'public/assets/plugins/ionicons/fonts/'
	},{
		'url': 'http://lab.xero.nu/bootstrap_calendar/lib/css/bootstrap_calendar.css', 
		'dest': 'public/assets/plugins/bootstrap-calendar/css/'
	},{
		'url': 'http://lab.xero.nu/bootstrap_calendar/lib/js/bootstrap_calendar.min.js', 
		'dest': 'public/assets/plugins/bootstrap-calendar/js/'
	},{
		'url': 'https://jvectormap.com/js/jquery-jvectormap-world-mill.js', 
		'dest': 'public/assets/plugins/jvectormap-next/'
	}]
});