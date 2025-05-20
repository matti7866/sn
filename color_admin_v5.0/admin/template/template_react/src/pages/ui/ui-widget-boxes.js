import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelFooter, PanelBody } from './../../components/panel/panel.jsx';
import { UncontrolledDropdown, DropdownToggle, DropdownMenu, DropdownItem, Button, ButtonGroup, Alert } from 'reactstrap';
import PerfectScrollbar from 'react-perfect-scrollbar';
import { TabContent, TabPane, Nav, NavItem, NavLink } from 'reactstrap';
import classnames from 'classnames';
import Highlight from 'react-highlight';

class UIWidgetBoxes extends React.Component {
	constructor(props) {
		super(props);

		this.state = { 
			cSelected: [],
			activeTab: '1'
		};
		this.toggleTab = this.toggleTab.bind(this);
		this.onRadioBtnClick = this.onRadioBtnClick.bind(this);
	}

	onRadioBtnClick(rSelected) {
		this.setState({ rSelected });
	}

	toggleTab(tab) {
		if (this.state.activeTab !== tab) {
			this.setState({
				activeTab: tab
			});
		}
	}
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/ui/widget-boxes">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/ui/widget-boxes">UI Elements</Link></li>
					<li className="breadcrumb-item active">Widget Boxes</li>
				</ol>
				<h1 className="page-header">Widget Boxes <small>header small text goes here...</small></h1>
		
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Panel (Default)</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader>Panel (Default)</PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
				
