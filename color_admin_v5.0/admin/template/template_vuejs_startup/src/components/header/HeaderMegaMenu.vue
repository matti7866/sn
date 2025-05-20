<template>
	<!-- begin navbar-collapse -->
	<div class="collapse d-md-block me-auto" v-bind:class="{ 'show': appOptions.appHeaderMegaMenuMobileToggled }">
		<div class="navbar-nav">
			<b-nav-item-dropdown class="navbar-item dropdown dropdown-lg" toggle-class="navbar-link dropdown-toggle d-flex align-items-center">
				<template slot="button-content">
					<i class="fa fa-th-large fa-fw me-1"></i> 
					<span class="d-lg-inline d-md-none">Mega</span>
					<b class="caret ms-1"></b>
				</template>
				<div class="row">
					<div class="col-lg-4 col-md-4">
						<div class="h5 fw-bolder mb-2">UI Kits</div>
						<div class="row mb-3">
							<div class="col-lg-6">
								<ul class="nav d-block fw-bold">
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> FontAwesome</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Ionicons</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Simple Line Icons</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Typography</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Media Object</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Widget Boxes</a></li>
									<li><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Tabs & Accordions</a></li>
								</ul>
							</div>
							<div class="col-lg-6">
								<ul class="nav d-block fw-bold">
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Unlimited Nav Tabs</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Modal & Notification</a></li>
									<li><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Buttons</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4">
						<div class="h5 fw-bolder mb-2">Page Options <span class="badge bg-pink ms-2">11</span></div>
						<div class="row">
							<div class="col-lg-6">
								<ul class="nav d-block fw-bold">
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Blank Page</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Footer <span class="badge bg-success py-1">NEW</span></a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page without Sidebar</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Right Sidebar</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Minified Sidebar</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Two Sidebar</a></li>
									<li><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Line Icons</a></li>
								</ul>
							</div>
							<div class="col-lg-6">
								<ul class="nav d-block fw-bold">
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Full Height Content</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Mega Menu</a></li>
									<li class="mb-1"><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Light Sidebar</a></li>
									<li><a href="javascript:;" class="text-ellipsis text-gray-800 text-decoration-none"><i class="fa fa-chevron-right fa-fw text-gray-500"></i> Page with Large Sidebar</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 fw-bold">
						<div class="h5 fw-bolder mb-2">Paragraph</div>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis libero purus, fermentum at libero convallis, auctor dignissim mauris. Nunc laoreet pellentesque turpis sodales ornare. Nunc vestibulum nunc lorem, at sodales velit malesuada congue. Nam est tortor, tincidunt sit amet eros vitae, aliquam finibus mauris.
						</p>
						<p>
							Fusce ac ligula laoreet ante dapibus mattis. Nam auctor vulputate aliquam. Suspendisse efficitur, felis sed elementum eleifend, ipsum tellus sodales nisi, ut condimentum nisi sem in nibh. Phasellus suscipit vulputate purus at venenatis. Quisque luctus tincidunt tempor.
						</p>
					</div>
				</div>
			</b-nav-item-dropdown>
			<div class="navbar-item">
				<a href="javascript:;" class="navbar-link d-flex align-items-center">
					<i class="fa fa-gem fa-fw me-1"></i>
					<span class="d-lg-inline d-md-none">Client</span>
				</a>
			</div>
			<b-nav-item-dropdown class="navbar-item" toggle-class="navbar-link dropdown-toggle d-flex align-items-center">
				<template slot="button-content">
					<i class="fa fa-database fa-fw me-1"></i>
					<span class="d-lg-inline d-md-none">New</span>
					<b class="caret ms-1"></b>
				</template>
				<b-dropdown-item href="#">Action</b-dropdown-item>
				<b-dropdown-item href="#">Another action</b-dropdown-item>
				<b-dropdown-item href="#">Something else here...</b-dropdown-item>
				<b-dropdown-divider></b-dropdown-divider>
				<b-dropdown-item href="#">Separated link</b-dropdown-item>
				<b-dropdown-divider></b-dropdown-divider>
				<b-dropdown-item href="#">One more separated link</b-dropdown-item>
			</b-nav-item-dropdown>
		</div>
	</div>
	<!-- end navbar-collapse -->
</template>

<script>
import AppOptions from '../../config/AppOptions.vue'

export default {
  name: 'HeaderMegaMenu',
  data() {
		return {
			appOptions: AppOptions
		}
  }
}
</script>
