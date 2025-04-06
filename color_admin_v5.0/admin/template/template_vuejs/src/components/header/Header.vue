<template>
	<div>
		<!-- BEGIN #header -->
		<div id="header" class="app-header" v-bind:class="{ 'app-header-inverse': appOptions.appHeaderInverse }">
			<!-- BEGIN navbar-header -->
			<div class="navbar-header">
				<button type="button" class="navbar-mobile-toggler" v-on:click="toggleSidebarEndMobile" v-if="appOptions.appSidebarTwo">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<router-link to="/dashboard/v2" class="navbar-brand"><span class="navbar-logo"></span> <b>Color</b> Admin</router-link>
				<button type="button" class="navbar-mobile-toggler" v-on:click="toggleTopMenuMobile" v-if="appOptions.appTopMenu && !appOptions.appSidebarNone">
					<span class="fa-stack fa-lg text-inverse">
						<i class="far fa-square fa-stack-2x"></i>
						<i class="fa fa-cog fa-stack-1x"></i>
					</span>
				</button>
				<button type="button" class="navbar-mobile-toggler" v-on:click="toggleTopMenuMobile" v-if="appOptions.appTopMenu && appOptions.appSidebarNone">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<button type="button" class="navbar-mobile-toggler" v-on:click="toggleHeaderMegaMenuMobile" v-if="appOptions.appHeaderMegaMenu">
					<span class="fa-stack fa-lg text-inverse m-t-2">
						<i class="far fa-square fa-stack-2x"></i>
						<i class="fa fa-cog fa-stack-1x"></i>
					</span>
				</button>
				<button type="button" class="navbar-mobile-toggler" v-on:click="toggleSidebarMobile" v-if="!appOptions.appSidebarNone">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- END navbar-header -->
			
		
			<!-- BEGIN header-nav -->
			<div class="navbar-nav">
				<header-mega-menu v-if="appOptions.appHeaderMegaMenu"></header-mega-menu>
				<div class="navbar-item navbar-form">
					<form action="" method="POST" name="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Enter keyword" />
							<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div>
				
				<b-nav-item-dropdown class="navbar-item" menu-class="dropdown-menu media-list dropdown-menu-end" toggle-class="navbar-link dropdown-toggle icon" no-caret>
					<template slot="button-content">
						<i class="fa fa-bell"></i>
						<span class="badge">5</span>
					</template>
					<div class="dropdown-header">NOTIFICATIONS (5)</div>
					<a href="javascript:;" class="dropdown-item media">
						<div class="media-left">
							<i class="fa fa-bug media-object bg-gray-500"></i>
						</div>
						<div class="media-body">
							<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
							<div class="text-muted fs-10px">3 minutes ago</div>
						</div>
					</a>
					<a href="javascript:;" class="dropdown-item media">
						<div class="media-left">
							<img src="/assets/img/user/user-1.jpg" class="media-object" alt="" />
							<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
						</div>
						<div class="media-body">
							<h6 class="media-heading">John Smith</h6>
							<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
							<div class="text-muted fs-10px">25 minutes ago</div>
						</div>
					</a>
					<a href="javascript:;" class="dropdown-item media">
						<div class="media-left">
							<img src="/assets/img/user/user-2.jpg" class="media-object" alt="" />
							<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
						</div>
						<div class="media-body">
							<h6 class="media-heading">Olivia</h6>
							<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
							<div class="text-muted fs-10px">35 minutes ago</div>
						</div>
					</a>
					<a href="javascript:;" class="dropdown-item media">
						<div class="media-left">
							<i class="fa fa-plus media-object bg-gray-500"></i>
						</div>
						<div class="media-body">
							<h6 class="media-heading"> New User Registered</h6>
							<div class="text-muted fs-10px">1 hour ago</div>
						</div>
					</a>
					<a href="javascript:;" class="dropdown-item media">
						<div class="media-left">
							<i class="fa fa-envelope media-object bg-gray-500"></i>
							<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
						</div>
						<div class="media-body">
							<h6 class="media-heading"> New Email From John</h6>
							<div class="text-muted fs-10px">2 hour ago</div>
						</div>
					</a>
					<div class="dropdown-footer text-center">
						<a href="javascript:;" class="text-decoration-none">View more</a>
					</div>
				</b-nav-item-dropdown>
				<b-nav-item-dropdown class="navbar-item" toggle-class="navbar-link dropdown-toggle" no-caret  v-if="appOptions.appHeaderLanguageBar">
					<template slot="button-content">
						<span class="flag-icon flag-icon-us me-1" title="us"></span>
						<span class="name d-none d-sm-inline me-1">EN</span> <b class="caret"></b>
					</template>
					<b-dropdown-item href="javascript:;"><span class="flag-icon flag-icon-us" title="us"></span> English</b-dropdown-item>
					<b-dropdown-item href="javascript:;"><span class="flag-icon flag-icon-cn" title="cn"></span> Chinese</b-dropdown-item>
					<b-dropdown-item href="javascript:;"><span class="flag-icon flag-icon-jp" title="jp"></span> Japanese</b-dropdown-item>
					<b-dropdown-item href="javascript:;"><span class="flag-icon flag-icon-be" title="be"></span> Belgium</b-dropdown-item>
					<b-dropdown-divider class="m-b-0"></b-dropdown-divider>
					<b-dropdown-item href="javascript:;" class="text-center">more options</b-dropdown-item>
				</b-nav-item-dropdown>
				<b-nav-item-dropdown menu-class="dropdown-menu-end me-1" class="navbar-item navbar-user dropdown" toggle-class="navbar-link dropdown-toggle d-flex align-items-center" no-caret>
					<template slot="button-content">
						<img src="/assets/img/user/user-13.jpg" alt="" /> 
						<span class="d-none d-md-inline">Adam Schwartz</span> <b class="caret"></b>
					</template>
					<a href="javascript:;" class="dropdown-item">Edit Profile</a>
					<a href="javascript:;" class="dropdown-item"><span class="badge bg-danger float-end rounded-pill">2</span> Inbox</a>
					<a href="javascript:;" class="dropdown-item">Calendar</a>
					<a href="javascript:;" class="dropdown-item">Setting</a>
					<div class="dropdown-divider"></div>
					<a href="javascript:;" class="dropdown-item">Log Out</a>
				</b-nav-item-dropdown>
				<div class="navbar-divider d-none d-md-block" v-if="appOptions.appSidebarTwo"></div>
				<div class="navbar-item d-none d-md-block" v-if="appOptions.appSidebarTwo">
					<a href="javascript:;" v-on:click="toggleSidebarEnd" class="navbar-link icon">
						<i class="fa fa-th"></i>
					</a>
				</div>
			</div>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
	</div>
</template>

<script>
import AppOptions from '../../config/AppOptions.vue'
import HeaderMegaMenu from './HeaderMegaMenu.vue'

export default {
  name: 'Header',
	components: {
		HeaderMegaMenu
	},
  data() {
		return {
			appOptions: AppOptions
		}
  },
	methods: {
		toggleSidebarMobile() {
			this.appOptions.appSidebarMobileToggled = !this.appOptions.appSidebarMobileToggled;
		},
		toggleSidebarEnd() {
			this.appOptions.appSidebarEndToggled = !this.appOptions.appSidebarEndToggled;
		},
		toggleSidebarEndMobile() {
			this.appOptions.appSidebarEndMobileToggled = !this.appOptions.appSidebarEndMobileToggled;
		},
		toggleTopMenuMobile() {
			this.appOptions.appTopMenuMobileToggled = !this.appOptions.appTopMenuMobileToggled;
		},
		toggleHeaderMegaMenuMobile() {
			this.appOptions.appHeaderMegaMenuMobileToggled = !this.appOptions.appHeaderMegaMenuMobileToggled;
		},
		checkForm: function(e) {
			e.preventDefault();
			this.$router.push({ path: '/extra/search' })
		}
	}
}
</script>
