<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-right">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">UI Elements</a></li>
			<li class="breadcrumb-item active">Widget Boxes</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Widget Boxes <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN row -->
		<div class="row mb-3">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Panel (Default)">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel (Default)"&gt;
  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel noButton="true">
					<template slot="header">
						<h4 class="panel-title">Panel Header with Dropdown</h4>
						<b-dropdown text="Action" variant="success" toggle-class="btn-xs my-n1">
							<b-dropdown-item href="#">Action</b-dropdown-item>
							<b-dropdown-item href="#">Another action</b-dropdown-item>
							<b-dropdown-item href="#">Something else here</b-dropdown-item>
							<b-dropdown-divider></b-dropdown-divider>
							<b-dropdown-item href="#">Separated link</b-dropdown-item>
						</b-dropdown>
					</template>
					
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel noButton="true"&gt;
  &lt;template slot="header"&gt;
    &lt;h4 class="panel-title"&gt;Panel Header with Dropdown&lt;/h4&gt;
    &lt;b-dropdown text="Action" variant="success" toggle-class="btn-xs my-n1"&gt;
      &lt;b-dropdown-item href="#"&gt;Action&lt;/b-dropdown-item&gt;
      &lt;b-dropdown-item href="#"&gt;Another action&lt;/b-dropdown-item&gt;
      &lt;b-dropdown-item href="#"&gt;Something else here&lt;/b-dropdown-item&gt;
      &lt;b-dropdown-divider&gt;&lt;/b-dropdown-divider&gt;
      &lt;b-dropdown-item href="#"&gt;Separated link&lt;/b-dropdown-item&gt;
    &lt;/b-dropdown&gt;
  &lt;/template&gt;
  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel noButton="true">
					<template slot="header">
						<h4 class="panel-title">Panel Header with Radio Button</h4>
						<div class="btn-group btn-group-toggle my-n1" data-toggle="buttons">
							<input type="radio" name="options" class="btn-check" id="option1" checked />
							<label class="btn btn-success btn-xs" for="option1">Option 1</label>
		
							<input type="radio" name="options" class="btn-check" id="option2" />
							<label class="btn btn-success btn-xs" for="option2">Option 2</label>
						</div>
					</template>
					
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel noButton="true"&gt;
  &lt;template slot="header"&gt;
    &lt;h4 class="panel-title"&gt;Panel Header with Radio Button&lt;/h4&gt;
    &lt;div class="btn-group btn-group-toggle my-n1" data-toggle="buttons"&gt;
      &lt;input type="radio" name="options" class="btn-check" id="option1" checked /&gt;
      &lt;label class="btn btn-success btn-xs" for="option1"&gt;Option 1&lt;/label&gt;
    
      &lt;input type="radio" name="options" class="btn-check" id="option2" /&gt;
      &lt;label class="btn btn-success btn-xs" for="option2"&gt;Option 2&lt;/label&gt;
    &lt;/div&gt;
  &lt;/template&gt;

  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel noButton="true">
					<template slot="header">
						<h4 class="panel-title">Panel Header with Progress Bar</h4>
						<div class="progress h-10px bg-gray-700 w-150px">
							<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" style="width: 40%">40%</div>
						</div>
					</template>
					
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" noButton="true" v-highlight>
						<pre><code class="html">&lt;panel noButton="true"&gt;
  &lt;template slot="header"&gt;
    &lt;h4 class="panel-title"&gt;Panel Header with Progress Bar&lt;/h4&gt;
    &lt;div class="progress h-10px bg-gray-700 w-150px"&gt;
      &lt;div class="progress-bar progress-bar-striped bg-success progress-bar-animated" style="width: 40%"&gt;40%&lt;/div&gt;
    &lt;/div&gt;
  &lt;/template&gt;

  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel noButton="true">
					<template slot="header">
						<h4 class="panel-title">Panel Header with Badge <span class="badge bg-success">NEW</span></h4>
					</template>
					
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel noButton="true"&gt;
  &lt;template slot="header"&gt;
    &lt;h4 class="panel-title"&gt;Panel Header with Badge &lt;span class="badge bg-success"&gt;NEW&lt;/span&gt;&lt;/h4&gt;
  &lt;/template&gt;

  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel with Alert Box" noButton="true">
					<template slot="beforeBody">
						<b-alert variant="success mb-0" class="rounded-0" show>
							<div class="d-flex">
								<i class="fa fa-check fa-2x me-1"></i>
								<div class="mb-0 ps-2">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ac posuere lacus, quis suscipit sem. Nulla sagittis aliquam erat non convallis.
								</div>
							</div>
						</b-alert>
					</template>
					<p>Panel Content Here</p>
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel with Alert Box" noButton="true"&gt;
  &lt;template slot="beforeBody"&gt;
    &lt;b-alert variant="success" show&gt;
      &lt;div class="d-flex"&gt;
        &lt;i class="fa fa-check fa-2x me-1"&gt;&lt;/i&gt;
        &lt;div class="mb-0 ps-2"&gt;
          ...
        &lt;/div&gt;
      &lt;/div&gt;
    &lt;/b-alert&gt;
  &lt;/template&gt;
  &lt;p&gt;Panel Content Here&lt;/p&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Hover View Icon" panelClass="panel-hover-icon">
					<p>Panel Content Here</p>
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Hover View Icon" panelClass="panel-hover-icon"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel with Scrollbar">
					<vue-custom-scrollbar style="height: 280px">
						<p>
							<span class="fa-stack fa-4x pull-left m-r-10 text-inverse">
								<i class="fab fa-twitter fa-stack-1x"></i>
							</span>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque sed enim arcu. 
							Ut posuere in ligula quis ultricies. In in justo turpis. Donec ut dui at massa gravida 
							interdum nec vitae justo. Quisque ullamcorper vehicula dictum. Nullam hendrerit interdum eleifend. 
							Aenean luctus sed arcu laoreet scelerisque. Vivamus non ullamcorper mauris, id sagittis lorem. 
							Proin tincidunt mauris et dolor mattis imperdiet. Sed facilisis mattis diam elementum adipiscing. 
						</p>
						<p>
							<span class="fa-stack fa-4x pull-right m-l-10 text-inverse">
								<i class="fab fa-google-plus fa-stack-1x"></i>
							</span>
							Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
							Ut ante velit, pretium non nisi a, egestas placerat diam. Nullam aliquet iaculis ultricies. 
							Aliquam volutpat, sapien quis volutpat elementum, ligula purus auctor diam, at vestibulum nulla augue 
							vel purus. Praesent ac nisl a magna tincidunt interdum sed in turpis. Maecenas nec condimentum risus. 
							In congue pretium est, eget euismod tortor ornare quis.
						</p>
						<p>
							<span class="fa-stack fa-4x pull-left m-r-10 text-inverse">
								<i class="fab fa-facebook fa-stack-1x"></i>
							</span>
							Praesent eu ultrices justo. Vestibulum ante 
							ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; 
							Cras mattis ipsum quis sapien consectetur fringilla. 
							Etiam sagittis sem tempus purus elementum, vitae pretium sapien porta. Curabitur iaculis ante ut aliquam luctus. 
							Vivamus ullamcorper blandit imperdiet. Ut egestas, orci id rhoncus cursus, orci risus scelerisque enim, eget mattis eros lacus quis ligula. 
							Vivamus ullamcorper urna eget hendrerit laoreet.
						</p>
						<p>
							<span class="fa-stack fa-4x pull-right m-l-10 text-inverse">
								<i class="fab fa-apple fa-stack-1x"></i>
							</span>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
							Morbi accumsan velit dolor. Donec convallis eleifend magna, at euismod tellus convallis a. 
							Curabitur in nisi dolor. Cras viverra scelerisque orci, sed interdum ligula volutpat non. 
							Nunc eu enim ac neque tempor feugiat. Duis posuere lacus non magna eleifend, 
							non dictum sem feugiat. Duis eleifend condimentum pulvinar.
						</p>
					</vue-custom-scrollbar>
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel with Scrollbar"&gt;
  &lt;vue-custom-scrollbar style="height: 280px"&gt;
    ...
  &lt;/vue-custom-scrollbar&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel with Toolbar & Footer" footerClass="text-right" noBody="true">
					<template slot="beforeBody">
						<div class="panel-toolbar">
							<div class="btn-group m-r-5">
								<a class="btn btn-white" href="javascript:;"><i class="fa fa-bold"></i></a>
								<a class="btn btn-white active" href="javascript:;"><i class="fa fa-italic"></i></a>
								<a class="btn btn-white" href="javascript:;"><i class="fa fa-underline"></i></a>
							</div>
							<div class="btn-group">
								<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-left"></i></a>
								<a class="btn btn-white active" href="javascript:;"><i class="fa fa-align-center"></i></a>
								<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-right"></i></a>
								<a class="btn btn-white" href="javascript:;"><i class="fa fa-align-justify"></i></a>
							</div>
						</div>
						<textarea class="form-control no-rounded-pill border-1 border-start-0 border-end-0 border-bottom-0 rounded-0 bg-gray-100" rows="5">This is a form textarea.</textarea>
					</template>
					<div slot="footer" class="text-end">
						<a href="javascript:;" class="btn btn-white btn-sm">Cancel</a>
						<a href="javascript:;" class="btn btn-primary btn-sm ms-5px">Action</a>
					</div>
					<div class="hljs-wrapper" slot="afterFooter" v-highlight>
						<pre><code class="html">&lt;panel title="Panel with Toolbar & Footer" footerClass="text-right" noBody="true"&gt;
  &lt;template slot="beforeBody"&gt;
    &lt;div class="panel-toolbar"&gt;
      &lt;div class="btn-group m-r-5"&gt;
        ...
      &lt;/div&gt;
      &lt;div class="btn-group"&gt;
        ...
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;textarea class="form-control no-rounded-pill border-1 border-start-0 border-end-0 border-bottom-0 rounded-0 bg-gray-100" rows="5"&gt;This is a form textarea.&lt;/textarea&gt;
  &lt;/template&gt;
  &lt;div slot="footer" class="text-end"&gt;
    ...
  &lt;/div&gt;
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel panelClass="panel-with-tabs" variant="default" noButton="true" noBody="true">
					<template slot="header">
						<h4 class="panel-title">Panel with Tabs</h4>
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="javascript:;" v-on:click.prevent="toggleTabs('home')" class="nav-link" v-bind:class="{ 'active': tabs.home }">
									<i class="fa fa-home mr-1"></i> 
									<span class="hidden-xs">Home</span>
								</a>
							</li>
							<li class="nav-item">
								<a href="javascript:;" v-on:click.prevent="toggleTabs('profile')" class="nav-link" v-bind:class="{ 'active': tabs.profile }">
									<i class="fa fa-user mr-1"></i> 
									<span class="hidden-xs">Profile</span>
								</a>
							</li>
						</ul>
					</template>
					<template slot="outsideBody">
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane fade" v-bind:class="{ 'active show': tabs.home }" id="home">
									<p class="m-b-0">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
								</div>
								<div class="tab-pane fade" v-bind:class="{ 'active show': tabs.profile }" id="profile">
									<p class="m-b-0">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
								</div>
							</div>
						</div>
						<div class="hljs-wrapper" v-highlight>
							<pre><code class="html">&lt;panel panelClass="panel-with-tabs" variant="default" noButton="true" noBody="true"&gt;
  &lt;template slot="header"&gt;
    &lt;ul class="nav nav-tabs"&gt;
      &lt;li class="nav-item"&gt;&lt;a href="#" v-on:click.prevent="toggleTabs('home')" class="nav-link" v-bind:class="{ 'active': tabs.home }"&gt;&lt;i class="fa fa-home"&gt;&lt;/i&gt; &lt;span class="d-none d-md-inline"&gt;Home&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;
      &lt;li class="nav-item"&gt;&lt;a href="#" v-on:click.prevent="toggleTabs('profile')" class="nav-link" v-bind:class="{ 'active': tabs.profile }"&gt;&lt;i class="fa fa-user"&gt;&lt;/i&gt; &lt;span class="d-none d-md-inline"&gt;Profile&lt;/span&gt;&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
    &lt;h4 class="panel-title"&gt;Panel with Tabs&lt;/h4&gt;
  &lt;/template&gt;
  &lt;template slot="outsideBody"&gt;
    &lt;div class="tab-content"&gt;
      &lt;div class="tab-pane fade" v-bind:class="{ 'active show': tabs.home }" id="home"&gt;
      &lt;p class="m-b-0"&gt;Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="tab-pane fade" v-bind:class="{ 'active show': tabs.profile }" id="profile"&gt;
      &lt;p class="m-b-0"&gt;Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.&lt;/p&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/panel&gt;</code></pre>
						</div>
					</template>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
	
		<h3 class="mb-20px">Panel Theme</h3>
		
		<!-- BEGIN row -->
		<div class="row mb-3">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Panel (Default)" variant="default">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel (Default)" variant="default"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel Success" headerClass="bg-success-700 text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Success"  headerClass="bg-success-700 text-white"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel Warning" headerClass="bg-warning-700 text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Warning" headerClass="bg-warning-700 text-white"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel Danger" headerClass="bg-danger-700 text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Danger" headerClass="bg-danger-700 text-white"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Panel Inverse">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Inverse"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel Primary" headerClass="bg-blue-700 text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Primary" headerClass="bg-blue-700 text-white"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Panel Info" headerClass="bg-info-700 text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Panel Info" headerClass="bg-info-700 text-white"&gt;...&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
		
		<h3>Full Color Panel Theme</h3>
		<p class="mb-20px">
			You can customize the panel body / header <code>background</code> & <code>text color</code> by adding predefined CSS class. E.g <code>.bg-gray-900</code>. Full list of available predefined background class can be found <a href="helper_css.html">here</a>.
		</p>
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" bodyClass="bg-black text-white">
					<p>Panel Content Here</p>
						
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" bodyClass="bg-black text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" headerClass="bg-blue-700 text-white" bodyClass="bg-blue text-white">
					<p>Panel Content Here</p>
						
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" headerClass="bg-blue-700 text-white" bodyClass="bg-blue text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" headerClass="bg-success-700 text-white" bodyClass="bg-teal text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" headerClass="bg-success-700 text-white" bodyClass="bg-teal text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" headerClass="bg-warning-700 text-white" bodyClass="bg-orange text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" headerClass="bg-warning-700 text-white" bodyClass="bg-orange text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" headerClass="bg-red-700 text-white" bodyClass="bg-red text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" headerClass="bg-red-700 text-white" bodyClass="bg-red text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Full Color Panel" headerClass="bg-info-700 text-white" bodyClass="bg-aqua text-white">
					<p>Panel Content Here</p>
					
					<div class="hljs-wrapper" slot="outsideBody" v-highlight>
						<pre><code class="html">&lt;panel title="Full Color Panel" headerClass="bg-info-700 text-white" variant="info" bodyClass="bg-aqua text-white"&gt;
  ...
&lt;/panel&gt;</code></pre>
					</div>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
	</div>
</template>

<script>
export default {
	data() {
		return {
			tabs: {
				home: true,
				profile: false
			}
		}
	},
	methods: {
		toggleTabs(tab) {
			if (tab == 'home') {
				this.tabs.home = true;
				this.tabs.profile = false;
			} else if (tab == 'profile') {
				this.tabs.home = false;
				this.tabs.profile = true;
			}
		}
	}
}
</script>