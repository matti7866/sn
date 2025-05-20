import React from 'react';
import { Link } from 'react-router-dom';
import { Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import SweetAlert from 'react-bootstrap-sweetalert';
import ReactNotification from 'react-notifications-component';
import { store } from 'react-notifications-component';
import 'react-notifications-component/dist/theme.css';
import Highlight from 'react-highlight';

class UIModalNotification extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			modalDialog: false,
			modalWithoutAnimation: false,
			modalMessage: false,
			modalAlert: false,
			sweetAlertPrimary: false,
			sweetAlertInfo: false,
			sweetAlertWarning: false,
			sweetAlertSuccess: false,
			sweetAlertError: false
		};

		this.toggleModal = this.toggleModal.bind(this);
		this.toggleSweetAlert = this.toggleSweetAlert.bind(this);
		this.addNotification = this.addNotification.bind(this);
		this.notificationDOMRef = React.createRef();
	}
  
	addNotification(notificationType, notificationTitle, notificationMessage, notificationPosition, notificationContent) {
		if (notificationContent) {
			notificationContent = (
				<div className="d-flex align-items-center bg-gray-900 rounded p-2 text-white w-100">
					<img src="../assets/img/user/user-12.jpg" width="52" alt="" className="rounded" />
					<div className="flex-1 ps-2">
						<h6 className="mb-1">Christopher Struth</h6>
						<p className="mb-0">Bank Transfer</p>
					</div>
				</div>
			);
		}
		store.addNotification({
			title: notificationTitle,
			message: notificationMessage,
			type: notificationType,
			insert: "top",
			container: notificationPosition,
			animationIn: ["animated", "fadeIn"],
			animationOut: ["animated", "fadeOut"],
			dismiss: { duration: 2000 },
			dismissable: { click: true },
			content: notificationContent
		});
	}
  
	toggleModal(name) {
		switch (name) {
			case 'modalDialog':	
				this.setState({ modalDialog: !this.state.modalDialog });
				break;
			case 'modalWithoutAnimation':	
				this.setState({ modalWithoutAnimation: !this.state.modalWithoutAnimation });
				break;
			case 'modalMessage':	
				this.setState({ modalMessage: !this.state.modalMessage });
				break;
			case 'modalAlert':	
				this.setState({ modalAlert: !this.state.modalAlert });
				break;
			default:
				break;
		}
	}
  
	toggleSweetAlert(name) {
		switch(name) {
			case 'primary':
				this.setState({ sweetAlertPrimary: !this.state.sweetAlertPrimary });
				break;
			case 'info':
				this.setState({ sweetAlertInfo: !this.state.sweetAlertInfo });
				break;
			case 'success':
				this.setState({ sweetAlertSuccess: !this.state.sweetAlertSuccess });
				break;
			case 'warning':
				this.setState({ sweetAlertWarning: !this.state.sweetAlertWarning });
				break;
			case 'error':
				this.setState({ sweetAlertError: !this.state.sweetAlertError });
				break;
			default:
				break;
		}
	}
  
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/ui">UI Elements</Link></li>
					<li className="breadcrumb-item active">Modal & Notification</li>
				</ol>
				<h1 className="page-header">Modal & Notification <small>header small text goes here...</small></h1>
				<div className="row">
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>
								React Notifications Component <span className="badge bg-success">NEW</span>
							</PanelHeader>
							<ReactNotification />
							<PanelBody>
								<table className="table mb-0">
									<thead>
										<tr>
											<th>Description</th>
											<th>Demo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><b className="text-inverse">Bottom Left</b> Success Notification</td>
											<td><button onClick={() => this.addNotification('success', 'Success', 'All your data has been successfully updated', 'bottom-left')} className="btn btn-sm btn-inverse">Demo</button></td>
										</tr>
										<tr>
											<td><b className="text-inverse">Bottom Right</b> Default Notification</td>
											<td><button onClick={() => this.addNotification('default', 'Default', 'A new issue has been reported by Office Desk', 'bottom-right')} className="btn btn-sm btn-primary">Demo</button></td>
										</tr>
										<tr>
											<td><b className="text-inverse">Bottom Center</b> Info Notification</td>
											<td><button onClick={() => this.addNotification('info', 'Info', 'You have an appointment at 4PM today', 'bottom-center')} className="btn btn-sm btn-info">Demo</button></td>
										</tr>
										<tr>
											<td><b className="text-inverse">Top Left</b> Success Notification</td>
											<td><button onClick={() => this.addNotification('warning', 'Success', 'All your data has been successfully updated', 'top-left')} className="btn btn-sm btn-warning">Demo</button></td>
										</tr>
										<tr>
											<td><b className="text-inverse">Top Right</b> Warning Notification</td>
											<td><button onClick={() => this.addNotification('danger', 'Danger', 'Document has been permanently removed', 'top-right')} className="btn btn-sm btn-danger">Demo</button></td>
										</tr>
										<tr>
											<td><b className="text-inverse">Top Center</b> Custom Notification</td>
											<td>
												<button onClick={() => this.addNotification('custom', 'Success', 'All your data has been successfully updated', 'top-center', true)} className="btn btn-sm btn-default">Demo</button></td>
										</tr>
									</tbody>
								</table>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='typescript'>{
'import ReactNotification from \'react-notifications-component\';\n'+
'\n'+
'class UIModalNotification extends React.Component {\n'+
'  constructor(props) {\n'+
'    super(props);\n'+
'    this.addNotification = this.addNotification.bind(this);\n'+
'    this.notificationDOMRef = React.createRef();\n'+
'  }\n'+
'\n'+
'  addNotification(notificationType, notificationTitle, notificationMessage, notificationPosition, notificationContent) {\n'+
'    store.addNotification({\n'+
'      title: notificationTitle,\n'+
'      message: notificationMessage,\n'+
'      type: notificationType,\n'+
'      insert: "top",\n'+
'      container: notificationPosition,\n'+
'      animationIn: ["animated", "fadeIn"],\n'+
'      animationOut: ["animated", "fadeOut"],\n'+
'      dismiss: { duration: 2000 },\n'+
'      dismissable: { click: true },\n'+
'      content: notificationContent\n'+
'    });\n'+
'  }\n'+
'}\n'+
'\n'+
'<button onClick={() => this.addNotification(\'success\', \'Success\', \'All your data has been successfully updated\', \'bottom-left\')} className="btn btn-sm btn-inverse">Demo</button>'
}
							</Highlight>
						</div>
						</Panel>
					</div>
					<div className="col-xl-6">
						<Panel>
							<PanelHeader>Modal</PanelHeader>
							<PanelBody>
								<table className="table">
									<thead>
										<tr>
											<th>Description</th>
											<th>Demo</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Default Modal Dialog Box.</td>
											<td><button onClick={() => this.toggleModal('modalDialog')} className="btn btn-sm btn-success">Demo</button></td>
										</tr>
										<tr>
											<td>Modal Dialog Box with fade out animation.</td>
											<td><button onClick={() => this.toggleModal('modalWithoutAnimation')} className="btn btn-sm btn-default">Demo</button></td>
										</tr>
										<tr>
											<td>Modal Dialog Box with full width white background.</td>
											<td><button onClick={() => this.toggleModal('modalMessage')} className="btn btn-sm btn-primary">Demo</button></td>
										</tr>
										<tr>
											<td>Modal Dialog Box with alert message.</td>
											<td><button onClick={() => this.toggleModal('modalAlert')} className="btn btn-sm btn-danger">Demo</button></td>
										</tr>
									</tbody>
								</table>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='typescript'>{
'import { Modal, ModalHeader, ModalBody, ModalFooter } from\'reactstrap\';\n\n'+
'class UIModalNotification extends React.Component {\n'+
'  constructor(props) {\n'+
'    super(props);\n'+
'    this.state = {\n'+
'      modalDialog: false\n'+
'    };\n'+
'\n'+
'    this.toggleModal = this.toggleModal.bind(this);\n'+
'  }\n'+
'  \n'+
'  toggleModal(name) {\n'+
'    switch (name) {\n'+
'      case \'modalDialog\':  \n'+
'        this.setState({ modalDialog: !this.state.modalDialog });\n'+
'        break;\n'+
'    }\n'+
'  }\n'+
'}\n'+
'\n'+
'<button onClick={() => this.toggleModal(\'modalDialog\')} className="btn btn-sm btn-success">Demo</button>\n'+
'\n'+
'<Modal isOpen={this.state.modalDialog} toggle={() => this.toggleModal(\'modalDialog\')}>\n'+
'  <ModalHeader toggle={() => this.toggleModal(\'modalDialog\')} close={<button className="btn-close" onClick={() => this.toggleModal(\'modalDialog\')}></button>}>Modal Dialog</ModalHeader>\n'+
'  <ModalBody>\n'+
'    ..\n'+
'  </ModalBody>\n'+
'  <ModalFooter>\n'+
'    <button className="btn btn-white" onClick={() => this.toggleModal(\'modalDialog\')}>Close</button>\n'+
'    <button className="btn btn-success">Action</button>\n'+
'  </ModalFooter>\n'+
'</Modal>'}
							</Highlight>
						</div>
							<Modal isOpen={this.state.modalDialog} toggle={() => this.toggleModal('modalDialog')}>
								<ModalHeader toggle={() => this.toggleModal('modalDialog')} close={<button className="btn-close" onClick={() => this.toggleModal('modalDialog')}></button>}>Modal Dialog</ModalHeader>
          			<ModalBody>
          				<p>
										Modal body content here...
									</p>
								</ModalBody>
								<ModalFooter>
									<button className="btn btn-white" onClick={() => this.toggleModal('modalDialog')}>Close</button>
									<button className="btn btn-success">Action</button>
								</ModalFooter>
							</Modal>
							
							<Modal isOpen={this.state.modalWithoutAnimation} fade={false} toggle={() => this.toggleModal('modalWithoutAnimation')}>
								<ModalHeader toggle={() => this.toggleModal('modalWithoutAnimation')} close={<button className="btn-close" onClick={() => this.toggleModal('modalWithoutAnimation')}></button>}>Modal Without Animation</ModalHeader>
          			<ModalBody>
          				<p>
										Modal body content here...
									</p>
								</ModalBody>
								<ModalFooter>
									<button className="btn btn-white" onClick={() => this.toggleModal('modalWithoutAnimation')}>Close</button>
								</ModalFooter>
							</Modal>
							
							<Modal isOpen={this.state.modalMessage} toggle={() => this.toggleModal('modalMessage')} modalClassName="modal-message">
								<ModalHeader toggle={() => this.toggleModal('modalMessage')} close={<button className="btn-close" onClick={() => this.toggleModal('modalMessage')}></button>}>Modal Message Header</ModalHeader>
          			<ModalBody>
									<p>Text in a modal</p>
									<p>Do you want to turn on location services so GPS can use your location ?</p>
								</ModalBody>
								<ModalFooter>
									<button className="btn btn-white" onClick={() => this.toggleModal('modalMessage')}>Close</button>
									<button className="btn btn-primary">Save Changes</button>
								</ModalFooter>
							</Modal>
							
							<Modal isOpen={this.state.modalAlert} toggle={() => this.toggleModal('modalAlert')}>
								<ModalHeader toggle={() => this.toggleModal('modalAlert')} close={<button className="btn-close" onClick={() => this.toggleModal('modalAlert')}></button>}>Alert Header</ModalHeader>
          			<ModalBody>
									<div className="alert alert-danger mb-0">
										<h5><i className="fa fa-info-circle"></i> Alert Header</h5>
										<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
									</div>
								</ModalBody>
								<ModalFooter>
									<button className="btn btn-white" onClick={() => this.toggleModal('modalAlert')}>Close</button>
									<button className="btn btn-danger">Action</button>
								</ModalFooter>
							</Modal>
						</Panel>
						<Panel title="Modal">
							<PanelHeader>
								Bootstrap SweetAlert <span className="badge bg-success">NEW</span>
							</PanelHeader>
							<PanelBody>
								<p className="lead text-inverse">
									SweetAlert for Bootstrap. A beautiful replacement for JavaScript's "alert"
								</p>
								<hr />
								<p className="">
									Try any of those!
								</p>
								<button onClick={() => this.toggleSweetAlert('primary')} className="btn btn-primary me-5px">Primary</button>
								<button onClick={() => this.toggleSweetAlert('info')} className="btn btn-info me-5px">Info</button>
								<button onClick={() => this.toggleSweetAlert('success')} className="btn btn-success me-5px">Success</button>
								<button onClick={() => this.toggleSweetAlert('warning')} className="btn btn-warning me-5px">Warning</button>
								<button onClick={() => this.toggleSweetAlert('error')} className="btn btn-danger me-5px">Danger</button>
							</PanelBody>
							<div className="hljs-wrapper">
							<Highlight className='typescript'>{
'import SweetAlert from \'react-bootstrap-sweetalert\';\n'+
'\n'+
'class UIModalNotification extends React.Component {\n'+
'  constructor(props) {\n'+
'    super(props);\n'+
'    this.state = {\n'+
'      sweetAlertPrimary: false\n'+
'    };\n'+
'\n'+
'    this.toggleModal = this.toggleModal.bind(this);\n'+
'    this.toggleSweetAlert = this.toggleSweetAlert.bind(this);\n'+
'  }\n'+
'  \n'+
'  toggleSweetAlert(name) {\n'+
'    switch(name) {\n'+
'      case \'primary\':\n'+
'        this.setState({ sweetAlertPrimary: !this.state.sweetAlertPrimary });\n'+
'        break;\n'+
'    }\n'+
'  }\n'+
'}\n'+
'\n'+
'{(this.state.sweetAlertPrimary &&\n'+
'  <SweetAlert showCancel\n'+
'    confirmBtnText="Continue"\n'+
'    confirmBtnBsStyle="primary"\n'+
'    cancelBtnBsStyle="default"\n'+
'    title="Are you sure?"\n'+
'    onConfirm={() => this.toggleSweetAlert(\'primary\')}\n'+
'    onCancel={() => this.toggleSweetAlert(\'primary\')}\n'+
'  >\n'+
'    You will not be able to recover this imaginary file!\n'+
'  </SweetAlert>\n'+
')}'}
							</Highlight>
						</div>
						</Panel>
						{(this.state.sweetAlertPrimary &&
							<SweetAlert showCancel
								confirmBtnText="Continue"
								confirmBtnBsStyle="primary"
								cancelBtnBsStyle="default"
								title="Are you sure?"
								onConfirm={() => this.toggleSweetAlert('primary')}
								onCancel={() => this.toggleSweetAlert('primary')}
							>
								You will not be able to recover this imaginary file!
							</SweetAlert>
						)}
						{(this.state.sweetAlertInfo &&
							<SweetAlert info showCancel
								confirmBtnText="Continue"
								confirmBtnBsStyle="info"
								cancelBtnBsStyle="default"
								title="Are you sure?"
								onConfirm={() => this.toggleSweetAlert('info')}
								onCancel={() => this.toggleSweetAlert('info')}
							>
								You will not be able to recover this imaginary file!
							</SweetAlert>
						)}
						{(this.state.sweetAlertSuccess &&
							<SweetAlert success showCancel
								confirmBtnText="Continue"
								confirmBtnBsStyle="success"
								cancelBtnBsStyle="default"
								title="Are you sure?"
								onConfirm={() => this.toggleSweetAlert('success')}
								onCancel={() => this.toggleSweetAlert('success')}
							>
								You will not be able to recover this imaginary file!
							</SweetAlert>
						)}
						{(this.state.sweetAlertWarning &&
							<SweetAlert warning showCancel
								confirmBtnText="Continue"
								confirmBtnBsStyle="warning"
								cancelBtnBsStyle="default"
								title="Are you sure?"
								onConfirm={() => this.toggleSweetAlert('warning')}
								onCancel={() => this.toggleSweetAlert('warning')}
							>
								You will not be able to recover this imaginary file!
							</SweetAlert>
						)}
						{(this.state.sweetAlertError &&
							<SweetAlert danger showCancel
								confirmBtnText="Yes, delete it!"
								confirmBtnBsStyle="danger"
								cancelBtnBsStyle="default"
								title="Are you sure?"
								onConfirm={() => this.toggleSweetAlert('error')}
								onCancel={() => this.toggleSweetAlert('error')}
							>
								You will not be able to recover this imaginary file!
							</SweetAlert>
						)}
					</div>
				</div>
			</div>
		)
	}
}

export default UIModalNotification;