<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
			<li class="breadcrumb-item active">d3 Chart</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">d3 Chart <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Line Chart">
					<vn-line :model="lineChart.data"
						x-format=",.1f"
						y-format=",.2f">
					</vn-line>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-line :model="lineChart.data"
  x-format=",.1f"
  y-format=",.2f"&gt;
&lt;/vn-line&gt;

&lt;script&gt;
export default {
  data() {
    var sin = [], cos = [];
    for (var i = 0; i &lt; 100; i++) {
      sin.push({x: i, y:  Math.sin(i/10) });
      cos.push({x: i, y: .5 * Math.cos(i/10)});
    }
    var lineChartData = [
      { values: sin, key: 'Sine Wave', color: '#00ACAC' }, 
      { values: cos, key: 'Cosine Wave', color: '#348fe2' }
    ];
    
    return {
      lineChart: {
        data: lineChartData
      }
    }
  }
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Bar Chart">
					<vn-bar :model="barChart.data"
						textField="label"
						valueField="value"
						:staggerLabels="barChart.staggerLabels"
						x-format=",.1f"
						y-format=",.2f">
					</vn-bar>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-bar :model="barChart.data"
  textField="label"
  valueField="value"
  :staggerLabels="barChart.staggerLabels"
  x-format=",.1f"
  y-format=",.2f"&gt;
&lt;/vn-bar&gt;

&lt;script&gt;
export default {
  data() {
    var barChartData = [{
      key: 'Cumulative Return',
      values: [
        { 'label' : 'A', 'value' : 29, 'color' : '#ff5b57' }, 
        { 'label' : 'B', 'value' : 15, 'color' : '#f59c1a' }, 
        { 'label' : 'C', 'value' : 32, 'color' : '#00ACAC' }, 
        { 'label' : 'D', 'value' : 196, 'color' : '#5AC8FA' },  
        { 'label' : 'E', 'value' : 44, 'color' : '#348fe2' },  
        { 'label' : 'F', 'value' : 98, 'color' : '#727cb6' },  
        { 'label' : 'G', 'value' : 13, 'color' : '#b6c2c9' },  
        { 'label' : 'H', 'value' : 5, 'color' : '#2d353c' }
      ]
    }];
    
    return {
      barChart: {
        data: barChartData,
        staggerLabels: false
      }
    }
  }
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
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
				<panel title="Stacked Area Chart">
					<div class="height-sm nv-stacked-area-chart">
						<vn-stacked-area :model="stackedAreaChart.data"
							yFormat=",.4f" 
							:xFormat="formatDate" 
							:clipEdge="stackedAreaChart.clipEdge"
							:rightAlignYAxis="stackedAreaChart.rightAlignYAxis" 
							:showControls="stackedAreaChart.showControls">
						</vn-stacked-area>
					</div>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-stacked-area :model="stackedAreaChart.data"
  yFormat=",.4f" 
  :xFormat="formatDate" 
  :clipEdge="stackedAreaChart.clipEdge"
  :rightAlignYAxis="stackedAreaChart.rightAlignYAxis" 
  :showControls="stackedAreaChart.showControls"&gt;
&lt;/vn-stacked-area&gt;

&lt;script&gt;
export default {
  data() {
    var stackedAreaChartData = [{
        'key' : 'Financials',
        'color' : '#ff5b57',
        'values' : [{ x: 1138683600000, y: 13.356778764352 }, { x: 1141102800000, y: 13.611196863271 }, { x: 1143781200000, y: 6.895903006119 }, { x: 1146369600000, y: 6.9939633271352 }, { x: 1149048000000, y: 6.7241510257675 }, { x: 1151640000000, y: 5.5611293669516 }, { x: 1154318400000, y: 5.6086488714041 }, { x: 1156996800000, y: 5.4962849907033 }, { x: 1159588800000, y: 6.9193153169279 }, { x: 1162270800000, y: 7.0016334389777 }, { x: 1164862800000, y: 6.7865422443273 }, { x: 1167541200000, y: 9.0006454225383 }, { x: 1170219600000, y: 9.2233916171431 }, { x: 1172638800000, y: 8.8929316009479 }, { x: 1175313600000, y: 10.345937520404 }, { x: 1177905600000, y: 10.075914677026 }, { x: 1180584000000, y: 10.089006188111 }, { x: 1183176000000, y: 10.598330295008 }, { x: 1185854400000, y: 9.968954653301 }, { x: 1188532800000, y: 9.7740580198146 }, { x: 1191124800000, y: 10.558483060626 }, { x: 1193803200000, y: 9.9314651823603 }, { x: 1196398800000, y: 9.3997715873769 }, { x: 1199077200000, y: 8.4086493387262 }, { x: 1201755600000, y: 8.9698309085926 }, { x: 1204261200000, y: 8.2778357995396 }, { x: 1206936000000, y: 8.8585045600123 }, { x: 1209528000000, y: 8.7013756413322 }, { x: 1212206400000, y: 7.7933605469443 }, { x: 1214798400000, y: 7.0236183483064 }, { x: 1217476800000, y: 6.9873088186829 }, { x: 1220155200000, y: 6.8031713070097 }, { x: 1222747200000, y: 6.6869531315723 }, { x: 1225425600000, y: 6.138256993963 }, { x: 1228021200000, y: 5.6434994016354 }, { x: 1230699600000, y: 5.495220262512 }, { x: 1233378000000, y: 4.6885326869846 }, { x: 1235797200000, y: 4.4524349883438 }, { x: 1238472000000, y: 5.6766520778185 }, { x: 1241064000000, y: 5.7675774480752 }, { x: 1243742400000, y: 5.7882863168337 }, { x: 1246334400000, y: 7.2666010034924 }, { x: 1249012800000, y: 7.519182132226 }, { x: 1251691200000, y: 7.849651451445 }, { x: 1254283200000, y: 10.383992037985 }, { x: 1256961600000, y: 9.0653691861818 }, { x: 1259557200000, y: 9.6705248324159 }, { x: 1262235600000, y: 10.856380561349 }, { x: 1264914000000, y: 11.27452370892 }, { x: 1267333200000, y: 11.754156529088 }, { x: 1270008000000, y: 8.2870811422456 }, { x: 1272600000000, y: 8.0210264360699 }, { x: 1275278400000, y: 7.5375074474865 }, { x: 1277870400000, y: 8.3419527338039 }, { x: 1280548800000, y: 9.4197471818443 }, { x: 1283227200000, y: 8.7321733185797 }, { x: 1285819200000, y: 9.6627062648126 }, { x: 1288497600000, y: 10.187962234549 }, { x: 1291093200000, y: 9.8144201733476 }, { x: 1293771600000, y: 10.275723361713 }, { x: 1296450000000, y: 16.796066079353 }, { x: 1298869200000, y: 17.543254984075 }, { x: 1301544000000, y: 16.673660675084 }, { x: 1304136000000, y: 17.963944353609 }, { x: 1306814400000, y: 16.637740867211 }, { x: 1309406400000, y: 15.84857094609 }, { x: 1312084800000, y: 14.767303362182 }, { x: 1314763200000, y: 24.778452182432 }, { x: 1317355200000, y: 18.370353229999 }, { x: 1320033600000, y: 15.2531374291 }, { x: 1322629200000, y: 14.989600840649 }, { x: 1325307600000, y: 16.052539160125 }, { x: 1327986000000, y: 16.424390322793 }, { x: 1330491600000, y: 17.884020741105 }, { x: 1333166400000, y: 7.1424929577921 }, { x: 1335758400000, y: 7.8076213051482 }, { x: 1338436800000, y: 7.2462684949232}]
    }, {
        'key' : 'Health Care',
        'color' : '#f59c1a',
        'values' : [{ x: 1138683600000, y: 14.212410956029 }, { x: 1141102800000, y: 13.973193618249 }, { x: 1143781200000, y: 15.218233920665 }, { x: 1146369600000, y: 14.38210972745 }, { x: 1149048000000, y: 13.894310878491 }, { x: 1151640000000, y: 15.593086090032 }, { x: 1154318400000, y: 16.244839695188 }, { x: 1156996800000, y: 16.017088850646 }, { x: 1159588800000, y: 14.183951830055 }, { x: 1162270800000, y: 14.148523245697 }, { x: 1164862800000, y: 13.424326059972 }, { x: 1167541200000, y: 12.974450435753 }, { x: 1170219600000, y: 13.23247041802 }, { x: 1172638800000, y: 13.318762655574 }, { x: 1175313600000, y: 15.961407746104 }, { x: 1177905600000, y: 16.287714639805 }, { x: 1180584000000, y: 16.246590583889 }, { x: 1183176000000, y: 17.564505594809 }, { x: 1185854400000, y: 17.872725373165 }, { x: 1188532800000, y: 18.018998508757 }, { x: 1191124800000, y: 15.584518016603 }, { x: 1193803200000, y: 15.480850647181 }, { x: 1196398800000, y: 15.699120036984 }, { x: 1199077200000, y: 19.184281817226 }, { x: 1201755600000, y: 19.691226605207 }, { x: 1204261200000, y: 18.982314051295 }, { x: 1206936000000, y: 18.707820309008 }, { x: 1209528000000, y: 17.459630929761 }, { x: 1212206400000, y: 16.500616076782 }, { x: 1214798400000, y: 18.086324003979 }, { x: 1217476800000, y: 18.929464156258 }, { x: 1220155200000, y: 18.233728682084 }, { x: 1222747200000, y: 16.315776297325 }, { x: 1225425600000, y: 14.63289219025 }, { x: 1228021200000, y: 14.667835024478 }, { x: 1230699600000, y: 13.946993947308 }, { x: 1233378000000, y: 14.394304684397 }, { x: 1235797200000, y: 13.724462792967 }, { x: 1238472000000, y: 10.930879035806 }, { x: 1241064000000, y: 9.8339915513708 }, { x: 1243742400000, y: 10.053858541872 }, { x: 1246334400000, y: 11.786998438287 }, { x: 1249012800000, y: 11.780994901769 }, { x: 1251691200000, y: 11.305889670276 }, { x: 1254283200000, y: 10.918452290083 }, { x: 1256961600000, y: 9.6811395055706 }, { x: 1259557200000, y: 10.971529744038 }, { x: 1262235600000, y: 13.330210480209 }, { x: 1264914000000, y: 14.592637568961 }, { x: 1267333200000, y: 14.605329141157 }, { x: 1270008000000, y: 13.936853794037 }, { x: 1272600000000, y: 12.189480759072 }, { x: 1275278400000, y: 11.676151385046 }, { x: 1277870400000, y: 13.058852800017 }, { x: 1280548800000, y: 13.62891543203 }, { x: 1283227200000, y: 13.811107569918 }, { x: 1285819200000, y: 13.786494560787 }, { x: 1288497600000, y: 14.04516285753 }, { x: 1291093200000, y: 13.697412447288 }, { x: 1293771600000, y: 13.677681376221 }, { x: 1296450000000, y: 19.961511864531 }, { x: 1298869200000, y: 21.049198298158 }, { x: 1301544000000, y: 22.687631094008 }, { x: 1304136000000, y: 25.469010617433 }, { x: 1306814400000, y: 24.883799437121 }, { x: 1309406400000, y: 24.203843814248 }, { x: 1312084800000, y: 22.138760964038 }, { x: 1314763200000, y: 16.034636966228 }, { x: 1317355200000, y: 15.394958944556 }, { x: 1320033600000, y: 12.625642461969 }, { x: 1322629200000, y: 12.973735699739 }, { x: 1325307600000, y: 15.786018336149 }, { x: 1327986000000, y: 15.227368020134 }, { x: 1330491600000, y: 15.899752650734 }, { x: 1333166400000, y: 18.994731295388 }, { x: 1335758400000, y: 18.450055817702 }, { x: 1338436800000, y: 17.863719889669}]
    }, {
        'key' : 'Information Technology',
        'color' : '#2d353c',
        'values' : [{ x: 1138683600000, y: 13.242301508051 }, { x: 1141102800000, y: 12.863536342042 }, { x: 1143781200000, y: 21.034044171629 }, { x: 1146369600000, y: 21.419084618803 }, { x: 1149048000000, y: 21.142678863691 }, { x: 1151640000000, y: 26.568489677529 }, { x: 1154318400000, y: 24.839144939905 }, { x: 1156996800000, y: 25.456187462167 }, { x: 1159588800000, y: 26.350164502826 }, { x: 1162270800000, y: 26.47833320519 }, { x: 1164862800000, y: 26.425979547847 }, { x: 1167541200000, y: 28.191461582256 }, { x: 1170219600000, y: 28.930307448808 }, { x: 1172638800000, y: 29.521413891117 }, { x: 1175313600000, y: 28.188285966466 }, { x: 1177905600000, y: 27.704619625832 }, { x: 1180584000000, y: 27.490862424829 }, { x: 1183176000000, y: 28.770679721286 }, { x: 1185854400000, y: 29.060480671449 }, { x: 1188532800000, y: 28.240998844973 }, { x: 1191124800000, y: 33.004893194127 }, { x: 1193803200000, y: 34.075180359928 }, { x: 1196398800000, y: 32.548560664833 }, { x: 1199077200000, y: 30.629727432728 }, { x: 1201755600000, y: 28.642858788159 }, { x: 1204261200000, y: 27.973575227842 }, { x: 1206936000000, y: 27.393351882726 }, { x: 1209528000000, y: 28.476095288523 }, { x: 1212206400000, y: 29.29667866426 }, { x: 1214798400000, y: 29.222333802896 }, { x: 1217476800000, y: 28.092966093843 }, { x: 1220155200000, y: 28.107159262922 }, { x: 1222747200000, y: 25.482974832098 }, { x: 1225425600000, y: 21.208115993834 }, { x: 1228021200000, y: 20.295043095268 }, { x: 1230699600000, y: 15.925754618401 }, { x: 1233378000000, y: 17.162864628346 }, { x: 1235797200000, y: 17.084345773174 }, { x: 1238472000000, y: 22.246007102281 }, { x: 1241064000000, y: 24.530543998509 }, { x: 1243742400000, y: 25.084184918242 }, { x: 1246334400000, y: 16.606166527358 }, { x: 1249012800000, y: 17.239620011628 }, { x: 1251691200000, y: 17.336739127379 }, { x: 1254283200000, y: 25.478492475753 }, { x: 1256961600000, y: 23.017152085245 }, { x: 1259557200000, y: 25.617745423683 }, { x: 1262235600000, y: 24.061133998642 }, { x: 1264914000000, y: 23.223933318644 }, { x: 1267333200000, y: 24.425887263937 }, { x: 1270008000000, y: 35.501471156693 }, { x: 1272600000000, y: 33.775013878676 }, { x: 1275278400000, y: 30.417993630285 }, { x: 1277870400000, y: 30.023598978467 }, { x: 1280548800000, y: 33.327519522436 }, { x: 1283227200000, y: 31.963388450371 }, { x: 1285819200000, y: 30.498967232092 }, { x: 1288497600000, y: 32.403696817912 }, { x: 1291093200000, y: 31.47736071922 }, { x: 1293771600000, y: 31.53259666241 }, { x: 1296450000000, y: 41.760282761548 }, { x: 1298869200000, y: 45.605771243237 }, { x: 1301544000000, y: 39.986557966215 }, { x: 1304136000000, y: 43.846330510051 }, { x: 1306814400000, y: 39.857316881857 }, { x: 1309406400000, y: 37.675127768208 }, { x: 1312084800000, y: 35.775077970313 }, { x: 1314763200000, y: 48.631009702577 }, { x: 1317355200000, y: 42.830831754505 }, { x: 1320033600000, y: 35.611502589362 }, { x: 1322629200000, y: 35.320136981738 }, { x: 1325307600000, y: 31.564136901516 }, { x: 1327986000000, y: 32.074407502433 }, { x: 1330491600000, y: 35.053013769976 }, { x: 1333166400000, y: 26.434568573937 }, { x: 1335758400000, y: 25.305617871002 }, { x: 1338436800000, y: 24.520919418236}]
    }];
    
    return {
      stackedAreaChart: {
        data: stackedAreaChartData,
        rightAlignYAxis: false,
        showControls: false,
        clipEdge: false
      }
    }
  },
	methods: {
		formatDate (d){
			// eslint-disable-next-line
			return d3.time.format('%x')(new Date(d))
		}
	}
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Multi Bar Horizontal Chart">
					<vn-hbar :model="multiBarHorizontalChart.data">
					</vn-hbar>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-hbar :model="multiBarHorizontalChart.data"&gt;
&lt;/vn-hbar&gt;

&lt;script&gt;
export default {
  data() {
    var multiBarHorizontalChartData = [{
      'key': 'Series 1',
      'color': '#2d353c',
      'values': [{
        'label': 'Group A',
        'value': -1.8746444827653
      }, {
        'label': 'Group B',
        'value': -8.0961543492239
      }, {
        'label': 'Group C',
        'value': -0.57072943117674
      }]
    }, {
      'key': 'Series 2',
      'color': '#f59c1a',
      'values': [{
        'label': 'Group A',
        'value': 25.307646510375
      }, {
        'label': 'Group B',
        'value': 16.756779544553
      }, {
        'label': 'Group C',
        'value': 18.451534877007
      }]
    }];
    
    return {
      multiBarHorizontalChart: {
        data: multiBarHorizontalChartData
      }
    }
  }
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
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
				<panel title="Pie Chart">
					<div class="h-250px">
						<vn-pie :model="pieChart.data"
							text-field="label"
							value-field="value"
							:show-tooltip-percent="true"
							:show-legend="true"></vn-pie>
					</div>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-pie :model="pieChart.data"
text-field="label"
value-field="value"
:show-tooltip-percent="true"
:show-legend="true"&gt;&lt;/vn-pie&gt;

&lt;script&gt;
export default {
  data() {
    var pieChartData = [
        { 'label': 'One', 'value' : 29, 'color': '#ff5b57' }, 
        { 'label': 'Two', 'value' : 12, 'color': '#f59c1a' }, 
        { 'label': 'Three', 'value' : 32, 'color': '#00ACAC' }, 
        { 'label': 'Four', 'value' : 196, 'color': '#5AC8FA' }, 
        { 'label': 'Five', 'value' : 17, 'color': '#348fe2' }, 
        { 'label': 'Six', 'value' : 98, 'color': '#727cb6' }, 
        { 'label': 'Seven', 'value' : 13, 'color': '#b6c2c9' }, 
        { 'label': 'Eight', 'value' : 5, 'color': '#2d353c' }
    ];
    
    return {
      pieChart: {
        data: pieChartData
      }
    }
  }
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Donut Chart">
					<div class="h-250px">
						<vn-pie :model="donutChart.data"
							text-field="label"
							value-field="value"
							:donut="true"
							:show-tooltip-percent="true"
							:show-legend="true"></vn-pie>
					</div>
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;vn-pie :model="donutChart.data"
text-field="label"
value-field="value"
:donut="true"
:show-tooltip-percent="true"
:show-legend="true"&gt;&lt;/vn-pie&gt;

&lt;script&gt;
export default {
  data() {
    var donutChartData = [
        { 'label': 'One', 'value' : 29, 'color': '#ff5b57' }, 
        { 'label': 'Two', 'value' : 12, 'color': '#f59c1a' }, 
        { 'label': 'Three', 'value' : 32, 'color': '#00ACAC' }, 
        { 'label': 'Four', 'value' : 196, 'color': '#5AC8FA' }, 
        { 'label': 'Five', 'value' : 17, 'color': '#348fe2' }, 
        { 'label': 'Six', 'value' : 98, 'color': '#727cb6' }, 
        { 'label': 'Seven', 'value' : 13, 'color': '#b6c2c9' }, 
        { 'label': 'Eight', 'value' : 5, 'color': '#2d353c' }
    ];
    
    return {
      donutChart: {
        data: donutChartData  
      }
    }
  }
}
&lt;/script&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
	</div>
</template>

<script>
export default {
	data() {
		var sin = [], cos = [];
		for (var i = 0; i < 100; i++) {
			sin.push({x: i, y:  Math.sin(i/10) });
			cos.push({x: i, y: .5 * Math.cos(i/10)});
		}
		var lineChartData = [
			{ values: sin, key: 'Sine Wave', color: '#00ACAC' }, 
			{ values: cos, key: 'Cosine Wave', color: '#348fe2' }
		];
		
		var barChartData = [{
			key: 'Cumulative Return',
			values: [
				{ 'label' : 'A', 'value' : 29, 'color' : '#ff5b57' }, 
				{ 'label' : 'B', 'value' : 15, 'color' : '#f59c1a' }, 
				{ 'label' : 'C', 'value' : 32, 'color' : '#00ACAC' }, 
				{ 'label' : 'D', 'value' : 196, 'color' : '#5AC8FA' },  
				{ 'label' : 'E', 'value' : 44, 'color' : '#348fe2' },  
				{ 'label' : 'F', 'value' : 98, 'color' : '#727cb6' },  
				{ 'label' : 'G', 'value' : 13, 'color' : '#b6c2c9' },  
				{ 'label' : 'H', 'value' : 5, 'color' : '#2d353c' }
			]
    }];
    
    
    var stackedAreaChartData = [{
        'key' : 'Financials',
        'color' : '#ff5b57',
        'values' : [{ x: 1138683600000, y: 13.356778764352 }, { x: 1141102800000, y: 13.611196863271 }, { x: 1143781200000, y: 6.895903006119 }, { x: 1146369600000, y: 6.9939633271352 }, { x: 1149048000000, y: 6.7241510257675 }, { x: 1151640000000, y: 5.5611293669516 }, { x: 1154318400000, y: 5.6086488714041 }, { x: 1156996800000, y: 5.4962849907033 }, { x: 1159588800000, y: 6.9193153169279 }, { x: 1162270800000, y: 7.0016334389777 }, { x: 1164862800000, y: 6.7865422443273 }, { x: 1167541200000, y: 9.0006454225383 }, { x: 1170219600000, y: 9.2233916171431 }, { x: 1172638800000, y: 8.8929316009479 }, { x: 1175313600000, y: 10.345937520404 }, { x: 1177905600000, y: 10.075914677026 }, { x: 1180584000000, y: 10.089006188111 }, { x: 1183176000000, y: 10.598330295008 }, { x: 1185854400000, y: 9.968954653301 }, { x: 1188532800000, y: 9.7740580198146 }, { x: 1191124800000, y: 10.558483060626 }, { x: 1193803200000, y: 9.9314651823603 }, { x: 1196398800000, y: 9.3997715873769 }, { x: 1199077200000, y: 8.4086493387262 }, { x: 1201755600000, y: 8.9698309085926 }, { x: 1204261200000, y: 8.2778357995396 }, { x: 1206936000000, y: 8.8585045600123 }, { x: 1209528000000, y: 8.7013756413322 }, { x: 1212206400000, y: 7.7933605469443 }, { x: 1214798400000, y: 7.0236183483064 }, { x: 1217476800000, y: 6.9873088186829 }, { x: 1220155200000, y: 6.8031713070097 }, { x: 1222747200000, y: 6.6869531315723 }, { x: 1225425600000, y: 6.138256993963 }, { x: 1228021200000, y: 5.6434994016354 }, { x: 1230699600000, y: 5.495220262512 }, { x: 1233378000000, y: 4.6885326869846 }, { x: 1235797200000, y: 4.4524349883438 }, { x: 1238472000000, y: 5.6766520778185 }, { x: 1241064000000, y: 5.7675774480752 }, { x: 1243742400000, y: 5.7882863168337 }, { x: 1246334400000, y: 7.2666010034924 }, { x: 1249012800000, y: 7.519182132226 }, { x: 1251691200000, y: 7.849651451445 }, { x: 1254283200000, y: 10.383992037985 }, { x: 1256961600000, y: 9.0653691861818 }, { x: 1259557200000, y: 9.6705248324159 }, { x: 1262235600000, y: 10.856380561349 }, { x: 1264914000000, y: 11.27452370892 }, { x: 1267333200000, y: 11.754156529088 }, { x: 1270008000000, y: 8.2870811422456 }, { x: 1272600000000, y: 8.0210264360699 }, { x: 1275278400000, y: 7.5375074474865 }, { x: 1277870400000, y: 8.3419527338039 }, { x: 1280548800000, y: 9.4197471818443 }, { x: 1283227200000, y: 8.7321733185797 }, { x: 1285819200000, y: 9.6627062648126 }, { x: 1288497600000, y: 10.187962234549 }, { x: 1291093200000, y: 9.8144201733476 }, { x: 1293771600000, y: 10.275723361713 }, { x: 1296450000000, y: 16.796066079353 }, { x: 1298869200000, y: 17.543254984075 }, { x: 1301544000000, y: 16.673660675084 }, { x: 1304136000000, y: 17.963944353609 }, { x: 1306814400000, y: 16.637740867211 }, { x: 1309406400000, y: 15.84857094609 }, { x: 1312084800000, y: 14.767303362182 }, { x: 1314763200000, y: 24.778452182432 }, { x: 1317355200000, y: 18.370353229999 }, { x: 1320033600000, y: 15.2531374291 }, { x: 1322629200000, y: 14.989600840649 }, { x: 1325307600000, y: 16.052539160125 }, { x: 1327986000000, y: 16.424390322793 }, { x: 1330491600000, y: 17.884020741105 }, { x: 1333166400000, y: 7.1424929577921 }, { x: 1335758400000, y: 7.8076213051482 }, { x: 1338436800000, y: 7.2462684949232}]
    }, {
        'key' : 'Health Care',
        'color' : '#f59c1a',
        'values' : [{ x: 1138683600000, y: 14.212410956029 }, { x: 1141102800000, y: 13.973193618249 }, { x: 1143781200000, y: 15.218233920665 }, { x: 1146369600000, y: 14.38210972745 }, { x: 1149048000000, y: 13.894310878491 }, { x: 1151640000000, y: 15.593086090032 }, { x: 1154318400000, y: 16.244839695188 }, { x: 1156996800000, y: 16.017088850646 }, { x: 1159588800000, y: 14.183951830055 }, { x: 1162270800000, y: 14.148523245697 }, { x: 1164862800000, y: 13.424326059972 }, { x: 1167541200000, y: 12.974450435753 }, { x: 1170219600000, y: 13.23247041802 }, { x: 1172638800000, y: 13.318762655574 }, { x: 1175313600000, y: 15.961407746104 }, { x: 1177905600000, y: 16.287714639805 }, { x: 1180584000000, y: 16.246590583889 }, { x: 1183176000000, y: 17.564505594809 }, { x: 1185854400000, y: 17.872725373165 }, { x: 1188532800000, y: 18.018998508757 }, { x: 1191124800000, y: 15.584518016603 }, { x: 1193803200000, y: 15.480850647181 }, { x: 1196398800000, y: 15.699120036984 }, { x: 1199077200000, y: 19.184281817226 }, { x: 1201755600000, y: 19.691226605207 }, { x: 1204261200000, y: 18.982314051295 }, { x: 1206936000000, y: 18.707820309008 }, { x: 1209528000000, y: 17.459630929761 }, { x: 1212206400000, y: 16.500616076782 }, { x: 1214798400000, y: 18.086324003979 }, { x: 1217476800000, y: 18.929464156258 }, { x: 1220155200000, y: 18.233728682084 }, { x: 1222747200000, y: 16.315776297325 }, { x: 1225425600000, y: 14.63289219025 }, { x: 1228021200000, y: 14.667835024478 }, { x: 1230699600000, y: 13.946993947308 }, { x: 1233378000000, y: 14.394304684397 }, { x: 1235797200000, y: 13.724462792967 }, { x: 1238472000000, y: 10.930879035806 }, { x: 1241064000000, y: 9.8339915513708 }, { x: 1243742400000, y: 10.053858541872 }, { x: 1246334400000, y: 11.786998438287 }, { x: 1249012800000, y: 11.780994901769 }, { x: 1251691200000, y: 11.305889670276 }, { x: 1254283200000, y: 10.918452290083 }, { x: 1256961600000, y: 9.6811395055706 }, { x: 1259557200000, y: 10.971529744038 }, { x: 1262235600000, y: 13.330210480209 }, { x: 1264914000000, y: 14.592637568961 }, { x: 1267333200000, y: 14.605329141157 }, { x: 1270008000000, y: 13.936853794037 }, { x: 1272600000000, y: 12.189480759072 }, { x: 1275278400000, y: 11.676151385046 }, { x: 1277870400000, y: 13.058852800017 }, { x: 1280548800000, y: 13.62891543203 }, { x: 1283227200000, y: 13.811107569918 }, { x: 1285819200000, y: 13.786494560787 }, { x: 1288497600000, y: 14.04516285753 }, { x: 1291093200000, y: 13.697412447288 }, { x: 1293771600000, y: 13.677681376221 }, { x: 1296450000000, y: 19.961511864531 }, { x: 1298869200000, y: 21.049198298158 }, { x: 1301544000000, y: 22.687631094008 }, { x: 1304136000000, y: 25.469010617433 }, { x: 1306814400000, y: 24.883799437121 }, { x: 1309406400000, y: 24.203843814248 }, { x: 1312084800000, y: 22.138760964038 }, { x: 1314763200000, y: 16.034636966228 }, { x: 1317355200000, y: 15.394958944556 }, { x: 1320033600000, y: 12.625642461969 }, { x: 1322629200000, y: 12.973735699739 }, { x: 1325307600000, y: 15.786018336149 }, { x: 1327986000000, y: 15.227368020134 }, { x: 1330491600000, y: 15.899752650734 }, { x: 1333166400000, y: 18.994731295388 }, { x: 1335758400000, y: 18.450055817702 }, { x: 1338436800000, y: 17.863719889669}]
    }, {
        'key' : 'Information Technology',
        'color' : '#2d353c',
        'values' : [{ x: 1138683600000, y: 13.242301508051 }, { x: 1141102800000, y: 12.863536342042 }, { x: 1143781200000, y: 21.034044171629 }, { x: 1146369600000, y: 21.419084618803 }, { x: 1149048000000, y: 21.142678863691 }, { x: 1151640000000, y: 26.568489677529 }, { x: 1154318400000, y: 24.839144939905 }, { x: 1156996800000, y: 25.456187462167 }, { x: 1159588800000, y: 26.350164502826 }, { x: 1162270800000, y: 26.47833320519 }, { x: 1164862800000, y: 26.425979547847 }, { x: 1167541200000, y: 28.191461582256 }, { x: 1170219600000, y: 28.930307448808 }, { x: 1172638800000, y: 29.521413891117 }, { x: 1175313600000, y: 28.188285966466 }, { x: 1177905600000, y: 27.704619625832 }, { x: 1180584000000, y: 27.490862424829 }, { x: 1183176000000, y: 28.770679721286 }, { x: 1185854400000, y: 29.060480671449 }, { x: 1188532800000, y: 28.240998844973 }, { x: 1191124800000, y: 33.004893194127 }, { x: 1193803200000, y: 34.075180359928 }, { x: 1196398800000, y: 32.548560664833 }, { x: 1199077200000, y: 30.629727432728 }, { x: 1201755600000, y: 28.642858788159 }, { x: 1204261200000, y: 27.973575227842 }, { x: 1206936000000, y: 27.393351882726 }, { x: 1209528000000, y: 28.476095288523 }, { x: 1212206400000, y: 29.29667866426 }, { x: 1214798400000, y: 29.222333802896 }, { x: 1217476800000, y: 28.092966093843 }, { x: 1220155200000, y: 28.107159262922 }, { x: 1222747200000, y: 25.482974832098 }, { x: 1225425600000, y: 21.208115993834 }, { x: 1228021200000, y: 20.295043095268 }, { x: 1230699600000, y: 15.925754618401 }, { x: 1233378000000, y: 17.162864628346 }, { x: 1235797200000, y: 17.084345773174 }, { x: 1238472000000, y: 22.246007102281 }, { x: 1241064000000, y: 24.530543998509 }, { x: 1243742400000, y: 25.084184918242 }, { x: 1246334400000, y: 16.606166527358 }, { x: 1249012800000, y: 17.239620011628 }, { x: 1251691200000, y: 17.336739127379 }, { x: 1254283200000, y: 25.478492475753 }, { x: 1256961600000, y: 23.017152085245 }, { x: 1259557200000, y: 25.617745423683 }, { x: 1262235600000, y: 24.061133998642 }, { x: 1264914000000, y: 23.223933318644 }, { x: 1267333200000, y: 24.425887263937 }, { x: 1270008000000, y: 35.501471156693 }, { x: 1272600000000, y: 33.775013878676 }, { x: 1275278400000, y: 30.417993630285 }, { x: 1277870400000, y: 30.023598978467 }, { x: 1280548800000, y: 33.327519522436 }, { x: 1283227200000, y: 31.963388450371 }, { x: 1285819200000, y: 30.498967232092 }, { x: 1288497600000, y: 32.403696817912 }, { x: 1291093200000, y: 31.47736071922 }, { x: 1293771600000, y: 31.53259666241 }, { x: 1296450000000, y: 41.760282761548 }, { x: 1298869200000, y: 45.605771243237 }, { x: 1301544000000, y: 39.986557966215 }, { x: 1304136000000, y: 43.846330510051 }, { x: 1306814400000, y: 39.857316881857 }, { x: 1309406400000, y: 37.675127768208 }, { x: 1312084800000, y: 35.775077970313 }, { x: 1314763200000, y: 48.631009702577 }, { x: 1317355200000, y: 42.830831754505 }, { x: 1320033600000, y: 35.611502589362 }, { x: 1322629200000, y: 35.320136981738 }, { x: 1325307600000, y: 31.564136901516 }, { x: 1327986000000, y: 32.074407502433 }, { x: 1330491600000, y: 35.053013769976 }, { x: 1333166400000, y: 26.434568573937 }, { x: 1335758400000, y: 25.305617871002 }, { x: 1338436800000, y: 24.520919418236}]
    }];
    
    var multiBarHorizontalChartData = [{
			'key': 'Series 1',
			'color': '#2d353c',
			'values': [{
				'label': 'Group A',
				'value': -1.8746444827653
			}, {
				'label': 'Group B',
				'value': -8.0961543492239
			}, {
				'label': 'Group C',
				'value': -0.57072943117674
			}, {
				'label': 'Group D',
				'value': -2.4174010336624
			}, {
				'label': 'Group E',
				'value': -0.72009071426284
			}, {
				'label': 'Group F',
				'value': -0.77154485523777
			}, {
				'label': 'Group G',
				'value': -0.90152097798131
			}, {
				'label': 'Group H',
				'value': -0.91445417330854
			}, {
				'label': 'Group I',
				'value': -0.055746319141851
			}]
		}, {
			'key': 'Series 2',
			'color': '#f59c1a',
			'values': [{
				'label': 'Group A',
				'value': 25.307646510375
			}, {
				'label': 'Group B',
				'value': 16.756779544553
			}, {
				'label': 'Group C',
				'value': 18.451534877007
			}, {
				'label': 'Group D',
				'value': 8.6142352811805
			}, {
				'label': 'Group E',
				'value': 7.8082472075876
			}, {
				'label': 'Group F',
				'value': 5.259101026956
			}, {
				'label': 'Group G',
				'value': 0.30947953487127
			}, {
				'label': 'Group H',
				'value': 0
			}, {
				'label': 'Group I',
				'value': 0
			}]
		}];
		
		var pieChartData = [
        { 'label': 'One', 'value' : 29, 'color': '#ff5b57' }, 
        { 'label': 'Two', 'value' : 12, 'color': '#f59c1a' }, 
        { 'label': 'Three', 'value' : 32, 'color': '#00ACAC' }, 
        { 'label': 'Four', 'value' : 196, 'color': '#5AC8FA' }, 
        { 'label': 'Five', 'value' : 17, 'color': '#348fe2' }, 
        { 'label': 'Six', 'value' : 98, 'color': '#727cb6' }, 
        { 'label': 'Seven', 'value' : 13, 'color': '#b6c2c9' }, 
        { 'label': 'Eight', 'value' : 5, 'color': '#2d353c' }
    ];
		
		return {
			lineChart: {
				data: lineChartData
			},
			barChart: {
				data: barChartData,
				staggerLabels: false
			},
			stackedAreaChart: {
				data: stackedAreaChartData,
				rightAlignYAxis: false,
				showControls: false,
				clipEdge: false
			},
			multiBarHorizontalChart: {
				data: multiBarHorizontalChartData
			},
			pieChart: {
				data: pieChartData
			},
			donutChart: {
				data: pieChartData	
			}
		}
	},
	methods: {
		formatDate (d){
			// eslint-disable-next-line
			return d3.time.format('%x')(new Date(d))
		}
	}
}
</script>