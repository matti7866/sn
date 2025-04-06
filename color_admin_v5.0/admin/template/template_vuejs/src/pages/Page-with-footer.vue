<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
			<li class="breadcrumb-item active">Page with Footer</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Page with Footer <small>header small text goes here...</small></h1>
		<!-- END page-header -->
	
		<!-- BEGIN panel -->
		<panel title="Installation Settings">
			<p>
				Add the <code>.app-footer</code> element to <code>div</code> container for footer page element.
			</p>
			<!-- BEGIN hljs-wrapper -->
			<div class="hljs-wrapper" slot="outsideBody" v-highlight><pre><code>&lt;div&gt;
  ...
  &lt;div id="footer" class="app-footer mx-0 px-0"&gt;
    &amp;copy; 2021 SeanTheme All Right Reserved
  &lt;/div&gt;
&lt;/div&gt;</code></pre>
			</div>
			<!-- END hljs-wrapper -->
		</panel>
		<!-- END panel -->
		
		<!-- BEGIN #footer -->
		<div id="footer" class="app-footer mx-0 px-0">
			&copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved
		</div>
		<!-- END #footer -->
	</div>
</template>