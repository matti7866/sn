<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">UI Elements</a></li>
			<li class="breadcrumb-item active">Tabs & Accordions</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Tabs & Accordions <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN nav-tabs -->
				<b-tabs nav-wrapper-class="nav nav-tabs" content-class="p-15px bg-white">
					<!-- BEGIN tab -->
					<b-tab active>
						<template slot="title">
							<span class="d-sm-none">Tab 1</span>
							<span class="d-sm-block d-none">Default Tab 1</span>
						</template>
						
						<h3 class="mt-10px"><i class="fa fa-cog"></i> Lorem ipsum dolor sit amet</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
							Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
							est diam sagittis orci, a ornare nisi quam elementum tortor. Proin interdum ante porta est convallis 
							dapibus dictum in nibh. Aenean quis massa congue metus mollis fermentum eget et tellus. 
							Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, nec eleifend orci eros id lectus.
						</p>
						<p class="text-end mb-0">
							<a href="javascript:;" class="btn btn-white me-5px">Default</a>
							<a href="javascript:;" class="btn btn-primary">Primary</a>
						</p>
					</b-tab>
					<!-- END tab -->
					<!-- BEGIN tab -->
					<b-tab>
						<template slot="title">
							<span class="d-sm-none">Tab 2</span>
							<span class="d-sm-block d-none">Default Tab 2</span>
						</template>
						
						<blockquote>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
							<small>Someone famous in <cite title="Source Title">Source Title</cite></small>
						</blockquote>
						<h4>Lorem ipsum dolor sit amet</h4>
						<p>
							Nullam ac sapien justo. Nam augue mauris, malesuada non magna sed, feugiat blandit ligula. 
							In tristique tincidunt purus id iaculis. Pellentesque volutpat tortor a mauris convallis, 
							sit amet scelerisque lectus adipiscing.
						</p>
					</b-tab>
					<!-- END tab -->
					<!-- BEGIN tab -->
					<b-tab>
						<template slot="title">
							<span class="d-sm-none">Tab 3</span>
							<span class="d-sm-block d-none">Default Tab 3</span>
						</template>
						
						<p>
							<span class="fa-stack fa-4x float-start me-10px">
								<i class="fa fa-square-o fa-stack-2x"></i>
								<i class="fab fa-twitter fa-stack-1x"></i>
							</span>
							Praesent tincidunt nulla ut elit vestibulum viverra. Sed placerat magna eget eros accumsan elementum. 
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam quis lobortis neque. 
							Maecenas justo odio, bibendum fringilla quam nec, commodo rutrum quam. 
							Donec cursus erat in lacus congue sodales. Nunc bibendum id augue sit amet placerat. 
							Quisque et quam id felis tempus volutpat at at diam. Vivamus ac diam turpis.Sed at lacinia augue. 
							Nulla facilisi. Fusce at erat suscipit, dapibus elit quis, luctus nulla. 
							Quisque adipiscing dui nec orci fermentum blandit.
							Sed at lacinia augue. Nulla facilisi. Fusce at erat suscipit, dapibus elit quis, luctus nulla. 
							Quisque adipiscing dui nec orci fermentum blandit.
						</p>
					</b-tab>
					<!-- END tab -->
				</b-tabs>
				<!-- BEGIN hljs-wrapper -->
				<div class="hljs-wrapper rounded-0 rounded-bottom mb-4" v-highlight>
					<pre><code class="html">&lt;b-tabs nav-wrapper-class="nav nav-tabs" content-class="p-15px bg-white mb-4"&gt;
  &lt;b-tab active&gt;
    ...
  &lt;/b-tab&gt;
  &lt;b-tab&gt;
    ...
  &lt;/b-tab&gt;