						<Panel>
							<PanelHeader noButton={true}>
								<div className="d-flex align-items-center">
									Panel Header with Dropdown
									<UncontrolledDropdown className="ms-auto my-n1">
										<DropdownToggle caret className="btn-xs btn-success">
											Action <span className="caret"></span>
										</DropdownToggle>
										<DropdownMenu>
											<DropdownItem>Action</DropdownItem>
											<DropdownItem>Another Action</DropdownItem>
											<DropdownItem>Something else here</DropdownItem>
											<DropdownItem divider />
											<DropdownItem>Separated link</DropdownItem>
										</DropdownMenu>
									</UncontrolledDropdown>
								</div>
							</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader noButton={true}>\n'+
'    <div className="d-flex align-items-center">\n'+
'      Panel Header with Dropdown\n'+
'      <UncontrolledDropdown className="ms-auto my-n1">\n'+
'        <DropdownToggle caret className="btn-xs btn-success">\n'+
'          Dropdown <span className="caret"></span>\n'+
'        </DropdownToggle>\n'+
'        <DropdownMenu>\n'+
'          <DropdownItem>Action</DropdownItem>\n'+
'          <DropdownItem>Another Action</DropdownItem>\n'+
'          <DropdownItem>Something else here</DropdownItem>\n'+
'          <DropdownItem divider />\n'+
'          <DropdownItem>Separated link</DropdownItem>\n'+
'        </DropdownMenu>\n'+
'      </UncontrolledDropdown>\n'+
'    </div>\n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader noButton={true}>
								<div className="d-flex align-items-center">
									Panel Header with Radio Button
									<ButtonGroup className="ms-auto my-n1">
										<Button color="success" size="xs" onClick={() => this.onRadioBtnClick(1)} active={this.state.rSelected === 1}>Option 1</Button>
										<Button color="success" size="xs" onClick={() => this.onRadioBtnClick(2)} active={this.state.rSelected === 2}>Option 2</Button>
									</ButtonGroup>
								</div>
							</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader noButton={true}>\n'+
'    <div className="d-flex align-items-center">\n'+
'      Panel Header with Radio Button\n'+
'      <ButtonGroup className="pull-right">\n'+
'        <Button color="success" size="xs" onClick={() => this.onRadioBtnClick(1)} active={this.state.rSelected === 1}>Option 1</Button>\n'+
'        <Button color="success" size="xs" onClick={() => this.onRadioBtnClick(2)} active={this.state.rSelected === 2}>Option 2</Button>\n'+
'      </ButtonGroup>\n'+
'    </div>\n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader noButton={true}>
								<div className="d-flex align-items-center">
									Panel Header with Progress Bar
									<div className="progress progress-sm ms-auto my-n1 w-150px h-10px bg-gray-700">
										<div className="progress-bar progress-bar-striped bg-success progress-bar-animated" style={{width: '40%'}}>40%</div>
									</div>
								</div>
							</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader noButton={true}>\n'+
'    <div className="d-flex align-items-center">\n'+
'      Panel Header with Progress Bar\n'+
'      <div className="progress progress-sm ms-auto my-n1 w-150px h-10px bg-gray-700">\n'+
'        <div className="progress-bar progress-bar-striped bg-success progress-bar-animated">40%</div>\n'+
'      </div>\n'+
'    </div>\n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader noButton={true}>
								Panel Header with Badge <span className="badge bg-success ms-2">NEW</span> 
							</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader noButton={true}>\n'+
'    Panel Header with Badge <span className="badge bg-success ms-2">NEW</span> \n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader>
								Panel with Alert Box
							</PanelHeader>
							<Alert color="success" className="mb-0 rounded-0">
								<i className="fa fa-check fa-2x float-start me-3"></i>
								<p className="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ac posuere lacus, quis suscipit sem. Nulla sagittis aliquam erat non convallis.</p>
							</Alert>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader>\n'+
'    Panel with Alert Box\n'+
'  </PanelHeader>\n'+
'  <Alert color="success" className="mb-0 rounded-0">\n'+
'    <i className="fa fa-check fa-2x float-start me-3"></i>\n'+
'    <p className="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ac posuere lacus, quis suscipit sem. Nulla sagittis aliquam erat non convallis.</p>\n'+
'  </Alert>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel className="panel-hover-icon">
							<PanelHeader>
								Hover View Icon
							</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel className="panel-hover-icon">\n'+
'  <PanelHeader>\n'+
'    Hover View Icon\n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <p>Panel Content Here</p>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader>
								Panel with Scrollbar
							</PanelHeader>
							<PanelBody>
								<PerfectScrollbar style={{height: '280px'}} options={{suppressScrollX: true}}>
									<p>
										<span className="fa-stack fa-4x float-start me-10px text-inverse">
											<i className="fab fa-twitter fa-stack-1x"></i>
										</span>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque sed enim arcu. 
										Ut posuere in ligula quis ultricies. In in justo turpis. Donec ut dui at massa gravida 
										interdum nec vitae justo. Quisque ullamcorper vehicula dictum. Nullam hendrerit interdum eleifend. 
										Aenean luctus sed arcu laoreet scelerisque. Vivamus non ullamcorper mauris, id sagittis lorem. 
										Proin tincidunt mauris et dolor mattis imperdiet. Sed facilisis mattis diam elementum adipiscing. 
									</p>
									<p>
										<span className="fa-stack fa-4x float-end ms-10px text-inverse">
											<i className="fab fa-google-plus fa-stack-1x"></i>
										</span>
										Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
										Ut ante velit, pretium non nisi a, egestas placerat diam. Nullam aliquet iaculis ultricies. 
										Aliquam volutpat, sapien quis volutpat elementum, ligula purus auctor diam, at vestibulum nulla augue 
										vel purus. Praesent ac nisl a magna tincidunt interdum sed in turpis. Maecenas nec condimentum risus. 
										In congue pretium est, eget euismod tortor ornare quis.
									</p>
									<p>
										<span className="fa-stack fa-4x float-start me-10px text-inverse">
											<i className="fab fa-facebook fa-stack-1x"></i>
										</span>
										Praesent eu ultrices justo. Vestibulum ante 
										ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; 
										Cras mattis ipsum quis sapien consectetur fringilla. 
										Etiam sagittis sem tempus purus elementum, vitae pretium sapien porta. Curabitur iaculis ante ut aliquam luctus. 
										Vivamus ullamcorper blandit imperdiet. Ut egestas, orci id rhoncus cursus, orci risus scelerisque enim, eget mattis eros lacus quis ligula. 
										Vivamus ullamcorper urna eget hendrerit laoreet.
									</p>
									<p>
										<span className="fa-stack fa-4x float-end ms-10px text-inverse">
											<i className="fab fa-apple fa-stack-1x"></i>
										</span>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
										Morbi accumsan velit dolor. Donec convallis eleifend magna, at euismod tellus convallis a. 
										Curabitur in nisi dolor. Cras viverra scelerisque orci, sed interdum ligula volutpat non. 
										Nunc eu enim ac neque tempor feugiat. Duis posuere lacus non magna eleifend, 
										non dictum sem feugiat. Duis eleifend condimentum pulvinar.
									</p>
								</PerfectScrollbar>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader>\n'+
'    Panel with Scrollbar\n'+
'  </PanelHeader>\n'+
'  <PanelBody>\n'+
'    <PerfectScrollbar className="height-sm" options={{suppressScrollX: true}}>\n'+
'      ...\n'+
'    </PerfectScrollbar>\n'+
'  </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader>
								Panel with Toolbar & Footer
							</PanelHeader>
							<div className="panel-toolbar">
								<div className="btn-group me-5px">
									<button className="btn btn-white"><i className="fa fa-bold"></i></button>
									<button className="btn btn-white active"><i className="fa fa-italic"></i></button>
									<button className="btn btn-white"><i className="fa fa-underline"></i></button>
								</div>
								<div className="btn-group">
									<button className="btn btn-white"><i className="fa fa-align-left"></i></button>
									<button className="btn btn-white active"><i className="fa fa-align-center"></i></button>
									<button className="btn btn-white"><i className="fa fa-align-right"></i></button>
									<button className="btn btn-white"><i className="fa fa-align-justify"></i></button>
								</div>
							</div>
							<textarea className="form-control rounded-0 border-start-0 border-end-0 p-3 bg-light" rows="5" defaultValue="This is a form textarea."></textarea>
							<PanelFooter className="text-end border-0">
								<button className="btn btn-white btn-sm">Cancel</button>
								<button className="btn btn-primary btn-sm ms-5px">Action</button>
							</PanelFooter>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader>\n'+
'    Panel with Toolbar & Footer\n'+
'  </PanelHeader>\n'+
'  <div className="panel-toolbar">\n'+
'    <div className="btn-group me-5px">\n'+
'      ...\n'+
'    </div>\n'+
'    <div className="btn-group">\n'+
'      ...\n'+
'    </div>\n'+
'  </div>\n'+
'  <textarea className="form-control rounded-0 border-start-0 border-end-0 p-3 bg-light" rows="5">This is a form textarea.</textarea>\n'+
'  <PanelFooter className="text-end border-0">\n'+
'    <a href="#" className="btn btn-white btn-sm">Cancel</a>\n'+
'    <a href="#" className="btn btn-primary btn-sm ms-5px">Action</a>\n'+
'  </PanelFooter>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel theme="default" className="panel-with-tabs">
							<PanelHeader noButton={true}>
								<div className="d-flex align-items-end">
									Panel with Tabs
									
