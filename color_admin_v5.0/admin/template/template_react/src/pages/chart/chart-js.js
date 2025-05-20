import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import { Line, Bar, Radar, PolarArea, Pie, Doughnut } from 'react-chartjs-2';
import Highlight from 'react-highlight';

class ChartJS extends React.Component {
	constructor(props) {
		super(props);
		
		var randomScalingFactor = function() { 
			return Math.round(Math.random()*100)
		};
		
		this.lineChart = {
			data: {
				labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
				datasets: [{
						label: 'Dataset 1',
						borderColor: '#348fe2',
						pointBackgroundColor: '#348fe2',
						pointRadius: 2,
						borderWidth: 2,
						backgroundColor: 'rgba(52, 143, 226, 0.3)',
						data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
				}, {
						label: 'Dataset 2',
						borderColor: '#2d353c',
						pointBackgroundColor: '#2d353c',
						pointRadius: 2,
						borderWidth: 2,
						backgroundColor: 'rgba(45, 53, 60, 0.3)',
						data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false,
				hover: {
					mode: 'nearest',
					intersect: true
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true,
							max: 100
						}
					}]
				}
			}
		};
		
		this.barChart = {
			data: {
				labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
				datasets: [{
					label: 'Dataset 1',
					borderWidth: 2,
					borderColor: '#727cb6',
					backgroundColor: 'rgba(114, 124, 182, 0.3)',
					data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
				}, {
					label: 'Dataset 2',
					borderWidth: 2,
					borderColor: '#2d353c',
					backgroundColor: 'rgba(45, 53, 60, 0.3)',
					data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false
			}
		};
		
		this.radarChart = {
			data: {
				labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
				datasets: [{
					label: 'Dataset 1',
					borderWidth: 2,
					borderColor: '#ff5b57',
					pointBackgroundColor: '#ff5b57',
					pointRadius: 2,
					backgroundColor: 'rgba(255, 91, 87, 0.2)',
					data: [65,59,90,81,56,55,40]
				}, {
					label: 'Dataset 2',
					borderWidth: 2,
					borderColor: '#2d353c',
					pointBackgroundColor: '#2d353c',
					pointRadius: 2,
					backgroundColor: 'rgba(45, 53, 60, 0.2)',
					data: [28,48,40,19,96,27,100]
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false
			}
		};
		
		this.polarAreaChart = {
			data: {
				labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
				datasets: [{
					data: [300, 160, 100, 200, 120],
					backgroundColor: ['rgba(114, 124, 182, 0.7)', 'rgba(52, 143, 226, 0.7)', 'rgba(0, 172, 172, 0.7)', 'rgba(182, 194, 201, 0.7)', 'rgba(45, 53, 60, 0.7)'],
					borderColor: ['#727cb6', '#348fe2', '#00ACAC', '#b6c2c9', '#2d353c'],
					borderWidth: 2,
					label: 'My dataset'
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false
			}
		};
		
		this.pieChart = {
			data: {
				labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
				datasets: [{
					data: [300, 50, 100, 40, 120],
					backgroundColor: ['rgba(255, 91, 87, 0.7)', 'rgba(245, 156, 26, 0.7)', 'rgba(240, 243, 244, 0.7)', 'rgba(182, 194, 201, 0.7)', 'rgba(45, 53, 60, 0.7)'],
					borderColor: ['#ff5b57', '#f59c1a', '#b4b6b7', '#b6c2c9', '#2d353c'],
					borderWidth: 2,
					label: 'My dataset'
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false
			}
		};
		
		this.doughnutChart = {
			data: {
				labels: ['Dataset 1', 'Dataset 2', 'Dataset 3', 'Dataset 4', 'Dataset 5'],
				datasets: [{
					data: [300, 50, 100, 40, 120],
					backgroundColor: ['rgba(114, 124, 182, 0.7)', 'rgba(52, 143, 226, 0.7)', 'rgba(0, 172, 172, 0.7)', 'rgba(182, 194, 201, 0.7)', 'rgba(45, 53, 60, 0.7)'],
					borderColor: ['#727cb6', '#348fe2', '#00ACAC', '#b6c2c9', '#2d353c'],
					borderWidth: 2,
					label: 'My dataset'
				}]
			},
			options: {
				animation: {
					duration: 0
				},
				responsive: true, 
				maintainAspectRatio: false
			}
		};
	}
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/chart/js">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/chart/js">Chart</Link></li>
					<li className="breadcrumb-item active">Chart JS</li>
				</ol>
				<h1 className="page-header">Chart JS <small>header small text goes here...</small></h1>
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Line Chart</PanelHeader>
							<PanelBody>
								<p>
									A line chart is a way of plotting data points on a line.
									Often, it is used to show trend data, and the comparison of two data sets.
								</p>
								<div style={{ height: '300px'}}>
									<Line data={this.lineChart.data} options={this.lineChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { Line } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() {\n'+
'	return Math.round(Math.random()*100)\n'+
'};\n'+
'this.lineChart = {\n'+
'	data: {\n'+
'		labels: [\'January\', \'February\', \'March\', \'April\', \'May\', \'June\', \'July\'],\n'+
'		datasets: [{\n'+
'				label: \'Dataset 1\',\n'+
'				borderColor: \'#348fe2\',\n'+
'				pointBackgroundColor: \'#348fe2\',\n'+
'				pointRadius: 2,\n'+
'				borderWidth: 2,\n'+
'				backgroundColor: \'rgba(52, 143, 226, 0.3)\',\n'+
'				data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]\n'+
'		}, {\n'+
'				label: \'Dataset 2\',\n'+
'				borderColor: \'#2d353c\',\n'+
'				pointBackgroundColor: \'#2d353c\',\n'+
'				pointRadius: 2,\n'+
'				borderWidth: 2,\n'+
'				backgroundColor: \'rgba(45, 53, 60, 0.3)\',\n'+
'				data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]\n'+
'		}]\n'+
'	},\n'+
'	options: {\n'+
'		animation: {\n'+
'			duration: 0\n'+
'		},\n'+
'		responsive: true, \n'+
'		maintainAspectRatio: false,\n'+
'		hover: {\n'+
'			mode: \'nearest\',\n'+
'			intersect: true\n'+
'		},\n'+
'		tooltips: {\n'+
'			mode: \'index\',\n'+
'			intersect: false,\n'+
'		},\n'+
'		scales: {\n'+
'			yAxes: [{\n'+
'				ticks: {\n'+
'					beginAtZero:true,\n'+
'					max: 100\n'+
'				}\n'+
'			}]\n'+
'		}\n'+
'	}\n'+
'};\n'+
'\n'+
'<Line data={this.lineChart.data} options={this.lineChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Bar Chart</PanelHeader>
							<PanelBody>
								<p>
									A bar chart is a way of showing data as bars.
									It is sometimes used to show trend data, and the comparison of multiple data sets side by side.
								</p>
								<div style={{ height: '300px'}}>
									<Bar data={this.barChart.data} options={this.barChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { Bar } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() { \n'+
'  return Math.round(Math.random()*100)\n'+
'};\n'+
'\n'+
'this.barChart = {\n'+
'  data: {\n'+
'    labels: [\'January\', \'February\', \'March\', \'April\', \'May\', \'June\', \'July\'],\n'+
'    datasets: [{\n'+
'      label: \'Dataset 1\',\n'+
'      borderWidth: 2,\n'+
'      borderColor: \'#727cb6\',\n'+
'      backgroundColor: \'rgba(114, 124, 182, 0.3)\',\n'+
'      data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]\n'+
'    }, {\n'+
'      label: \'Dataset 2\',\n'+
'      borderWidth: 2,\n'+
'      borderColor: \'#2d353c\',\n'+
'      backgroundColor: \'rgba(45, 53, 60, 0.3)\',\n'+
'      data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]\n'+
'    }]\n'+
'  },\n'+
'  options: {\n'+
'    animation: {\n'+
'      duration: 0\n'+
'    },\n'+
'    responsive: true, \n'+
'    maintainAspectRatio: false\n'+
'  }\n'+
'};\n'+
'\n'+
'<Bar data={this.barChart.data} options={this.barChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Radar Chart</PanelHeader>
							<PanelBody>
								<p>
									A radar chart is a way of showing multiple data points and the variation between them.
									They are often useful for comparing the points of two or more different data sets.
								</p>
								<div style={{ height: '300px'}}>
									<Radar data={this.radarChart.data} options={this.radarChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { Radar } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() { \n'+
'  return Math.round(Math.random()*100)\n'+
'};\n'+
'\n'+
'this.radarChart = {\n'+
'  data: {\n'+
'    labels: [\'Eating\', \'Drinking\', \'Sleeping\', \'Designing\', \'Coding\', \'Cycling\', \'Running\'],\n'+
'    datasets: [{\n'+
'      label: \'Dataset 1\',\n'+
'      borderWidth: 2,\n'+
'      borderColor: \'#ff5b57\',\n'+
'      pointBackgroundColor: \'#ff5b57\',\n'+
'      pointRadius: 2,\n'+
'      backgroundColor: \'rgba(255, 91, 87, 0.2)\',\n'+
'      data: [65,59,90,81,56,55,40]\n'+
'    }, {\n'+
'      label: \'Dataset 2\',\n'+
'      borderWidth: 2,\n'+
'      borderColor: \'#2d353c\',\n'+
'      pointBackgroundColor: \'#2d353c\',\n'+
'      pointRadius: 2,\n'+
'      backgroundColor: \'rgba(45, 53, 60, 0.2)\',\n'+
'      data: [28,48,40,19,96,27,100]\n'+
'    }]\n'+
'  },\n'+
'  options: {\n'+
'    animation: {\n'+
'      duration: 0\n'+
'    },\n'+
'    responsive: true, \n'+
'    maintainAspectRatio: false\n'+
'  }\n'+
'};\n'+
'\n'+
'<Radar data={this.radarChart.data} options={this.radarChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Polar Area Chart</PanelHeader>
							<PanelBody>
								<p>
									Polar area charts are similar to pie charts, but each segment has the same angle - the radius of the segment 
									differs depending on the value.
								</p>
								<div style={{ height: '300px'}}>
									<PolarArea data={this.polarAreaChart.data} options={this.polarAreaChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { PolarArea } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() { \n'+
'  return Math.round(Math.random()*100)\n'+
'};\n'+
'\n'+
'this.polarAreaChart = {\n'+
'  data: {\n'+
'    labels: [\'Dataset 1\', \'Dataset 2\', \'Dataset 3\', \'Dataset 4\', \'Dataset 5\'],\n'+
'    datasets: [{\n'+
'      data: [300, 160, 100, 200, 120],\n'+
'      backgroundColor: [\'rgba(114, 124, 182, 0.7)\', \'rgba(52, 143, 226, 0.7)\', \'rgba(0, 172, 172, 0.7)\', \'rgba(182, 194, 201, 0.7)\', \'rgba(45, 53, 60, 0.7)\'],\n'+
'      borderColor: [\'#727cb6\', \'#348fe2\', \'#00ACAC\', \'#b6c2c9\', \'#2d353c\'],\n'+
'      borderWidth: 2,\n'+
'      label: \'My dataset\'\n'+
'    }]\n'+
'  },\n'+
'  options: {\n'+
'    animation: {\n'+
'      duration: 0\n'+
'    },\n'+
'    responsive: true, \n'+
'    maintainAspectRatio: false\n'+
'  }\n'+
'};\n'+
'\n'+
'<PolarArea data={this.polarAreaChart.data} options={this.polarAreaChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
				<div className="row">
					<div className="col-md-6">
						<Panel>
							<PanelHeader>Pie Chart</PanelHeader>
							<PanelBody>
								<p>
									Pie and doughnut charts are probably the most commonly used chart there are. They are divided into segments, the arc of each segment shows the proportional value of each piece of data.
								</p>
								<div style={{ height: '300px'}}>
									<Pie data={this.pieChart.data} options={this.pieChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { Pie } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() { \n'+
'  return Math.round(Math.random()*100)\n'+
'};\n'+
'\n'+
'this.pieChart = {\n'+
'  data: {\n'+
'    labels: [\'Dataset 1\', \'Dataset 2\', \'Dataset 3\', \'Dataset 4\', \'Dataset 5\'],\n'+
'    datasets: [{\n'+
'      data: [300, 50, 100, 40, 120],\n'+
'      backgroundColor: [\'rgba(255, 91, 87, 0.7)\', \'rgba(245, 156, 26, 0.7)\', \'rgba(240, 243, 244, 0.7)\', \'rgba(182, 194, 201, 0.7)\', \'rgba(45, 53, 60, 0.7)\'],\n'+
'      borderColor: [\'#ff5b57\', \'#f59c1a\', \'#b4b6b7\', \'#b6c2c9\', \'#2d353c\'],\n'+
'      borderWidth: 2,\n'+
'      label: \'My dataset\'\n'+
'    }]\n'+
'  },\n'+
'  options: {\n'+
'    animation: {\n'+
'      duration: 0\n'+
'    },\n'+
'    responsive: true, \n'+
'    maintainAspectRatio: false\n'+
'  }\n'+
'};\n'+
'\n'+
'<Pie data={this.pieChart.data} options={this.pieChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-md-6">
						<Panel>
							<PanelHeader>Doughnut Chart</PanelHeader>
							<PanelBody>
								<p>
									Pie and doughnut charts are registered under two aliases in the Chart core. Other than their different default value, and different alias, they are exactly the same.
								</p>
								<div style={{ height: '300px'}}>
									<Doughnut data={this.doughnutChart.data} options={this.doughnutChart.options} />
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='typescript'>{
'import { Doughnut } from \'react-chartjs-2\';\n'+
'\n'+
'var randomScalingFactor = function() { \n'+
'  return Math.round(Math.random()*100)\n'+
'};\n'+
'\n'+
'this.doughnutChart = {\n'+
'  data: {\n'+
'    labels: [\'Dataset 1\', \'Dataset 2\', \'Dataset 3\', \'Dataset 4\', \'Dataset 5\'],\n'+
'    datasets: [{\n'+
'      data: [300, 50, 100, 40, 120],\n'+
'      backgroundColor: [\'rgba(114, 124, 182, 0.7)\', \'rgba(52, 143, 226, 0.7)\', \'rgba(0, 172, 172, 0.7)\', \'rgba(182, 194, 201, 0.7)\', \'rgba(45, 53, 60, 0.7)\'],\n'+
'      borderColor: [\'#727cb6\', \'#348fe2\', \'#00ACAC\', \'#b6c2c9\', \'#2d353c\'],\n'+
'      borderWidth: 2,\n'+
'      label: \'My dataset\'\n'+
'    }]\n'+
'  },\n'+
'  options: {\n'+
'    animation: {\n'+
'      duration: 0\n'+
'    },\n'+
'    responsive: true, \n'+
'    maintainAspectRatio: false\n'+
'  }\n'+
'};\n'+
'\n'+
'<Doughnut data={this.doughnutChart.data} options={this.doughnutChart.options} />'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
			</div>
		)
	}
}

export default ChartJS;