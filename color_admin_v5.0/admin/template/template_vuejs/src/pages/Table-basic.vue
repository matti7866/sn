<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">Tables</a></li>
			<li class="breadcrumb-item active">Basic Tables</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Basic Tables <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Default Table">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Hover Table">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-hover mb-0 text-inverse">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table table-hover"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Table Small">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-sm mb-0 text-inverse">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table table-sm"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Responsive Table">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th nowrap>Table heading</th>
										<th nowrap>Table heading</th>
										<th nowrap>Table heading</th>
										<th nowrap>Table heading</th>
										<th nowrap>Table heading</th>
										<th nowrap>Table heading</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
										<td>Table cell</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;div class="table-responsive"&gt;
  &lt;table class="table"&gt;
    ...
  &lt;/table&gt;
&lt;/div&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Table Striped">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table table-striped"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Bordered Table">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-bordered mb-0">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table table-bordered"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel>
					<template slot="header">
						<h4 class="panel-title">UI Elements in Table <span class="badge bg-success ms-5px">NEW</span></h4>
					</template>
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-striped mb-0 align-middle">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
										<th width="1%"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<img src="/assets/img/user/user-1.jpg" class="rounded h-30px" />
										</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
										<td nowrap>
											<a href="javascript:;" class="btn btn-sm btn-primary w-60px me-1">Edit</a>
											<a href="javascript:;" class="btn btn-sm btn-white w-60px">Delete</a>
										</td>
									</tr>
									<tr>
										<td>
											<img src="/assets/img/user/user-2.jpg" class="rounded h-30px" />
										</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
										<td nowrap>
											<div class="btn-group">
												<a href="javascript:;" class="btn btn-white btn-sm w-90px">Settings</a>
												<a href="javascript:;" class="btn btn-white btn-sm dropdown-toggle w-30px no-caret" data-bs-toggle="dropdown">
												<span class="caret"></span>
												</a>
												<div class="dropdown-menu dropdown-menu-end">
													<a href="javascript:;" class="dropdown-item">Action 1</a>
													<a href="javascript:;" class="dropdown-item">Action 2</a>
													<a href="javascript:;" class="dropdown-item">Action 3</a>
													<div class="dropdown-divider"></div>
													<a href="javascript:;" class="dropdown-item">Action 4</a>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<img src="/assets/img/user/user-3.jpg" class="rounded h-30px" />
										</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
										<td class="with-btn" nowrap>
											<a href="javascript:;" class="btn btn-sm btn-primary w-60px me-1">Edit</a>
											<a href="javascript:;" class="btn btn-sm btn-white w-60px">Delete</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table align-middle"&gt;
  ...
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel>
					<template slot="header">
						<h4 class="panel-title">Form Elements in Table <span class="badge bg-success ms-1">NEW</span></h4>
					</template>
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table table-striped align-middle mb-0">
								<thead>
									<tr>
										<th>
											<div class="form-check">
												<input type="checkbox" value="" id="table_checkbox_1" class="form-check-input" />
												<label for="table_checkbox_1" class="form-check-label">&nbsp;</label>
											</div>
										</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="form-check">
												<input type="checkbox" value="" id="table_checkbox_2" class="form-check-input" />
												<label for="table_checkbox_2" class="form-check-label">&nbsp;</label>
											</div>
										</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr>
										<td>
											<div class="form-check">
												<input type="radio" value="" id="table_radio_1" class="form-check-input" />
												<label for="table_radio_1" class="form-check-label">&nbsp;</label>
											</div>
										</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr>
										<td>
											<div class="form-check">
												<input type="radio" value="" id="table_radio_2" class="form-check-input" />
												<label for="table_radio_2" class="form-check-label">&nbsp;</label>
											</div>
										</td>
										<td><input type="text" class="form-control my-n1" value="Harvinder Singh" /></td>
										<td>harvinder@gmail.com</td>
									</tr>
									<tr>
										<td>
											<div class="form-check">
												<input type="radio" value="" id="table_radio_3" class="form-check-input" />
												<label for="table_radio_3" class="form-check-label">&nbsp;</label>
											</div>
										</td>
										<td>
											<div class="input-group my-n1">
												<div class="input-group-text">@</div>
												<input type="text" class="form-control" placeholder="Terry" />
											</div>
										</td>
										<td>terry@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table align-middle"&gt;
  &lt;tbody&gt;
    &lt;tr&gt;
      &lt;!-- with input --&gt;
      &lt;td&gt;
        &lt;input type="text" class="form-control my-n1" /&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
    
    &lt;tr&gt;
      &lt;!-- with input-group --&gt;
      &lt;td&gt;
        &lt;div class="input-group my-n1"&gt;&lt;/div&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
    
    &lt;tr&gt;
      &lt;!-- with btn-group --&gt;
      &lt;td&gt;
        &lt;div class="btn-group my-n1"&gt;&lt;/div&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
  &lt;/tbody&gt;
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Table Row Classes">
					<div>
						<!-- BEGIN table-responsive -->
						<div class="table-responsive">
							<table class="table mb-0">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Email Address</th>
									</tr>
								</thead>
								<tbody>
									<tr class="table-active">
										<td>1</td>
										<td>Nicky Almera</td>
										<td>nicky@hotmail.com</td>
									</tr>
									<tr class="table-info">
										<td>2</td>
										<td>Terry Khoo</td>
										<td>terry@gmail.com</td>
									</tr>
									<tr class="table-success">
										<td>3</td>
										<td>Edmund Wong</td>
										<td>edmund@yahoo.com</td>
									</tr>
									<tr class="table-warning">
										<td>4</td>
										<td>Harvinder Singh</td>
										<td>harvinder@gmail.com</td>
									</tr>
									<tr class="table-danger">
										<td>5</td>
										<td>Terry Khoo</td>
										<td>terry@gmail.com</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- END table-responsive -->
					</div>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;table class="table"&gt;
  &lt;tbody&gt;
    &lt;tr class="table-active"&gt;...&lt;/tr&gt;
    &lt;tr class="table-info"&gt;...&lt;/tr&gt;
    &lt;tr class="table-success"&gt;...&lt;/tr&gt;
    &lt;tr class="table-warning"&gt;...&lt;/tr&gt;
    &lt;tr class="table-danger"&gt;...&lt;/tr&gt;
  &lt;/tbody&gt;
&lt;/table&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
	</div>
</template>