									<Nav tabs className="nav nav-tabs ms-auto mb-n3 mt-n1">
										<NavItem>
											<NavLink className={'pt-2 pb-2 ' + classnames({ active: this.state.activeTab === '1' })}
												onClick={() => { this.toggleTab('1'); }}>
												Home
											</NavLink>
										</NavItem>
										<NavItem>
											<NavLink className={'pt-2 pb-2 ' + classnames({ active: this.state.activeTab === '2' })}
												onClick={() => { this.toggleTab('2'); }}>
												Profile
											</NavLink>
										</NavItem>
									</Nav>
								</div>
							</PanelHeader>
							<PanelBody>
								<TabContent activeTab={this.state.activeTab}>
									<TabPane tabId="1">
										<p className="m-b-0">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
									</TabPane>
									<TabPane tabId="2">
										<p className="m-b-0">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
									</TabPane>
								</TabContent>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel theme="default" className="panel-with-tabs">\n'+
'  <PanelHeader noButton={true}>\n'+
'   <div className="d-flex align-items-end">\n'+
'      Panel with Tabs\n'+
'      <Nav tabs className="nav nav-tabs ms-auto mb-n3 mt-n1">\n'+
'        <NavItem>\n'+
'          <NavLink className="pt-2 pb-2">\n'+
'            Home\n'+
'          </NavLink>\n'+
'        </NavItem>\n'+
'        <NavItem>\n'+
'          <NavLink className="pt-2 pb-2">\n'+
'            Profile\n'+
'          </NavLink>\n'+
'        </NavItem>\n'+
'      </Nav>\n'+
'   </div>\n'+
'  </PanelHeader>\n'+
' <PanelBody>\n'+
'    <TabContent activeTab={this.state.activeTab}>\n'+
'      <TabPane tabId="1">\n'+
'        <p className="m-b-0">...</p>\n'+
'      </TabPane>\n'+
'      <TabPane tabId="2">\n'+
'        <p className="m-b-0">...</p>\n'+
'      </TabPane>\n'+
'    </TabContent>\n'+
' </PanelBody>\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
					</div>
				</div>
		
