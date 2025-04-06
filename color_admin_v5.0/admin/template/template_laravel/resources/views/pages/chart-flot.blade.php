@extends('layouts.default')

@section('title', 'Flot Chart')

@push('scripts')
	<script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"></script>
	<script src="/assets/plugins/flot/source/jquery.colorhelpers.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.saturated.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.browser.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.time.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.resize.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.pie.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.crosshair.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.categories.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.navigate.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.touchNavigate.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.hover.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.touch.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.selection.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.symbol.js"></script>
	<script src="/assets/plugins/flot/source/jquery.flot.legend.js"></script>
	<script src="/assets/js/demo/chart-flot.demo.js"></script>
	<script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
	<script src="/assets/js/demo/render.highlight.js"></script>
@endpush

@section('content')
	<!-- BEGIN breadcrumb -->
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Chart</a></li>
		<li class="breadcrumb-item active">Flot Chart</li>
	</ol>
	<!-- END breadcrumb -->
	<!-- BEGIN page-header -->
	<h1 class="page-header">Flot Chart <small>header small text goes here...</small></h1>
	<!-- END page-header -->
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-1">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Basic Line Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						Create a placeholder, make sure it has dimensions (so Flot knows at what size to draw the plot), then call the plot function with your data.
						The <code>axes</code> are automatically scaled.
					</p>
					<div id="basic-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="basic-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var d1 = d2 = d3 = [];
  for (var x = 0; x &lt; Math.PI * 2; x += 0.25) {
    d1.push([x, Math.sin(x)]);
    d2.push([x, Math.cos(x)]);
  }
  for (var z = 0; z &lt; Math.PI * 2; z += 0.1) {
    d3.push([z, Math.tan(z)]);
  }
  $.plot($('#basic-chart'), [
    { label: 'data 1',  data: d1, color: COLOR_BLUE, shadowSize: 0 },
    { label: 'data 2',  data: d2, color: COLOR_GREEN, shadowSize: 0 }
  ], {
    series: {
      lines: { show: true },
      points: { show: false }
    },
    xaxis: {
      min: 0,
      max: 6,
      tickColor: COLOR_SILVER_TRANSPARENT_3,
    },
    yaxis: {
      min: -2,
      max: 2,
      tickColor: COLOR_SILVER_TRANSPARENT_3
    },
    grid: {
      borderColor: COLOR_SILVER_TRANSPARENT_5,
      borderWidth: 1,
      backgroundColor: COLOR_SILVER_TRANSPARENT_1
    }
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-2">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Interactive Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						One of the goals of Flot is to support user interactions. Try pointing and clicking on the points and you will see a jQuery created <code>tooltip</code>.
					</p>
					<div id="interactive-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="interactive-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var d1 = [[0, 42], [1, 53], [2,66], [3, 60], [4, 68], [5, 66], [6,71],[7, 75], [8, 69], [9,70], [10, 68], [11, 72], [12, 78], [13, 86]];
  var d2 = [[0, 12], [1, 26], [2,13], [3, 18], [4, 35], [5, 23], [6, 18],[7, 35], [8, 24], [9,14], [10, 14], [11, 29], [12, 30], [13, 43]];

  $.plot($('#interactive-chart'), [{
    data: d1, 
    label: 'Page Views', 
    color: COLOR_BLUE,
    lines: { show: true, fill:false, lineWidth: 2.5 },
    points: { show: true, radius: 5, fillColor: COLOR_WHITE },
    shadowSize: 0
  }, {
    data: d2,
    label: 'Visitors',
    color: COLOR_GREEN,
    lines: { show: true, fill:false, lineWidth: 2.5, fillColor: '' },
    points: { show: true, radius: 5, fillColor: COLOR_WHITE },
    shadowSize: 0
  }], {
    xaxis: {  tickColor: COLOR_SILVER_TRANSPARENT_3,tickSize: 2 },
    yaxis: {  tickColor: COLOR_SILVER_TRANSPARENT_3, tickSize: 20 },
    grid: { 
      hoverable: true, 
      clickable: true,
      tickColor: COLOR_SILVER_TRANSPARENT_3,
      borderWidth: 1,
      borderColor: COLOR_SILVER_TRANSPARENT_5,
      backgroundColor: COLOR_SILVER_TRANSPARENT_1
    },
    legend: {
      noColumns: 1,
      show: true
    }
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
	</div>
	<!-- END row -->
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-3">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Bar Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						Flot supports lines, points, filled areas, bars and any combinations of these, in the same plot and even on the same data series.
					</p>
					<div id="bar-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="bar-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var data = [[0, 10], [1, 8], [2, 4], [3, 13], [4, 17], [5, 9]];
  var ticks = [[0, 'JAN'], [1, 'FEB'], [2, 'MAR'], [3, 'APR'], [4, 'MAY'], [5, 'JUN']];
  $.plot('#bar-chart', [{ label: 'Bounce Rate', data: data, color: COLOR_DARK_LIGHTER }], {
    series: {
      bars: {
        show: true,
        barWidth: 0.6,
        align: 'center',
        fill: true,
        fillColor: COLOR_DARK_LIGHTER,
        zero: true
      }
    },
    xaxis: {
      tickColor: COLOR_SILVER_TRANSPARENT_3,
      autoscaleMargin: 0.05,
      ticks: ticks
    },
    yaxis: {
      tickColor: COLOR_SILVER_TRANSPARENT_3
    },
    grid: {
      borderColor: COLOR_SILVER_TRANSPARENT_5,
      borderWidth: 1,
      backgroundColor: COLOR_SILVER_TRANSPARENT_1
    },
    legend: {
      noColumns: 0
    },
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-4">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Interactive Pie Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>The pie can be made interactive with <code>hover</code> and <code>click</code> events. It will alert the value for each categories</p>
					<div id="interactive-pie-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.pie.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.time.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.resize.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.touchNavigate.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.hover.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.touch.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.selection.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.symbol.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.legend.js"&gt;&lt;/script&gt;

&lt;div id="interactive-pie-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var data = [];
  var series = 3;
  var colorArray = [COLOR_ORANGE, COLOR_DARK_LIGHTER, COLOR_GREY];
  for( var i = 0; i < series; i++) {
    data[i] = { label: 'Series'+(i+1), data: Math.floor(Math.random()*100)+1, color: colorArray[i]};
  }
  $.plot($('#interactive-pie-chart'), data, {
    series: {
      pie: { 
        show: true
      }
    },
    grid: {
      hoverable: true,
      clickable: true
    }
  });
  $('#interactive-pie-chart').bind('plotclick', function(event, pos, obj) {
    if (!obj) {
      return;
    }
    var percent = parseFloat(obj.series.percent).toFixed(2);
    alert(obj.series.label + ': ' + percent + '%');
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
	</div>
	<!-- END row -->
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-5">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Live Updated Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						You can <code>update</code> a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.
					</p>
					<div id="live-updated-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="live-updated-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  function update() {
    plot.setData([ getRandomData() ]);
    plot.draw();
    setTimeout(update, updateInterval);
  }
    
  function getRandomData() {
    if (data.length > 0) {
      data = data.slice(1);
    }
    while (data.length < totalPoints) {
      var prev = data.length > 0 ? data[data.length - 1] : 50;
      var y = prev + Math.random() * 10 - 5;
      if (y < 0) {
        y = 0;
      }
      if (y > 100) {
        y = 100;
      }
      data.push(y);
    }
    var res = [];
    for (var i = 0; i < data.length; ++i) {
      res.push([i, data[i]]);
    }
    return res;
  }
  
  var data = [], totalPoints = 150;
  var updateInterval = 1000;

  $('#updateInterval').val(updateInterval).change(function () {
    var v = $(this).val();
    if (v && !isNaN(+v)) {
      updateInterval = +v;
      if (updateInterval < 1) {
        updateInterval = 1;
      }
      if (updateInterval > 2000) {
        updateInterval = 2000;
      }
      $(this).val('' + updateInterval);
    }
  });

  var plot = $.plot($('#live-updated-chart'), [{ label: 'Server stats', data: getRandomData() }], {
    series: { 
      shadowSize: 0, 
      color: COLOR_GREEN, 
      lines: { 
        show: true, 
        fill:true 
      } 
    },
    yaxis: { 
      min: 0, 
      max: 100, 
      tickColor: COLOR_SILVER_TRANSPARENT_3 
    },
    xaxis: { 
      show: true, 
      tickColor: COLOR_SILVER_TRANSPARENT_3 
    },
    grid: {
      borderWidth: 1,
      borderColor: COLOR_SILVER_TRANSPARENT_5,
      backgroundColor: COLOR_SILVER_TRANSPARENT_1
    },
    legend: {
      noColumns: 1,
      show: true
    }
  });

  update();
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-6">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Stacked Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						This is useful if you wish to display both a <code>total</code> and the <code>constituents</code> it is made of. The only requirement is that you provide the input sorted on x.
					</p>
					<div id="stacked-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="stacked-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var d1 = [];
  for (var a = 0; a <= 5; a += 1) {
    d1.push([a, parseInt(Math.random() * 5)]);
  }
  var d2 = [];
  for (var b = 0; b <= 5; b += 1) {
    d2.push([b, parseInt(Math.random() * 5 + 5)]);
  }
  var d3 = [];
  for (var c = 0; c <= 5; c += 1) {
    d3.push([c, parseInt(Math.random() * 5 + 5)]);
  }
  var d4 = [];
  for (var d = 0; d <= 5; d += 1) {
    d4.push([d, parseInt(Math.random() * 5 + 5)]);
  }
  var d5 = [];
  for (var e = 0; e <= 5; e += 1) {
    d5.push([e, parseInt(Math.random() * 5 + 5)]);
  }
  var d6 = [];
  for (var f = 0; f <= 5; f += 1) {
    d6.push([f, parseInt(Math.random() * 5 + 5)]);
  }
    
  var xData = [{
    data:d1,
    color: COLOR_DARK_LIGHTER,
    label: 'China',
    bars: { fillColor: COLOR_DARK_LIGHTER }
  }, {
    data:d2,
    color: COLOR_GREY,
    label: 'Russia',
    bars: { fillColor: COLOR_GREY }
  }, {
    data:d3,
    color: COLOR_WHITE,
    label: 'Canada',
    bars: { fillColor: COLOR_WHITE }
  }, {
    data:d4,
    color: COLOR_PURPLE,
    label: 'Japan',
    bars: { fillColor: COLOR_PURPLE }
  }, {
    data:d5,
    color: COLOR_BLUE,
    label: 'USA',
    bars: { fillColor: COLOR_BLUE }
  }, {
    data:d6,
    color: COLOR_AQUA,
    label: 'Others',
    bars: { fillColor: COLOR_AQUA }
  }];

  $.plot('#stacked-chart', xData, { 
    xaxis: {  
      tickColor: COLOR_SILVER_TRANSPARENT_3,  
      ticks: [[0, 'MON'], [1, 'TUE'], [2, 'WED'], [3, 'THU'], [4, 'FRI'], [5, 'SAT']],
      autoscaleMargin: 0.05
    },
    yaxis: { tickColor: COLOR_SILVER_TRANSPARENT_3, ticksLength: 5},
    grid: { 
      hoverable: true, 
      tickColor: COLOR_SILVER_TRANSPARENT_3,
      borderWidth: 1,
      borderColor: COLOR_SILVER_TRANSPARENT_5,
      backgroundColor: COLOR_SILVER_TRANSPARENT_1
    },
    series: {
      stack: true,
      lines: { show: false, fill: false, steps: false },
      bars: { show: true, barWidth: 0.6, align: 'center', fillColor: null },
      highlightColor: COLOR_DARK_TRANSPARENT_9
    },
    legend: {
      show: true,
      position: 'ne',
      noColumns: 1
    }
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
	</div>
	<!-- END row -->
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-7">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Tacking Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>You can add <code>crosshairs</code> that'll track the mouse position, either on both axes or as here on only one.</p>
					<div id="tracking-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;

&lt;div id="tracking-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var sin = [], cos = [];
  for (var i = 0; i < 14; i += 0.1) {
    sin.push([i, Math.sin(i)]);
    cos.push([i, Math.cos(i)]);
  }
    
  function updateLegend() {
    updateLegendTimeout = null;

    var pos = latestPosition;
    var axes = plot.getAxes();
    if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max || pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
      return;
    }
    var i, j, dataset = plot.getData();
    for (i = 0; i < dataset.length; ++i) {
      var series = dataset[i];

      for (j = 0; j < series.data.length; ++j) {
        if (series.data[j][0] > pos.x) {
          break;
        }
      }
      var y, p1 = series.data[j - 1], p2 = series.data[j];
      if (p1 === null) {
        y = p2[1];
      } else if (p2 === null) {
        y = p1[1];
      } else {
        y = p1[1];
      }
      legends.eq(i).text(series.label.replace(/=.*/, '= ' + y.toFixed(2)));
    }
  }
  if ($('#tracking-chart').length !== 0) {
    var plot = $.plot($('#tracking-chart'), [ 
      { data: sin, label: 'Series1', color: COLOR_DARK_LIGHTER, shadowSize: 0},
      { data: cos, label: 'Series2', color: COLOR_BLUE, shadowSize: 0} 
    ], {
      series: { 
        lines: { show: true } 
      },
      crosshair: {
        mode: 'x', 
        color: COLOR_RED 
      },
      grid: { 
        hoverable: true, 
        autoHighlight: false, 
        borderColor: COLOR_SILVER_TRANSPARENT_5, 
        borderWidth: 1,
        backgroundColor: COLOR_SILVER_TRANSPARENT_1
      },
      yaxis: { tickColor: COLOR_SILVER_TRANSPARENT_3 },
      xaxis: {
        tickColor: COLOR_SILVER_TRANSPARENT_3
      },
      legend: { show: true }
    });

    var legends = $('#tracking-chart .legendLabel');
    legends.each(function () {
      $(this).css('width', $(this).width());
    });

    var updateLegendTimeout = null;
    var latestPosition = null;

    $('#tracking-chart').bind('plothover',  function (pos) {
      latestPosition = pos;
      if (!updateLegendTimeout) {
        updateLegendTimeout = setTimeout(updateLegend, 50);
      }
    });
  }
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="flot-chart-8">
				<div class="panel-heading">
					<h4 class="panel-title">Flot Donut Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>Multiple slices less than a given percentage (5% in this case) of the pie can be combined into a single, larger slice.</p>
					<div id="donut-chart" class="h-250px"></div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/flot/source/jquery.canvaswrapper.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.colorhelpers.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.saturated.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.browser.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.drawSeries.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.uiConstants.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.pie.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.time.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.resize.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.touchNavigate.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.hover.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.touch.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.selection.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.symbol.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/flot/source/jquery.flot.legend.js"&gt;&lt;/script&gt;

&lt;div id="donut-chart" class="h-250px"&gt;&lt;/div&gt;

&lt;script&gt;
  var data = [];
  var series = 3;
  var colorArray = [COLOR_DARK_LIGHTER, COLOR_GREY, COLOR_RED];
  var nameArray = ['Unique Visitor', 'Bounce Rate', 'Total Page Views', 'Avg Time On Site', '% New Visits'];
  var dataArray = [20,14,12,31,23];
  
  for( var i = 0; i < series; i++) {
    data[i] = { label: nameArray[i], data: dataArray[i], color: colorArray[i] };
  }

  $.plot($('#donut-chart'), data, {
    series: {
      pie: { 
        innerRadius: 0.5,
        show: true,
        combine: {
          threshold: 0.1
        }
      }
    },
    grid:{borderWidth:0, hoverable: true, clickable: true},
    legend: {
      show: false
    }
  });
&lt;/script&gt;</code></pre>
				</div>
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-6 -->
	</div>
	<!-- END row -->
@endsection
