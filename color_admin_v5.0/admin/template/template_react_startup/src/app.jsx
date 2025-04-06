import React from 'react';
import { AppSettings } from './config/app-settings.js';

import Header from './components/header/header.jsx';
import Sidebar from './components/sidebar/sidebar.jsx';
import SidebarRight from './components/sidebar-right/sidebar-right.jsx';
import TopMenu from './components/top-menu/top-menu.jsx';
import Content from './components/content/content.jsx';
import FloatSubMenu from './components/float-sub-menu/float-sub-menu.jsx';


class App extends React.Component {
	constructor(props) {
		super(props);
		
		this.toggleAppSidebarMinify = (e) => {
			e.preventDefault();
			if (this.state.appSidebarMinify) {
				this.setState(state => ({
					appSidebarFloatSubMenuActive: false
				}));
			}
			this.setState(state => ({
				appSidebarMinify: !this.state.appSidebarMinify
			}));
		}
		this.toggleAppSidebarMobile = (e) => {
			e.preventDefault();
			this.setState(state => ({
				appSidebarMobileToggled: !this.state.appSidebarMobileToggled
			}));
		}
		this.handleSetAppSidebarNone = (value) => {
			this.setState(state => ({
				appSidebarNone: value
			}));
		}
		this.handleSetAppSidebarMinified = (value) => {
			this.setState(state => ({
				appSidebarMinify: value
			}));
		}
		this.handleSetAppSidebarWide = (value) => {
			this.setState(state => ({
				appSidebarWide: value
			}));
		}
		this.handleSetAppSidebarLight = (value) => {
			this.setState(state => ({
				appSidebarLight: value
			}));
		}
		this.handleSetAppSidebarTransparent = (value) => {
			this.setState(state => ({
				appSidebarTransparent: value
			}));
		}
		this.handleSetAppSidebarSearch = (value) => {
			this.setState(state => ({
				appSidebarSearch: value
			}));
		}
		
		this.toggleAppSidebarEnd = (e) => {
			e.preventDefault();
			this.setState(state => ({
				appSidebarEndToggled: !this.state.appSidebarEndToggled
			}));
		}
		this.toggleAppSidebarEndMobile = (e) => {
			e.preventDefault();
			this.setState(state => ({
				appSidebarEndMobileToggled: !this.state.appSidebarEndMobileToggled
			}));
		}
		this.handleSetAppSidebarEnd = (value) => {
			this.setState(state => ({
				appSidebarEnd: value
			}));
		}
		
		var appSidebarFloatSubMenuRemove;
		var appSidebarFloatSubMenuCalculate;
		var appSidebarFloatSubMenuRemoveTime = 250;
		this.handleAppSidebarFloatSubMenuOnMouseOver = (e) => {
			clearTimeout(appSidebarFloatSubMenuRemove);
			clearTimeout(appSidebarFloatSubMenuCalculate);
		}
		this.handleAppSidebarFloatSubMenuOnMouseOut = (e) => {
			appSidebarFloatSubMenuRemove = setTimeout(() => {
				this.setState(state => ({
					appSidebarFloatSubMenuActive: false
				}));
			}, appSidebarFloatSubMenuRemoveTime);
		}
		this.handleAppSidebarOnMouseOver = (e, menu) => {
			if (this.state.appSidebarMinify) {
				if (menu.children) {
					var left = (document.getElementById('sidebar').offsetWidth + document.getElementById('sidebar').offsetLeft) + 'px';
					
					clearTimeout(appSidebarFloatSubMenuRemove);
					clearTimeout(appSidebarFloatSubMenuCalculate);
			
					this.setState(state => ({
						appSidebarFloatSubMenu: menu,
						appSidebarFloatSubMenuActive: true,
						appSidebarFloatSubMenuLeft: left
					}));
					
					var offset = e.currentTarget.offsetParent.getBoundingClientRect();
					
					appSidebarFloatSubMenuCalculate = setTimeout(() => {
						var targetTop = offset.top;
						var windowHeight = window.innerHeight;
						var targetHeight = document.querySelector('.app-sidebar-float-submenu-container').offsetHeight;
						var top, bottom, arrowTop, arrowBottom, lineTop, lineBottom;
						
						if ((windowHeight - targetTop) > targetHeight) {
							top = offset.top + 'px';
							bottom = 'auto';
							arrowTop = '20px';
							arrowBottom = 'auto';
							lineTop = '20px';
							lineBottom = 'auto';
						} else {
							var aBottom = (windowHeight - targetTop) - 21;
							top = 'auto';
							bottom = '0';
							arrowTop = 'auto';
							arrowBottom = aBottom + 'px';
							lineTop = '20px';
							lineBottom = aBottom + 'px';
						}
						
						this.setState(state => ({
							appSidebarFloatSubMenuTop: top,
							appSidebarFloatSubMenuBottom: bottom,
							appSidebarFloatSubMenuLineTop: lineTop,
							appSidebarFloatSubMenuLineBottom: lineBottom,
							appSidebarFloatSubMenuArrowTop: arrowTop,
							appSidebarFloatSubMenuArrowBottom: arrowBottom,
							appSidebarFloatSubMenuOffset: offset
						}));
					}, 0);
					
				} else {
					appSidebarFloatSubMenuRemove = setTimeout(() => {
						this.setState(state => ({
							appSidebarFloatSubMenu: '',
							appSidebarFloatSubMenuActive: false
						}));
					}, appSidebarFloatSubMenuRemoveTime);
				}
			}
		}
		this.handleAppSidebarOnMouseOut = (e) => {
			if (this.state.appSidebarMinify) {
				appSidebarFloatSubMenuRemove = setTimeout(() => {
					this.setState(state => ({
						appSidebarFloatSubMenuActive: false
					}));
				}, appSidebarFloatSubMenuRemoveTime);
			}
		}
		this.handleAppSidebarFloatSubMenuClick = () => {
			if (this.state.appSidebarMinify) {
				const windowHeight = window.innerHeight;
				const targetHeight = document.getElementById('app-sidebar-float-submenu').offsetHeight;
				const targetTop = this.state.appSidebarFloatSubMenuOffset.top;
				const top = ((windowHeight - targetTop) > targetHeight) ? targetTop : 'auto';
				const left = (this.state.appSidebarFloatSubMenuOffset.left + document.getElementById('sidebar').offsetWidth) + 'px';
				const bottom = ((windowHeight - targetTop) > targetHeight) ? 'auto' : '0';
				const arrowTop = ((windowHeight - targetTop) > targetHeight) ? '20px' : 'auto';
				const arrowBottom = ((windowHeight - targetTop) > targetHeight) ? 'auto' : ((windowHeight - targetTop) - 21) + 'px';
				const lineTop = ((windowHeight - targetTop) > targetHeight) ? '20px' : 'auto';
				const lineBottom = ((windowHeight - targetTop) > targetHeight) ? 'auto' : ((windowHeight - targetTop) - 21) + 'px';
			
				this.setState(state => ({
					appSidebarFloatSubMenuTop: top,
					appSidebarFloatSubMenuLeft: left,
					appSidebarFloatSubMenuBottom: bottom,
					appSidebarFloatSubMenuLineTop: lineTop,
					appSidebarFloatSubMenuLineBottom: lineBottom,
					appSidebarFloatSubMenuArrowTop: arrowTop,
					appSidebarFloatSubMenuArrowBottom: arrowBottom
				}));
			}
		}
		
		this.handleSetAppContentNone = (value) => {
			this.setState(state => ({
				appContentNone: value
			}));
		}
		this.handleSetAppContentClass = (value) => {
			this.setState(state => ({
				appContentClass: value
			}));
		}
		this.handleSetAppContentFullHeight = (value) => {
			this.setState(state => ({
				appContentFullHeight: value
			}));
		}
		
		this.handleSetAppHeaderNone = (value) => {
			this.setState(state => ({
				appHeaderNone: value
			}));
		}
		this.handleSetAppHeaderInverse = (value) => {
			this.setState(state => ({
				appHeaderInverse: value
			}));
		}
		this.handleSetAppHeaderMegaMenu = (value) => {
			this.setState(state => ({
				appHeaderMegaMenu: value
			}));
		}
		this.handleSetAppHeaderLanguageBar = (value) => {
			this.setState(state => ({
				appHeaderLanguageBar: value
			}));
		}
		
		this.handleSetAppTopMenu = (value) => {
			this.setState(state => ({
				appTopMenu: value
			}));
		}
		this.toggleAppTopMenuMobile = (e) => {
			e.preventDefault();
			this.setState(state => ({
				appTopMenuMobileToggled: !this.state.appTopMenuMobileToggled
			}));
		}
		this.handleSetAppSidebarTwo = (value) => {
			this.setState(state => ({
				appSidebarTwo: value
			}));
			this.setState(state => ({
				appSidebarEndToggled: value
			}));
		}
		this.handleSetAppBoxedLayout = (value) => {
			if (value === true) {
				document.body.classList.add('boxed-layout');
			} else {
				document.body.classList.remove('boxed-layout');
			}
		}
		
		this.state = {
			appHeaderNone: false,
			appHeaderInverse: false,
			appHeaderMegaMenu: false,
			appHeaderLanguageBar: false,
			hasScroll: false,
			handleSetAppHeaderNone: this.handleSetAppHeaderNone,
			handleSetAppHeaderInverse: this.handleSetAppHeaderInverse,
			handleSetAppHeaderLanguageBar: this.handleSetAppHeaderLanguageBar,
			handleSetAppHeaderMegaMenu: this.handleSetAppHeaderMegaMenu,
			
			appSidebarNone: false,
			appSidebarWide: false,
			appSidebarLight: false,
			appSidebarMinify: false,
			appSidebarMobileToggled: false,
			appSidebarTransparent: false,
			appSidebarSearch: false,
			handleSetAppSidebarNone: this.handleSetAppSidebarNone,
			handleSetAppSidebarWide: this.handleSetAppSidebarWide,
			handleSetAppSidebarLight: this.handleSetAppSidebarLight,
			handleSetAppSidebarMinified: this.handleSetAppSidebarMinified,
			handleSetAppSidebarTransparent: this.handleSetAppSidebarTransparent,
			handleSetAppSidebarSearch: this.handleSetAppSidebarSearch,
			handleAppSidebarOnMouseOut: this.handleAppSidebarOnMouseOut,
			handleAppSidebarOnMouseOver: this.handleAppSidebarOnMouseOver,
			toggleAppSidebarMinify: this.toggleAppSidebarMinify,
			toggleAppSidebarMobile: this.toggleAppSidebarMobile,
			
			appSidebarFloatSubMenuActive: false,
			appSidebarFloatSubMenu: '',
			appSidebarFloatSubMenuTop: 'auto',
			appSidebarFloatSubMenuLeft: 'auto',
			appSidebarFloatSubMenuBottom: 'auto',
			appSidebarFloatSubMenuLineTop: 'auto',
			appSidebarFloatSubMenuLineBottom: 'auto',
			appSidebarFloatSubMenuArrowTop: 'auto',
			appSidebarFloatSubMenuArrowBottom: 'auto',
			appSidebarFloatSubMenuOffset: '',
			handleAppSidebarFloatSubMenuOnMouseOver: this.handleAppSidebarFloatSubMenuOnMouseOver,
			handleAppSidebarFloatSubMenuOnMouseOut: this.handleAppSidebarFloatSubMenuOnMouseOut,
			handleAppSidebarFloatSubMenuClick: this.handleAppSidebarFloatSubMenuClick,
			
			appContentNone: false,
			appContentClass: '',
			appContentFullHeight: false,
			handleSetAppContentNone: this.handleSetAppContentNone,
			handleSetAppContentClass: this.handleSetAppContentClass,
			handleSetAppContentFullHeight: this.handleSetAppContentFullHeight,
			
			appTopMenu: false,
			appTopMenuMobileToggled: false,
			toggleAppTopMenuMobile: this.toggleAppTopMenuMobile,
			handleSetAppTopMenu: this.handleSetAppTopMenu,
			
			appSidebarTwo: false,
			handleSetAppSidebarTwo: this.handleSetAppSidebarTwo,
			
			appSidebarEnd: false,
			appSidebarEndToggled: false,
			appSidebarEndMobileToggled: false,
			toggleAppSidebarEnd: this.toggleAppSidebarEnd,
			toggleAppSidebarEndMobile: this.toggleAppSidebarEndMobile,
			handleSetAppSidebarEnd: this.handleSetAppSidebarEnd,
			
			handleSetAppBoxedLayout: this.handleSetAppBoxedLayout
		};
	}
	
