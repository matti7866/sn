import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import { AppSettings } from './../../config/app-settings.js';
import Highlight from 'react-highlight';

class PageWithTopMenu extends React.Component {
	static contextType = AppSettings;

	componentDidMount() {
		this.context.handleSetAppTopMenu(true);
		this.context.handleSetAppSidebarNone(true);
	}

	componentWillUnmount() {
		this.context.handleSetAppTopMenu(false);
		this.context.handleSetAppSidebarNone(false);
	}
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/page-option/with-top-menu">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/page-option/with-top-menu">Page Options</Link></li>
					<li className="breadcrumb-item active">Page with Top Menu</li>
				</ol>
				<h1 className="page-header">Page with Top Menu <small>header small text goes here...</small></h1>
				<Panel>
					<PanelHeader>Installation Settings</PanelHeader>
					<PanelBody>
						<p>
							Add the following settings into your individual page or change the variable value from <code>app.jsx</code> to make it globally affected in your app.
						</p>
					</PanelBody>
					<div className="hljs-wrapper">
						<Highlight className='typescript'>{
'import { AppSettings } from "./../../config/app-settings.js";\n'+
'\n'+
'class PageWithTopMenu extends React.Component {\n'+
'  static contextType = AppSettings;\n'+
'\n'+
'  componentDidMount() {\n'+
'    this.context.handleSetAppTopMenu(true);\n'+
'    this.context.handleSetAppSidebarNone(true);\n'+
'  }\n'+
'\n'+
'  componentWillUnmount() {\n'+
'    this.context.handleSetAppTopMenu(false);\n'+
'    this.context.handleSetAppSidebarNone(false);\n'+
'  }\n'+
'}'}
						</Highlight>
					</div>
				</Panel>
			</div>
		)
	}
}

export default PageWithTopMenu;