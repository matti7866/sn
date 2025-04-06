import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import { UncontrolledButtonDropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import Highlight from 'react-highlight';

class UIButtons extends React.Component {
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/ui/buttons">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/ui/buttons">UI Elements</Link></li>
					<li className="breadcrumb-item active">Buttons</li>
				</ol>
				<h1 className="page-header">Buttons <small>header small text goes here...</small></h1>
				
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Buttons <span className="badge bg-success ms-1">NEW</span></PanelHeader>
							<PanelBody>
								<div className="d-flex flex-wrap">
									<button type="button" className="btn btn-white me-1 mb-1">White</button>
									<button type="button" className="btn btn-default me-1 mb-1">Default</button>
									<button type="button" className="btn btn-gray me-1 mb-1">Gray</button>
									<button type="button" className="btn btn-purple me-1 mb-1">Purple</button>
									<button type="button" className="btn btn-indigo me-1 mb-1">Indigo</button>
									<button type="button" className="btn btn-primary me-1 mb-1">Primary</button>
									<button type="button" className="btn btn-info me-1 mb-1">Info</button>
									<button type="button" className="btn btn-yellow me-1 mb-1">Yellow</button>
									<button type="button" className="btn btn-warning me-1 mb-1">Warning</button>
									<button type="button" className="btn btn-pink me-1 mb-1">Pink</button>
									<button type="button" className="btn btn-danger me-1 mb-1">Danger</button>
									<button type="button" className="btn btn-success me-1 mb-1">Success</button>
									<button type="button" className="btn btn-green me-1 mb-1">Green</button>
									<button type="button" className="btn btn-lime me-1 mb-1">Lime</button>
									<button type="button" className="btn btn-inverse me-1 mb-1">Inverse</button>
									<button type="button" className="btn btn-link">Link</button>
								</div>
								<hr />
								<div className="d-flex flex-wrap">
									<button type="button" className="btn btn-outline-primary me-1 mb-1">Outline Primary</button>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<!-- button -->\n'+
'<button type="button" className="btn btn-primary">\n'+
'  Primary\n'+
'</button>\n'+
'\n'+
'<!-- outine button -->\n'+
'<button type="button" className="btn btn-outline-primary">\n'+
'  Outline Primary\n'+
'</button>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button Dropdowns</PanelHeader>
							<PanelBody>
								<UncontrolledButtonDropdown direction="down" className="me-2 mb-1">
									<Link to="/ui/buttons" className="btn btn-default">Dropdown</Link>
									<DropdownToggle color="default" className="dropdown-toggle"><i className="fa fa-caret-down"></i></DropdownToggle>
									<DropdownMenu>
										<DropdownItem>Action 1</DropdownItem>
										<DropdownItem>Action 2</DropdownItem>
										<DropdownItem>Action 3</DropdownItem>
										<DropdownItem divider></DropdownItem>
										<DropdownItem>Action 4</DropdownItem>
									</DropdownMenu>
								</UncontrolledButtonDropdown>
								
								<UncontrolledButtonDropdown direction="up" className="me-2 mb-1">
									<Link to="/ui/buttons" className="btn btn-primary">Dropup</Link>
									<DropdownToggle color="primary" className="dropdown-toggle"><i className="fa fa-caret-up"></i></DropdownToggle>
									<DropdownMenu>
										<DropdownItem>Action 1</DropdownItem>
										<DropdownItem>Action 2</DropdownItem>
										<DropdownItem>Action 3</DropdownItem>
										<DropdownItem divider></DropdownItem>
										<DropdownItem>Action 4</DropdownItem>
									</DropdownMenu>
								</UncontrolledButtonDropdown>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<UncontrolledButtonDropdown direction="down" className="me-2 mb-1">\n'+
'  <Link to="/ui/buttons" className="btn btn-default">Dropdown</Link>\n'+
'  <DropdownToggle color="default" className="dropdown-toggle">\n'+
'    <i className="fa fa-caret-down"></i>\n'+
'  </DropdownToggle>\n'+
'  <DropdownMenu>\n'+
'    <DropdownItem>Action 1</DropdownItem>\n'+
'    <DropdownItem>Action 2</DropdownItem>\n'+
'    <DropdownItem>Action 3</DropdownItem>\n'+
'    <DropdownItem divider></DropdownItem>\n'+
'    <DropdownItem>Action 4</DropdownItem>\n'+
'  </DropdownMenu>\n'+
'</UncontrolledButtonDropdown>'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button Sizes</PanelHeader>
							<PanelBody>
								<Link to="/ui/buttons" className="btn btn-primary btn-lg me-1 mb-1">Large Button</Link>
								<Link to="/ui/buttons" className="btn btn-primary me-1 mb-1">Default Button</Link>
								<Link to="/ui/buttons" className="btn btn-default btn-sm me-1 mb-1">Small Button</Link>
								<Link to="/ui/buttons" className="btn btn-default btn-xs me-1 mb-1">Extra Small</Link>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<button className="btn btn-primary btn-lg">Large Button</button>\n'+
'<button className="btn btn-primary">Default Button</button>\n'+
'<button className="btn btn-primary btn-sm">Small Button</button>\n'+
'<button className="btn btn-primary btn-xs">Extra Small</button>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button State</PanelHeader>
							<PanelBody>
								<div className="row">
									<div className="col-md-6">
										<Link to="/ui/buttons" className="btn btn-default disabled me-1 mb-1">Disabled Button</Link>
										<Link to="/ui/buttons" className="btn btn-default active me-1 mb-1">Active Button</Link>
									</div>
									<div className="col-md-6">
										<Link to="/ui/buttons" className="btn btn-primary d-block">Block Button</Link>
									</div>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<!-- disabled -->\n'+
'<button className="btn btn-default disabled me-1 mb-1">Disabled Button</Link>\n'+
'<!-- active -->\n'+
'<button className="btn btn-default active me-1 mb-1">Active Button</button>'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button with Icon</PanelHeader>
							<PanelBody>
								<div className="row text-center">
									<div className="col-md-6">
										<div className="mb-2">
											<Link to="/ui/buttons" className="btn btn-lg btn-primary">
												<span className="d-flex align-items-center text-start">
													<i className="fab fa-twitter fa-3x me-3 text-black"></i>
													<span>
														<span className="d-block"><b>Twitter Bootstrap</b></span>
														<span className="d-block fs-12px opacity-7">Version 5.0</span>
													</span>
												</span>
											</Link>
										</div>
									</div>
									<div className="col-md-6">
										<p>
											<Link to="/ui/buttons" className="btn btn-default me-5px"><i className="fa fa-comment"></i> Comment</Link>
											<Link to="/ui/buttons" className="btn btn-default me-5px"><i className="fa fa-cogs"></i> Setting</Link>
											<Link to="/ui/buttons" className="btn btn-default"><i className="fa fa-cog"></i></Link>
										</p>
										<p>
											<Link to="/ui/buttons" className="btn btn-default d-block"><i className="fa fa-list"></i> Button block with icon</Link>
										</p>
									</div>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<button className="btn btn-lg btn-primary">\n'+
'  <span className="d-flex align-items-center text-start">\n'+
'    <i className="fab fa-twitter fa-3x me-3 text-black"></i>\n'+
'    <span>\n'+
'      <span className="d-block"><b>Twitter Bootstrap</b></span>\n'+
'      <span className="d-block fs-12px opacity-7">Version 5.0</span>\n'+
'    </span>\n'+
'  </span>\n'+
'</button>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button Groups & Toolbars</PanelHeader>
							<PanelBody>
								<div className="row">
									<div className="col-md-6">
										<div className="btn-group">
											<button className="btn btn-white">Left</button>
											<button className="btn btn-white active">Middle</button>
											<button className="btn btn-white">Right</button>
										</div>
										<p></p>
										<div className="btn-group">
											<Link to="/ui/buttons" className="btn btn-white"><i className="fa fa-align-left"></i></Link>
											<Link to="/ui/buttons" className="btn btn-white active"><i className="fa fa-align-center"></i></Link>
											<Link to="/ui/buttons" className="btn btn-white"><i className="fa fa-align-right"></i></Link>
											<Link to="/ui/buttons" className="btn btn-white"><i className="fa fa-align-justify"></i></Link>
										</div>
										<p></p>
									</div>
									<div className="col-md-6">
										<div className="btn-group">
											<button type="button" className="btn btn-white">1</button>
											<button type="button" className="btn btn-white active">2</button>
											<button type="button" className="btn btn-white">3</button>
										</div>
										<div className="btn-group">
											<button type="button" className="btn btn-white">4</button>
											<button type="button" className="btn btn-white">5</button>
											<button type="button" className="btn btn-white">6</button>
										</div>
										<div className="btn-group">
											<button type="button" className="btn btn-white">7</button>
										</div>
										<p></p>
										<div className="btn-group">
											<Link to="/ui/buttons" className="btn btn-inverse"><i className="fa fa-backward"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse"><i className="fa fa-fast-backward"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse"><i className="fa fa-pause"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse active"><i className="fa fa-play"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse"><i className="fa fa-forward"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse"><i className="fa fa-fast-forward"></i></Link>
										</div>
									</div>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<div className="btn-group">\n'+
'  <button className="btn btn-white"><i className="fa fa-align-left"></i></button>\n'+
'  <button className="btn btn-white active"><i className="fa fa-align-center"></i></button>\n'+
'  <button className="btn btn-white"><i className="fa fa-align-right"></i></button>\n'+
'  <button className="btn btn-white"><i className="fa fa-align-justify"></i></button>\n'+
'</div>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button Group Vertical & Justified</PanelHeader>
							<PanelBody>
								<div className="row">
									<div className="col-md-4">
										<div className="btn-group-vertical me-5px">
											<button type="button" className="btn btn-indigo"><i className="fa fa-cog"></i> Button</button>
											<button type="button" className="btn btn-indigo active"><i className="fa fa-cog"></i> Button</button>
											<button type="button" className="btn btn-indigo"><i className="fa fa-cog"></i> Button</button>
										</div>
									</div>
									<div className="col-md-8">
										<div className="btn-group w-100">
											<Link to="/ui/buttons" className="btn btn-outline-inverse">Left</Link>
											<Link to="/ui/buttons" className="btn btn-outline-inverse active">Middle</Link>
											<Link to="/ui/buttons" className="btn btn-outline-inverse">Right</Link>
										</div>
									</div>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<!-- btn-group -->\n'+
'<div className="btn-group">\n'+
'  <button className="btn btn-outline-inverse">Left</button>\n'+
'  <button className="btn btn-outline-inverse active">Middle</button>\n'+
'  <button className="btn btn-outline-inverse">Right</button>\n'+
'</div>\n'+
'\n'+
'<!-- vertical -->\n'+
'<div className="btn-group-vertical">\n'+
'  ...\n'+
'</div>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Button Icon & Sizes</PanelHeader>
							<PanelBody>
								<div className="row">
									<div className="col-md-8">
										<p>
											<Link to="/ui/buttons" className="btn btn-default btn-icon btn-circle btn-lg me-1"><i className="fa fa-expand"></i></Link>
											<Link to="/ui/buttons" className="btn btn-success btn-icon btn-circle btn-lg me-1"><i className="fa fa-redo"></i></Link>
											<Link to="/ui/buttons" className="btn btn-warning btn-icon btn-circle btn-lg me-1"><i className="fa fa-minus"></i></Link>
											<Link to="/ui/buttons" className="btn btn-danger btn-icon btn-circle btn-lg me-1"><i className="fa fa-times"></i></Link>
											<Link to="/ui/buttons" className="btn btn-primary btn-icon btn-circle btn-lg me-1"><i className="fab fa-facebook-f"></i></Link>
											<Link to="/ui/buttons" className="btn btn-info btn-icon btn-circle btn-lg me-1"><i className="fab fa-twitter"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse btn-icon btn-circle btn-lg me-1"><i className="fab fa-stack-overflow"></i></Link>
										</p>
										<p>
											<Link to="/ui/buttons" className="btn btn-default btn-icon btn-circle me-1"><i className="fa fa-expand"></i></Link>
											<Link to="/ui/buttons" className="btn btn-success btn-icon btn-circle me-1"><i className="fa fa-redo"></i></Link>
											<Link to="/ui/buttons" className="btn btn-warning btn-icon btn-circle me-1"><i className="fa fa-minus"></i></Link>
											<Link to="/ui/buttons" className="btn btn-danger btn-icon btn-circle me-1"><i className="fa fa-times"></i></Link>
											<Link to="/ui/buttons" className="btn btn-primary btn-icon btn-circle me-1"><i className="fab fa-facebook-f"></i></Link>
											<Link to="/ui/buttons" className="btn btn-info btn-icon btn-circle me-1"><i className="fab fa-twitter"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse btn-icon btn-circle me-1"><i className="fab fa-stack-overflow"></i></Link>
										</p>
										<p>
											<Link to="/ui/buttons" className="btn btn-default btn-icon btn-circle btn-sm me-1"><i className="fa fa-expand"></i></Link>
											<Link to="/ui/buttons" className="btn btn-success btn-icon btn-circle btn-sm me-1"><i className="fa fa-redo"></i></Link>
											<Link to="/ui/buttons" className="btn btn-warning btn-icon btn-circle btn-sm me-1"><i className="fa fa-minus"></i></Link>
											<Link to="/ui/buttons" className="btn btn-danger btn-icon btn-circle btn-sm me-1"><i className="fa fa-times"></i></Link>
											<Link to="/ui/buttons" className="btn btn-primary btn-icon btn-circle btn-sm me-1"><i className="fab fa-facebook-f"></i></Link>
											<Link to="/ui/buttons" className="btn btn-info btn-icon btn-circle btn-sm me-1"><i className="fab fa-twitter"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse btn-icon btn-circle btn-sm me-1"><i className="fab fa-stack-overflow"></i></Link>
										</p>
										<p>
											<Link to="/ui/buttons" className="btn btn-default btn-icon btn-circle btn-xs me-1"><i className="fa fa-expand"></i></Link>
											<Link to="/ui/buttons" className="btn btn-success btn-icon btn-circle btn-xs me-1"><i className="fa fa-redo"></i></Link>
											<Link to="/ui/buttons" className="btn btn-warning btn-icon btn-circle btn-xs me-1"><i className="fa fa-minus"></i></Link>
											<Link to="/ui/buttons" className="btn btn-danger btn-icon btn-circle btn-xs me-1"><i className="fa fa-times"></i></Link>
											<Link to="/ui/buttons" className="btn btn-primary btn-icon btn-circle btn-xs me-1"><i className="fab fa-facebook-f"></i></Link>
											<Link to="/ui/buttons" className="btn btn-info btn-icon btn-circle btn-xs me-1"><i className="fab fa-twitter"></i></Link>
											<Link to="/ui/buttons" className="btn btn-inverse btn-icon btn-circle btn-xs me-1"><i className="fab fa-stack-overflow"></i></Link>
										</p>
									</div>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<button className="btn btn-default btn-icon btn-circle btn-lg"><i className="fa fa-expand"></i></button>\n'+
'<button className="btn btn-default btn-icon btn-circle"><i className="fa fa-expand"></i></button>\n'+
'<button className="btn btn-default btn-icon btn-circle btn-sm"><i className="fa fa-expand"></i></button>\n'+
'<button className="btn btn-default btn-icon btn-circle btn-xs"><i className="fa fa-expand"></i></button>'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
			</div>
		)
	}
}

export default UIButtons;