				<h3 className="mb-3 mt-4">Panel Theme</h3>
		
				<div className="row">
					<div className="col-xl-6">
						<Panel theme="default">
							<PanelHeader>Panel (Default)</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel theme="default">\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader className="bg-teal-700 text-white">Panel Success</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader className="bg-teal-700 text-white">...</PanelHeader>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader className="bg-orange-700 text-white">Panel Warning</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader className="bg-orange-700 text-white">...</PanelHeader>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader className="bg-red-700 text-white">Panel Danger</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader className="bg-red-700 text-white">...</PanelHeader>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Panel Inverse</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader className="bg-blue-700 text-white">Panel Primary</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader className="bg-blue-700 text-white">...</PanelHeader>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
				
						<Panel>
							<PanelHeader className="bg-cyan-700 text-white">Panel Info</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<Panel>\n'+
'  <PanelHeader className="bg-cyan-700 text-white">...</PanelHeader>\n'+
'  ...\n'+
'</Panel>'}</Highlight>
						</div>
						</Panel>
					</div>
				</div>
		
		
				<h3 className="mt-4">Full Color Panel Theme</h3>
				<p className="mb-3">
					You can customize the panel body / header <code>background</code> & <code>text color</code> by adding predefined CSS class. E.g <code>.bg-black</code>. Full list of available predefined background class can be found <a href="helper_css.html">here</a>.
				</p>
		
				<div className="row">
					<div className="col-xl-6">
						<Panel className="bg-gray-700">
							<PanelHeader>Full Color Panel</PanelHeader>
							<PanelBody className="text-white">
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<Panel className="bg-gray-700">\n'+
'  <PanelHeader>Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
				
						<Panel className="bg-blue text-white">
							<PanelHeader className="bg-blue-700 text-white">Full Color Panel</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							  <Highlight className='html'>{
'<Panel className="bg-blue text-white">\n'+
'  <PanelHeader className="bg-blue-700 text-white">Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
				
						<Panel className="bg-teal text-white">
							<PanelHeader className="bg-teal-700 text-white">Full Color Panel</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							  <Highlight className='html'>{
'<Panel className="bg-teal text-white">\n'+
'  <PanelHeader className="bg-teal-700 text-white">Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel className="bg-orange text-white">
							<PanelHeader className="bg-orange-700 text-white">Full Color Panel</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							  <Highlight className='html'>{
'<Panel className="bg-orange text-white">\n'+
'  <PanelHeader className="bg-orange-700 text-white">Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
				
						<Panel className="bg-red text-white">
							<PanelHeader className="bg-red-700 text-white">Full Color Panel</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							  <Highlight className='html'>{
'<Panel className="bg-red text-white">\n'+
'  <PanelHeader className="bg-red-700 text-white">Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
				
						<Panel className="bg-cyan text-white">
							<PanelHeader className="bg-cyan-700 text-white">Full Color Panel</PanelHeader>
							<PanelBody>
								<p>Panel Content Here</p>
							</PanelBody>
							<div className="hljs-wrapper">
							  <Highlight className='html'>{
'<Panel className="bg-cyan text-white">\n'+
'  <PanelHeader className="bg-cyan-700 text-white">Full Color Panel</PanelHeader>\n'+
'  <PanelBody>\n'+
'    ...\n'+
'  </PanelBody>\n'+
'</Panel>'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
			</div>
		)
	}
}

export default UIWidgetBoxes