<template>
	<div class="app app-header-fixed app-sidebar-fixed" v-if="!appOptions.appEmpty" v-bind:class="{
		'app-sidebar-minified': appOptions.appSidebarMinified, 
		'app-content-full-height': appOptions.appContentFullHeight, 
		'app-without-sidebar': appOptions.appSidebarNone, 
		'app-with-end-sidebar': appOptions.appSidebarEnd, 
		'app-with-two-sidebar': appOptions.appSidebarTwo,
		'app-with-wide-sidebar': appOptions.appSidebarWide,
		'app-with-light-sidebar': appOptions.appSidebarLight,
		'app-sidebar-mobile-toggled': appOptions.appSidebarMobileToggled,
		'app-sidebar-end-toggled': appOptions.appSidebarEndToggled,
		'app-sidebar-end-collapsed': !appOptions.appSidebarEndToggled,
		'app-sidebar-end-mobile-toggled': appOptions.appSidebarEndMobileToggled,
		'app-header-fixed': !appOptions.appHeaderNone,
		'app-without-header': appOptions.appHeaderNone,
		'app-with-top-menu': appOptions.appTopMenu,
		'has-scroll': appOptions.appBodyScrollTop
	}">
		<Header />
		<TopMenu v-if="appOptions.appTopMenu" />
		<Sidebar v-if="!appOptions.appSidebarNone" />
		<SidebarRight v-if="appOptions.appSidebarTwo" />
		<div id="content" class="app-content" v-bind:class="appOptions.appContentClass">
			<router-view></router-view>
			<vue-ins-progress-bar></vue-ins-progress-bar>
		</div>
	</div>
	<div class="h-100" v-else>
		<router-view></router-view>
		<vue-ins-progress-bar></vue-ins-progress-bar>
	</div>
</template>

<script>
import Sidebar from './components/sidebar/Sidebar.vue'
import SidebarRight from './components/sidebar-right/SidebarRight.vue'
import Header from './components/header/Header.vue'
import TopMenu from './components/top-menu/TopMenu.vue'
import AppOptions from './config/AppOptions.vue'

export default {
  name: 'app',
  components: {
    Sidebar,
    SidebarRight,
    Header,
    TopMenu
  },
  data() {
		return {
			appOptions: AppOptions
		}
  },
	methods: {
		handleScroll: function() {
			AppOptions.appBodyScrollTop = window.scrollY;
		}
	},
  mounted () {
    this.$insProgress.finish()
  },
	created() {
		AppOptions.appBodyScrollTop = window.scrollY;
		
		window.addEventListener('scroll', this.handleScroll);
		
		this.$insProgress.start()
		
		this.$router.beforeEach((to, from, next) => {
			this.$insProgress.start()
			next()
		})
		this.$router.afterEach(() => {
			this.$insProgress.finish()
		})
	}
}
</script>
