import React from 'react';
import { Link } from 'react-router-dom';
import { TabContent, TabPane, Nav, NavItem, NavLink } from 'reactstrap';
import classnames from 'classnames';

class HelperCSS extends React.Component {
	constructor(props) {
		super(props);

		this.toggle = this.toggle.bind(this);
		this.state = {
			activeTab: '1'
		};
	}

	toggle(tab) {
		if (this.state.activeTab !== tab) {
			this.setState({
				activeTab: tab
			});
		}
	}
  
	render() {
		return (
			<React.Fragment>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/helper/css">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/helper/css">CSS Helper</Link></li>
					<li className="breadcrumb-item active">Predefined CSS Class</li>
				</ol>
				<h1 className="page-header">Predefined CSS Class <small>header small text goes here...</small></h1>
				
				<Nav tabs className="nav-tabs-inverse">
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '1' })} onClick={() => { this.toggle('1'); }}>
              <i className="fa fa-fw fa-lg fa-cog"></i> <span className="d-none d-xl-inline">General</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '2' })} onClick={() => { this.toggle('2'); }}>
            	<i className="fa fa-fw fa-lg fa-arrows-alt-h"></i> <span className="d-none d-xl-inline">Width & Height</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '3' })} onClick={() => { this.toggle('3'); }}>
            	<i className="fa fa-fw fa-lg fa-text-height"></i> <span className="d-none d-xl-inline">Text & Font</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '4' })} onClick={() => { this.toggle('4'); }}>
            	<i className="fa fa-fw fa-lg fa-arrows-alt"></i> <span className="d-none d-xl-inline">Margin</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '5' })} onClick={() => { this.toggle('5'); }}>
            	<i className="fa fa-fw fa-lg fa-expand"></i> <span className="d-none d-xl-inline">Padding</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '6' })} onClick={() => { this.toggle('6'); }}>
            	<i className="fa fa-fw fa-lg fa-paint-brush"></i> <span className="d-none d-xl-inline">Background Color</span>
            </NavLink>
          </NavItem>
          <NavItem>
            <NavLink className={classnames({ active: this.state.activeTab === '7' })} onClick={() => { this.toggle('7'); }}>
            	<i className="fa fa-fw fa-lg fa-font text-gradient bg-blue"></i> <span className="d-none d-xl-inline">Text Color</span>
            </NavLink>
          </NavItem>
        </Nav>
        <TabContent className="rounded-bottom p-3 bg-white" activeTab={this.state.activeTab}>
          <TabPane tabId="1">
						<h4 className="mb-1"><b>General CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-bordered table-condensed">
								<thead>
									<tr>
										<th width="15%">Row Space</th>
										<th width="15%">Table</th>
										<th width="15%">Float</th>
										<th width="15%">Border Radius</th>
										<th width="15%">Display</th>
										<th width="15%">Overflow</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.row.gx-1</td></tr>
													<tr><td nowrap="true">.row.gx-2</td></tr>
													<tr><td nowrap="true">.row.gx-3</td></tr>
													<tr><td nowrap="true">.row.gx-4</td></tr>
													<tr><td nowrap="true">.row.gx-5</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.align-baseline</td></tr>
													<tr><td nowrap="true">.align-top</td></tr>
													<tr><td nowrap="true">.align-middle</td></tr>
													<tr><td nowrap="true">.align-bottom</td></tr>
													<tr><td nowrap="true">.align-text-top</td></tr>
													<tr><td nowrap="true">.align-text-bottom</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.float-start</td></tr>
													<tr><td nowrap="true">.float-end</td></tr>
													<tr><td nowrap="true">.float-none</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.rounded-0</td></tr>
													<tr><td nowrap="true">.rounded-1</td></tr>
													<tr><td nowrap="true">.rounded-2</td></tr>
													<tr><td nowrap="true">.rounded-3</td></tr>
													<tr><td nowrap="true">.rounded-top</td></tr>
													<tr><td nowrap="true">.rounded-end</td></tr>
													<tr><td nowrap="true">.rounded-bottom</td></tr>
													<tr><td nowrap="true">.rounded-start</td></tr>
													<tr><td nowrap="true">.rounded-circle</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.rounded-pill</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.d-none</td></tr>
													<tr><td nowrap="true">.d-inline</td></tr>
													<tr><td nowrap="true">.d-inline-block</td></tr>
													<tr><td nowrap="true">.d-block</td></tr>
													<tr><td nowrap="true">.d-grid</td></tr>
													<tr><td nowrap="true">.d-table</td></tr>
													<tr><td nowrap="true">.d-table-cell</td></tr>
													<tr><td nowrap="true">.d-table-row</td></tr>
													<tr><td nowrap="true">.d-flex</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.d-inline-flex</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.overflow-auto</td></tr>
													<tr><td nowrap="true">.overflow-hidden</td></tr>
													<tr><td nowrap="true">.overflow-visible</td></tr>
													<tr><td nowrap="true">.overflow-scroll</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="15%">Flex</th>
										<th width="15%">Borders</th>
										<th width="15%">Position</th>
										<th width="15%">Interactions</th>
										<th width="15%">Shadows</th>
										<th width="15%">Visibility</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.flex-row</td></tr>
													<tr><td nowrap="true">.flex-row-reverse</td></tr>
													<tr><td nowrap="true">.flex-column</td></tr>
													<tr><td nowrap="true">.flex-column-reverse</td></tr>
													<tr><td nowrap="true">.justify-content-start</td></tr>
													<tr><td nowrap="true">.justify-content-end</td></tr>
													<tr><td nowrap="true">.justify-content-center</td></tr>
													<tr><td nowrap="true">.justify-content-between</td></tr>
													<tr><td nowrap="true">.justify-content-around</td></tr>
													<tr><td nowrap="true">.justify-content-evenly</td></tr>
													<tr><td nowrap="true">.align-items-start</td></tr>
													<tr><td nowrap="true">.align-items-end</td></tr>
													<tr><td nowrap="true">.align-items-center</td></tr>
													<tr><td nowrap="true">.align-items-baseline</td></tr>
													<tr><td nowrap="true">.align-items-stretch</td></tr>
													<tr><td nowrap="true">.align-self-start</td></tr>
													<tr><td nowrap="true">.align-self-end</td></tr>
													<tr><td nowrap="true">.align-self-center</td></tr>
													<tr><td nowrap="true">.align-self-baseline</td></tr>
													<tr><td nowrap="true">.align-self-stretch</td></tr>
													<tr><td nowrap="true">.flex-grow-1</td></tr>
													<tr><td nowrap="true">.flex-grow-0</td></tr>
													<tr><td nowrap="true">.flex-shrink-1</td></tr>
													<tr><td nowrap="true">.flex-shrink-0</td></tr>
													<tr><td nowrap="true">.flex-nowrap</td></tr>
													<tr><td nowrap="true">.flex-wrap</td></tr>
													<tr><td nowrap="true">.flex-wrap-reverse</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.order-{1|2|3|4|5}</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.border</td></tr>
													<tr><td nowrap="true">.border-top</td></tr>
													<tr><td nowrap="true">.border-end</td></tr>
													<tr><td nowrap="true">.border-bottom</td></tr>
													<tr><td nowrap="true">.border-start</td></tr>
													<tr><td nowrap="true">.border-0</td></tr>
													<tr><td nowrap="true">.border-top-0</td></tr>
													<tr><td nowrap="true">.border-end-0</td></tr>
													<tr><td nowrap="true">.border-bottom-0</td></tr>
													<tr><td nowrap="true">.border-start-0</td></tr>
													<tr><td nowrap="true">.border-1</td></tr>
													<tr><td nowrap="true">.border-2</td></tr>
													<tr><td nowrap="true">.border-3</td></tr>
													<tr><td nowrap="true">.border-4</td></tr>
													<tr><td nowrap="true">.border-5</td></tr>
													<tr><td nowrap="true">.border-primary</td></tr>
													<tr><td nowrap="true">.border-secondary</td></tr>
													<tr><td nowrap="true">.border-success</td></tr>
													<tr><td nowrap="true">.border-danger</td></tr>
													<tr><td nowrap="true">.border-warning</td></tr>
													<tr><td nowrap="true">.border-info</td></tr>
													<tr><td nowrap="true">.border-light</td></tr>
													<tr><td nowrap="true">.border-dark</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.border-white</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.position-static</td></tr>
													<tr><td nowrap="true">.position-relative</td></tr>
													<tr><td nowrap="true">.position-absolute</td></tr>
													<tr><td nowrap="true">.position-fixed</td></tr>
													<tr><td nowrap="true">.position-sticky</td></tr>
													<tr><td nowrap="true">.top-0</td></tr>
													<tr><td nowrap="true">.top-50</td></tr>
													<tr><td nowrap="true">.top-100</td></tr>
													<tr><td nowrap="true">.end-0</td></tr>
													<tr><td nowrap="true">.end-50</td></tr>
													<tr><td nowrap="true">.end-100</td></tr>
													<tr><td nowrap="true">.bottom-0</td></tr>
													<tr><td nowrap="true">.bottom-50</td></tr>
													<tr><td nowrap="true">.bottom-100</td></tr>
													<tr><td nowrap="true">.start-0</td></tr>
													<tr><td nowrap="true">.start-50</td></tr>
													<tr><td nowrap="true">.start-100</td></tr>
													<tr><td nowrap="true">.translate-middle</td></tr>
													<tr><td nowrap="true">.translate-middle-x</td></tr>
													<tr><td nowrap="true">.translate-middle-y</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.user-select-all</td></tr>
													<tr><td nowrap="true">.user-select-auto</td></tr>
													<tr><td nowrap="true">.user-select-none</td></tr>
													<tr><td nowrap="true">.pe-none</td></tr>
													<tr><td nowrap="true">.pe-auto</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.shadow-none</td></tr>
													<tr><td nowrap="true">.shadow-sm</td></tr>
													<tr><td nowrap="true">.shadow</td></tr>
													<tr><td nowrap="true">.shadow-lg</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.d-inline-flex</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.visible</td></tr>
													<tr><td nowrap="true">.invisible</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="2">
          	<h4 className="mb-1"><b>Width & Height CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-bordered table-condensed">
								<thead>
									<tr>
										<th colSpan="3">Width</th>
										<th colSpan="3">Height</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.w-100</td></tr>
													<tr><td nowrap="true">.w-75</td></tr>
													<tr><td nowrap="true">.w-50</td></tr>
													<tr><td nowrap="true">.w-25</td></tr>
													<tr><td nowrap="true">.w-auto</td></tr>
													<tr><td nowrap="true">.vw-100</td></tr>
													<tr><td nowrap="true">.min-vw-100</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.w-5px</td></tr>
													<tr><td nowrap="true">.w-10px</td></tr>
													<tr><td nowrap="true">.w-15px</td></tr>
													<tr><td nowrap="true">.w-20px</td></tr>
													<tr><td nowrap="true">.w-25px</td></tr>
													<tr><td nowrap="true">.w-30px</td></tr>
													<tr><td nowrap="true">.w-35px</td></tr>
													<tr><td nowrap="true">.w-40px</td></tr>
													<tr><td nowrap="true">.w-45px</td></tr>
													<tr><td nowrap="true">.w-50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.w-100px</td></tr>
													<tr><td nowrap="true">.w-150px</td></tr>
													<tr><td nowrap="true">.w-200px</td></tr>
													<tr><td nowrap="true">.w-250px</td></tr>
													<tr><td nowrap="true">.w-300px</td></tr>
													<tr><td nowrap="true">.w-350px</td></tr>
													<tr><td nowrap="true">.w-400px</td></tr>
													<tr><td nowrap="true">.w-450px</td></tr>
													<tr><td nowrap="true">.w-500px</td></tr>
													<tr><td nowrap="true">.w-550px</td></tr>
													<tr><td nowrap="true">.w-600px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.h-100</td></tr>
													<tr><td nowrap="true">.h-75</td></tr>
													<tr><td nowrap="true">.h-50</td></tr>
													<tr><td nowrap="true">.h-25</td></tr>
													<tr><td nowrap="true">.h-auto</td></tr>
													<tr><td nowrap="true">.vh-100</td></tr>
													<tr><td nowrap="true">.min-vh-100</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.h-5px</td></tr>
													<tr><td nowrap="true">.h-10px</td></tr>
													<tr><td nowrap="true">.h-15px</td></tr>
													<tr><td nowrap="true">.h-20px</td></tr>
													<tr><td nowrap="true">.h-25px</td></tr>
													<tr><td nowrap="true">.h-30px</td></tr>
													<tr><td nowrap="true">.h-35px</td></tr>
													<tr><td nowrap="true">.h-40px</td></tr>
													<tr><td nowrap="true">.h-45px</td></tr>
													<tr><td nowrap="true">.h-50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0" width="15%">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.h-100px</td></tr>
													<tr><td nowrap="true">.h-150px</td></tr>
													<tr><td nowrap="true">.h-200px</td></tr>
													<tr><td nowrap="true">.h-250px</td></tr>
													<tr><td nowrap="true">.h-300px</td></tr>
													<tr><td nowrap="true">.h-350px</td></tr>
													<tr><td nowrap="true">.h-400px</td></tr>
													<tr><td nowrap="true">.h-450px</td></tr>
													<tr><td nowrap="true">.h-500px</td></tr>
													<tr><td nowrap="true">.h-550px</td></tr>
													<tr><td nowrap="true">.h-600px</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="3">
						<h4 className="mb-1"><b>Text & Font CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-bordered table-condensed">
								<thead>
									<tr>
										<th className="w-25">Font Size</th>
										<th width="25%">Font Weight</th>
										<th width="25%">Text Align</th>
										<th width="25%">Text Overflow</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.fs-1</td></tr>
													<tr><td nowrap="true">.fs-2</td></tr>
													<tr><td nowrap="true">.fs-3</td></tr>
													<tr><td nowrap="true">.fs-4</td></tr>
													<tr><td nowrap="true">.fs-5</td></tr>
													<tr><td nowrap="true">.fs-6</td></tr>
													<tr><td nowrap="true">.fs-1px, 2px... to 80px</td></tr>
													<tr className="bg-white"><th>Line Height</th></tr>
													<tr><td nowrap="true">.lh-1</td></tr>
													<tr><td nowrap="true">.lh-sm</td></tr>
													<tr><td nowrap="true">.lh-base</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.lh-lg</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.fw-bold</td></tr>
													<tr><td nowrap="true">.fw-bolder</td></tr>
													<tr><td nowrap="true">.fw-normal</td></tr>
													<tr><td nowrap="true">.fw-light</td></tr>
													<tr><td nowrap="true">.fw-lighter</td></tr>
													<tr><td nowrap="true">.fw-100 to 800</td></tr>
													<tr className="bg-white"><th>Italics</th></tr>
													<tr><td nowrap="true">.fst-italic</td></tr>
													<tr><td nowrap="true" className="border-bottom-0">.fst-normal</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.text-center</td></tr>
													<tr><td nowrap="true">.text-start</td></tr>
													<tr><td nowrap="true">.text-end</td></tr>
													<tr className="bg-white"><th>Text decoration</th></tr>
													<tr><td nowrap="true">.text-decoration-underline</td></tr>
													<tr><td nowrap="true">.text-decoration-line-through</td></tr>
													<tr><td nowrap="true">.text-decoration-none</td></tr>
													<tr className="bg-white"><th>Reset Color</th></tr>
													<tr><td nowrap="true">.reset-link</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.text-wrap</td></tr>
													<tr><td nowrap="true">.text-nowrap</td></tr>
													<tr><td nowrap="true">.text-ellipsis</td></tr>
													<tr className="bg-white"><th>Text Transform</th></tr>
													<tr><td nowrap="true">.text-lowercase</td></tr>
													<tr><td nowrap="true">.text-uppercase</td></tr>
													<tr><td nowrap="true">.text-capitalize</td></tr>
													<tr className="bg-white"><th>Word Break</th></tr>
													<tr><td nowrap="true">.text-break</td></tr>
													<tr className="bg-white"><th>Monospace</th></tr>
													<tr><td nowrap="true">.font-monospace</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="4">
          	<h4 className="mb-1"><b>Margin CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-condensed table-bordered">
								<thead>
									<tr>
										<th width="20%">Margin</th>
										<th width="20%">Margin Top</th>
										<th width="20%">Margin Right</th>
										<th width="20%">Margin Bottom</th>
										<th width="20%">Margin Left</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.m-0</td></tr>
													<tr><td nowrap="true">.m-1</td></tr>
													<tr><td nowrap="true">.m-2</td></tr>
													<tr><td nowrap="true">.m-3</td></tr>
													<tr><td nowrap="true">.m-4</td></tr>
													<tr><td nowrap="true">.m-5</td></tr>
													<tr><td nowrap="true">.m-auto</td></tr>
													<tr><td nowrap="true">.m-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.m-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.mt-0</td></tr>
													<tr><td nowrap="true">.mt-1</td></tr>
													<tr><td nowrap="true">.mt-2</td></tr>
													<tr><td nowrap="true">.mt-3</td></tr>
													<tr><td nowrap="true">.mt-4</td></tr>
													<tr><td nowrap="true">.mt-5</td></tr>
													<tr><td nowrap="true">.mt-auto</td></tr>
													<tr><td nowrap="true">.mt-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.mt-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.me-0</td></tr>
													<tr><td nowrap="true">.me-1</td></tr>
													<tr><td nowrap="true">.me-2</td></tr>
													<tr><td nowrap="true">.me-3</td></tr>
													<tr><td nowrap="true">.me-4</td></tr>
													<tr><td nowrap="true">.me-5</td></tr>
													<tr><td nowrap="true">.me-auto</td></tr>
													<tr><td nowrap="true">.me-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.me-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.mb-0</td></tr>
													<tr><td nowrap="true">.mb-1</td></tr>
													<tr><td nowrap="true">.mb-2</td></tr>
													<tr><td nowrap="true">.mb-3</td></tr>
													<tr><td nowrap="true">.mb-4</td></tr>
													<tr><td nowrap="true">.mb-5</td></tr>
													<tr><td nowrap="true">.mb-auto</td></tr>
													<tr><td nowrap="true">.mb-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.mb-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.ms-0</td></tr>
													<tr><td nowrap="true">.ms-1</td></tr>
													<tr><td nowrap="true">.ms-2</td></tr>
													<tr><td nowrap="true">.ms-3</td></tr>
													<tr><td nowrap="true">.ms-4</td></tr>
													<tr><td nowrap="true">.ms-5</td></tr>
													<tr><td nowrap="true">.ms-auto</td></tr>
													<tr><td nowrap="true">.ms-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.ms-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="5">
          	<h4 className="mb-1"><b>Padding CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-condensed table-bordered">
								<thead>
									<tr>
										<th width="20%">Padding</th>
										<th width="20%">Padding Top</th>
										<th width="20%">Padding Right</th>
										<th width="20%">Padding Bottom</th>
										<th width="20%">Padding Left</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.p-0</td></tr>
													<tr><td nowrap="true">.p-1</td></tr>
													<tr><td nowrap="true">.p-2</td></tr>
													<tr><td nowrap="true">.p-3</td></tr>
													<tr><td nowrap="true">.p-4</td></tr>
													<tr><td nowrap="true">.p-5</td></tr>
													<tr><td nowrap="true">.p-auto</td></tr>
													<tr><td nowrap="true">.p-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.p-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.pt-0</td></tr>
													<tr><td nowrap="true">.pt-1</td></tr>
													<tr><td nowrap="true">.pt-2</td></tr>
													<tr><td nowrap="true">.pt-3</td></tr>
													<tr><td nowrap="true">.pt-4</td></tr>
													<tr><td nowrap="true">.pt-5</td></tr>
													<tr><td nowrap="true">.pt-auto</td></tr>
													<tr><td nowrap="true">.pt-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.pt-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.pe-0</td></tr>
													<tr><td nowrap="true">.pe-1</td></tr>
													<tr><td nowrap="true">.pe-2</td></tr>
													<tr><td nowrap="true">.pe-3</td></tr>
													<tr><td nowrap="true">.pe-4</td></tr>
													<tr><td nowrap="true">.pe-5</td></tr>
													<tr><td nowrap="true">.pe-auto</td></tr>
													<tr><td nowrap="true">.pe-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.pe-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.pb-0</td></tr>
													<tr><td nowrap="true">.pb-1</td></tr>
													<tr><td nowrap="true">.pb-2</td></tr>
													<tr><td nowrap="true">.pb-3</td></tr>
													<tr><td nowrap="true">.pb-4</td></tr>
													<tr><td nowrap="true">.pb-5</td></tr>
													<tr><td nowrap="true">.pb-auto</td></tr>
													<tr><td nowrap="true">.pb-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.pb-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed m-0 bg-none">
												<tbody>
													<tr><td nowrap="true">.ps-0</td></tr>
													<tr><td nowrap="true">.ps-1</td></tr>
													<tr><td nowrap="true">.ps-2</td></tr>
													<tr><td nowrap="true">.ps-3</td></tr>
													<tr><td nowrap="true">.ps-4</td></tr>
													<tr><td nowrap="true">.ps-5</td></tr>
													<tr><td nowrap="true">.ps-auto</td></tr>
													<tr><td nowrap="true">.ps-1px, 2px... to 10px</td></tr>
													<tr><td nowrap="true">.ps-15px, 20px... to 50px</td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="6">
          	<h4 className="mb-1"><b>Background CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-condensed table-bordered">
								<thead>
									<tr>
										<th width="20%">Blue</th>
										<th width="20%">Indigo</th>
										<th width="20%">Purple</th>
										<th width="20%">Aqua</th>
										<th width="20%">Teal</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-100 w-15px h-15px rounded me-2"></div><div>.bg-blue-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-200 w-15px h-15px rounded me-2"></div><div>.bg-blue-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-300 w-15px h-15px rounded me-2"></div><div>.bg-blue-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-400 w-15px h-15px rounded me-2"></div><div>.bg-blue-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-500 w-15px h-15px rounded me-2"></div><div>.bg-blue-500 / .bg-blue</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-600 w-15px h-15px rounded me-2"></div><div>.bg-blue-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-700 w-15px h-15px rounded me-2"></div><div>.bg-blue-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-800 w-15px h-15px rounded me-2"></div><div>.bg-blue-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-blue-900 w-15px h-15px rounded me-2"></div><div>.bg-blue-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-blue w-15px h-15px rounded me-2"></div><div>.bg-gradient-blue</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-100 w-15px h-15px rounded me-2"></div><div>.bg-indigo-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-200 w-15px h-15px rounded me-2"></div><div>.bg-indigo-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-300 w-15px h-15px rounded me-2"></div><div>.bg-indigo-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-400 w-15px h-15px rounded me-2"></div><div>.bg-indigo-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-500 w-15px h-15px rounded me-2"></div><div>.bg-indigo-500 / .bg-indigo</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-600 w-15px h-15px rounded me-2"></div><div>.bg-indigo-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-700 w-15px h-15px rounded me-2"></div><div>.bg-indigo-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-800 w-15px h-15px rounded me-2"></div><div>.bg-indigo-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-indigo-900 w-15px h-15px rounded me-2"></div><div>.bg-indigo-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-indigo w-15px h-15px rounded me-2"></div><div>.bg-gradient-indigo</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-100 w-15px h-15px rounded me-2"></div><div>.bg-purple-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-200 w-15px h-15px rounded me-2"></div><div>.bg-purple-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-300 w-15px h-15px rounded me-2"></div><div>.bg-purple-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-400 w-15px h-15px rounded me-2"></div><div>.bg-purple-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-500 w-15px h-15px rounded me-2"></div><div>.bg-purple-500 / .bg-purple</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-600 w-15px h-15px rounded me-2"></div><div>.bg-purple-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-700 w-15px h-15px rounded me-2"></div><div>.bg-purple-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-800 w-15px h-15px rounded me-2"></div><div>.bg-purple-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-purple-900 w-15px h-15px rounded me-2"></div><div>.bg-purple-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-purple w-15px h-15px rounded me-2"></div><div>.bg-gradient-purple</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-100 w-15px h-15px rounded me-2"></div><div>.bg-cyan-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-200 w-15px h-15px rounded me-2"></div><div>.bg-cyan-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-300 w-15px h-15px rounded me-2"></div><div>.bg-cyan-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-400 w-15px h-15px rounded me-2"></div><div>.bg-cyan-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-500 w-15px h-15px rounded me-2"></div><div>.bg-cyan-500 / .bg-cyan</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-600 w-15px h-15px rounded me-2"></div><div>.bg-cyan-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-700 w-15px h-15px rounded me-2"></div><div>.bg-cyan-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-800 w-15px h-15px rounded me-2"></div><div>.bg-cyan-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-cyan-900 w-15px h-15px rounded me-2"></div><div>.bg-cyan-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-cyan w-15px h-15px rounded me-2"></div><div>.bg-gradient-cyan</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-100 w-15px h-15px rounded me-2"></div><div>.bg-teal-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-200 w-15px h-15px rounded me-2"></div><div>.bg-teal-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-300 w-15px h-15px rounded me-2"></div><div>.bg-teal-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-400 w-15px h-15px rounded me-2"></div><div>.bg-teal-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-500 w-15px h-15px rounded me-2"></div><div>.bg-teal-500 / .bg-teal</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-600 w-15px h-15px rounded me-2"></div><div>.bg-teal-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-700 w-15px h-15px rounded me-2"></div><div>.bg-teal-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-800 w-15px h-15px rounded me-2"></div><div>.bg-teal-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-teal-900 w-15px h-15px rounded me-2"></div><div>.bg-teal-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-teal w-15px h-15px rounded me-2"></div><div>.bg-gradient-teal</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="20%">Green</th>
										<th width="20%">Lime</th>
										<th width="20%">Orange</th>
										<th width="20%">Yellow</th>
										<th width="20%">Red</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-100 w-15px h-15px rounded me-2"></div><div>.bg-green-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-200 w-15px h-15px rounded me-2"></div><div>.bg-green-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-300 w-15px h-15px rounded me-2"></div><div>.bg-green-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-400 w-15px h-15px rounded me-2"></div><div>.bg-green-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-500 w-15px h-15px rounded me-2"></div><div>.bg-green-500 / .bg-green</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-600 w-15px h-15px rounded me-2"></div><div>.bg-green-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-700 w-15px h-15px rounded me-2"></div><div>.bg-green-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-800 w-15px h-15px rounded me-2"></div><div>.bg-green-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-green-900 w-15px h-15px rounded me-2"></div><div>.bg-green-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-green w-15px h-15px rounded me-2"></div><div>.bg-gradient-green</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-100 w-15px h-15px rounded me-2"></div><div>.bg-lime-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-200 w-15px h-15px rounded me-2"></div><div>.bg-lime-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-300 w-15px h-15px rounded me-2"></div><div>.bg-lime-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-400 w-15px h-15px rounded me-2"></div><div>.bg-lime-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-500 w-15px h-15px rounded me-2"></div><div>.bg-lime-500 / .bg-lime</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-600 w-15px h-15px rounded me-2"></div><div>.bg-lime-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-700 w-15px h-15px rounded me-2"></div><div>.bg-lime-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-800 w-15px h-15px rounded me-2"></div><div>.bg-lime-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-lime-900 w-15px h-15px rounded me-2"></div><div>.bg-lime-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-lime w-15px h-15px rounded me-2"></div><div>.bg-gradient-lime</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-100 w-15px h-15px rounded me-2"></div><div>.bg-orange-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-200 w-15px h-15px rounded me-2"></div><div>.bg-orange-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-300 w-15px h-15px rounded me-2"></div><div>.bg-orange-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-400 w-15px h-15px rounded me-2"></div><div>.bg-orange-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-500 w-15px h-15px rounded me-2"></div><div>.bg-orange-500 / .bg-orange</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-600 w-15px h-15px rounded me-2"></div><div>.bg-orange-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-700 w-15px h-15px rounded me-2"></div><div>.bg-orange-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-800 w-15px h-15px rounded me-2"></div><div>.bg-orange-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-orange-900 w-15px h-15px rounded me-2"></div><div>.bg-orange-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-orange w-15px h-15px rounded me-2"></div><div>.bg-gradient-orange</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-100 w-15px h-15px rounded me-2"></div><div>.bg-yellow-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-200 w-15px h-15px rounded me-2"></div><div>.bg-yellow-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-300 w-15px h-15px rounded me-2"></div><div>.bg-yellow-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-400 w-15px h-15px rounded me-2"></div><div>.bg-yellow-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-500 w-15px h-15px rounded me-2"></div><div>.bg-yellow-500 / .bg-yellow</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-600 w-15px h-15px rounded me-2"></div><div>.bg-yellow-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-700 w-15px h-15px rounded me-2"></div><div>.bg-yellow-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-800 w-15px h-15px rounded me-2"></div><div>.bg-yellow-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-yellow-900 w-15px h-15px rounded me-2"></div><div>.bg-yellow-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-yellow w-15px h-15px rounded me-2"></div><div>.bg-gradient-yellow</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-100 w-15px h-15px rounded me-2"></div><div>.bg-red-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-200 w-15px h-15px rounded me-2"></div><div>.bg-red-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-300 w-15px h-15px rounded me-2"></div><div>.bg-red-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-400 w-15px h-15px rounded me-2"></div><div>.bg-red-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-500 w-15px h-15px rounded me-2"></div><div>.bg-red-500 / .bg-red</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-600 w-15px h-15px rounded me-2"></div><div>.bg-red-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-700 w-15px h-15px rounded me-2"></div><div>.bg-red-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-800 w-15px h-15px rounded me-2"></div><div>.bg-red-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-red-900 w-15px h-15px rounded me-2"></div><div>.bg-red-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-red w-15px h-15px rounded me-2"></div><div>.bg-gradient-red</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="20%">Pink</th>
										<th width="20%">Black</th>
										<th width="20%">Grey</th>
										<th width="20%">Silver</th>
										<th width="20%">White</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-100 w-15px h-15px rounded me-2"></div><div>.bg-pink-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-200 w-15px h-15px rounded me-2"></div><div>.bg-pink-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-300 w-15px h-15px rounded me-2"></div><div>.bg-pink-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-400 w-15px h-15px rounded me-2"></div><div>.bg-pink-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-500 w-15px h-15px rounded me-2"></div><div>.bg-pink-500 / .bg-pink</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-600 w-15px h-15px rounded me-2"></div><div>.bg-pink-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-700 w-15px h-15px rounded me-2"></div><div>.bg-pink-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-800 w-15px h-15px rounded me-2"></div><div>.bg-pink-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-pink-900 w-15px h-15px rounded me-2"></div><div>.bg-pink-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-pink w-15px h-15px rounded me-2"></div><div>.bg-gradient-pink</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-100 w-15px h-15px rounded me-2"></div><div>.bg-black-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-200 w-15px h-15px rounded me-2"></div><div>.bg-black-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-300 w-15px h-15px rounded me-2"></div><div>.bg-black-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-400 w-15px h-15px rounded me-2"></div><div>.bg-black-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-500 w-15px h-15px rounded me-2"></div><div>.bg-black-500 / .bg-black</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-600 w-15px h-15px rounded me-2"></div><div>.bg-black-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-700 w-15px h-15px rounded me-2"></div><div>.bg-black-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-800 w-15px h-15px rounded me-2"></div><div>.bg-black-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-black-900 w-15px h-15px rounded me-2"></div><div>.bg-black-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-black w-15px h-15px rounded me-2"></div><div>.bg-gradient-black</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-100 w-15px h-15px rounded me-2"></div><div>.bg-gray-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-200 w-15px h-15px rounded me-2"></div><div>.bg-gray-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-300 w-15px h-15px rounded me-2"></div><div>.bg-gray-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-400 w-15px h-15px rounded me-2"></div><div>.bg-gray-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-500 w-15px h-15px rounded me-2"></div><div>.bg-gray-500 / .bg-gray</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-600 w-15px h-15px rounded me-2"></div><div>.bg-gray-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-700 w-15px h-15px rounded me-2"></div><div>.bg-gray-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-800 w-15px h-15px rounded me-2"></div><div>.bg-gray-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gray-900 w-15px h-15px rounded me-2"></div><div>.bg-gray-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-gray w-15px h-15px rounded me-2"></div><div>.bg-gradient-gray</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0 bg-inverse">
											<table className="table table-condensed p-0 bg-none mb-0 text-white">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-100 w-15px h-15px rounded me-2"></div><div>.bg-silver-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-200 w-15px h-15px rounded me-2"></div><div>.bg-silver-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-300 w-15px h-15px rounded me-2"></div><div>.bg-silver-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-400 w-15px h-15px rounded me-2"></div><div>.bg-silver-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-500 w-15px h-15px rounded me-2"></div><div>.bg-silver-500 / .bg-silver</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-600 w-15px h-15px rounded me-2"></div><div>.bg-silver-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-700 w-15px h-15px rounded me-2"></div><div>.bg-silver-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-800 w-15px h-15px rounded me-2"></div><div>.bg-silver-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-silver-900 w-15px h-15px rounded me-2"></div><div>.bg-silver-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-silver w-15px h-15px rounded me-2"></div><div>.bg-gradient-silver</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0 bg-inverse">
											<table className="table table-condensed p-0 bg-none mb-0 text-white">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-100 w-15px h-15px rounded me-2"></div><div>.bg-white-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-200 w-15px h-15px rounded me-2"></div><div>.bg-white-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-300 w-15px h-15px rounded me-2"></div><div>.bg-white-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-400 w-15px h-15px rounded me-2"></div><div>.bg-white-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-500 w-15px h-15px rounded me-2"></div><div>.bg-white-500 / .bg-white</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-600 w-15px h-15px rounded me-2"></div><div>.bg-white-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-700 w-15px h-15px rounded me-2"></div><div>.bg-white-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-800 w-15px h-15px rounded me-2"></div><div>.bg-white-800</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-white-900 w-15px h-15px rounded me-2"></div><div>.bg-white-900</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-white w-15px h-15px rounded me-2"></div><div>.bg-gradient-white</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="20%">Extra</th>
										<th colSpan="4">Custom Background</th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-none w-15px h-15px rounded me-2"></div><div>.bg-none</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-transparent w-15px h-15px rounded me-2"></div><div>.bg-transparent</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-red-pink w-15px h-15px rounded me-2"></div><div>.bg-gradient-red-pink</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-red-pink w-15px h-15px rounded me-2"></div><div>.bg-gradient-orange-red</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-yellow-orange w-15px h-15px rounded me-2"></div><div>.bg-gradient-yellow-orange</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-yellow-red w-15px h-15px rounded me-2"></div><div>.bg-gradient-yellow-red</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-teal-green w-15px h-15px rounded me-2"></div><div>.bg-gradient-teal-green</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-yellow-green w-15px h-15px rounded me-2"></div><div>.bg-gradient-yellow-green</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-blue-purple w-15px h-15px rounded me-2"></div><div>.bg-gradient-blue-purple</div></div></td></tr>
												</tbody>
											</table>	
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-cyan-blue w-15px h-15px rounded me-2"></div><div>.bg-gradient-cyan-blue</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-cyan-purple w-15px h-15px rounded me-2"></div><div>.bg-gradient-cyan-purple</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-cyan-indigo w-15px h-15px rounded me-2"></div><div>.bg-gradient-cyan-indigo</div></div></td></tr>
													</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-blue-indigo w-15px h-15px rounded me-2"></div><div>.bg-gradient-blue-indigo</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><div className="bg-gradient-purple-indigo w-15px h-15px rounded me-2"></div><div>.bg-gradient-purple-indigo</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><div className="bg-gradient-silver-black w-15px h-15px rounded me-2"></div><div>.bg-gradient-silver-black</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
          <TabPane tabId="7">
          	<h4 className="mb-1"><b>Text Color CSS Class</b></h4>
						<p className="mb-3">
							All the predefined css classes will override the defined css styling in your classes, UNLESS the <code>!important</code> is declared in your defined css styling.
						</p>
						<div className="table-responsive">
							<table className="table table-condensed table-bordered">
								<thead>
									<tr>
										<th width="20%">Blue</th>
										<th width="20%">Indigo</th>
										<th width="20%">Purple</th>
										<th width="20%">Aqua</th>
										<th width="20%">Teal</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-100 w-15px h-15px rounded me-2"></i><div>.bg-blue-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-200 w-15px h-15px rounded me-2"></i><div>.bg-blue-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-300 w-15px h-15px rounded me-2"></i><div>.bg-blue-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-400 w-15px h-15px rounded me-2"></i><div>.bg-blue-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-500 w-15px h-15px rounded me-2"></i><div>.bg-blue-500 / .bg-blue</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-600 w-15px h-15px rounded me-2"></i><div>.bg-blue-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-700 w-15px h-15px rounded me-2"></i><div>.bg-blue-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-800 w-15px h-15px rounded me-2"></i><div>.bg-blue-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-blue-900 w-15px h-15px rounded me-2"></i><div>.bg-blue-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-100 w-15px h-15px rounded me-2"></i><div>.bg-indigo-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-200 w-15px h-15px rounded me-2"></i><div>.bg-indigo-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-300 w-15px h-15px rounded me-2"></i><div>.bg-indigo-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-400 w-15px h-15px rounded me-2"></i><div>.bg-indigo-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-500 w-15px h-15px rounded me-2"></i><div>.bg-indigo-500 / .bg-indigo</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-600 w-15px h-15px rounded me-2"></i><div>.bg-indigo-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-700 w-15px h-15px rounded me-2"></i><div>.bg-indigo-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-800 w-15px h-15px rounded me-2"></i><div>.bg-indigo-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-indigo-900 w-15px h-15px rounded me-2"></i><div>.bg-indigo-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-100 w-15px h-15px rounded me-2"></i><div>.bg-purple-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-200 w-15px h-15px rounded me-2"></i><div>.bg-purple-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-300 w-15px h-15px rounded me-2"></i><div>.bg-purple-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-400 w-15px h-15px rounded me-2"></i><div>.bg-purple-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-500 w-15px h-15px rounded me-2"></i><div>.bg-purple-500 / .bg-purple</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-600 w-15px h-15px rounded me-2"></i><div>.bg-purple-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-700 w-15px h-15px rounded me-2"></i><div>.bg-purple-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-800 w-15px h-15px rounded me-2"></i><div>.bg-purple-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-purple-900 w-15px h-15px rounded me-2"></i><div>.bg-purple-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-100 w-15px h-15px rounded me-2"></i><div>.bg-cyan-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-200 w-15px h-15px rounded me-2"></i><div>.bg-cyan-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-300 w-15px h-15px rounded me-2"></i><div>.bg-cyan-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-400 w-15px h-15px rounded me-2"></i><div>.bg-cyan-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-500 w-15px h-15px rounded me-2"></i><div>.bg-cyan-500 / .bg-cyan</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-600 w-15px h-15px rounded me-2"></i><div>.bg-cyan-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-700 w-15px h-15px rounded me-2"></i><div>.bg-cyan-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-800 w-15px h-15px rounded me-2"></i><div>.bg-cyan-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-cyan-900 w-15px h-15px rounded me-2"></i><div>.bg-cyan-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-100 w-15px h-15px rounded me-2"></i><div>.bg-teal-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-200 w-15px h-15px rounded me-2"></i><div>.bg-teal-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-300 w-15px h-15px rounded me-2"></i><div>.bg-teal-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-400 w-15px h-15px rounded me-2"></i><div>.bg-teal-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-500 w-15px h-15px rounded me-2"></i><div>.bg-teal-500 / .bg-teal</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-600 w-15px h-15px rounded me-2"></i><div>.bg-teal-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-700 w-15px h-15px rounded me-2"></i><div>.bg-teal-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-800 w-15px h-15px rounded me-2"></i><div>.bg-teal-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-teal-900 w-15px h-15px rounded me-2"></i><div>.bg-teal-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="20%">Green</th>
										<th width="20%">Lime</th>
										<th width="20%">Orange</th>
										<th width="20%">Yellow</th>
										<th width="20%">Red</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-100 w-15px h-15px rounded me-2"></i><div>.bg-green-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-200 w-15px h-15px rounded me-2"></i><div>.bg-green-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-300 w-15px h-15px rounded me-2"></i><div>.bg-green-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-400 w-15px h-15px rounded me-2"></i><div>.bg-green-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-500 w-15px h-15px rounded me-2"></i><div>.bg-green-500 / .bg-green</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-600 w-15px h-15px rounded me-2"></i><div>.bg-green-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-700 w-15px h-15px rounded me-2"></i><div>.bg-green-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-800 w-15px h-15px rounded me-2"></i><div>.bg-green-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-green-900 w-15px h-15px rounded me-2"></i><div>.bg-green-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-100 w-15px h-15px rounded me-2"></i><div>.bg-lime-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-200 w-15px h-15px rounded me-2"></i><div>.bg-lime-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-300 w-15px h-15px rounded me-2"></i><div>.bg-lime-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-400 w-15px h-15px rounded me-2"></i><div>.bg-lime-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-500 w-15px h-15px rounded me-2"></i><div>.bg-lime-500 / .bg-lime</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-600 w-15px h-15px rounded me-2"></i><div>.bg-lime-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-700 w-15px h-15px rounded me-2"></i><div>.bg-lime-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-800 w-15px h-15px rounded me-2"></i><div>.bg-lime-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-lime-900 w-15px h-15px rounded me-2"></i><div>.bg-lime-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-100 w-15px h-15px rounded me-2"></i><div>.bg-orange-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-200 w-15px h-15px rounded me-2"></i><div>.bg-orange-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-300 w-15px h-15px rounded me-2"></i><div>.bg-orange-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-400 w-15px h-15px rounded me-2"></i><div>.bg-orange-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-500 w-15px h-15px rounded me-2"></i><div>.bg-orange-500 / .bg-orange</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-600 w-15px h-15px rounded me-2"></i><div>.bg-orange-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-700 w-15px h-15px rounded me-2"></i><div>.bg-orange-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-800 w-15px h-15px rounded me-2"></i><div>.bg-orange-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-orange-900 w-15px h-15px rounded me-2"></i><div>.bg-orange-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-100 w-15px h-15px rounded me-2"></i><div>.bg-yellow-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-200 w-15px h-15px rounded me-2"></i><div>.bg-yellow-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-300 w-15px h-15px rounded me-2"></i><div>.bg-yellow-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-400 w-15px h-15px rounded me-2"></i><div>.bg-yellow-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-500 w-15px h-15px rounded me-2"></i><div>.bg-yellow-500 / .bg-yellow</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-600 w-15px h-15px rounded me-2"></i><div>.bg-yellow-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-700 w-15px h-15px rounded me-2"></i><div>.bg-yellow-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-800 w-15px h-15px rounded me-2"></i><div>.bg-yellow-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-yellow-900 w-15px h-15px rounded me-2"></i><div>.bg-yellow-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-100 w-15px h-15px rounded me-2"></i><div>.bg-red-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-200 w-15px h-15px rounded me-2"></i><div>.bg-red-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-300 w-15px h-15px rounded me-2"></i><div>.bg-red-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-400 w-15px h-15px rounded me-2"></i><div>.bg-red-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-500 w-15px h-15px rounded me-2"></i><div>.bg-red-500 / .bg-red</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-600 w-15px h-15px rounded me-2"></i><div>.bg-red-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-700 w-15px h-15px rounded me-2"></i><div>.bg-red-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-800 w-15px h-15px rounded me-2"></i><div>.bg-red-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-red-900 w-15px h-15px rounded me-2"></i><div>.bg-red-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th width="20%">Pink</th>
										<th width="20%">Black</th>
										<th width="20%">Grey</th>
										<th width="20%">Silver</th>
										<th width="20%">White</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-100 w-15px h-15px rounded me-2"></i><div>.bg-pink-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-200 w-15px h-15px rounded me-2"></i><div>.bg-pink-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-300 w-15px h-15px rounded me-2"></i><div>.bg-pink-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-400 w-15px h-15px rounded me-2"></i><div>.bg-pink-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-500 w-15px h-15px rounded me-2"></i><div>.bg-pink-500 / .bg-pink</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-600 w-15px h-15px rounded me-2"></i><div>.bg-pink-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-700 w-15px h-15px rounded me-2"></i><div>.bg-pink-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-800 w-15px h-15px rounded me-2"></i><div>.bg-pink-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-pink-900 w-15px h-15px rounded me-2"></i><div>.bg-pink-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-100 w-15px h-15px rounded me-2"></i><div>.bg-black-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-200 w-15px h-15px rounded me-2"></i><div>.bg-black-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-300 w-15px h-15px rounded me-2"></i><div>.bg-black-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-400 w-15px h-15px rounded me-2"></i><div>.bg-black-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-500 w-15px h-15px rounded me-2"></i><div>.bg-black-500 / .bg-black</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-600 w-15px h-15px rounded me-2"></i><div>.bg-black-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-700 w-15px h-15px rounded me-2"></i><div>.bg-black-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-800 w-15px h-15px rounded me-2"></i><div>.bg-black-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-black-900 w-15px h-15px rounded me-2"></i><div>.bg-black-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-100 w-15px h-15px rounded me-2"></i><div>.bg-gray-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-200 w-15px h-15px rounded me-2"></i><div>.bg-gray-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-300 w-15px h-15px rounded me-2"></i><div>.bg-gray-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-400 w-15px h-15px rounded me-2"></i><div>.bg-gray-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-500 w-15px h-15px rounded me-2"></i><div>.bg-gray-500 / .bg-gray</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-600 w-15px h-15px rounded me-2"></i><div>.bg-gray-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-700 w-15px h-15px rounded me-2"></i><div>.bg-gray-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-800 w-15px h-15px rounded me-2"></i><div>.bg-gray-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-gray-900 w-15px h-15px rounded me-2"></i><div>.bg-gray-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0 bg-inverse">
											<table className="table table-condensed p-0 bg-none mb-0 text-white">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-100 w-15px h-15px rounded me-2"></i><div>.bg-silver-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-200 w-15px h-15px rounded me-2"></i><div>.bg-silver-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-300 w-15px h-15px rounded me-2"></i><div>.bg-silver-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-400 w-15px h-15px rounded me-2"></i><div>.bg-silver-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-500 w-15px h-15px rounded me-2"></i><div>.bg-silver-500 / .bg-silver</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-600 w-15px h-15px rounded me-2"></i><div>.bg-silver-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-700 w-15px h-15px rounded me-2"></i><div>.bg-silver-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-800 w-15px h-15px rounded me-2"></i><div>.bg-silver-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-silver-900 w-15px h-15px rounded me-2"></i><div>.bg-silver-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
										<td className="p-0 bg-inverse">
											<table className="table table-condensed p-0 bg-none mb-0 text-white">
												<tbody>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-100 w-15px h-15px rounded me-2"></i><div>.bg-white-100</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-200 w-15px h-15px rounded me-2"></i><div>.bg-white-200</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-300 w-15px h-15px rounded me-2"></i><div>.bg-white-300</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-400 w-15px h-15px rounded me-2"></i><div>.bg-white-400</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-500 w-15px h-15px rounded me-2"></i><div>.bg-white-500 / .bg-white</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-600 w-15px h-15px rounded me-2"></i><div>.bg-white-600</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-700 w-15px h-15px rounded me-2"></i><div>.bg-white-700</div></div></td></tr>
													<tr><td nowrap="true"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-800 w-15px h-15px rounded me-2"></i><div>.bg-white-800</div></div></td></tr>
													<tr><td nowrap="true" className="border-bottom-0"><div className="d-flex align-items-center"><i className="fa fa-lg fa-font text-white-900 w-15px h-15px rounded me-2"></i><div>.bg-white-900</div></div></td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<thead>
									<tr>
										<th colSpan="5">Extra</th>
									</tr>
								</thead>
								<tbody>
									<tr className="bg-light">
										<td className="p-0" colSpan="5">
											<table className="table table-condensed p-0 bg-none mb-0">
												<tbody>
													<tr>
														<td nowrap="true" className="border-bottom-0">
															<i className="fa fa-5x fa-font text-gradient bg-gradient-orange-red"></i>
															<i className="fa fa-5x fa-font text-gradient bg-gradient-blue-indigo"></i>
															<i className="fa fa-5x fa-font text-gradient bg-gradient-black"></i>
															<br /><br />
															.text-gradient<br />
															.bg-gradient-*
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
          </TabPane>
        </TabContent>
			</React.Fragment>
		)
	}
}

export default HelperCSS;