	componentDidMount() {
    window.addEventListener('scroll', this.handleScroll)
  }

  componentWillUnmount() {
    window.removeEventListener('scroll', this.handleScroll)
  }
  
  handleScroll = () => {
  	if (window.scrollY > 0) {
  		this.setState(state => ({
				hasScroll: true
			}));
  	} else {
  		this.setState(state => ({
				hasScroll: false
			}));
  	}
  	var elm = document.getElementsByClassName('nvtooltip');
  	for (var i = 0; i < elm.length; i++) {
  		elm[i].classList.add('d-none');
  	}
  }
	
	render() {
		return (
			<AppSettings.Provider value={this.state}>
				<div className={
					'app app-sidebar-fixed ' + 
					(this.state.appHeaderNone ? 'app-without-header ' : 'app-header-fixed ') + 
					(this.state.appSidebarNone ? 'app-without-sidebar ' : '') + 
					(this.state.appSidebarEnd ? 'app-with-end-sidebar ' : '') +
					(this.state.appSidebarWide ? 'app-with-wide-sidebar ' : '') +
					(this.state.appSidebarLight ? 'app-with-light-sidebar ' : '') +
					(this.state.appSidebarMinify ? 'app-sidebar-minified ' : '') + 
					(this.state.appSidebarMobileToggled ? 'app-sidebar-mobile-toggled ' : '') + 
					(this.state.appTopMenu ? 'app-with-top-menu ' : '') + 
					(this.state.appContentFullHeight ? 'app-content-full-height ' : '') + 
					(this.state.appSidebarTwo ? 'app-with-two-sidebar ' : '') + 
					(this.state.appSidebarEndToggled ? 'app-sidebar-end-toggled ' : '') + 
					(this.state.appSidebarEndMobileToggled ? 'app-sidebar-end-mobile-toggled ' : '') + 
					(this.state.hasScroll ? 'has-scroll ' : '')
				}>
					{!this.state.appHeaderNone && (<Header />)}
					{!this.state.appSidebarNone && (<Sidebar />)}
					{this.state.appSidebarTwo && (<SidebarRight />)}
					{this.state.appTopMenu && (<TopMenu />)}
					{!this.state.appContentNone && (<Content />)}
					<FloatSubMenu />
				</div>
			</AppSettings.Provider>
		)
	}
}

export default App;