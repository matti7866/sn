import React from 'react';
import { Dropdown, DropdownToggle, DropdownMenu, DropdownItem } from 'reactstrap';

class DropdownNotification extends React.Component {
	constructor(props) {
		super(props);

		this.toggle = this.toggle.bind(this);
		this.state = {
			dropdownOpen: false
		};
	}

	toggle() {
		this.setState(prevState => ({
			dropdownOpen: !prevState.dropdownOpen
		}));
	}
  
	render() {
		return (
			<Dropdown isOpen={this.state.dropdownOpen} toggle={this.toggle} className="navbar-item dropdown" tag="div">
				<DropdownToggle className="navbar-link dropdown-toggle icon" tag="a">
					<i className="fa fa-bell"></i>
					<span className="badge">0</span>
				</DropdownToggle>
				<DropdownMenu className="dropdown-menu media-list dropdown-menu-end" right tag="div">
					<DropdownItem className="dropdown-header" tag="div" header>NOTIFICATIONS (0)</DropdownItem>
					<div className="text-center w-300px py-3">
						No notification found
					</div>
				</DropdownMenu>
			</Dropdown>
		);
	}
};

export default DropdownNotification;