&lt;/div&gt;</code></pre>
				</div>
				<!-- END hljs-wrapper -->
				<!-- END nav-tabs -->
				
				<!-- BEGIN nav-tabs-pills -->
				<b-tabs pills nav-class="mb-3" content-class="p-15px rounded-top bg-white">
					<b-tab active>
						<template slot="title">
							<span class="d-sm-none">Pills 1</span>
							<span class="d-sm-block d-none">Pills Tab 1</span>
						</template>
						
						<h3 class="mt-10px">Nav Pills Tab 1</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
							Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
							est diam sagittis orci, a ornare nisi quam elementum tortor. 
							Proin interdum ante porta est convallis dapibus dictum in nibh. 
							Aenean quis massa congue metus mollis fermentum eget et tellus. 
							Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
							nec eleifend orci eros id lectus.
						</p>
					</b-tab>
					<b-tab>
						<template slot="title">
							<span class="d-sm-none">Pills 2</span>
							<span class="d-sm-block d-none">Pills Tab 2</span>
						</template>
						
						<h3 class="mt-10px">Nav Pills Tab 2</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
							Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
							est diam sagittis orci, a ornare nisi quam elementum tortor. 
							Proin interdum ante porta est convallis dapibus dictum in nibh. 
							Aenean quis massa congue metus mollis fermentum eget et tellus. 
							Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
							nec eleifend orci eros id lectus.
						</p>
					</b-tab>
					<b-tab>
						<template slot="title">
							<span class="d-sm-none">Pills 3</span>
							<span class="d-sm-block d-none">Pills Tab 3</span>
						</template>
						
						<h3 class="mt-10px">Nav Pills Tab 3</h3>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
							Integer ac dui eu felis hendrerit lobortis. Phasellus elementum, nibh eget adipiscing porttitor, 
							est diam sagittis orci, a ornare nisi quam elementum tortor. 
							Proin interdum ante porta est convallis dapibus dictum in nibh. 
							Aenean quis massa congue metus mollis fermentum eget et tellus. 
							Aenean tincidunt, mauris ut dignissim lacinia, nisi urna consectetur sapien, 
							nec eleifend orci eros id lectus.
						</p>
					</b-tab>
				</b-tabs>
				<!-- END nav-tabs-pills -->
				<!-- BEGIN hljs-wrapper -->
				<div class="hljs-wrapper rounded-0 rounded-bottom mb-4" v-highlight>
					<pre><code class="html">&lt;b-tabs pills nav-class="mb-3" content-class="p-15px rounded-top bg-white"&gt;
  &lt;b-tab active&gt;
    ...
  &lt;/b-tab&gt;
  &lt;b-tab&gt;
    ...
  &lt;/b-tab&gt;
&lt;/b-tabs&gt;</code></pre>
				</div>
				<!-- END hljs-wrapper -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN #accordion -->
				<div id="accordion" class="accordion rounded-top overflow-hidden">
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion1>
							<i class="fa fa-circle fa-fw text-blue fs-8px me-2"></i> Collapsible Group Item #1
						</b-card-header>
						<b-collapse id="accordion1" visible accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion2>
							<i class="fa fa-circle fa-fw text-indigo fs-8px me-2"></i> Collapsible Group Item #2
						</b-card-header>
						<b-collapse id="accordion2" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion3>
							<i class="fa fa-circle fa-fw text-teal fs-8px me-2"></i> Collapsible Group Item #3
						</b-card-header>
						<b-collapse id="accordion3" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion4>
							<i class="fa fa-circle fa-fw text-info fs-8px me-2"></i> Collapsible Group Item #4
						</b-card-header>
						<b-collapse id="accordion4" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion5>
							<i class="fa fa-circle fa-fw text-warning fs-8px me-2"></i> Collapsible Group Item #5
						</b-card-header>
						<b-collapse id="accordion5" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion6>
							<i class="fa fa-circle fa-fw text-danger fs-8px me-2"></i> Collapsible Group Item #6
						</b-card-header>
						<b-collapse id="accordion6" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
					<!-- BEGIN b-card -->
					<b-card class="bg-gray-800 text-white border-0 rounded-0" no-body>
						<b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion7>
							<i class="fa fa-circle fa-fw text-muted fs-8px me-2"></i> Collapsible Group Item #7
						</b-card-header>
						<b-collapse id="accordion7" accordion="my-accordion">
							<b-card-body>
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</b-card-body>
						</b-collapse>
					</b-card>
					<!-- END b-card -->
				</div>
				<!-- BEGIN hljs-wrapper -->
				<div class="hljs-wrapper rounded-0 rounded-bottom mb-4" v-highlight>
					<pre><code class="html">&lt;div id="accordion" class="accordion rounded overflow-hidden"&gt;
  &lt;b-card class="bg-gray-800 text-white border-0 rounded-0" no-body&gt;
    &lt;b-card-header header-tag="header" class="card-header bg-gray-900 text-white pointer-cursor px-3 py-10px d-flex align-items-center" v-b-toggle.accordion1&gt;
      &lt;i class="fa fa-circle fa-fw text-blue fs-8px me-2"&gt;&lt;/i&gt; Collapsible Group Item #1
    &lt;/b-card-header&gt;
    &lt;b-collapse id="accordion1" visible accordion="my-accordion"&gt;
      &lt;b-card-body&gt;
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      &lt;/b-card-body&gt;
    &lt;/b-collapse&gt;
  &lt;/b-card&gt;
  &lt;b-card class="bg-gray-800 text-white border-0 rounded-0" no-body&gt;
    ...
  &lt;/b-card&gt;
&lt;/div&gt;</code></pre>
				</div>
				<!-- END hljs-wrapper -->
				<!-- END #accordion -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
	</div>
</template>