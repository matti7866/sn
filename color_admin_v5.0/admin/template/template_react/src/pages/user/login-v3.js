import React from 'react';
import { Link, withRouter } from 'react-router-dom';
import { AppSettings } from './../../config/app-settings.js';

class LoginV3 extends React.Component {
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
			<div className="login login-with-news-feed">
				<div className="news-feed">
					<div className="news-image" style={{backgroundImage: 'url(/assets/img/login-bg/login-bg-11.jpg)'}}></div>
					<div className="news-caption">
						<h4 className="caption-title"><b>Color</b> Admin App</h4>
						<p>
							Download the Color Admin app for iPhone®, iPad®, and Android™. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						</p>
					</div>
				</div>
				<div className="login-container">
					<div className="login-header mb-30px">
						<div className="brand">
							<div className="d-flex align-items-center">
								<span className="logo"></span>
							
							
								<b>Color</b> Admin
							</div>
							<small>Bootstrap 5 Responsive Admin Template</small>
						</div>
						<div className="icon">
							<i className="fa fa-sign-in-alt"></i>
						</div>
					</div>
					<div className="login-content">
						<form onSubmit={this.handleSubmit} className="fs-13px">
							<div className="form-floating mb-15px">
								<input type="text" className="form-control h-45px fs-13px" placeholder="Email Address" id="emailAddress" />
								<label htmlFor="emailAddress" className="d-flex align-items-center fs-13px text-gray-600">Email Address</label>
							</div>
							<div className="form-floating mb-15px">
								<input type="password" className="form-control h-45px fs-13px" placeholder="Password" id="password" />
								<label htmlFor="password" className="d-flex align-items-center fs-13px text-gray-600">Password</label>
							</div>
							<div className="form-check mb-30px">
								<input className="form-check-input" type="checkbox" value="1" id="rememberMe" />
								<label className="form-check-label" htmlFor="rememberMe">
									Remember Me
								</label>
							</div>
							<div className="mb-15px">
								<button type="submit" className="btn btn-success d-block h-45px w-100 btn-lg fs-14px">Sign me in</button>
							</div>
							<div className="mb-40px pb-40px text-inverse">
								Not a member yet? Click <Link to="/user/register-v3" className="text-primary">here</Link> to register.
							</div>
							<hr className="bg-gray-600 opacity-2" />
							<div className="text-gray-600 text-center text-gray-500-darker mb-0">
								&copy; Color Admin All Right Reserved 2021
							</div>
						</form>
					</div>
				</div>
			</div>
		)
	}
}

export default withRouter(LoginV3);