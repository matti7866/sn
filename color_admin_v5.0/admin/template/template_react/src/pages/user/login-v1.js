import React from 'react';
import { withRouter } from 'react-router-dom';
import { AppSettings } from './../../config/app-settings.js';

class LoginV1 extends React.Component {
	static contextType = AppSettings;

	constructor(props) {
    super(props);
    
    this.handleSubmit = this.handleSubmit.bind(this);
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
			<div className="login login-v1">
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
					<div className="login-body">
						<div className="login-content fs-13px">
							<form onSubmit={this.handleSubmit}>
								<div className="form-floating mb-20px">
									<input type="email" className="form-control fs-13px h-45px" id="emailAddress" placeholder="Email Address" />
									<label htmlFor="emailAddress" className="d-flex align-items-center py-0">Email Address</label>
								</div>
								<div className="form-floating mb-20px">
									<input type="password" className="form-control fs-13px h-45px" id="password" placeholder="Password" />
									<label htmlFor="password" className="d-flex align-items-center py-0">Password</label>
								</div>
								<div className="form-check mb-20px">
									<input className="form-check-input" type="checkbox" value="" id="rememberMe" />
									<label className="form-check-label" htmlFor="rememberMe">
										Remember Me
									</label>
								</div>
								<div className="login-buttons">
									<button type="submit" className="btn h-45px btn-success d-block w-100 btn-lg">Sign me in</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		)
	}
}

export default withRouter(LoginV1);