import React from 'react';
import NVD3Chart from 'react-nvd3';
import d3 from 'd3';
import { Link } from 'react-router-dom';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import { UncontrolledDropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import PerfectScrollbar from 'react-perfect-scrollbar';
import Highlight from 'react-highlight';

class Widgets extends React.Component {
	constructor(props) {
		super(props);
		
		this.state = {
			lightTheme: true,
			darkTheme: false,
			modalWidgetList: false,
			modalWidgetTable: false,
			modalWidgetChart: false,
			modalWidgetListWithIcon: false,
			modalWidgetStat: false,
			modalWidgetCardSquare: false,
			modalWidgetImgIcon: false,
			modalWidgetCard: false,
			modalWidgetChatInput: false,
			modalWidgetMap: false,
			modalWidgetTodolist: false
		}
		
		this.codeMirrorOptions = {
			mode: 'application/xml',
			theme: 'material',
			lineNumbers: true,
			indentWithTabs: true,
			tabSize: 2,
			autoScroll: false
		}
		
		this.areaChartData = [{
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
    
		this.areaChartOptions = {
			pointSize: 0.5,
			useInteractiveGuideline: true,
			durection: 300,
			showControls: false,
			rightAlignYAxis: false,
			clipEdge: false,
			controlLabels: {
				stacked: 'Stacked'
			},
			yAxis: {
				tickFormat: d3.format(',.0f')
			},
			xAxis: {
				tickFormat: function(d) {
					var monthsName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
					d = new Date(d);
					d = monthsName[d.getMonth()] + ' ' + d.getDate();
					return d;
				}
			}
		};
		
		this.toggle = this.toggle.bind(this);
		this.emptyLink = () => { }
	}
	
	setWidgetTheme = (theme) => {
		this.setState(state => ({
			lightTheme: (theme === 'light') ? true : false,
			darkTheme: (theme === 'dark') ? true : false
		}));
	}
	
	toggle(modal) {
		switch (modal) {
			case 'modalWidgetList':
				this.setState({ modalWidgetList: !this.state.modalWidgetList });
				break;
			case 'modalWidgetListWithIcon':
				this.setState({ modalWidgetListWithIcon: !this.state.modalWidgetListWithIcon });
				break;
			case 'modalWidgetTable':
				this.setState({ modalWidgetTable: !this.state.modalWidgetTable });
				break;
			case 'modalWidgetChart':
				this.setState({ modalWidgetChart: !this.state.modalWidgetChart });
				break;
			case 'modalWidgetCardSquare':
				this.setState({ modalWidgetCardSquare: !this.state.modalWidgetCardSquare });
				break;
			case 'modalWidgetImgIcon':
				this.setState({ modalWidgetImgIcon: !this.state.modalWidgetImgIcon });
				break;
			case 'modalWidgetCard':
				this.setState({ modalWidgetCard: !this.state.modalWidgetCard });
				break;
			case 'modalWidgetChatInput':
				this.setState({ modalWidgetChatInput: !this.state.modalWidgetChatInput });
				break;
			case 'modalWidgetMap':
				this.setState({ modalWidgetMap: !this.state.modalWidgetMap });
				break;
			case 'modalWidgetTodolist':
				this.setState({ modalWidgetTodolist: !this.state.modalWidgetTodolist });
				break;
			case 'modalWidgetStat':
				this.setState({ modalWidgetStat: !this.state.modalWidgetStat });
				break;
			default:
				break;
		}
	}
	
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/widget">Home</Link></li>
					<li className="breadcrumb-item active">Widgets</li>
				</ol>
				<h1 className="page-header">
					Widgets <small>header small text goes here...</small>
				</h1>
			
				<span className="btn-group float-end ms-20px  p-2px bg-black-transparent-2 rounded">
					<button className={'btn btn-sm btn-white border-0 ' + (this.state.lightTheme ? 'active ' : '')} onClick={() => this.setWidgetTheme('light')}><i className="fa fa-sun text-blue"></i> Light</button>
					<button className={'btn btn-sm btn-white border-0 ' + (this.state.darkTheme ? 'active ' : '')} onClick={() => this.setWidgetTheme('dark')}><i className="fa fa-moon"></i> Dark</button>
				</span>
				<p className="mb-20px">
					All the widgets is reusable and responsive. You may use the predefined css to configure the padding, margin or background. Besides that, all the widgets have light and dark version. 
					Kindly add the <code>.inverse-mode</code> to the widget will change it to the dark version.
				</p>
				
				<div className="row">
					<div className="col-lg-12 col-xl-4">
						<div className="row">
							<div className="col-xl-12 col-lg-6">
								<div className="mb-10px mt-10px fs-10px">
									<Link to="/widgets" onClick={() => this.toggle('modalWidgetList')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
									<b className="text-inverse">WIDGET LIST</b>
								</div>
								<div className={'widget-list rounded mb-4 ' + (this.state.darkTheme ? 'inverse-mode ' : '')}>
									<div className="widget-list-item">
										<div className="widget-list-media">
											<img src="/assets/img/user/user-12.jpg" width="50" alt="" className="rounded" />
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Christopher Struth</h4>
											<p className="widget-list-desc">Bank Transfer</p>
										</div>
										<div className="widget-list-action">
											<UncontrolledDropdown>
												<DropdownToggle tag="a" className="text-gray-500">
													<i className="fa fa-ellipsis-h fs-14px"></i>
												</DropdownToggle>
												<DropdownMenu>
													<DropdownItem>Option 1</DropdownItem>
													<DropdownItem>Option 2</DropdownItem>
													<DropdownItem>Option 3</DropdownItem>
													<DropdownItem divider />
													<DropdownItem>Option 4</DropdownItem>
												</DropdownMenu>
											</UncontrolledDropdown>
										</div>
									</div>
									<div className="widget-list-item">
										<div className="widget-list-media">
											<img src="/assets/img/user/user-13.jpg" width="50" alt="" className="rounded" />
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Janie Flowers</h4>
											<p className="widget-list-desc">Bank Transfer</p>
										</div>
										<div className="widget-list-action">
											<UncontrolledDropdown>
												<DropdownToggle tag="a" className="text-gray-500">
													<i className="fa fa-ellipsis-h fs-14px"></i>
												</DropdownToggle>
												<DropdownMenu>
													<DropdownItem>Option 1</DropdownItem>
													<DropdownItem>Option 2</DropdownItem>
													<DropdownItem>Option 3</DropdownItem>
													<DropdownItem divider />
													<DropdownItem>Option 4</DropdownItem>
												</DropdownMenu>
											</UncontrolledDropdown>
										</div>
									</div>
									<div className="widget-list-item">
										<div className="widget-list-media">
											<img src="/assets/img/user/user-14.jpg" width="50" alt="" className="rounded" />
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Janie Flowers</h4>
											<p className="widget-list-desc">Bank Transfer</p>
										</div>
										<div className="widget-list-action">
											<UncontrolledDropdown>
												<DropdownToggle tag="a" className="text-gray-500">
													<i className="fa fa-ellipsis-h fs-14px"></i>
												</DropdownToggle>
												<DropdownMenu>
													<DropdownItem>Option 1</DropdownItem>
													<DropdownItem>Option 2</DropdownItem>
													<DropdownItem>Option 3</DropdownItem>
													<DropdownItem divider />
													<DropdownItem>Option 4</DropdownItem>
												</DropdownMenu>
											</UncontrolledDropdown>
										</div>
									</div>
								</div>
							</div>
							
							<div className="col-xl-12 col-lg-6">
								<div className="mb-10px mt-10px fs-10px">
									<Link to="/widget" onClick={() => this.toggle('modalWidgetListWithIcon')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
									<b className="text-inverse">WIDGET LIST WITH ICON</b>
								</div>
								<div className={'widget-list rounded mb-4 ' + (this.state.darkTheme ? 'inverse-mode ' : '')}>
									<Link to="/widget" className="widget-list-item">
										<div className="widget-list-media icon">
											<i className="fa fa-plane bg-inverse text-white"></i>
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Airplane Mode</h4>
										</div>
										<div className="widget-list-action text-end">
											<i className="fa fa-angle-right fa-lg text-gray-500"></i>
										</div>
									</Link>
									<Link to="/widget" className="widget-list-item">
										<div className="widget-list-media icon">
											<i className="fa fa-wifi bg-inverse text-white"></i>
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Wi-Fi</h4>
										</div>
										<div className="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
											Connected
											<i className="fa fa-angle-right fa-lg ms-3 text-gray-500"></i>
										</div>
									</Link>
									<Link to="/widget" className="widget-list-item">
										<div className="widget-list-media icon">
											<i className="fab fa-bluetooth bg-indigo text-white"></i>
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Bluetooth</h4>
										</div>
										<div className="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
											On
											<i className="fa fa-angle-right text-gray-500 fa-lg ms-3"></i>
										</div>
									</Link>
									<Link to="/widget" className="widget-list-item">
										<div className="widget-list-media icon">
											<i className="fa fa-signal bg-pink text-white"></i>
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Cellular</h4>
										</div>
										<div className="widget-list-action text-decoration-none fw-bold">
											<i className="fa fa-angle-right text-gray-500 fa-lg"></i>
										</div>
									</Link>
									<Link to="/widget" className="widget-list-item">
										<div className="widget-list-media icon">
											<i className="fa fa-link bg-yellow text-inverse"></i>
										</div>
										<div className="widget-list-content">
											<h4 className="widget-list-title">Personal Hotspot</h4>
										</div>
										<div className="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
											Off
											<i className="fa fa-angle-right text-gray-500 fa-lg ms-3"></i>
										</div>
									</Link>
								</div>
							</div>
							
							<div className="col-xl-12 col-lg-6">
								<div className="mb-10px mt-10px fs-10px">
									<Link to="/widgets" onClick={() => this.toggle('modalWidgetChatInput')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
									<b className="text-inverse">WIDGET CHAT</b>
								</div>
								<div className={'widget-chat rounded mb-4' + (this.state.darkTheme ? 'inverse-mode ' : '')}>
									<div className="widget-chat-header">
										<div className="widget-chat-header-icon">
											<i className="fab fa-earlybirds w-30px h-30px fs-20px bg-yellow text-inverse d-flex align-items-center justify-content-center rounded"></i>
										</div>
										<div className="widget-chat-header-content">
											<h4 className="widget-chat-header-title">Company Discussion Group</h4>
											<p className="widget-chat-header-desc">55 members, 4 online</p>
										</div>
									</div>
									<PerfectScrollbar className="widget-chat-body" style={{height: '235px'}} options={{suppressScrollX: true}}>
										<div className="widget-chat-item with-media start">
											<div className="widget-chat-media">
												<img alt="" src="/assets/img/user/user-1.jpg" />
											</div>
											<div className="widget-chat-info">
												<div className="widget-chat-info-container">
													<div className="widget-chat-name text-indigo">Hudson Mendes</div>
													<div className="widget-chat-message">Should we plan for a company trip this year?</div>
													<div className="widget-chat-time">6:00PM</div>
												</div>
											</div>
										</div>
										<div className="widget-chat-item with-media start">
											<div className="widget-chat-media">
												<img alt="" src="/assets/img/user/user-2.jpg" />
											</div>
											<div className="widget-chat-info">
												<div className="widget-chat-info-container">
													<div className="widget-chat-name text-primary">Sam Sugerman</div>
													<div className="widget-chat-message">ok let's do it</div>
													<div className="widget-chat-time">6:01PM</div>
												</div>
											</div>
										</div>
										<div className="widget-chat-item end">
											<div className="widget-chat-info">
												<div className="widget-chat-info-container">
													<div className="widget-chat-message">i'm ok with it</div>
													<div className="widget-chat-time">6:05PM</div>
												</div>
											</div>
										</div>
										<div className="text-center text-gray-500 m-2 fw-bold">Today</div>
										<div className="widget-chat-item with-media start">
											<div className="widget-chat-media">
												<img alt="" src="/assets/img/user/user-3.jpg" />
											</div>
											<div className="widget-chat-info">
												<div className="widget-chat-info-container">
													<div className="widget-chat-name text-orange">Jaxon Allwood</div>
													<div className="widget-chat-message">
														Here is some images for New Zealand
														<div className="row gx-1 mt-5px">
															<div className="col-md-4">
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-51-thumb.jpg)'}}></div>
																</Link>
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-52-thumb.jpg)'}}></div>
																</Link>
															</div>
															<div className="col-md-4">
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-53-thumb.jpg)'}}></div>
																</Link>
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-54-thumb.jpg)'}}></div>
																</Link>
															</div>
															<div className="col-md-4">
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-59-thumb.jpg)'}}></div>
																</Link>
																<Link to="/widgets" className="widget-card widget-card-sm square mb-1">
																	<div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-60-thumb.jpg)'}}></div>
																</Link>
															</div>
														</div>
													</div>
													<div className="widget-chat-time">6:20PM</div>
												</div>
											</div>
										</div>
									</PerfectScrollbar>
									<div className="widget-input">
										<form action="" method="POST" name="">
											<div className="widget-input-container">
												<div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-camera"></i></Link></div>
												<div className="widget-input-box">
													<input type="text" className="form-control" placeholder="Write a message..." />
												</div>
												<div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-smile"></i></Link></div>
												<div className="widget-input-divider"></div>
												<div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-microphone"></i></Link></div>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<div className="col-xl-12 col-lg-6">
								<div className="mb-10px mt-10px fs-10px">
									<Link to="/widgets" onClick={() => this.toggle('modalWidgetTodolist')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
									<b className="text-inverse">WIDGET TODOLIST</b>
								</div>
								<div className={'widget-todolist rounded mb-4 '+ (this.state.darkTheme ? 'inverse-mode ' : '')}>
									<div className="widget-todolist-header">
										<div className="widget-todolist-header-title">Todolist</div>
										<div className="widget-todolist-header-total"><span>0</span><small>Done</small></div>
									</div>
									<div className="widget-todolist-body">
										<div className="widget-todolist-item">
											<div className="widget-todolist-input">
												<div className="form-check">
													<input className="form-check-input" type="checkbox" id="widget_todolist_1" />
												</div>
											</div>
											<div className="widget-todolist-content">
												<h6 className="mb-2px">Borrow Tony's travel guide</h6>
												<div className="text-gray-500 fw-bold fs-11px">Vacation in Rome</div>
											</div>
											<div className="widget-todolist-icon">
												<Link to="/widgets"><i className="fa fa-question-circle"></i></Link>
											</div>
										</div>
										<div className="widget-todolist-item">
											<div className="widget-todolist-input">
												<div className="form-check">
													<input className="form-check-input" type="checkbox" id="widget_todolist_2" />
												</div>
											</div>
											<div className="widget-todolist-content">
												<h6 className="mb-2px">Finish expense report</h6>
												<div className="text-gray-500 fw-bold fs-11px">Today, 4:00PM, Daily</div>
											</div>
											<div className="widget-todolist-icon">
												<Link to="/widgets"><i className="fa fa-question-circle"></i></Link>
											</div>
										</div>
										<div className="widget-todolist-item">
											<div className="widget-todolist-input">
												<div className="form-check">
													<input className="form-check-input" type="checkbox" id="widget_todolist_3" />
												</div>
											</div>
											<div className="widget-todolist-content">
												<h6 className="mb-2px">Confirm conference call for Wednesday</h6>
												<div className="text-gray-500 fw-bold fs-11px">Work</div>
											</div>
											<div className="widget-todolist-icon">
												<Link to="/widgets"><i className="fa fa-question-circle"></i></Link>
											</div>
										</div>
										<div className="widget-todolist-item">
											<div className="widget-todolist-input">
												<div className="form-check">
													<input className="form-check-input" type="checkbox" id="widget_todolist_4" />
												</div>
											</div>
											<div className="widget-todolist-content">
												<h6 className="mb-2px"><b className="text-warning">!!</b> Mobile App WIP Presentation</h6>
												<div className="text-gray-500 fw-bold fs-11px">Today, 12:00PM</div>
											</div>
											<div className="widget-todolist-icon">
												<Link to="/widgets"><i className="fa fa-question-circle"></i></Link>
											</div>
										</div>
										<div className="widget-todolist-item">
											<div className="widget-todolist-input">
												<i className="fa fa-plus text-gray-500 fa-fw"></i>
											</div>
											<div className="widget-todolist-content">
												<input type="text" className="form-control border-0 shadow-none rounded-0 p-0 h-20px bg-none" placeholder="Write your task here..." />
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div className="col-xl-12 col-lg-6">
								<div className="mb-10px mt-10px fs-10px">
									<Link to="/widgets" onClick={() => this.toggle('modalWidgetMap')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
									<b className="text-inverse">WIDGET MAP</b>
								</div>
								<div className={'widget-map rounded mb-4 '+ (this.state.darkTheme ? 'inverse-mode ' : '')}>
									<div className="widget-input-container">
										<div className="widget-input-icon"><Link to="/widgets" className={(this.state.darkTheme ? 'text-white ' : 'text-gray-500')}><i className="fa fa-ellipsis-v"></i></Link></div>
										<div className="widget-input-box">
											<input type="text" className="form-control" placeholder="Search here" />
										</div>
										<div className="widget-input-icon"><Link to="/widgets" className={(this.state.darkTheme ? 'text-white ' : 'text-gray-500')}><i className="fa fa-microphone"></i></Link></div>
										<div className="widget-input-divider"></div>
										<div className="widget-input-icon"><Link to="/widgets" className={(this.state.darkTheme ? 'text-white ' : 'text-gray-500')}><i className="fa fa-location-arrow"></i></Link></div>
									</div>
									<div className="widget-map-body">
										<iframe className="d-block" title="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.5650178360584!2d-122.41879278478642!3d37.77679637975903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c6c8f4459%3A0xb10ed6d9b5050fa5!2sTwitter+HQ!5e0!3m2!1sen!2smy!4v1524046379645" width="100%" height="230" frameBorder="0" style={{border:0}} allowFullScreen></iframe>
									</div>
									<div className="widget-map-list" data-id="widget">
										<div className="widget-list bg-none">
											<div className="widget-list-item">
												<div className="widget-list-media text-center">
													<Link to="/widgets"><i className="fab fa-twitter fa-3x"></i></Link>
												</div>
												<div className="widget-list-content">
													<h4 className="widget-list-title">Twitter Headquater</h4>
													<p className="widget-list-desc">Corporate Office</p>
												</div>
												<div className="widget-list-action">
													<Link to="/widgets" className="text-gray-500"><i className="fa fa-angle-right fa-2x"></i></Link>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div className="col-lg-12 col-xl-8">
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetImgIcon')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
							<b className="text-inverse">WIDGET IMAGE / ICON</b>
						</div>
						<div className="row mb-4">
							<div className="col-sm-6">
								<div className="d-flex flex-wrap">
									<div className="widget-icon rounded bg-indigo me-5px mb-5px text-white">
										<i className="fab fa-digital-ocean"></i>
									</div>
									<div className="widget-icon rounded bg-blue me-5px mb-5px text-white">
										<i className="fab fa-twitter"></i>
									</div>
									<div className="widget-icon rounded bg-success me-5px mb-5px text-white">
										<i className="fab fa-android"></i>
									</div>
									<div className="widget-icon rounded bg-warning me-5px mb-5px text-white">
										<i className="fab fa-firefox"></i>
									</div>
									<div className="widget-icon rounded bg-danger me-5px mb-5px text-white">
										<i className="fab fa-google-plus-g"></i>
									</div>
									<div className="widget-icon rounded bg-pink me-5px mb-5px text-white">
										<i className="fab fa-pinterest"></i>
									</div>
								</div>
								<div className="clearfix">
									<div className={'widget-icon widget-icon-xl user rounded float-start me-5px mb-5px ' + (this.state.darkTheme ? 'text-white-transparent-5 bg-inverse ' : 'bg-gray-500 text-white')}>
										<i className="fa fa-user"></i>
									</div>
									<div className={'widget-icon widget-icon-lg user rounded float-start me-5px mb-5px ' + (this.state.darkTheme ? 'text-white-transparent-5 bg-inverse ' : 'bg-gray-500 text-white')}>
										<i className="fa fa-user"></i>
									</div>
									<div className={'widget-icon user rounded float-start me-5px mb-5px ' + (this.state.darkTheme ? 'text-white-transparent-5 bg-inverse ' : 'bg-gray-500 text-white')}>
										<i className="fa fa-user"></i>
									</div>
									<div className={'widget-icon widget-icon-sm user rounded float-start me-5px mb-5px ' + (this.state.darkTheme ? 'text-white-transparent-5 bg-inverse ' : 'bg-gray-500 text-white')}>
										<i className="fa fa-user"></i>
									</div>
									<div className={'widget-icon widget-icon-xs user rounded float-start me-5px mb-5px ' + (this.state.darkTheme ? 'text-white-transparent-5 bg-inverse ' : 'bg-gray-500 text-white')}>
										<i className="fa fa-user"></i>
									</div>
								</div>
							</div>
							<div className="col-sm-6">
								<div className="float-start">
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-11-thumb.jpg)'}}
									></div>
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-12-thumb.jpg)'}}
									></div>
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-13-thumb.jpg)'}}
									></div>
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-14-thumb.jpg)'}}
									></div>
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-15-thumb.jpg)'}}
									></div>
									<div className="widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-16-thumb.jpg)'}}
									></div>
									<br />
									<div className="widget-img widget-img-xl rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-21-thumb.jpg)'}}
									></div>
									<div className="widget-img widget-img-lg rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-22-thumb.jpg)'}}
									></div>
									<div className="widget-img widget-img rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-23-thumb.jpg)'}}
									></div>
									<div className="widget-img widget-img-sm rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-24-thumb.jpg)'}}
									></div>
									<div className="widget-img widget-img-xs rounded bg-inverse float-start me-5px mb-5px"
										style={{ backgroundImage: 'url(/assets/img/gallery/gallery-25-thumb.jpg)'}}
									></div>
								</div>
							</div>
						</div>
						
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetCard')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
							<b className="text-inverse">WIDGET CARD</b>
						</div>
						<div className="row">
							<div className="col-sm-6">
								<Link to="/widgets" className="widget-card rounded mb-20px" data-id="widget">
									<div className="widget-card-cover rounded" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-portrait-11-thumb.jpg)'}}></div>
									<div className="widget-card-content">
										<b className="text-white">Download and get free trial.</b>
									</div>
									<div className="widget-card-content bottom">
										<i className="fab fa-pushed fa-5x text-indigo"></i>
										<h4 className="text-white mt-10px"><b>Apple Draw<br /> Photo Booth</b></h4>
										<h5 className="fs-12px text-white-transparent-7 mb-0"><b>EASILY DRAW ON PHOTOS</b></h5>
									</div>
								</Link>
							</div>
							<div className="col-sm-6">
								<Link to="/widgets" className="widget-card rounded mb-20px" data-id="widget">
									<div className="widget-card-cover rounded"></div>
									<div className="widget-card-content">
										<h5 className={'fs-12px ' + (this.state.darkTheme ? 'text-white-transparent-7' : 'text-black-transparent-7')}><b>MAKING A DIFFERENCE</b></h5>
										<h4 className="mb-10px text-pink"><b>Apple Heart<br /> Study App</b></h4>
										<i className="fa fa-heartbeat fa-5x text-pink-transparent-5"></i>
									</div>
									<div className="widget-card-content bottom">
										<b className={'fs-12px ' + (this.state.darkTheme ? 'text-white-transparent-7' : 'text-black-transparent-7')}>Opt in and help heart research.</b>
									</div>
								</Link>
							</div>
						</div>
						
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetCardSquare')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
							<b className="text-inverse">WIDGET CARD SQUARE</b>
						</div>
						<div className="row">
							<div className="col-xs-6 col-sm-3">
								<div className="row gx-1 mb-1">
									<div className="col-6">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-1-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-2-thumb.jpg)'}}></div>
										</Link>
									</div>
									<div className="col-6">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-3-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-4-thumb.jpg)'}}></div>
										</Link>
									</div>
								</div>
								<div className="fs-12px fw-bold text-black">Camera Roll</div>
								<div className="fs-10px fw-bold text-black-lighter mb-3">2,711</div>
							</div>
							<div className="col-xs-6 col-sm-3">
								<Link to="/widgets" className="widget-card rounded square mb-5px">
									<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-7-thumb.jpg)'}}>
										<span className="widget-card-cover-icon"><i className="fa fa-play fa-lg"></i></span>
									</div>
								</Link>
								<div className="fs-12px fw-bold text-black pt-2px">Videos</div>
								<div className="fs-10px fw-bold text-black-lighter mb-3">31</div>
							</div>
							<div className="col-xs-6 col-sm-3">
								<Link to="/widgets" className="widget-card rounded square mb-5px">
									<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/login-bg/login-bg-10-thumb.jpg)'}}>
										<div className="d-flex h-100 align-items-center justify-content-center">
											<div className="text-center">
												<div className="text-white fs-14px"><b>PORTRAITS OF</b></div>
												<div className="text-white-transparent-8 fw-bold">2021</div>
											</div>
										</div>
									</div>
								</Link>
								<div className="fs-12px fw-bold text-black pt-2px">Memory 2021</div>
								<div className="fs-10px fw-bold text-black-lighter mb-3">1,239</div>
							</div>
							<div className="col-xs-6 col-sm-3">
								<div className="row gx-1 mb-5px">
									<div className="col-3">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-1-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-2-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-3-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-4-thumb.jpg)'}}></div>
										</Link>
									</div>
									<div className="col-3">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-5-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-6-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-7-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-8-thumb.jpg)'}}></div>
										</Link>
									</div>
									<div className="col-3">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-9-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-10-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-11-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-12-thumb.jpg)'}}></div>
										</Link>
									</div>
									<div className="col-3">
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-13-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-14-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-15-thumb.jpg)'}}></div>
										</Link>
										<Link to="/widgets" className="widget-card rounded square mb-1">
											<div className="widget-card-cover" style={{ backgroundImage: 'url(/assets/img/gallery/gallery-16-thumb.jpg)'}}></div>
										</Link>
									</div>
								</div>
								<div className="fs-12px fw-bold text-black">Albums</div>
								<div className="fs-10px fw-bold text-black-lighter mb-15px">8</div>
							</div>
						</div>
					
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetStat')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
							<b className="text-inverse">WIDGET STAT</b>
						</div>
						<div className="row gx-2 mb-20px">
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-teal mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-globe fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">TODAY'S VISITS</div>
										<div className="stats-number">7,842,900</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '70.1%'}}></div>
										</div>
										<div className="stats-desc">Better than last week (70.1%)</div>
									</div>
								</div>
							</div>
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-blue mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-dollar-sign fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">TODAY'S PROFIT</div>
										<div className="stats-number">180,200</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '40.5%'}}></div>
										</div>
										<div className="stats-desc">Better than last week (40.5%)</div>
									</div>
								</div>
							</div>
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-purple mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-archive fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">NEW ORDERS</div>
										<div className="stats-number">38,900</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '76.3%'}}></div>
										</div>
										<div className="stats-desc">Better than last week (76.3%)</div>
									</div>
								</div>
							</div>
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-black mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-comment-alt fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">NEW COMMENTS</div>
										<div className="stats-number">3,988</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '54.9%'}}></div>
										</div>
										<div className="stats-desc">Better than last week (54.9%)</div>
									</div>
								</div>
							</div>
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-orange mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-file-alt fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">PENDING INVOICE</div>
										<div className="stats-number">20</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '23.5%'}}></div>
										</div>
										<div className="stats-desc">More than last week (23.5%)</div>
									</div>
								</div>
							</div>
							<div className="col-lg-4 col-sm-6">
								<div className="widget widget-stats bg-pink mb-7px">
									<div className="stats-icon stats-icon-lg"><i className="fa fa-exclamation-triangle fa-fw"></i></div>
									<div className="stats-content">
										<div className="stats-title">ERROR LOG</div>
										<div className="stats-number">5</div>
										<div className="stats-progress progress">
											<div className="progress-bar" style={{width: '10.5%'}}></div>
										</div>
										<div className="stats-desc">More than last week (10.5%)</div>
									</div>
								</div>
							</div>
						</div>
					
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetChart')}  className="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</Link>
							<b className="text-inverse">WIDGET CHART</b>
						</div>
						<div className={'widget rounded mb-4 '+ (this.state.darkTheme ? 'inverse-mode ' : '')}>
							<div className="widget-header">
								<h4 className="widget-header-title">Audience Overview</h4>
								<div className="widget-header-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-fw fa-upload"></i></Link></div>
								<div className="widget-header-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-fw fa-cog"></i></Link></div>
							</div>
							<div className="row m-0">
								<div className="col widget-chart-content">
									<NVD3Chart type="stackedAreaChart" datum={this.areaChartData} height={350} options={this.areaChartOptions} />
								</div>
								<div className="col-lg-4 p-3">
									<div className="widget-chart-info">
										<h4 className="widget-chart-info-title">Total sales</h4>
										<p className="widget-chart-info-desc">Lorem ipsum dolor sit consectetur adipiscing elit.</p>
										<div className="widget-chart-info-progress">
											<b>Monthly Plan</b>
											<span className="float-end">70%</span>
										</div>
										<div className="progress h-10px">
											<div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill" style={{width: '70%'}}></div>
										</div>
									</div>
									<hr />
									<div className="widget-chart-info">
										<h4 className="widget-chart-info-title">Task progress</h4>
										<p className="widget-chart-info-desc">Vestibulum sollicitudin in lectus a cursus.</p>
										<div className="widget-chart-info-progress">
											<b>Marketing Research</b>
											<span className="float-end">74%</span>
										</div>
										<div className="progress h-10px mb-15px">
											<div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-indigo" style={{width: '74%'}}></div>
										</div>
										<div className="widget-chart-info-progress">
											<b>Mobile App Development</b>
											<span className="float-end">25%</span>
										</div>
										<div className="progress h-10px mb-15px">
											<div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-green" style={{width: '25%'}}></div>
										</div>
										<div className="widget-chart-info-progress">
											<b>Website Redesign</b>
											<span className="float-end">95%</span>
										</div>
										<div className="progress h-10px">
											<div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-orange" style={{width: '95%'}}></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					
						<div className="mb-10px mt-10px fs-10px">
							<Link to="/widgets" onClick={() => this.toggle('modalWidgetTable')} className="float-end text-gray-600 text-decoration-none me-3px fw-bold" data-bs-toggle="modal">source code</Link>
							<b className="text-inverse">WIDGET TABLE</b>
						</div>
						<div className="table-responsive">
							<table className={'table table-bordered widget-table rounded '+ (this.state.darkTheme ? 'inverse-mode ' : '')}>
								<thead>
									<tr className="text-nowrap">
										<th width="1%">Image</th>
										<th>Product Info</th>
										<th>Price</th>
										<th>Qty</th>
										<th>Total</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<img src="/assets/img/product/product-6.png" alt="" width="100" />
										</td>
										<td>
											<h5 className="mb-1">Mavic Pro Combo</h5>
											<p className="fs-11px fw-bold text-gray-600 mb-3">Portable yet powerful, the Mavic Pro is your personal drone, ready to go with you everywhere.</p>
											<div className="progress h-10px rounded-pill mb-5px">
												<div className="progress-bar progress-bar-striped progress-bar-animated bg-orange fs-10px fw-bold" style={{width: '30%'}}>30%</div>
											</div>
											<div className="clearfix fs-10px">
												status: 
												<b className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}>Shipped</b>
											</div>
										</td>
										<td className="text-nowrap">
											<b className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}>$999</b><br />
											<small className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}><del>$1,202</del></small>
										</td>
										<td>1</td>
										<td>999.00</td>
										<td>
											<Link to="/widgets" className={'btn btn-sm w-80px rounded-pill '+ (this.state.darkTheme ? 'btn-inverse ' : 'btn-default ')}>Edit</Link>
										</td>
									</tr>
									<tr>
										<td>
											<img src="/assets/img/product/product-7.png" alt="" width="100" />
										</td>
										<td>
											<h5 className="mb-1">Inspire 2</h5>
											<p className="fs-11px fw-bold text-gray-600 mb-3">Cinematic aerial performance for filmmakers.</p>
											<div className="progress h-10px rounded-pill mb-5px">
												<div className="progress-bar progress-bar-striped progress-bar-animated bg-success fs-10px fw-bold" style={{width: '100%'}}>100%</div>
											</div>
											<div className="clearfix fs-10px">
												status: 
												<b className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}>received</b>
											</div>
										</td>
										<td className="text-nowrap">
											<b className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}>$999</b><br />
											<small className={(this.state.darkTheme ? 'text-white ' : 'text-inverse')}><del>$1,202</del></small>
										</td>
										<td>1</td>
										<td>999.00</td>
										<td>
											<Link to="/widgets" className={'btn btn-sm w-80px rounded-pill '+ (this.state.darkTheme ? 'btn-inverse ' : 'btn-default ')}>Edit</Link>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<Modal size="lg "isOpen={this.state.modalWidgetMap} toggle={() => this.toggle('modalWidgetMap')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetMap')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetMap')}></button>}>Widget Map</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget-map rounded">\n'+
