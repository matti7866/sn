import React from 'react';
import { Link } from 'react-router-dom';
import { TabContent, TabPane, Nav, NavItem, NavLink } from 'reactstrap';
import { Collapse, CardHeader, CardBody, Card } from 'reactstrap';
import classnames from 'classnames';
import Highlight from 'react-highlight';

class UITabsAccordion extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			activeTab: '1',
			activePill: '1',
			collapse: [
				{	id: 1, collapse: true },
				{	id: 2, collapse: false },
				{	id: 3, collapse: false },
				{	id: 4, collapse: false },
				{	id: 5, collapse: false },
				{	id: 6, collapse: false },
				{	id: 7, collapse: false },
			]
		};
		this.toggleCollapse = this.toggleCollapse.bind(this);
	}
	
	toggleTab(tab) {
		if (this.state.activeTab !== tab) {
			this.setState({
				activeTab: tab
			});
		}
	}
	
	togglePill(pill) {
		if (this.state.activePill !== pill) {
			this.setState({
				activePill: pill
			});
		}
	}
	
	toggleCollapse(index) {
		var newArray = [];
		for (let collapseObj of this.state.collapse) {
			if (collapseObj.id === index) {
				collapseObj.collapse = !collapseObj.collapse;
			} else {
				collapseObj.collapse = false;
			}
			newArray.push(collapseObj);
		}

		this.setState({
			collapse: newArray
		});
	}
  
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/ui">UI Elements</Link></li>
					<li className="breadcrumb-item active">Tabs & Accordions</li>
				</ol>
				<h1 className="page-header">Tabs & Accordions <small>header small text goes here...</small></h1>
				<div className="row">
					<div className="col-xl-6">
						<Nav tabs>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activeTab === '1' })}
									onClick={() => { this.toggleTab('1'); }}
								>
									<span className="d-sm-none">Tab 1</span>
									<span className="d-sm-block d-none">Default Tab 1</span>
								</NavLink>
							</NavItem>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activeTab === '2' })}
									onClick={() => { this.toggleTab('2'); }}
								>
									<span className="d-sm-none">Tab 2</span>
									<span className="d-sm-block d-none">Default Tab 2</span>
								</NavLink>
							</NavItem>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activeTab === '3' })}
									onClick={() => { this.toggleTab('3'); }}
								>
									<span className="d-sm-none">Tab 3</span>
									<span className="d-sm-block d-none">Default Tab 3</span>
								</NavLink>
							</NavItem>
						</Nav>
						<TabContent className="tab-content p-3 bg-white" activeTab={this.state.activeTab}>
							<TabPane tabId="1">
								<h3 className="mt-10px"><i className="fa fa-cog"></i> Lorem ipsum dolor sit amet</h3>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
									Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
									est diam sagittis orci, a ornare nisi quam elementum tortor. Proin interdum ante porta est convallis 
									dapibus dictum in nibh. Aenean quis massa congue metus mollis fermentum eget et tellus. 
									Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, nec eleifend orci eros id lectus.
								</p>
								<p className="text-end mb-0">
									<Link to="/ui/tabs-accordion" className="btn btn-white me-5px">Default</Link>
									<Link to="/ui/tabs-accordion" className="btn btn-primary">Primary</Link>
								</p>
							</TabPane>
							<TabPane tabId="2">
								<blockquote className="blockquote">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
								</blockquote>
								<figcaption className="blockquote-footer">
									Someone famous in <cite title="Source Title">Source Title</cite>
								</figcaption>
								<h4>Lorem ipsum dolor sit amet</h4>
								<p>
									Nullam ac sapien justo. Nam augue mauris, malesuada non magna sed, feugiat blandit ligula. 
									In tristique tincidunt purus id iaculis. Pellentesque volutpat tortor a mauris convallis, 
									sit amet scelerisque lectus adipiscing.
								</p>
							</TabPane>
							<TabPane tabId="3">
								<p>
									<span className="fa-stack fa-4x float-start me-10px">
										<i className="fa fa-square fa-stack-2x"></i>
										<i className="fab fa-twitter text-white fa-stack-1x"></i>
									</span>
									Praesent tincidunt nulla ut elit vestibulum viverra. Sed placerat magna eget eros accumsan elementum. 
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam quis lobortis neque. 
									Maecenas justo odio, bibendum fringilla quam nec, commodo rutrum quam. 
									Donec cursus erat in lacus congue sodales. Nunc bibendum id augue sit amet placerat. 
									Quisque et quam id felis tempus volutpat at at diam. Vivamus ac diam turpis.Sed at lacinia augue. 
									Nulla facilisi. Fusce at erat suscipit, dapibus elit quis, luctus nulla. 
									Quisque adipiscing dui nec orci fermentum blandit.
									Sed at lacinia augue. Nulla facilisi. Fusce at erat suscipit, dapibus elit quis, luctus nulla. 
									Quisque adipiscing dui nec orci fermentum blandit.
								</p>
							</TabPane>
						</TabContent>
						<div className="hljs-wrapper rounded-0 rounded-bottom mb-4">
							<Highlight className='html'>{
'class UITabsAccordion extends React.Component {\n'+
'  constructor(props) {\n'+
'    super(props);\n'+
'    this.state = { activeTab: \'1\' };\n'+
'  }\n'+
'  \n'+
'  toggleTab(tab) {\n'+
'    if (this.state.activeTab !== tab) {\n'+
'      this.setState({ activeTab: tab });\n'+
'    }\n'+
'  }\n'+
'}\n'+
'<Nav tabs>\n'+
'  <NavItem>\n'+
'    <NavLink className={classnames({ active: this.state.activeTab === \'1\' })} onClick={() => { this.toggleTab(\'1\'); }}>\n'+
'      <span className="d-sm-none">Tab 1</span>\n'+
'      <span className="d-sm-block d-none">Default Tab 1</span>\n'+
'    </NavLink>\n'+
'  </NavItem>\n'+
'  <TabContent className="tab-content p-3 bg-white" activeTab={this.state.activeTab}>\n'+
'    <TabPane tabId="1">\n'+
'      ...\n'+
'    </TabPane>\n'+
'  </TabContent>\n'+
'</Nav>'}
							</Highlight>
						</div>
						
						<Nav className="mb-3" pills>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activePill === '1' })}
									onClick={() => { this.togglePill('1'); }}
								>
									<span className="d-sm-none">Pills 1</span>
									<span className="d-sm-block d-none">Pills Tab 1</span>
								</NavLink>
							</NavItem>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activePill === '2' })}
									onClick={() => { this.togglePill('2'); }}
								>
									<span className="d-sm-none">Pills 2</span>
									<span className="d-sm-block d-none">Pills Tab 2</span>
								</NavLink>
							</NavItem>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activePill === '3' })}
									onClick={() => { this.togglePill('3'); }}
								>
									<span className="d-sm-none">Pills 3</span>
									<span className="d-sm-block d-none">Pills Tab 3</span>
								</NavLink>
							</NavItem>
							<NavItem>
								<NavLink
									className={classnames({ active: this.state.activePill === '4' })}
									onClick={() => { this.togglePill('4'); }}
								>
									<span className="d-sm-none">Pills 4</span>
									<span className="d-sm-block d-none">Pills Tab 4</span>
								</NavLink>
							</NavItem>
						</Nav>
						<TabContent className="p-3 rounded-top bg-white" activeTab={this.state.activePill}>
							<TabPane tabId="1">
								<h3 className="mt-10px">Nav Pills Tab 1</h3>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
									Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
									est diam sagittis orci, a ornare nisi quam elementum tortor. 
									Proin interdum ante porta est convallis dapibus dictum in nibh. 
									Aenean quis massa congue metus mollis fermentum eget et tellus. 
									Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
									nec eleifend orci eros id lectus.
								</p>
							</TabPane>
							<TabPane tabId="2">
								<h3 className="mt-10px">Nav Pills Tab 2</h3>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
									Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
									est diam sagittis orci, a ornare nisi quam elementum tortor. 
									Proin interdum ante porta est convallis dapibus dictum in nibh. 
									Aenean quis massa congue metus mollis fermentum eget et tellus. 
									Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
									nec eleifend orci eros id lectus.
								</p>
							</TabPane>
							<TabPane tabId="3">
								<h3 className="mt-10px">Nav Pills Tab 3</h3>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
									Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
									est diam sagittis orci, a ornare nisi quam elementum tortor. 
									Proin interdum ante porta est convallis dapibus dictum in nibh. 
									Aenean quis massa congue metus mollis fermentum eget et tellus. 
									Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
									nec eleifend orci eros id lectus.
								</p>
							</TabPane>
							<TabPane tabId="4">
								<h3 className="mt-10px">Nav Pills Tab 4</h3>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
									Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
									est diam sagittis orci, a ornare nisi quam elementum tortor. 
									Proin interdum ante porta est convallis dapibus dictum in nibh. 
									Aenean quis massa congue metus mollis fermentum eget et tellus. 
									Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
									nec eleifend orci eros id lectus.
								</p>
							</TabPane>
						</TabContent>
						<div className="hljs-wrapper rounded-0 rounded-bottom mb-4">
							<Highlight className='html'>{
'class UITabsAccordion extends React.Component {\n'+
'  constructor(props) {\n'+
'    super(props);\n'+
'    this.state = { activePill: \'1\' };\n'+
'  }\n'+
'  \n'+
'  togglePill(pill) {\n'+
'    if (this.state.activePill !== pill) {\n'+
'      this.setState({ activePill: pill });\n'+
'    }\n'+
'  }\n'+
'}\n'+
'<Nav pills>\n'+
'  <NavItem>\n'+
'    <NavLink className={classnames({ active: this.state.activePill === \'1\' })} onClick={() => { this.togglePill(\'1\'); }}>\n'+
'      <span className="d-sm-none">Pill 1</span>\n'+
'      <span className="d-sm-block d-none">Pill Tab 1</span>\n'+
'    </NavLink>\n'+
'  </NavItem>\n'+
'  <TabContent className="tab-content p-3 bg-white" activeTab={this.state.activePill}>\n'+
'    <TabPane tabId="1">\n'+
'      ...\n'+
'    </TabPane>\n'+
'  </TabContent>\n'+
'</Nav>'}
							</Highlight>
						</div>
					</div>
					<div className="col-xl-6">
						<div id="accordion" className="accordion rounded-top overflow-hidden">
							{
								this.state.collapse.map((value, i) => (
								<Card className="bg-gray-700 text-white rounded-0 border-bottom-0" key={i}>
									<CardHeader className={'card-header bg-gray-900 text-white pointer-cursor rounded-0 d-flex align-items-center ' + (!value.collapse ? 'collapsed ' : '')} onClick={() => this.toggleCollapse(value.id)}>
										<i className="fa fa-circle fs-6px me-2 text-indigo"></i> Collapsible Group Item #{value.id}
									</CardHeader>
									<Collapse isOpen={value.collapse}>
										<CardBody>
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										</CardBody>
									</Collapse>
								</Card>
								))
							}
						</div>
						<div className="hljs-wrapper rounded-0 rounded-bottom mb-4">
							<Highlight className='html'>{
'class UITabsAccordion extends React.Component {\n'+
'  constructor(props) {\n'+
'    this.state = {\n'+
'      collapse: [\n'+
'        {  id: 1, collapse: true },\n'+
'      ]\n'+
'    };\n'+
'    this.toggleCollapse = this.toggleCollapse.bind(this);\n'+
'  }\n'+
'  \n'+
'  toggleCollapse(index) {\n'+
'    var newArray = [];\n'+
'    for (let collapseObj of this.state.collapse) {\n'+
'      if (collapseObj.id === index) {\n'+
'        collapseObj.collapse = !collapseObj.collapse;\n'+
'      } else {\n'+
'        collapseObj.collapse = false;\n'+
'      }\n'+
'      newArray.push(collapseObj);\n'+
'    }\n'+
'    this.setState({ collapse: newArray });\n'+
'  }\n\n'+
'<div id="accordion" className="accordion rounded-top overflow-hidden">\n'+
'  {\n'+
'    this.state.collapse.map((value, i) => (\n'+
'    <Card className="bg-gray-700 text-white rounded-0 border-bottom-0" key={i}>\n'+
'      <CardHeader className={\'card-header bg-gray-900 text-white pointer-cursor rounded-0 d-flex align-items-center \' + (!value.collapse ? \'collapsed \' : \'\')} onClick={() => this.toggleCollapse(value.id)}>\n'+
'        <i className="fa fa-circle fs-6px me-2 text-indigo"></i> Collapsible Group Item #{value.id}\n'+
'      </CardHeader>\n'+
'      <Collapse isOpen={value.collapse}>\n'+
'        <CardBody>\n'+
'          ...\n'+
'        </CardBody>\n'+
'      </Collapse>\n'+
'    </Card>\n'+
'    ))\n'+
'  }\n'+
'</div>'}
							</Highlight>
						</div>
					</div>
				</div>
			</div>
		)
	}
}

export default UITabsAccordion