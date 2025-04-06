import React from 'react';
import { Link, withRouter } from 'react-router-dom';
import { AppSettings } from './../../config/app-settings.js';

class LoginV2 extends React.Component {
	static contextType = AppSettings;

	constructor(props) {
		super(props);

		this.state = {
			activeBg: '/assets/img/login-bg/login-bg-17.jpg',
			bg1: true,
			bg2: false,
			bg3: false,
			bg4: false,
			bg5: false,
			bg6: false
		}
		this.selectBg = this.selectBg.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
	}

	selectBg(e, active, bg) {
		e.preventDefault();

		this.setState(state => ({
			activeBg: bg,
			bg1: (active === 'bg1') ? true : false,
			bg2: (active === 'bg2') ? true : false,
			bg3: (active === 'bg3') ? true : false,
			bg4: (active === 'bg4') ? true : false,
			bg5: (active === 'bg5') ? true : false,
			bg6: (active === 'bg6') ? true : false
		}));
	}

	
	componentDidMount() {
		this.context.handleSetAppSidebarNone(true);
		this.context.handleSetAppHeaderNone(true);
		this.context.handleSetAppContentClass('p-0');
	}

	componentWillUnmount() {
		this.context.handleSetAppSidebarNone(false);
		this.context.handleSetAppHeaderNone(false);
		this.context.handleSetAppContentClass('');
	}
	
	handleSubmit(event) {
		event.preventDefault();
    this.props.history.push('/dashboard/v3');
  }
  
	render() {
		return (
			<React.Fragment>
				<div className="login login-v2 fw-bold">
					<div className="login-cover">
						<div className="login-cover-img" style={{ backgroundImage: 'url(' + this.state.activeBg + ')'}}></div>
						<div className="login-cover-bg"></div>
					</div>
			
					<div className="login-container">
						<div className="login-header">
							<div className="brand">
								<div className="d-flex align-items-center">
									<span className="logo"></span> <b>Color</b> Admin
								</div>
								<small>Bootstrap 5 Responsive Admin Template</small>
							</div>
							<div className="icon">
								<i className="fa fa-lock"></i>
							</div>
						</div>
						
						<div className="login-content">
							<form action="index.html" method="GET">
								<div className="form-floating mb-20px">
									<input type="text" className="form-control fs-13px h-45px border-0" placeholder="Email Address" id="emailAddress" />
									<label htmlFor="emailAddress" className="d-flex align-items-center text-gray-600 fs-13px">Email Address</label>
								</div>
								<div className="form-floating mb-20px">
									<input type="password" className="form-control fs-13px h-45px border-0" placeholder="Password" />
									<label htmlFor="emailAddress" className="d-flex align-items-center text-gray-600 fs-13px">Password</label>
								</div>
								<div className="form-check mb-20px">
									<input className="form-check-input border-0" type="checkbox" value="1" id="rememberMe" />
									<label className="form-check-label fs-13px text-gray-500" htmlFor="rememberMe">
										Remember Me
									</label>
								</div>
								<div className="mb-20px">
									<button type="submit" className="btn btn-success d-block w-100 h-45px btn-lg">Sign me in</button>
								</div>
								<div className="text-gray-500">
									Not a member yet? Click <Link to="/user/register-v3">here</Link> to register.
								</div>
							</form>
						</div>
					</div>
				</div>
		
				<div className="login-bg-list clearfix">
					<div className={'login-bg-list-item ' + (this.state.bg1 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg1', '/assets/img/login-bg/login-bg-17.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-17.jpg)'}} className="login-bg-list-link"></Link></div>
					<div className={'login-bg-list-item ' + (this.state.bg2 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg2', '/assets/img/login-bg/login-bg-16.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-16.jpg)'}} className="login-bg-list-link"></Link></div>
					<div className={'login-bg-list-item ' + (this.state.bg3 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg3', '/assets/img/login-bg/login-bg-15.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-15.jpg)'}} className="login-bg-list-link"></Link></div>
					<div className={'login-bg-list-item ' + (this.state.bg4 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg4', '/assets/img/login-bg/login-bg-14.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-14.jpg)'}} className="login-bg-list-link"></Link></div>
					<div className={'login-bg-list-item ' + (this.state.bg5 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg5', '/assets/img/login-bg/login-bg-13.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-13.jpg)'}} className="login-bg-list-link"></Link></div>
					<div className={'login-bg-list-item ' + (this.state.bg6 ? 'active ' : '')}><Link to="/user/login-v2" onClick={(e) => this.selectBg(e, 'bg6', '/assets/img/login-bg/login-bg-12.jpg')} style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-12.jpg)'}} className="login-bg-list-link"></Link></div>
				</div>
			</React.Fragment>
		)
	}
}

export default withRouter(LoginV2);