<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
			<li class="breadcrumb-item active">Page without Sidebar</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Page without Sidebar <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN panel -->
		<panel title="Installation Settings">
			<p>
				Add the following app settings to the <code>page.vue</code> that you wish to change 
				<b>OR</b> change it from <code>/config/AppOptions.vue</code> to make it affected to the whole app.
			</p>
			<!-- BEGIN hljs-wrapper -->
			<div class="hljs-wrapper" slot="outsideBody" v-highlight>
				<pre><code class="html">&lt;script&gt;
import AppOptions from '../config/AppOptions.vue'

export default {
  created() {
    AppOptions.appSidebarNone = true;
  },
  beforeRouteLeave (to, from, next) {
    AppOptions.appSidebarNone = false;
    next();
  }
}
&lt;/script&gt;</code></pre>
			</div>
			<!-- END hljs-wrapper -->
		</panel>
		<!-- END panel -->

		<p>
			<a href="javascript:history.back(-1);" class="btn btn-success">
				<i class="fa fa-arrow-circle-left"></i> Back to previous page
			</a>
		</p>
	</div>
</template>


<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	created() {
		AppOptions.appSidebarNone = true;
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appSidebarNone = false;
		next();
	}
}
</script>