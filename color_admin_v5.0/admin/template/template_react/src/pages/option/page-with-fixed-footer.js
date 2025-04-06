import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import { AppSettings } from './../../config/app-settings.js';
import PerfectScrollbar from 'react-perfect-scrollbar';
import Highlight from 'react-highlight';

class PageWithFooter extends React.Component {
	static contextType = AppSettings;

	componentDidMount() {
		this.context.handleSetAppContentFullHeight(true);
		this.context.handleSetAppContentClass('p-0 d-flex flex-column');
	}

	componentWillUnmount() {
		this.context.handleSetAppContentFullHeight(false);
		this.context.handleSetAppContentClass('');
	}
	
	render() {
		return (
			<div className="h-100 d-flex flex-column">
				<PerfectScrollbar className="app-content-padding flex-grow-1 overflow-hidden" options={{suppressScrollX: true}}>
					<ol className="breadcrumb float-xl-end">
						<li className="breadcrumb-item"><Link to="/page-option/with-fixed-footer">Home</Link></li>
						<li className="breadcrumb-item"><Link to="/page-option/with-fixed-footer">Page Options</Link></li>
						<li className="breadcrumb-item active">Page with Footer</li>
					</ol>
					<h1 className="page-header">Page with Fixed Footer <small>header small text goes here...</small></h1>
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
'import PerfectScrollbar from "react-perfect-scrollbar";\n'+
'\n'+
'class PageWithFooter extends React.Component {\n'+
'  static contextType = AppSettings;\n'+
'\n'+
'  componentDidMount() {\n'+
'    this.context.handleSetAppContentFullHeight(true);\n'+
'    this.context.handleSetAppContentClass("p-0 d-flex flex-column");\n'+
'  }\n'+
'\n'+
'  componentWillUnmount() {\n'+
'    this.context.handleSetAppContentFullHeight(false);\n'+
'    this.context.handleSetAppContentClass("");\n'+
'  }\n'+
'  \n'+
'  render() {\n'+
'    return (\n'+
'      <div className="h-100 d-flex flex-column">\n'+
'        <PerfectScrollbar className="app-content-padding flex-grow-1 overflow-hidden" options={{suppressScrollX: true}}>\n'+
'          ...\n'+
'        </PerfectScrollbar>\n'+
'        \n'+
'        <div id="footer" className="app-footer m-0">\n'+
'          &copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved\n'+
'        </div>\n'+
'      </div>\n'+
'    )\n'+
'}'}
							</Highlight>
						</div>
					</Panel>
				</PerfectScrollbar>
			
				<div id="footer" className="app-footer m-0">
					&copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved
				</div>
			</div>
		)
	}
}

export default PageWithFooter;