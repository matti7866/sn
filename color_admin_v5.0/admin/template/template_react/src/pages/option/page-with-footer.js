import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import { AppSettings } from './../../config/app-settings.js';
import Highlight from 'react-highlight';

class PageWithFooter extends React.Component {
	static contextType = AppSettings;
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/page-option/with-footer">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/page-option/with-footer">Page Options</Link></li>
					<li className="breadcrumb-item active">Page with Footer</li>
				</ol>
				<h1 className="page-header">Page with Footer <small>header small text goes here...</small></h1>
			
				<Panel>
					<PanelHeader>Installation Settings</PanelHeader>
					<PanelBody>
						<p>
							Add the <code>.app-footer</code> element to <code>.app-content</code> container for footer page element.
						</p>
					</PanelBody>
					<div className="hljs-wrapper">
						<Highlight className='typescript'>{
'<div id="footer" className="app-footer mx-0 px-0">\n'+
'  &copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved\n'+
'</div>'}
						</Highlight>
					</div>
				</Panel>
			
				<div id="footer" className="app-footer mx-0 px-0">
					&copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved
				</div>
			</div>
		)
	}
}

export default PageWithFooter;