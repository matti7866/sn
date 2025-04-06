<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
			<li class="breadcrumb-item active">Page with Mixed Menu</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Page with Mixed Menu <small>header small text goes here...</small></h1>
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
    AppOptions.appTopMenu = true;
  },
  beforeRouteLeave (to, from, next) {
    AppOptions.appTopMenu = false;
    next();
  }
}
&lt;/script&gt;</code></pre>
			</div>
			<!-- END hljs-wrapper -->
		</panel>
		<!-- END panel -->
	</div>
</template>

<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	created() {
		AppOptions.appTopMenu = true;
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appTopMenu = false;
		next();
	}
}
</script>