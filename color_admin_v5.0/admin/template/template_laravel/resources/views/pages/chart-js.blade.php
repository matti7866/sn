@extends('layouts.default')

@section('title', 'Chart JS')

@push('scripts')
	<script src="/assets/plugins/chart.js/dist/Chart.min.js"></script>
	<script src="/assets/js/demo/chart-js.demo.js"></script>
	<script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
	<script src="/assets/js/demo/render.highlight.js"></script>
@endpush

@section('content')
	<!-- BEGIN breadcrumb -->
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Chart</a></li>
		<li class="breadcrumb-item active">Chart JS</li>
	</ol>
	<!-- END breadcrumb -->
	<!-- BEGIN page-header -->
	<h1 class="page-header">Chart JS <small>header small text goes here...</small></h1>
	<!-- END page-header -->
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-6 -->
		<div class="col-xl-6">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse" data-sortable-id="chart-js-1">
				<div class="panel-heading">
					<h4 class="panel-title">Line Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						A line chart is a way of plotting data points on a line.
						Often, it is used to show trend data, and the comparison of two data sets.
					</p>
					<div>
						<canvas id="line-chart"></canvas>
					</div>
				</div>
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="line-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;

  var randomScalingFactor = function() { 
    return Math.round(Math.random()*100)
  };

  var ctx = document.getElementById('line-chart').getContext('2d');
  var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [{
        label: 'Dataset 1',
        borderColor: COLOR_BLUE,
        pointBackgroundColor: COLOR_BLUE,
        pointRadius: 2,
        borderWidth: 2,
        backgroundColor: COLOR_BLUE_TRANSPARENT_3,
        data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
      }, {
        label: 'Dataset 2',
        borderColor: COLOR_DARK_LIGHTER,
        pointBackgroundColor: COLOR_DARK,
        pointRadius: 2,
        borderWidth: 2,
        backgroundColor: COLOR_DARK_TRANSPARENT_3,
        data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
      }]
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
			<div class="panel panel-inverse" data-sortable-id="chart-js-2">
				<div class="panel-heading">
					<h4 class="panel-title">Bar Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						A bar chart is a way of showing data as bars.
						It is sometimes used to show trend data, and the comparison of multiple data sets side by side.
					</p>
					<div>
						<canvas id="bar-chart"></canvas>
					</div>
				</div>
				
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="bar-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;

  var randomScalingFactor = function() { 
    return Math.round(Math.random()*100)
  };

  var ctx2 = document.getElementById('bar-chart').getContext('2d');
  var barChart = new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [{
        label: 'Dataset 1',
        borderWidth: 2,
        borderColor: COLOR_INDIGO,
        backgroundColor: COLOR_INDIGO_TRANSPARENT_3,
        data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
      }, {
        label: 'Dataset 2',
        borderWidth: 2,
        borderColor: COLOR_DARK,
        backgroundColor: COLOR_DARK_TRANSPARENT_3,
        data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
      }]
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
					<h4 class="panel-title">Radar Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						A radar chart is a way of showing multiple data points and the variation between them.
						They are often useful for comparing the points of two or more different data sets.
					</p>
					<div class="w-75 mx-auto">
						<canvas id="radar-chart"></canvas>
					</div>
				</div>
				
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="radar-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;

  var ctx3 = document.getElementById('radar-chart').getContext('2d');
  var radarChart = new Chart(ctx3, {
    type: 'radar',
    data: {
      labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
      datasets: [{
        label: 'Dataset 1',
        borderWidth: 2,
        borderColor: COLOR_RED,
        pointBackgroundColor: COLOR_RED,
        pointRadius: 2,
        backgroundColor: COLOR_RED_TRANSPARENT_2,
        data: [65,59,90,81,56,55,40]
      }, {
        label: 'Dataset 2',
        borderWidth: 2,
        borderColor: COLOR_DARK,
        pointBackgroundColor: COLOR_DARK,
        pointRadius: 2,
        backgroundColor: COLOR_DARK_TRANSPARENT_2,
        data: [28,48,40,19,96,27,100]
      }]
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
			<div class="panel panel-inverse" data-sortable-id="chart-js-4">
				<div class="panel-heading">
					<h4 class="panel-title">Polar Area Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						Polar area charts are similar to pie charts, but each segment has the same angle - the radius of the segment 
						differs depending on the value.
					</p>
					<div class="w-75 mx-auto">
						<canvas id="polar-area-chart"></canvas>
					</div>
				</div>
				
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="polar-area-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;

  var ctx4 = document.getElementById('polar-area-chart').getContext('2d');
  var polarAreaChart = new Chart(ctx4, {
    type: 'polarArea',
    data: {
      labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
      datasets: [{
        data: [300, 160, 100, 200, 120],
        backgroundColor: [COLOR_INDIGO_TRANSPARENT_7, COLOR_BLUE_TRANSPARENT_7, COLOR_GREEN_TRANSPARENT_7, COLOR_GREY_TRANSPARENT_7, COLOR_DARK_TRANSPARENT_7],
        borderColor: [COLOR_INDIGO, COLOR_BLUE, COLOR_GREEN, COLOR_GREY, COLOR_DARK],
        borderWidth: 2,
        label: 'My dataset'
      }]
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
			<div class="panel panel-inverse" data-sortable-id="flot-chart-5">
				<div class="panel-heading">
					<h4 class="panel-title">Pie Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						Pie and doughnut charts are probably the most commonly used chart there are. They are divided into segments, the arc of each segment shows the proportional value of each piece of data.
					</p>
					<div class="w-75 mx-auto">
						<canvas id="pie-chart"></canvas>
					</div>
				</div>
				
				<div class="hljs-wrapper">
					<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="pie-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;

  var ctx5 = document.getElementById('pie-chart').getContext('2d');
  window.myPie = new Chart(ctx5, {
    type: 'pie',
    data: {
      labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
      datasets: [{
        data: [300, 50, 100, 40, 120],
        backgroundColor: [COLOR_RED_TRANSPARENT_7, COLOR_ORANGE_TRANSPARENT_7, COLOR_MUTED_TRANSPARENT_7, COLOR_GREY_TRANSPARENT_7, COLOR_DARK_TRANSPARENT_7],
        borderColor: [COLOR_RED, COLOR_ORANGE, COLOR_MUTED, COLOR_GREY, COLOR_DARK],
        borderWidth: 2,
        label: 'My dataset'
      }]
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
			<div class="panel panel-inverse" data-sortable-id="chart-js-6">
				<div class="panel-heading">
					<h4 class="panel-title">Doughnut Chart</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="panel-body">
					<p>
						Pie and doughnut charts are registered under two aliases in the Chart core. Other than their different default value, and different alias, they are exactly the same.
					</p>
					<div class="w-75 mx-auto">
						<canvas id="doughnut-chart"></canvas>
					</div>
				</div>
				
				<div class="hljs-wrapper">
				<pre><code class="html">&lt;!-- required files --&gt;
&lt;script src="/assets/plugins/chart.js/dist/Chart.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;canvas id="doughnut-chart"&gt;&lt;/canvas&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  Chart.defaults.font.family = FONT_FAMILY;
  Chart.defaults.font.weight = FONT_WEIGHT;
  
  var ctx6 = document.getElementById('doughnut-chart').getContext('2d');
  window.myDoughnut = new Chart(ctx6, {
    type: 'doughnut',
    data: {
      labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
      datasets: [{
        data: [300, 50, 100, 40, 120],
        backgroundColor: [COLOR_INDIGO_TRANSPARENT_7, COLOR_BLUE_TRANSPARENT_7, COLOR_GREEN_TRANSPARENT_7, COLOR_GREY_TRANSPARENT_7, COLOR_DARK_TRANSPARENT_7],
        borderColor: [COLOR_INDIGO, COLOR_BLUE, COLOR_GREEN, COLOR_GREY, COLOR_DARK],
        borderWidth: 2,
        label: 'My dataset'
      }]
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