'  <div className="widget-input-container">\n'+
'    <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-ellipsis-v"></i></Link></div>\n'+
'    <div className="widget-input-box">\n'+
'      <input type="text" className="form-control" placeholder="Search here" />\n'+
'    </div>\n'+
'    <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-microphone"></i></Link></div>\n'+
'    <div className="widget-input-divider"></div>\n'+
'    <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-location-arrow"></i></Link></div>\n'+
'  </div>\n'+
'  <div className="widget-map-body">\n'+
'    <iframe className="d-block" title="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.5650178360584!2d-122.41879278478642!3d37.77679637975903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c6c8f4459%3A0xb10ed6d9b5050fa5!2sTwitter+HQ!5e0!3m2!1sen!2smy!4v1524046379645" width="100%" height="230" frameborder="0" style={{border:0}} allowfullscreen></iframe>\n'+
'  </div>\n'+
'  <div className="widget-map-list" data-id="widget">\n'+
'    <div className="widget-list bg-none">\n'+
'      <div className="widget-list-item">\n'+
'        <div className="widget-list-media text-center">\n'+
'          <Link to="/widgets"><i className="fab fa-twitter fa-3x"></i></Link>\n'+
'        </div>\n'+
'        <div className="widget-list-content">\n'+
'          <h4 className="widget-list-title">Twitter Headquater</h4>\n'+
'          <p className="widget-list-desc">Corporate Office</p>\n'+
'        </div>\n'+
'        <div className="widget-list-action">\n'+
'          <Link to="/widgets" className="text-gray-500"><i className="fa fa-angle-right fa-2x"></i></Link>\n'+
'        </div>\n'+
'      </div>\n'+
'    </div>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetMap')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetTodolist} toggle={() => this.toggle('modalWidgetTodolist')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetTodolist')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetTodolist')}></button>}>Widget Todolist</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget-todolist rounded">\n'+
'  <div className="widget-todolist-header">\n'+
'    <div className="widget-todolist-header-title">Todolist</div>\n'+
'    <div className="widget-todolist-header-total"><span>0</span><small>Done</small></div>\n'+
'  </div>\n'+
'  <div className="widget-todolist-body">\n'+
'    <div className="widget-todolist-item">\n'+
'      <div className="widget-todolist-input">\n'+
'        <div className="form-check">\n'+
'          <input className="form-check-input" type="checkbox" id="widget_todolist_1" />\n'+
'        </div>\n'+
'      </div>\n'+
'      <div className="widget-todolist-content">\n'+
'        <h6 className="mb-2px">Borrow Tony\'s travel guide</h6>\n'+
'        <div className="text-gray-500 fw-bold fs-11px">Vacation in Rome</div>\n'+
'      </div>\n'+
'      <div className="widget-todolist-icon">\n'+
'        <Link to="/widgets"><i className="fa fa-question-circle"></i></Link>\n'+
'      </div>\n'+
'    </div>\n'+
'    ...\n'+
'    <div className="widget-todolist-item">\n'+
'      <div className="widget-todolist-input">\n'+
'        <i className="fa fa-plus text-gray-500 fa-fw"></i>\n'+
'      </div>\n'+
'      <div className="widget-todolist-content">\n'+
'        <input type="text" className="form-control border-0 shadow-none rounded-0 p-0 h-20px bg-none" placeholder="Write your task here..." />\n'+
'      </div>\n'+
'    </div>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetTodolist')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetChatInput} toggle={() => this.toggle('modalWidgetChatInput')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetChatInput')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetChatInput')}></button>}>Widget Chat & Input</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget-chat rounded mb-4">\n'+
'  <div className="widget-chat-header">\n'+
'    <div className="widget-chat-header-icon">\n'+
'      <i className="fab fa-earlybirds w-30px h-30px fs-20px bg-yellow text-inverse d-flex align-items-center justify-content-center rounded"></i>\n'+
'    </div>\n'+
'    <div className="widget-chat-header-content">\n'+
'      <h4 className="widget-chat-header-title">Company Discussion Group</h4>\n'+
'      <p className="widget-chat-header-desc">55 members, 4 online</p>\n'+
'    </div>\n'+
'  </div>\n'+
'  <PerfectScrollbar className="widget-chat-body" style={{height: \'235px\'}} options={{suppressScrollX: true}}>\n'+
'    <div className="text-center text-gray-500 m-2 fw-bold">Today</div>\n'+
'    <div className="widget-chat-item with-media start">\n'+
'      <div className="widget-chat-media">\n'+
'        <img alt="" src="/assets/img/user/user-1.jpg" />\n'+
'      </div>\n'+
'      <div className="widget-chat-info">\n'+
'        <div className="widget-chat-info-container">\n'+
'          <div className="widget-chat-name text-indigo">Hudson Mendes</div>\n'+
'          <div className="widget-chat-message">Should we plan for a company trip this year?</div>\n'+
'          <div className="widget-chat-time">6:00PM</div>\n'+
'        </div>\n'+
'      </div>\n'+
'    </div>\n'+
'    ...\n'+
'  </PerfectScrollbar>\n'+
'  <div className="widget-input">\n'+
'    <form action="" method="POST" name="">\n'+
'      <div className="widget-input-container">\n'+
'        <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-camera"></i></Link></div>\n'+
'        <div className="widget-input-box">\n'+
'          <input type="text" className="form-control" placeholder="Write a message..." />\n'+
'        </div>\n'+
'        <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-smile"></i></Link></div>\n'+
'        <div className="widget-input-divider"></div>\n'+
'        <div className="widget-input-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-microphone"></i></Link></div>\n'+
'      </div>\n'+
'    </form>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetChatInput')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetImgIcon} toggle={() => this.toggle('modalWidgetImgIcon')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetImgIcon')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetImgIcon')}></button>}>Widget Image / Icon</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget-icon rounded bg-indigo me-5px mb-5px text-white">\n'+
'  <i className="fab fa-digital-ocean"></i>\n'+
'</div>\n'+
'\n'+
'<div className="widget-icon widget-icon-xl user rounded float-start me-5px mb-5px bg-gray-500 text-white">\n'+
'  <i className="fa fa-user"></i>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetImgIcon')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetCard} toggle={() => this.toggle('modalWidgetCard')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetCard')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetCard')}></button>}>Widget List Card</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Link to="/widgets" className="widget-card rounded mb-20px">\n'+
'  <div className="widget-card-cover rounded" style={{ backgroundImage: \'url(/assets/img/gallery/gallery-portrait-11-thumb.jpg)\'}}></div>\n'+
'  <div className="widget-card-content">\n'+
'    <b className="text-white">Download and get free trial.</b>\n'+
'  </div>\n'+
'  <div className="widget-card-content bottom">\n'+
'    <i className="fab fa-pushed fa-5x text-indigo"></i>\n'+
'    <h4 className="text-white mt-10px"><b>Apple Draw<br /> Photo Booth</b></h4>\n'+
'    <h5 className="fs-12px text-white-transparent-7 mb-0"><b>EASILY DRAW ON PHOTOS</b></h5>\n'+
'  </div>\n'+
'</Link>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetCard')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetCardSquare} toggle={() => this.toggle('modalWidgetCardSquare')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetCardSquare')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetCardSquare')}></button>}>Widget List Card Square</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Link to="/widgets" className="widget-card rounded square mb-1">\n'+
'  <div className="widget-card-cover" style={{ backgroundImage: \'url(/assets/img/login-bg/login-bg-1-thumb.jpg)\'}}></div>\n'+
'</Link>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetCardSquare')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetStat} toggle={() => this.toggle('modalWidgetStat')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetStat')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetStat')}></button>}>Widget Stat</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget widget-stats bg-teal mb-7px">\n'+
'  <div className="stats-icon stats-icon-lg"><i className="fa fa-globe fa-fw"></i></div>\n'+
'  <div className="stats-content">\n'+
'    <div className="stats-title">TODAY\'S VISITS</div>\n'+
'    <div className="stats-number">7,842,900</div>v\n'+
'    <div className="stats-progress progress">\n'+
'      <div className="progress-bar" style={{width: \'70.1%\'}}></div>\n'+
'    </div>\n'+
'    <div className="stats-desc">Better than last week (70.1%)</div>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetStat')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetChart} toggle={() => this.toggle('modalWidgetChart')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetChart')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetChart')}></button>}>Widget Chart</ModalHeader>
          <ModalBody options={this.codeMirrorOptions} className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget rounded mb-4">\n'+
