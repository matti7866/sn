<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Dashboard <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-3 -->
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-blue">
					<div class="stats-icon"><i class="fa fa-desktop"></i></div>
					<div class="stats-info">
						<h4>TOTAL VISITORS</h4>
						<p>3,291,922</p>	
					</div>
					<div class="stats-link">
						<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<!-- END col-3 -->
			<!-- BEGIN col-3 -->
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-info">
					<div class="stats-icon"><i class="fa fa-link"></i></div>
					<div class="stats-info">
						<h4>BOUNCE RATE</h4>
						<p>20.44%</p>	
					</div>
					<div class="stats-link">
						<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<!-- END col-3 -->
			<!-- BEGIN col-3 -->
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-orange">
					<div class="stats-icon"><i class="fa fa-users"></i></div>
					<div class="stats-info">
						<h4>UNIQUE VISITORS</h4>
						<p>1,291,922</p>	
					</div>
					<div class="stats-link">
						<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<!-- END col-3 -->
			<!-- BEGIN col-3 -->
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-red">
					<div class="stats-icon"><i class="fa fa-clock"></i></div>
					<div class="stats-info">
						<h4>AVG TIME ON SITE</h4>
						<p>00:12:23</p>	
					</div>
					<div class="stats-link">
						<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<!-- END col-3 -->
		</div>
		<!-- END row -->
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-8 -->
			<div class="col-xl-8">
				<!-- BEGIN panel -->
				<panel title="Website Analytics (Last 7 Days)">
					<line-chart :data="lineChart.data" :options="lineChart.options" class="h-250px"></line-chart>
				</panel>
				<!-- END panel -->
				
				<!-- BEGIN tabs -->
				<b-tabs nav-wrapper-class="nav nav-tabs nav-tabs-inverse nav-justified" nav-class="nav-item">
					<div class="tab-content bg-white rounded-bottom mb-20px">
					<b-tab active>
						<template slot="title">
							<i class="fa fa-camera fa-lg me-5px"></i> <span class="d-none d-md-inline">Latest Post</span>
						</template>
						<vue-custom-scrollbar class="h-300px p-3">
							<div class="d-sm-flex">
								<a href="javascript:;" class="w-sm-200px">
									<img class="mw-100 rounded" src="/assets/img/gallery/gallery-1.jpg" alt="" />
								</a>
								<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
									<h5>Aenean viverra arcu nec pellentesque ultrices. In erat purus, adipiscing nec lacinia at, ornare ac eros.</h5>
									Nullam at risus metus. Quisque nisl purus, pulvinar ut mauris vel, elementum suscipit eros. Praesent ornare ante massa, egestas pellentesque orci convallis ut. Curabitur consequat convallis est, id luctus mauris lacinia vel. Nullam tristique lobortis mauris, ultricies fermentum lacus bibendum id. Proin non ante tortor. Suspendisse pulvinar ornare tellus nec pulvinar. Nam pellentesque accumsan mi, non pellentesque sem convallis sed. Quisque rutrum erat id auctor gravida.
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-sm-flex">
								<a href="javascript:;" class="w-sm-200px">
									<img class="mw-100 rounded" src="/assets/img/gallery/gallery-10.jpg" alt="" />
								</a>
								<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
									<h5>Vestibulum vitae diam nec odio dapibus placerat. Ut ut lorem justo.</h5>
									Fusce bibendum augue nec fermentum tempus. Sed laoreet dictum tempus. Aenean ac sem quis nulla malesuada volutpat. Nunc vitae urna pulvinar velit commodo cursus. Nullam eu felis quis diam adipiscing hendrerit vel ac turpis. Nam mattis fringilla euismod. Donec eu ipsum sit amet mauris iaculis aliquet. Quisque sit amet feugiat odio. Cras convallis lorem at libero lobortis, placerat lobortis sapien lacinia. Duis sit amet elit bibendum sapien dignissim bibendum.
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-sm-flex">
								<a href="javascript:;" class="w-sm-200px">
									<img class="mw-100 rounded" src="/assets/img/gallery/gallery-7.jpg" alt="" />
								</a>
								<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
									<h5>Maecenas eget turpis luctus, scelerisque arcu id, iaculis urna. Interdum et malesuada fames ac ante ipsum primis in faucibus.</h5>
									Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque. Nam augue nulla, accumsan quis nisi a, facilisis eleifend nulla. Praesent aliquet odio non imperdiet fringilla. Morbi a porta nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-sm-flex">
								<a href="javascript:;" class="w-sm-200px">
									<img class="mw-100 rounded" src="/assets/img/gallery/gallery-8.jpg" alt="" />
								</a>
								<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
									<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor accumsan rutrum.</h5>
									Fusce augue diam, vestibulum a mattis sit amet, vehicula eu ipsum. Vestibulum eu mi nec purus tempor consequat. Vestibulum porta non mi quis cursus. Fusce vulputate cursus magna, tincidunt sodales ipsum lobortis tincidunt. Mauris quis lorem ligula. Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque.
								</div>
							</div>
						</vue-custom-scrollbar>
					</b-tab>
					<b-tab>
						<template slot="title">
							<i class="fa fa-archive fa-lg me-5px"></i> <span class="d-none d-md-inline">Purchase</span>
						</template>
						<vue-custom-scrollbar class="h-300px">
							<table class="table table-panel mb-0">
								<thead>
									<tr>
										<th>Date</th>
										<th class="hidden-sm text-center">Product</th>
										<th></th>
										<th>Amount</th>
										<th>User</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="fw-bold text-muted">13/02/2021</td>
										<td class="hidden-sm text-center">
											<a href="javascript:;">
												<img src="/assets/img/product/product-1.png" alt="" width="32px"  />
											</a>
										</td>
										<td class="text-nowrap">
											<h6><a href="javascript:;" class="text-inverse text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
										</td>
										<td class="text-blue fw-bold">$349.00</td>
										<td class="text-nowrap"><a href="javascript:;" class="text-inverse text-decoration-none">Derick Wong</a></td>
									</tr>
									<tr>
										<td class="fw-bold text-muted">13/02/2021</td>
										<td class="hidden-sm text-center">
											<a href="javascript:;">
												<img src="/assets/img/product/product-2.png" alt="" width="32px" />
											</a>
										</td>
										<td class="text-nowrap">
											<h6><a href="javascript:;" class="text-inverse text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
										</td>
										<td class="text-blue fw-bold">$399.00</td>
										<td class="text-nowrap"><a href="javascript:;" class="text-inverse text-decoration-none">Derick Wong</a></td>
									</tr>
									<tr>
										<td class="fw-bold text-muted">13/02/2021</td>
										<td class="hidden-sm text-center">
											<a href="javascript:;">
												<img src="/assets/img/product/product-3.png" alt="" width="32px" />
											</a>
										</td>
										<td class="text-nowrap">
											<h6><a href="javascript:;" class="text-inverse text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
										</td>
										<td class="text-blue fw-bold">$499.00</td>
										<td class="text-nowrap"><a href="javascript:;" class="text-inverse text-decoration-none">Derick Wong</a></td>
									</tr>
									<tr>
										<td class="fw-bold text-muted">13/02/2021</td>
										<td class="hidden-sm text-center">
											<a href="javascript:;">
												<img src="/assets/img/product/product-4.png" alt="" width="32px" />
											</a>
										</td>
										<td class="text-nowrap">
											<h6><a href="javascript:;" class="text-inverse text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
										</td>
										<td class="text-blue fw-bold">$230.00</td>
										<td class="text-nowrap"><a href="javascript:;" class="text-inverse text-decoration-none">Derick Wong</a></td>
									</tr>
									<tr>
										<td class="fw-bold text-muted">13/02/2021</td>
										<td class="hidden-sm text-center">
											<a href="javascript:;">
												<img src="/assets/img/product/product-5.png" alt="" width="32px" />
											</a>
										</td>
										<td class="text-nowrap">
											<h6><a href="javascript:;" class="text-inverse text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
										</td>
										<td class="text-blue fw-bold">$500.00</td>
										<td class="text-nowrap"><a href="javascript:;" class="text-inverse text-decoration-none">Derick Wong</a></td>
									</tr>
								</tbody>
							</table>
						</vue-custom-scrollbar>
					</b-tab>
					<b-tab>
						<template slot="title">
							<i class="fa fa-envelope fa-lg me-5px"></i> <span class="d-none d-md-inline">Email</span>
						</template>
						<vue-custom-scrollbar class="h-300px p-3">
							<div class="d-flex">
								<a class="w-60px" href="javascript:;">
									<img src="/assets/img/user/user-1.jpg" alt="" class="mw-100 rounded-pill" />
								</a>
								<div class="flex-1 ps-3">
									<a href="javascript:;" class="text-inverse text-decoration-none"><h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h5></a>
									<p class="mb-5px">
										Aenean mollis arcu sed turpis accumsan dignissim. Etiam vel tortor at risus tristique convallis. Donec adipiscing euismod arcu id euismod. Suspendisse potenti. Aliquam lacinia sapien ac urna placerat, eu interdum mauris viverra.
									</p>
									<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-flex">
								<a class="w-60px" href="javascript:;">
									<img src="/assets/img/user/user-2.jpg" alt="" class="mw-100 rounded-pill" />
								</a>
								<div class="flex-1 ps-3">
									<a href="javascript:;" class="text-inverse text-decoration-none"><h5>Praesent et sem porta leo tempus tincidunt eleifend et arcu.</h5></a>
									<p class="mb-5px">
										Proin adipiscing dui nulla. Duis pharetra vel sem ac adipiscing. Vestibulum ut porta leo. Pellentesque orci neque, tempor ornare purus nec, fringilla venenatis elit. Duis at est non nisl dapibus lacinia.
									</p>
									<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-flex">
								<a class="w-60px" href="javascript:;">
									<img src="/assets/img/user/user-3.jpg" alt="" class="mw-100 rounded-pill" />
								</a>
								<div class="flex-1 ps-3">
									<a href="javascript:;" class="text-inverse text-decoration-none"><h5>Ut mi eros, varius nec mi vel, consectetur convallis diam.</h5></a>
									<p class="mb-5px">
										Ut mi eros, varius nec mi vel, consectetur convallis diam. Nullam eget hendrerit eros. Duis lacinia condimentum justo at ultrices. Phasellus sapien arcu, fringilla eu pulvinar id, mattis quis mauris.
									</p>
									<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
								</div>
							</div>
							<hr class="bg-gray-500" />
							<div class="d-flex">
								<a class="w-60px" href="javascript:;">
									<img src="/assets/img/user/user-4.jpg" alt="" class="mw-100 rounded-pill" />
								</a>
								<div class="flex-1 ps-3">
									<a href="javascript:;" class="text-inverse text-decoration-none"><h5>Aliquam nec dolor vel nisl dictum ullamcorper.</h5></a>
									<p class="mb-5px">
										Aliquam nec dolor vel nisl dictum ullamcorper. Duis vel magna enim. Aenean volutpat a dui vitae pulvinar. Nullam ligula mauris, dictum eu ullamcorper quis, lacinia nec mauris.
									</p>
									<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
								</div>
							</div>
						</vue-custom-scrollbar>
					</b-tab>
					</div>
				</b-tabs>
				<!-- END tabs -->
				
				<!-- BEGIN panel -->
				<panel title="Quick Post" footerClass="text-end" bodyClass="p-0">
					<div class="panel-toolbar">
						<div class="btn-group me-5px">
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
					<hr class="m-0" />
					<textarea class="form-control rounded-0 border-0 shadow-none bg-gray-200 p-3" rows="14">Enter some comment.</textarea>
					<template slot="footer">
						<a href="javascript:;" class="btn btn-white btn-sm">Cancel</a>
						<a href="javascript:;" class="btn btn-primary btn-sm ms-5px">Action</a>
					</template>
				</panel>
				<!-- END panel -->
									
				<!-- BEGIN panel -->
				<panel title="Message" bodyClass="p-0">
					<vue-custom-scrollbar class="h-300px p-3">
						<div class="d-flex">
							<a href="javascript:;" class="w-60px">
								<img src="/assets/img/user/user-5.jpg" alt="" class="mw-100 rounded-pill" />
							</a>
							<div class="flex-1 ps-3">
								<h5>John Doe</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id nunc non eros fermentum vestibulum ut id felis. Nunc molestie libero eget urna aliquet, vitae laoreet felis ultricies. Fusce sit amet massa malesuada, tincidunt augue vitae, gravida felis.</p>
							</div>
						</div>
						<hr class="bg-gray-500" />
						<div class="d-flex">
							<a href="javascript:;" class="w-60px">
								<img src="/assets/img/user/user-6.jpg" alt="" class="mw-100 rounded-pill" />
							</a>
							<div class="flex-1 ps-3">
								<h5>Terry Ng</h5>
								<p>Sed in ante vel ipsum tristique euismod posuere eget nulla. Quisque ante sem, scelerisque iaculis interdum quis, eleifend id mi. Fusce congue leo nec mauris malesuada, id scelerisque sapien ultricies.</p>
							</div>
						</div>
						<hr class="bg-gray-500" />
						<div class="d-flex">
							<a href="javascript:;" class="w-60px">
								<img src="/assets/img/user/user-8.jpg" alt="" class="mw-100 rounded-pill" />
							</a>
							<div class="flex-1 ps-3">
								<h5>Fiona Log</h5>
								<p>Pellentesque dictum in tortor ac blandit. Nulla rutrum eu leo vulputate ornare. Nulla a semper mi, ac lacinia sapien. Sed volutpat ornare eros, vel semper sem sagittis in. Quisque risus ipsum, iaculis quis cursus eu, tristique sed nulla.</p>
							</div>
						</div>
						<hr class="bg-gray-500" />
						<div class="d-flex">
							<a href="javascript:;" class="w-60px">
								<img src="/assets/img/user/user-7.jpg" alt="" class="mw-100 rounded-pill" />
							</a>
							<div class="flex-1 ps-3">
								<h5>John Doe</h5>
								<p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>
							</div>
						</div>
					</vue-custom-scrollbar>
					<template slot="footer">
						<form>
							<div class="input-group">
								<input type="text" class="form-control bg-light" placeholder="Enter message" />
								<button class="btn btn-primary" type="button"><i class="fa fa-pencil-alt"></i></button>
							</div>
						</form>
					</template>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-8 -->
			<!-- BEGIN col-4 -->
			<div class="col-xl-4">
				<!-- BEGIN panel -->
				<panel title="Analytics Details" bodyClass="p-0">
					<div class="table-responsive mb-0">
						<table class="table table-panel align-middle mb-0">
							<thead>
								<tr>	
									<th>Source</th>
									<th>Total</th>
									<th>Trend</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td nowrap><label class="badge bg-danger">Unique Visitor</label></td>
									<td>13,203 <span class="text-success"><i class="fa fa-arrow-up"></i></span></td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline1.data" :limit="sparkline1.length" :styles="sparkline1.lineStyle" />
										</sparkline>
									</td>
								</tr>
								<tr>
									<td nowrap><label class="badge bg-warning">Bounce Rate</label></td>
									<td>28.2%</td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline2.data" :limit="sparkline1.length" :styles="sparkline2.lineStyle" />
										</sparkline>
									</td>
								</tr>
								<tr>
									<td nowrap><label class="badge bg-success">Total Page Views</label></td>
									<td>1,230,030</td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline3.data" :limit="sparkline1.length" :styles="sparkline3.lineStyle" />
										</sparkline>
									</td>
								</tr>
								<tr>
									<td nowrap><label class="badge bg-blue">Avg Time On Site</label></td>
									<td>00:03:45</td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline4.data" :limit="sparkline4.length" :styles="sparkline4.lineStyle" />
										</sparkline>
									</td>
								</tr>
								<tr>
									<td nowrap><label class="badge bg-default text-gray-900">% New Visits</label></td>
									<td>40.5%</td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline5.data" :limit="sparkline5.length" :styles="sparkline5.lineStyle" />
										</sparkline>
									</td>
								</tr>
								<tr>
									<td nowrap><label class="badge bg-inverse">Return Visitors</label></td>
									<td>73.4%</td>
									<td>
										<sparkline height="23">
											<sparklineLine :data="sparkline6.data" :limit="sparkline6.length" :styles="sparkline6.lineStyle" />
										</sparkline>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</panel>
				<!-- END panel -->
				
				<!-- BEGIN panel -->
				<panel title="Visitors User Agent">
					<doughnut-chart :data="doughnutChart.data" :options="doughnutChart.options" class="h-300px"></doughnut-chart>
				</panel>
				<!-- END panel -->
				
				<!-- BEGIN panel -->
				<panel title="Todo List" bodyClass="p-0">
					<div class="todolist">
						<div class="todolist-item active">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist1" data-change="todolist" checked />
								</div>
							</div>
							<label class="todolist-label" for="todolist1">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist2" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist2">Duis a ullamcorper massa.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist3" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist3">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist4" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist4">Duis pharetra mi sit amet dictum congue.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist5" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum congue.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist6" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
						</div>
						<div class="todolist-item">
							<div class="todolist-input">
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="todolist7" data-change="todolist" />
								</div>
							</div>
							<label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
						</div>
					</div>
				</panel>
				<!-- END panel -->
				
				<!-- BEGIN panel -->
				<panel title="World Visitors" bodyClass="p-0">
					<GmapMap class="h-300px" v-bind:options="map" :zoom="3" :center="{lat: 25.304304, lng: -90.06591800000001}"></GmapMap>
				</panel>
				<!-- END panel -->
				
				<!-- BEGIN panel -->
				<panel title="Calendar" bodyClass="p-0">
					<vue-event-calendar :events="events.data"></vue-event-calendar>
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-4 -->
		</div>
		<!-- END row -->
	</div>
