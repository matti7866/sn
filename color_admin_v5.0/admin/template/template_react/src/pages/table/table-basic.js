import React from 'react';
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import Highlight from 'react-highlight';

class UIGeneral extends React.Component {
	
	constructor(props) {
		super(props);
		this.handleOnChange = this.handleOnChange.bind(this);
	}
	
	handleOnChange() {
	
	}
	
	render() {
		
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/table/basic">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/table/basic">Tables</Link></li>
					<li className="breadcrumb-item active">Basic Tables</li>
				</ol>
				<h1 className="page-header">Basic Tables <small>header small text goes here...</small></h1>
				
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Default Table</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table mb-0">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table">\n'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Hover Table</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-hover mb-0 text-inverse">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table table-hover">\n'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Table Small</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-sm mb-0 text-inverse">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table table-sm">\n'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Responsive Table</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table">
										<thead>
											<tr>
												<th>#</th>
												<th nowrap="true">Table heading</th>
												<th nowrap="true">Table heading</th>
												<th nowrap="true">Table heading</th>
												<th nowrap="true">Table heading</th>
												<th nowrap="true">Table heading</th>
												<th nowrap="true">Table heading</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
												<td>Table cell</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<div className="table-responsive">\n'+
'  <table className="table">\n'+
'    ...\n'+
'  </table>\n'+
'</div>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Table Striped</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-striped mb-0">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table table-striped">'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Bordered Table</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-bordered mb-0">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>3</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table table-bordered">'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>UI Elements in Table <span className="badge bg-success ms-5px">NEW</span></PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-striped mb-0 align-middle">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
												<th width="1%"></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<img src="../assets/img/user/user-1.jpg" alt="" className="rounded h-30px" />
												</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
												<td nowrap="true">
													<Link to="/table/basic" className="btn btn-sm btn-primary w-60px me-1">Edit</Link>
													<Link to="/table/basic" className="btn btn-sm btn-white w-60px">Delete</Link>
												</td>
											</tr>
											<tr>
												<td>
													<img src="../assets/img/user/user-2.jpg" alt="" className="rounded h-30px" />
												</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
												<td nowrap="true">
													<div className="btn-group">
														<Link to="/table/basic" className="btn btn-white btn-sm w-90px">Settings</Link>
														<Link to="/table/basic" className="btn btn-white btn-sm dropdown-toggle w-30px no-caret" data-bs-toggle="dropdown">
														<span className="caret"></span>
														</Link>
														<div className="dropdown-menu dropdown-menu-end">
															<Link to="/table/basic" className="dropdown-item">Action 1</Link>
															<Link to="/table/basic" className="dropdown-item">Action 2</Link>
															<Link to="/table/basic" className="dropdown-item">Action 3</Link>
															<div className="dropdown-divider"></div>
															<Link to="/table/basic" className="dropdown-item">Action 4</Link>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<img src="../assets/img/user/user-3.jpg" alt="" className="rounded h-30px" />
												</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
												<td className="with-btn" nowrap="true">
													<Link to="/table/basic" className="btn btn-sm btn-primary w-60px me-1">Edit</Link>
													<Link to="/table/basic" className="btn btn-sm btn-white w-60px">Delete</Link>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='html'>{
'<table className="table align-middle">'+
'  ...\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Form Elements in Table <span className="badge bg-success ms-1">NEW</span></PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table table-striped align-middle mb-0">
										<thead>
											<tr>
												<th>
													<div className="form-check">
														<input type="checkbox" value="" onChange={this.handleOnChange} id="table_checkbox_1" className="form-check-input" />
														<label htmlFor="table_checkbox_1" className="form-check-label">&nbsp;</label>
													</div>
												</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div className="form-check">
														<input type="checkbox" value="" onChange={this.handleOnChange} id="table_checkbox_2" className="form-check-input" />
														<label htmlFor="table_checkbox_2" className="form-check-label">&nbsp;</label>
													</div>
												</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr>
												<td>
													<div className="form-check">
														<input type="radio" value="" onChange={this.handleOnChange} id="table_radio_1" className="form-check-input" />
														<label htmlFor="table_radio_1" className="form-check-label">&nbsp;</label>
													</div>
												</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr>
												<td>
													<div className="form-check">
														<input type="radio" value="" onChange={this.handleOnChange} id="table_radio_2" className="form-check-input" />
														<label htmlFor="table_radio_2" className="form-check-label">&nbsp;</label>
													</div>
												</td>
												<td><input type="text" className="form-control my-n1" defaultValue="Harvinder Singh" /></td>
												<td>harvinder@gmail.com</td>
											</tr>
											<tr>
												<td>
													<div className="form-check">
														<input type="radio" value="" onChange={this.handleOnChange} id="table_radio_3" className="form-check-input" />
														<label htmlFor="table_radio_3" className="form-check-label">&nbsp;</label>
													</div>
												</td>
												<td>
													<div className="input-group my-n1">
														<div className="input-group-text">@</div>
														<input type="text" className="form-control" placeholder="Terry" />
													</div>
												</td>
												<td>terry@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table align-middle">\n'+
'  <tbody>\n'+
'    <tr>\n'+
'      <!-- with input -->\n'+
'      <td>\n'+
'        <input type="text" className="form-control my-n1" />\n'+
'      </td>\n'+
'    </tr>\n'+
'  \n'+
'    <tr>\n'+
'      <!-- with input-group -->\n'+
'      <td>\n'+
'        <div className="input-group my-n1"></div>\n'+
'      </td>\n'+
'    </tr>\n'+
'  \n'+
'    <tr>\n'+
'      <!-- with btn-group -->\n'+
'      <td>\n'+
'        <div className="btn-group my-n1"></div>\n'+
'      </td>\n'+
'    </tr>\n'+
'  </tbody>\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
						<Panel>
							<PanelHeader>Table Row Classes</PanelHeader>
							<PanelBody>
								<div className="table-responsive">
									<table className="table mb-0">
										<thead>
											<tr>
												<th>#</th>
												<th>Username</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>
											<tr className="table-active">
												<td>1</td>
												<td>Nicky Almera</td>
												<td>nicky@hotmail.com</td>
											</tr>
											<tr className="table-info">
												<td>2</td>
												<td>Terry Khoo</td>
												<td>terry@gmail.com</td>
											</tr>
											<tr className="table-success">
												<td>3</td>
												<td>Edmund Wong</td>
												<td>edmund@yahoo.com</td>
											</tr>
											<tr className="table-warning">
												<td>4</td>
												<td>Harvinder Singh</td>
												<td>harvinder@gmail.com</td>
											</tr>
											<tr className="table-danger">
												<td>5</td>
												<td>Terry Khoo</td>
												<td>terry@gmail.com</td>
											</tr>
										</tbody>
									</table>
								</div>
							</PanelBody>
							<div className="hljs-wrapper">
								<Highlight className='html'>{
'<table className="table">\n'+
'  <tbody>\n'+
'    <tr className="table-active">...</tr>\n'+
'    <tr className="table-info">...</tr>\n'+
'    <tr className="table-success">...</tr>\n'+
'    <tr className="table-warning">...</tr>\n'+
'    <tr className="table-danger">...</tr>\n'+
'  </tbody>\n'+
'</table>'}
								</Highlight>
							</div>
						</Panel>
					</div>
				</div>
			</div>
		)
	}
}

export default UIGeneral;