'  <div className="widget-header">\n'+
'    <h4 className="widget-header-title">Audience Overview</h4>\n'+
'    <div className="widget-header-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-fw fa-upload"></i></Link></div>\n'+
'    <div className="widget-header-icon"><Link to="/widgets" className="text-gray-500"><i className="fa fa-fw fa-cog"></i></Link></div>\n'+
'  </div>\n'+
'  <div className="row m-0">\n'+
'    <div className="col widget-chart-content">\n'+
'      <NVD3Chart type="stackedAreaChart" datum={this.areaChartData} height={350} options={this.areaChartOptions} />\n'+
'    </div>\n'+
'    <div className="col-lg-4 p-3">\n'+
'      <div className="widget-chart-info">\n'+
'        <h4 className="widget-chart-info-title">Total sales</h4>\n'+
'        <p className="widget-chart-info-desc">Lorem ipsum dolor sit consectetur adipiscing elit.</p>\n'+
'        <div className="widget-chart-info-progress">\n'+
'          <b>Monthly Plan</b>\n'+
'          <span className="float-end">70%</span>\n'+
'        </div>\n'+
'        <div className="progress h-10px">\n'+
'          <div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill" style={{width: \'70%\'}}></div>\n'+
'        </div>\n'+
'      </div>\n'+
'      <hr />\n'+
'      <div className="widget-chart-info">\n'+
'        <h4 className="widget-chart-info-title">Task progress</h4>\n'+
'        <p className="widget-chart-info-desc">Vestibulum sollicitudin in lectus a cursus.</p>\n'+
'        <div className="widget-chart-info-progress">\n'+
'          <b>Marketing Research</b>\n'+
'          <span className="float-end">74%</span>\n'+
'        </div>\n'+
'        <div className="progress h-10px mb-15px">\n'+
'          <div className="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-indigo" style={{width: \'74%\'}}></div>\n'+
'        </div>\n'+
'      </div>\n'+
'    </div>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetChart')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetTable} toggle={() => this.toggle('modalWidgetTable')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetTable')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetTable')}></button>}>Widget Table</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="table-responsive">\n'+
'  <table className="table table-bordered widget-table rounded">\n'+
'    <thead>\n'+
'      <tr className="text-nowrap">\n'+
'        <th width="1%">Image</th>\n'+
'        <th>Product Info</th>\n'+
'        <th>Price</th>\n'+
'        <th>Qty</th>\n'+
'        <th>Total</th>\n'+
'        <th></th>\n'+
'      </tr>\n'+
'    </thead>\n'+
'    <tbody>\n'+
'      <tr>\n'+
'        <td>\n'+
'          <img src="/assets/img/product/product-6.png" alt="" width="100" />\n'+
'        </td>\n'+
'        <td>\n'+
'          <h5 className="mb-1">Mavic Pro Combo</h5>\n'+
'          <p className="fs-11px fw-bold text-gray-600 mb-3">Portable yet powerful, the Mavic Pro is your personal drone, ready to go with you everywhere.</p>\n'+
'          <div className="progress h-10px rounded-pill mb-5px">\n'+
'            <div className="progress-bar progress-bar-striped progress-bar-animated bg-orange fs-10px fw-bold" style={{width: \'30%\'}}>30%</div>\n'+
'          </div>\n'+
'          <div className="clearfix fs-10px">\n'+
'            status: \n'+
'            <b className="text-inverse">Shipped</b>\n'+
'          </div>\n'+
'        </td>\n'+
'        <td className="text-nowrap">\n'+
'          <b className="text-inverse">$999</b><br />\n'+
'          <small className="text-inverse"><del>$1,202</del></small>\n'+
'        </td>\n'+
'        <td>1</td>\n'+
'        <td>999.00</td>\n'+
'        <td>\n'+
'          <Link to="/widgets" className="btn btn-sm w-80px rounded-pill btn-default">Edit</Link>\n'+
'        </td>\n'+
'      </tr>\n'+
'    </tbody>\n'+
'  </table>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetTable')}>Close</Button>
          </ModalFooter>
        </Modal>
				<Modal size="lg "isOpen={this.state.modalWidgetList} toggle={() => this.toggle('modalWidgetList')} className={this.props.className}>
          <ModalHeader toggle={() => this.toggle('modalWidgetList')} close={<button className="btn-close" onClick={() => this.toggle('modalWidgetList')}></button>}>Widget List</ModalHeader>
          <ModalBody className="p-0">
          	<div className="hljs-wrapper">
							<Highlight className='html'>{
'<div className="widget-list rounded mb-4">\n'+
'  <div className="widget-list-item">\n'+
'    <div className="widget-list-media">\n'+
'      <img src="/assets/img/user/user-12.jpg" width="50" alt="" className="rounded" />\n'+
'    </div>\n'+
'    <div className="widget-list-content">\n'+
'      <h4 className="widget-list-title">Christopher Struth</h4>\n'+
'      <p className="widget-list-desc">Bank Transfer</p>\n'+
'    </div>\n'+
'    <div className="widget-list-action">\n'+
'      <UncontrolledDropdown>\n'+
'        <DropdownToggle tag="a" className="text-gray-500">\n'+
'          <i className="fa fa-ellipsis-h fs-14px"></i>\n'+
'        </DropdownToggle>\n'+
'        <DropdownMenu>\n'+
'          <DropdownItem>Option 1</DropdownItem>\n'+
'          <DropdownItem>Option 2</DropdownItem>\n'+
'          <DropdownItem>Option 3</DropdownItem>\n'+
'          <DropdownItem divider />\n'+
'          <DropdownItem>Option 4</DropdownItem>\n'+
'        </DropdownMenu>\n'+
'      </UncontrolledDropdown>\n'+
'    </div>\n'+
'  </div>\n'+
'</div>'}</Highlight>
						</div>
          </ModalBody>
          <ModalFooter>
            <Button color="default" onClick={() => this.toggle('modalWidgetList')}>Close</Button>
          </ModalFooter>
        </Modal>
			</div>
		)
	}
}

export default Widgets;