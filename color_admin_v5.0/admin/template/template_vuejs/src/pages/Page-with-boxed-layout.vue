<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
			<li class="breadcrumb-item active">Page with Boxed Layout</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Page with Boxed Layout <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN panel -->
		<panel title="Installation Settings">
			<p>
				Add the following app settings to the <code>page.vue</code> that you wish to change
			</p>
			<!-- BEGIN hljs-wrapper -->
			<div class="hljs-wrapper" slot="outsideBody" v-highlight>
				<pre><code class="html">&lt;script&gt;
export default {
  created() {
    document.body.className = document.body.className + ' boxed-layout';
  },
  beforeRouteLeave (to, from, next) {
    document.body.className = document.body.className.replace('boxed-layout');
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
export default {
	created() {
		document.body.className = document.body.className + ' boxed-layout';
	},
	beforeRouteLeave (to, from, next) {
		document.body.className = document.body.className.replace('boxed-layout');
		next();
	}
}
</script>