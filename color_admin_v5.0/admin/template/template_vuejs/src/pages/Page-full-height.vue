<template>
	<div class="overflow-hidden h-100">
		<!-- BEGIN scrollbar -->
		<vue-custom-scrollbar class="app-content-padding h-100">
			<!-- BEGIN breadcrumb -->
			<ol class="breadcrumb float-xl-end">
				<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
				<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
				<li class="breadcrumb-item active">Full Height Content</li>
			</ol>
			<!-- END breadcrumb -->
			<!-- BEGIN page-header -->
			<h1 class="page-header">Full Height Content <small>header small text goes here...</small></h1>
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
    AppOptions.appContentFullHeight = true;
    AppOptions.appContentClass = 'p-0';
  },
  beforeRouteLeave (to, from, next) {
    AppOptions.appContentFullHeight = false;
    AppOptions.appContentClass = '';
    next();
  }
}
&lt;/script&gt;</code></pre>
				</div>
				<!-- END hljs-wrapper -->
			</panel>
			<!-- END panel -->
			
			Content Area with scrollbar. Try to scroll down.
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			You got the bottom
		</vue-custom-scrollbar>
		<!-- END scrollbar -->
	</div>
</template>

<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	created() {
		AppOptions.appContentFullHeight = true;
		AppOptions.appContentClass = 'p-0';
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appContentFullHeight = false;
		AppOptions.appContentClass = '';
		next();
	}
}
</script>