</template>

<script>
import LineChart from '../components/vue-chartjs/LineChart'
import DoughnutChart from '../components/vue-chartjs/DoughnutChart'

export default {
	components: {
		LineChart,
		DoughnutChart
	},
	data() {
		// eslint-disable-next-line
		Chart.defaults.global.defaultFontColor = '#2d353c';
		// eslint-disable-next-line
		Chart.defaults.global.defaultFontFamily = 'Open Sans, Helvetica Neue,Helvetica,Arial,sans-serif';
		// eslint-disable-next-line
		Chart.defaults.global.defaultFontStyle = '600';
		
		let today = new Date()
		var eventsData = [{
			date: `${today.getFullYear()}/${today.getMonth() + 1}/15`,
			title: 'Sales Reporting',
			badge: 'bg-gradient-teal',
			time: '9:00am'
		},{
			date: `${today.getFullYear()}/${today.getMonth() + 1}/24`,
			title: 'Have a meeting with sales team',
			badge: 'bg-gradient-blue',
			time: '2:45pm'
		}]
		
		return {
			lineChart: {
				data: {
					labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
					datasets: [{
						label: 'Page Views',
						backgroundColor: 'rgba(52, 143, 226, 0.2)',
						borderColor: '#348fe2',
						pointBackgroundColor: '#348fe2',
						pointRadius: 2,
						borderWidth: 2,
						data: [45, 60, 48, 55, 49, 58, 50, 45, 52, 39, 43, 67]
					}, {
						label: 'Visitors',
						backgroundColor: 'rgba(45, 53, 60, 0.2)',
						borderColor: '#2d353c',
						pointBackgroundColor: '#2d353c',
						pointRadius: 2,
						borderWidth: 2,
						data: [22, 18, 20, 18, 20, 23, 20, 18, 22, 18, 20, 23]
					}]
				},
				options: {
					responsive: true, 
					maintainAspectRatio: false,
					hover: {
						mode: 'nearest',
						intersect: true
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero:true,
								max: 100
							}
						}]
					}
				}
			},
			doughnutChart: {
				data: {
					labels: ['Chrome', 'Firefox', 'Safari', 'Opera', 'IE'],
					datasets: [{
						backgroundColor: ['#5b6392', '#727cb6', '#8e96c5', '#348fe2', '#2a72b5'],
						data: [35, 30, 15, 10, 5]
					}]
				},
				options: {
					responsive: true, 
					maintainAspectRatio: false,
					cutoutPercentage: 70
				}
			},
			map: {
				styles: [{featureType:"all",elementType:"labels.text.fill",stylers:[{saturation:36},{lightness:40}]},{featureType:"all",elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#000000"},{lightness:16}]},{featureType:"all",elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#2d353c"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:17},{weight:1.2}]},{featureType:"administrative",elementType:"labels.text.fill",stylers:[{color:"#d8d8d8"}]},{featureType:"administrative.neighborhood",elementType:"geometry.fill",stylers:[{color:"#ff0000"}]},{featureType:"administrative.land_parcel",elementType:"geometry.fill",stylers:[{color:"#2d353c"}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#000000"},{lightness:20}]},{featureType:"landscape",elementType:"geometry.fill",stylers:[{color:"#2d353c"}]},{featureType:"landscape",elementType:"labels.text.fill",stylers:[{color:"#00acac"}]},{featureType:"landscape.man_made",elementType:"geometry.fill",stylers:[{color:"#2d353c"}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#000000"},{lightness:21}]},{featureType:"poi",elementType:"geometry.fill",stylers:[{color:"#2d353c"}]},{featureType:"poi",elementType:"labels.text.fill",stylers:[{color:"#575d63"}]},{featureType:"road",elementType:"labels.text.fill",stylers:[{color:"#348fe2"}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#000000"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#000000"},{lightness:29},{weight:.2}]},{featureType:"road.highway.controlled_access",elementType:"geometry.fill",stylers:[{color:"#575d63"}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#000000"},{lightness:18}]},{featureType:"road.arterial",elementType:"geometry.fill",stylers:[{color:"#575d63"}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#000000"},{lightness:16}]},{featureType:"road.local",elementType:"geometry.fill",stylers:[{color:"#575d63"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#000000"},{lightness:19}]},{featureType:"transit",elementType:"geometry.fill",stylers:[{color:"#2d353c"}]},{featureType:"water",elementType:"geometry",stylers:[{color:"#000000"},{lightness:17}]},{featureType:"water",elementType:"geometry.fill",stylers:[{color:"#1a1f23"}]}]
			},
			events: {
				data: eventsData,
				displayData: eventsData
			},
			sparkline1: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#ff5b57',
					strokeWidth: 2
        }
      },
			sparkline2: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#f59c1a',
					strokeWidth: 2
        }
      },
			sparkline3: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#00ACAC',
					strokeWidth: 2
        }
      },
			sparkline4: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#348fe2',
					strokeWidth: 2
        }
      },
			sparkline5: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#b6c2c9',
					strokeWidth: 2
        }
      },
			sparkline6: {
				data: [ 50,30,45,40,50,20,35,40,50,70,90,40 ],
				length: 12,
				lineStyle: {
					stroke: '#2d353c',
					strokeWidth: 2
        }
      }
		}
	}
}
</script>