import React from 'react';
import ReactTags from 'react-tag-autocomplete';
import ReactQuill from 'react-quill';
import { Link } from 'react-router-dom';
import { AppSettings } from './../../config/app-settings.js';
import { ButtonDropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';
import PerfectScrollbar from 'react-perfect-scrollbar';

class EmailCompose extends React.Component {
	static contextType = AppSettings;
	
	constructor(props) {
		super(props);

		this.toggle = this.toggle.bind(this);
		this.toggleMobileEmailNav = this.toggleMobileEmailNav.bind(this);
		this.state = {
			dropdownOpen: false,
			cc: false,
			bcc: false,
			isMobileEmailNavOn: false,
			tags: [
				{ id: 1, name: 'bootstrap@gmail.com' },
				{ id: 2, name: 'google@gmail.com' }
			],
			tagsCc: [],
			tagsBcc: [],
			suggestions: [
				{ id: 3, name: 'programmer@gmail.com' },
				{ id: 4, name: 'uxui@gmail.com' },
				{ id: 5, name: 'designer@gmail.com' },
				{ id: 6, name: 'android@gmail.com' }
			],
			text: '',
			editor: {
				height: (window.innerHeight > 600) ? window.innerHeight - 315 : 300
			}
		};
	}

	componentDidMount() {
		this.context.handleSetAppContentFullHeight(true);
		this.context.handleSetAppContentClass('p-0');
	}

	componentWillUnmount() {
		this.context.handleSetAppContentFullHeight(false);
		this.context.handleSetAppContentClass('');
	}

	toggle() {
		this.setState(prevState => ({
			dropdownOpen: !prevState.dropdownOpen
		}));
	}
  
	handleTagDelete (i) {
		const tags = this.state.tags.slice(0)
		tags.splice(i, 1)
		this.setState({ tags })
	}

	handleTagAddition (tag) {
		const tags = [].concat(this.state.tags, tag)
		this.setState({ tags })
	}

	handleTagCcDelete (i) {
		const tagsCc = this.state.tagsCc.slice(0)
		tagsCc.splice(i, 1)
		this.setState({ tagsCc })
	}

	handleTagCcAddition (tag) {
		const tagsCc = [].concat(this.state.tagsCc, tag)
		this.setState({ tagsCc })
	}

	handleTagBccDelete (i) {
		const tagsBcc = this.state.tagsBcc.slice(0)
		tagsBcc.splice(i, 1)
		this.setState({ tagsBcc })
	}

	handleTagBccAddition (tag) {
		const tagsBcc = [].concat(this.state.tagsBcc, tag)
		this.setState({ tagsBcc })
	}

	handleCc(e) {
		e.preventDefault();
		this.setState(state => ({
			cc: true
		}));
	}

	handleBcc(e) {
		e.preventDefault();
		this.setState(state => ({
			bcc: true
		}));
	}

	handleChange(value) {
		this.setState({ text: value })
	}
	
	toggleMobileEmailNav() {
		this.setState(state => ({
      isMobileEmailNavOn: !state.isMobileEmailNavOn
    }));
	}
  
	render() {
		return (
			<div className="mailbox">
				<div className="mailbox-sidebar">
					<div className="mailbox-sidebar-header d-flex justify-content-center">
						<button onClick={this.toggleMobileEmailNav} className="btn btn-inverse btn-sm me-auto d-block d-lg-none">
							<i className="fa fa-cog"></i>
						</button>
						<Link to="/email/compose" className="btn btn-inverse ps-40px pe-40px btn-sm">
							Compose
						</Link>
					</div>
					<div className={'mailbox-sidebar-content collapse d-lg-block ' + (this.state.isMobileEmailNavOn ? 'show' : '') }>
						<PerfectScrollbar className="h-100" options={{suppressScrollX: true}}>
							<div className="nav-title"><b>FOLDERS</b></div>
							<ul className="nav nav-inbox">
								<li className="active"><Link to="/email/inbox"><i className="fa fa-hdd fa-lg fa-fw me-2"></i> Inbox <span className="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">52</span></Link></li>
								<li><Link to="/email/inbox"><i className="fa fa-flag fa-lg fa-fw me-2"></i> Important</Link></li>
								<li><Link to="/email/inbox"><i className="fa fa-envelope fa-lg fa-fw me-2"></i> Sent</Link></li>
								<li><Link to="/email/inbox"><i className="fa fa-save fa-lg fa-fw me-2"></i> Drafts</Link></li>
								<li><Link to="/email/inbox"><i className="fa fa-trash-alt fa-lg fa-fw me-2"></i> Trash</Link></li>
							</ul>
							<div className="nav-title"><b>LABEL</b></div>
							<ul className="nav nav-inbox">
								<li><Link to="/email/detail"><i className="fa fa-fw fa-lg fs-12px me-2 fa-circle text-inverse"></i> Admin</Link></li>
								<li><Link to="/email/detail"><i className="fa fa-fw fa-lg fs-12px me-2 fa-circle text-blue"></i> Designer & Employer</Link></li>
								<li><Link to="/email/detail"><i className="fa fa-fw fa-lg fs-12px me-2 fa-circle text-success"></i> Staff</Link></li>
								<li><Link to="/email/detail"><i className="fa fa-fw fa-lg fs-12px me-2 fa-circle text-warning"></i> Sponsorer</Link></li>
								<li><Link to="/email/detail"><i className="fa fa-fw fa-lg fs-12px me-2 fa-circle text-danger"></i> Client</Link></li>
							</ul>
						</PerfectScrollbar>
					</div>
				</div>
				<div className="mailbox-content">
					<div className="mailbox-content-header">
						<div className="btn-toolbar align-items-center">
							<div className="btn-group me-2">
								<Link to="/email/compose" className="btn btn-white btn-sm"><i className="fa fa-fw fa-envelope"></i> <span className="hidden-xs">Send</span></Link>
								<Link to="/email/compose" className="btn btn-white btn-sm"><i className="fa fa-fw fa-paperclip"></i> <span className="hidden-xs">Attach</span></Link>
							</div>
							<div>
								<ButtonDropdown isOpen={this.state.dropdownOpen} toggle={this.toggle}>
									<DropdownToggle color="white btn-sm">
										<i className="fa fa-ellipsis-h"></i>
									</DropdownToggle>
									<DropdownMenu>
										<DropdownItem>Save draft</DropdownItem>
										<DropdownItem>Show From</DropdownItem>
										<DropdownItem>Check names</DropdownItem>
										<DropdownItem>Switch to plain text</DropdownItem>
										<DropdownItem>Check for accessibility issues</DropdownItem>
									</DropdownMenu>
								</ButtonDropdown>
							</div>
							<div className="ms-auto">
								<Link to="/email/inbox" className="btn btn-white btn-sm"><i className="fa fa-fw fa-times"></i> <span className="hidden-xs">Discard</span></Link>
							</div>
						</div>
					</div>
					<div className="mailbox-content-body">
						<PerfectScrollbar className="h-100" options={{suppressScrollX: true}}>
							<form action="/" method="POST" name="email_to_form" className="mailbox-form">
								<div className="mailbox-to">
									<label className="control-label">To:</label>
									<ReactTags classNames={this.state.tagClass} placeholder="" tags={this.state.tags} suggestions={this.state.suggestions} onDelete={this.handleTagDelete.bind(this)} onAddition={this.handleTagAddition.bind(this)} />
									<span className="mailbox-float-link">
										{!this.state.cc && <Link to="/email/compose" onClick={this.handleCc.bind(this)} className="me-5px">Cc</Link>}
										{!this.state.bcc && <Link to="/email/compose" onClick={this.handleBcc.bind(this)}>Bcc</Link>}
									</span>
								</div>
								{this.state.cc &&
									<div className="mailbox-to">
										<label className="control-label">Cc:</label>
										<ReactTags classNames={this.state.tagClass} placeholder="" tags={this.state.tagsCc} suggestions={this.state.suggestions} onDelete={this.handleTagCcDelete.bind(this)} onAddition={this.handleTagCcAddition.bind(this)} />
									</div>
								}
								{this.state.bcc &&
									<div className="mailbox-to">
										<label className="control-label">Bcc:</label>
										<ReactTags classNames={this.state.tagClass} placeholder="" tags={this.state.tagsBcc} suggestions={this.state.suggestions} onDelete={this.handleTagBccDelete.bind(this)} onAddition={this.handleTagBccAddition.bind(this)} />
									</div>
								}
								<div className="mailbox-subject">
									<input type="text" className="form-control" placeholder="Subject" />
								</div>
								<div className="mailbox-input">
									<ReactQuill value={this.state.text} className="border-0" onChange={this.handleChange} style={{ height: this.state.editor.height + 'px', marginBottom: '20px' }} />
								</div>
							</form>
						</PerfectScrollbar>
					</div>
					<div className="mailbox-content-footer d-flex align-items-center justify-content-end">
						<button type="submit" className="btn btn-white ps-40px pe-40px me-5px">Discard</button>
						<button type="submit" className="btn btn-primary ps-40px pe-40px">Send</button>
					</div>
				</div>
			</div>
		)
	}
}

export default EmailCompose;