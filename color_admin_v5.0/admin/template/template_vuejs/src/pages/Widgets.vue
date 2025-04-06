<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item active">Widgets</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">
			Widgets <small>header small text goes here...</small>
		</h1>
		<!-- END page-header -->
		
		<span class="btn-group float-end ms-20px  p-2px bg-black-transparent-2 rounded">
			<a href="javascript:;" class="btn btn-sm btn-white border-0" v-on:click.prevent="handleChangeWidgetTheme('light')" v-bind:class="{ 'active': !inverseMode }"><i class="fa fa-sun text-blue"></i> Light</a>
			<a href="javascript:;" class="btn btn-sm btn-white border-0" v-on:click.prevent="handleChangeWidgetTheme('inverse')" v-bind:class="{ 'active': inverseMode }"><i class="fa fa-moon"></i> Dark</a>
		</span>
		<p class="mb-20px">
			All the widgets is reusable and responsive. You may use the predefined css to configure the padding, margin or background. Besides that, all the widgets have light and dark version. 
			Kindly add the <code>.inverse-mode</code> to the widget will change it to the dark version.
		</p>
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-4 -->
			<div class="col-lg-12 col-xl-4">
				<!-- BEGIN row -->
				<div class="row">
					<!-- BEGIN col-12 -->
					<div class="col-xl-12 col-lg-6">
						<!-- BEGIN widget-list -->
						<div class="mb-10px mt-10px fs-10px">
							<a v-b-modal.modalWidgetList class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
							<b class="text-inverse">WIDGET LIST</b>
						</div>
						<div class="widget-list rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
							<!-- BEGIN widget-list-item -->
							<div class="widget-list-item">
								<div class="widget-list-media">
									<img src="/assets/img/user/user-12.jpg" width="50" alt="" class="rounded" />
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Christopher Struth</h4>
									<p class="widget-list-desc">Bank Transfer</p>
								</div>
								<div class="widget-list-action">
									<b-dropdown variant="none" :no-caret="true" toggle-class="text-gray-500">
										<template slot="button-content">
											<i class="fa fa-ellipsis-h fs-14px"></i>
										</template>
										<b-dropdown-item>Option 1</b-dropdown-item>
										<b-dropdown-item>Option 2</b-dropdown-item>
										<b-dropdown-item>Option 3</b-dropdown-item>
										<b-dropdown-divider></b-dropdown-divider>
										<b-dropdown-item>Option 4</b-dropdown-item>
									</b-dropdown>
								</div>
							</div>
							<!-- END widget-list-item -->
							<!-- BEGIN widget-list-item -->
							<div class="widget-list-item">
								<div class="widget-list-media">
									<img src="/assets/img/user/user-13.jpg" width="50" alt="" class="rounded" />
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Janie Flowers</h4>
									<p class="widget-list-desc">Bank Transfer</p>
								</div>
								<div class="widget-list-action">
									<b-dropdown variant="none" :no-caret="true" toggle-class="text-gray-500">
										<template slot="button-content">
											<i class="fa fa-ellipsis-h fs-14px"></i>
										</template>
										<b-dropdown-item>Option 1</b-dropdown-item>
										<b-dropdown-item>Option 2</b-dropdown-item>
										<b-dropdown-item>Option 3</b-dropdown-item>
										<b-dropdown-divider></b-dropdown-divider>
										<b-dropdown-item>Option 4</b-dropdown-item>
									</b-dropdown>
								</div>
							</div>
							<!-- END widget-list-item -->
							<!-- BEGIN widget-list-item -->
							<div class="widget-list-item">
								<div class="widget-list-media">
									<img src="/assets/img/user/user-14.jpg" width="50" alt="" class="rounded" />
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Janie Flowers</h4>
									<p class="widget-list-desc">Bank Transfer</p>
								</div>
								<div class="widget-list-action">
									<b-dropdown variant="none" :no-caret="true" toggle-class="text-gray-500">
										<template slot="button-content">
											<i class="fa fa-ellipsis-h fs-14px"></i>
										</template>
										<b-dropdown-item>Option 1</b-dropdown-item>
										<b-dropdown-item>Option 2</b-dropdown-item>
										<b-dropdown-item>Option 3</b-dropdown-item>
										<b-dropdown-divider></b-dropdown-divider>
										<b-dropdown-item>Option 4</b-dropdown-item>
									</b-dropdown>
								</div>
							</div>
							<!-- END widget-list-item -->
						</div>
						<!-- END widget-list -->
					</div>
					<!-- END col-12 -->
					<!-- BEGIN col-12 -->
					<div class="col-xl-12 col-lg-6">
						<!-- BEGIN widget-list -->
						<div class="mb-10px mt-10px fs-10px">
							<a v-b-modal.modalWidgetListWithIcon class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
							<b class="text-inverse">WIDGET LIST WITH ICON</b>
						</div>
						<div class="widget-list rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
							<a href="javascript:;" class="widget-list-item">
								<div class="widget-list-media icon">
									<i class="fa fa-plane bg-inverse text-white"></i>
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Airplane Mode</h4>
								</div>
								<div class="widget-list-action text-end">
									<i class="fa fa-angle-right fa-lg text-gray-500"></i>
								</div>
							</a>
							<a href="javascript:;" class="widget-list-item">
								<div class="widget-list-media icon">
									<i class="fa fa-wifi bg-inverse text-white"></i>
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Wi-Fi</h4>
								</div>
								<div class="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
									Connected
									<i class="fa fa-angle-right fa-lg ms-3 text-gray-500"></i>
								</div>
							</a>
							<a href="javascript:;" class="widget-list-item">
								<div class="widget-list-media icon">
									<i class="fab fa-bluetooth bg-indigo text-white"></i>
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Bluetooth</h4>
								</div>
								<div class="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
									On
									<i class="fa fa-angle-right text-gray-500 fa-lg ms-3"></i>
								</div>
							</a>
							<a href="javascript:;" class="widget-list-item">
								<div class="widget-list-media icon">
									<i class="fa fa-signal bg-pink text-white"></i>
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Cellular</h4>
								</div>
								<div class="widget-list-action text-decoration-none fw-bold">
									<i class="fa fa-angle-right text-gray-500 fa-lg"></i>
								</div>
							</a>
							<a href="javascript:;" class="widget-list-item">
								<div class="widget-list-media icon">
									<i class="fa fa-link bg-yellow text-inverse"></i>
								</div>
								<div class="widget-list-content">
									<h4 class="widget-list-title">Personal Hotspot</h4>
								</div>
								<div class="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none">
									Off
									<i class="fa fa-angle-right text-gray-500 fa-lg ms-3"></i>
								</div>
							</a>
						</div>
						<!-- END widget-list -->
					</div>
					<!-- END col-12 -->
					<!-- BEGIN col-12 -->
					<div class="col-xl-12 col-lg-6">
						<!-- BEGIN widget-chat -->
						<div class="mb-10px mt-10px fs-10px">
							<a v-b-modal.modalWidgetChatInput class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
							<b class="text-inverse">WIDGET CHAT</b>
						</div>
						<div class="widget-chat rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
							<!-- BEGIN widget-chat-header -->
							<div class="widget-chat-header">
								<div class="widget-chat-header-icon">
									<i class="fab fa-earlybirds w-30px h-30px fs-20px bg-yellow text-inverse d-flex align-items-center justify-content-center rounded"></i>
								</div>
								<div class="widget-chat-header-content">
									<h4 class="widget-chat-header-title">Company Discussion Group</h4>
									<p class="widget-chat-header-desc">55 members, 4 online</p>
								</div>
							</div>
							<!-- END widget-chat-header -->
							<!-- BEGIN widget-chat-body -->
							<vue-custom-scrollbar style="height: 235px" class="widget-chat-body">
								<!-- BEGIN widget-chat-item -->
								<div class="widget-chat-item with-media start">
									<div class="widget-chat-media">
										<img alt="" src="/assets/img/user/user-1.jpg" />
									</div>
									<div class="widget-chat-info">
										<div class="widget-chat-info-container">
											<div class="widget-chat-name text-indigo">Hudson Mendes</div>
											<div class="widget-chat-message">Should we plan for a company trip this year?</div>
											<div class="widget-chat-time">6:00PM</div>
										</div>
									</div>
								</div>
								<!-- END widget-chat-item -->
								<!-- BEGIN widget-chat-item -->
								<div class="widget-chat-item with-media start">
									<div class="widget-chat-media">
										<img alt="" src="/assets/img/user/user-2.jpg" />
									</div>
									<div class="widget-chat-info">
										<div class="widget-chat-info-container">
											<div class="widget-chat-name text-primary">Sam Sugerman</div>
											<div class="widget-chat-message">ok let's do it</div>
											<div class="widget-chat-time">6:01PM</div>
										</div>
									</div>
								</div>
								<!-- END widget-chat-item -->
								<!-- BEGIN widget-chat-item -->
								<div class="widget-chat-item end">
									<div class="widget-chat-info">
										<div class="widget-chat-info-container">
											<div class="widget-chat-message">i'm ok with it</div>
											<div class="widget-chat-time">6:05PM</div>
										</div>
									</div>
								</div>
								<!-- END widget-chat-item -->
								<div class="text-center text-gray-500 m-2 fw-bold">Today</div>
								<!-- BEGIN widget-chat-item -->
								<div class="widget-chat-item with-media start">
									<div class="widget-chat-media">
										<img alt="" src="/assets/img/user/user-3.jpg" />
									</div>
									<div class="widget-chat-info">
										<div class="widget-chat-info-container">
											<div class="widget-chat-name text-orange">Jaxon Allwood</div>
											<div class="widget-chat-message">
												Here is some images for New Zealand
												<div class="row mt-5px">
													<div class="col-md-4">
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-51-thumb.jpg)"></div>
														</a>
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-52-thumb.jpg)"></div>
														</a>
													</div>
													<div class="col-md-4">
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-53-thumb.jpg)"></div>
														</a>
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-54-thumb.jpg)"></div>
														</a>
													</div>
													<div class="col-md-4">
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-59-thumb.jpg)"></div>
														</a>
														<a href="javascript:;" class="widget-card widget-card-sm square mb-2px">
															<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-60-thumb.jpg)"></div>
														</a>
													</div>
												</div>
											</div>
											<div class="widget-chat-time">6:20PM</div>
										</div>
									</div>
								</div>
								<!-- END widget-chat-item -->
							</vue-custom-scrollbar>
							<!-- END widget-chat-body -->
							<!-- BEGIN widget-input -->
							<div class="widget-input">
								<form action="" method="POST" name="">
									<div class="widget-input-container">
										<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-camera"></i></a></div>
										<div class="widget-input-box">
											<input type="text" class="form-control" placeholder="Write a message..." />
										</div>
										<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-smile"></i></a></div>
										<div class="widget-input-divider"></div>
										<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-microphone"></i></a></div>
									</div>
								</form>
							</div>
							<!-- END widget-input -->
						</div>
						<!-- END widget-chat -->
					</div>
					<!-- END col-12 -->
					<!-- BEGIN col-12 -->
					<div class="col-xl-12 col-lg-6">
						<!-- BEGIN widget-todolist -->
						<div class="mb-10px mt-10px fs-10px">
							<a v-b-modal.modalWidgetTodolist class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
							<b class="text-inverse">WIDGET TODOLIST</b>
						</div>
						<div class="widget-todolist rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
							<!-- BEGIN widget-todolist-header -->
							<div class="widget-todolist-header">
								<div class="widget-todolist-header-title">Todolist</div>
								<div class="widget-todolist-header-total"><span>0</span><small>Done</small></div>
							</div>
							<!-- END widget-todolist-header -->
							<!-- BEGIN widget-todolist-body -->
							<div class="widget-todolist-body">
								<!-- BEGIN widget-todolist-item -->
								<div class="widget-todolist-item">
									<div class="widget-todolist-input">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="widget_todolist_1" />
										</div>
									</div>
									<div class="widget-todolist-content">
										<h6 class="mb-2px">Borrow Tony's travel guide</h6>
										<div class="text-gray-500 fw-bold fs-11px">Vacation in Rome</div>
									</div>
									<div class="widget-todolist-icon">
										<a href="javascript:;"><i class="fa fa-question-circle"></i></a>
									</div>
								</div>
								<!-- END widget-todolist-item -->
								<!-- BEGIN widget-todolist-item -->
								<div class="widget-todolist-item">
									<div class="widget-todolist-input">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="widget_todolist_2" />
										</div>
									</div>
									<div class="widget-todolist-content">
										<h6 class="mb-2px">Finish expense report</h6>
										<div class="text-gray-500 fw-bold fs-11px">Today, 4:00PM, Daily</div>
									</div>
									<div class="widget-todolist-icon">
										<a href="javascript:;"><i class="fa fa-question-circle"></i></a>
									</div>
								</div>
								<!-- END widget-todolist-item -->
								<!-- BEGIN widget-todolist-item -->
								<div class="widget-todolist-item">
									<div class="widget-todolist-input">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="widget_todolist_3" />
										</div>
									</div>
									<div class="widget-todolist-content">
										<h6 class="mb-2px">Confirm conference call for Wednesday</h6>
										<div class="text-gray-500 fw-bold fs-11px">Work</div>
									</div>
									<div class="widget-todolist-icon">
										<a href="javascript:;"><i class="fa fa-question-circle"></i></a>
									</div>
								</div>
								<!-- END widget-todolist-item -->
								<!-- BEGIN widget-todolist-item -->
								<div class="widget-todolist-item">
									<div class="widget-todolist-input">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="widget_todolist_4" />
										</div>
									</div>
									<div class="widget-todolist-content">
										<h6 class="mb-2px"><b class="text-warning">!!</b> Mobile App WIP Presentation</h6>
										<div class="text-gray-500 fw-bold fs-11px">Today, 12:00PM</div>
									</div>
									<div class="widget-todolist-icon">
										<a href="javascript:;"><i class="fa fa-question-circle"></i></a>
									</div>
								</div>
								<!-- END widget-todolist-item -->
								<!-- BEGIN widget-todolist-item -->
								<div class="widget-todolist-item">
									<div class="widget-todolist-input">
										<i class="fa fa-plus text-gray-500 fa-fw"></i>
									</div>
									<div class="widget-todolist-content">
										<input type="text" class="form-control border-0 shadow-none rounded-0 p-0 h-20px bg-none" placeholder="Write your task here..." />
									</div>
								</div>
								<!-- END widget-todolist-item -->
							</div>
							<!-- END widget-todolist-body -->
						</div>
						<!-- END widget-todolist -->
					</div>
					<!-- END col-12 -->
					<!-- BEGIN col-12 -->
					<div class="col-xl-12 col-lg-6">
						<!-- BEGIN widget-map -->
						<div class="mb-10px mt-10px fs-10px">
							<a v-b-modal.modalWidgetMap class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
							<b class="text-inverse">WIDGET MAP</b>
						</div>
						<div class="widget-map rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
							<!-- BEGIN widget-input-container -->
							<div class="widget-input-container">
								<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500" data-id="widget-elm" data-light-class="text-gray-500" data-dark-class="text-white"><i class="fa fa-ellipsis-v"></i></a></div>
								<div class="widget-input-box">
									<input type="text" class="form-control" placeholder="Search here">
								</div>
								<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500" data-id="widget-elm" data-light-class="text-gray-500" data-dark-class="text-white"><i class="fa fa-microphone"></i></a></div>
								<div class="widget-input-divider"></div>
								<div class="widget-input-icon"><a href="javascript:;" class="text-gray-500" data-id="widget-elm" data-light-class="text-gray-500" data-dark-class="text-white"><i class="fa fa-location-arrow"></i></a></div>
							</div>
							<!-- END widget-input-container -->
							<!-- BEGIN widget-map-body -->
							<div class="widget-map-body">
								<iframe class="d-block" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.5650178360584!2d-122.41879278478642!3d37.77679637975903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c6c8f4459%3A0xb10ed6d9b5050fa5!2sTwitter+HQ!5e0!3m2!1sen!2smy!4v1524046379645" width="100%" height="230" frameborder="0" style="border:0" allowfullscreen></iframe>
							</div>
							<!-- END widget-map-body -->
							<!-- BEGIN widget-map-list -->
							<div class="widget-map-list" v-bind:class="{ 'inverse-mode': inverseMode }">
								<div class="widget-list bg-none">
									<!-- BEGIN widget-list-item -->
									<div class="widget-list-item">
										<div class="widget-list-media text-center">
											<a href="javascript:;"><i class="fab fa-twitter fa-3x"></i></a>
										</div>
										<div class="widget-list-content">
											<h4 class="widget-list-title">Twitter Headquater</h4>
											<p class="widget-list-desc">Corporate Office</p>
										</div>
										<div class="widget-list-action">
											<a href="javascript:;" class="text-gray-500"><i class="fa fa-angle-right fa-2x"></i></a>
										</div>
									</div>
									<!-- END widget-list-item -->
								</div>
							</div>
							<!-- END widget-map-list -->
						</div>
						<!-- END widget-map -->
					</div>
					<!-- END col-12 -->
				</div>
				<!-- END row -->
			</div>
			<!-- END col-4 -->
			<!-- BEGIN col-8 -->
			<div class="col-lg-12 col-xl-8">
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetImgIcon class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET IMAGE / ICON</b>
				</div>
				<!-- BEGIN row -->
				<div class="row mb-4">
					<!-- BEGIN col-6 -->
					<div class="col-sm-6">
						<div class="d-flex flex-wrap">
							<div class="widget-icon rounded bg-indigo me-5px mb-5px text-white">
								<i class="fab fa-digital-ocean"></i>
							</div>
							<div class="widget-icon rounded bg-blue me-5px mb-5px text-white">
								<i class="fab fa-twitter"></i>
							</div>
							<div class="widget-icon rounded bg-success me-5px mb-5px text-white">
								<i class="fab fa-android"></i>
							</div>
							<div class="widget-icon rounded bg-warning me-5px mb-5px text-white">
								<i class="fab fa-firefox"></i>
							</div>
							<div class="widget-icon rounded bg-danger me-5px mb-5px text-white">
								<i class="fab fa-google-plus-g"></i>
							</div>
							<div class="widget-icon rounded bg-pink me-5px mb-5px text-white">
								<i class="fab fa-pinterest"></i>
							</div>
						</div>
						<div class="clearfix">
							<div class="widget-icon widget-icon-xl user rounded float-start me-5px mb-5px text-white" v-bind:class="{ 'bg-inverse text-white-transparent-5': inverseMode, 'bg-grey text-white': !inverseMode }">
								<i class="fa fa-user"></i>
							</div>
							<div class="widget-icon widget-icon-lg user rounded float-start me-5px mb-5px text-white" v-bind:class="{ 'bg-inverse text-white-transparent-5': inverseMode, 'bg-grey text-white': !inverseMode }">
								<i class="fa fa-user"></i>
							</div>
							<div class="widget-icon user rounded float-start me-5px mb-5px text-white" v-bind:class="{ 'bg-inverse text-white-transparent-5': inverseMode, 'bg-grey text-white': !inverseMode }">
								<i class="fa fa-user"></i>
							</div>
							<div class="widget-icon widget-icon-sm user rounded float-start me-5px mb-5px text-white" v-bind:class="{ 'bg-inverse text-white-transparent-5': inverseMode, 'bg-grey text-white': !inverseMode }">
								<i class="fa fa-user"></i>
							</div>
							<div class="widget-icon widget-icon-xs user rounded float-start me-5px mb-5px text-white" v-bind:class="{ 'bg-inverse text-white-transparent-5': inverseMode, 'bg-grey text-white': !inverseMode }">
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
					<!-- END col-6 -->
					<!-- BEGIN col-6 -->
					<div class="col-sm-6">
						<div class="float-start">
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-11-thumb.jpg)"
							></div>
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-12-thumb.jpg)"
							></div>
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-13-thumb.jpg)"
							></div>
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-14-thumb.jpg)"
							></div>
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-15-thumb.jpg)"
							></div>
							<div class="widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-16-thumb.jpg)"
							></div>
							<br />
							<div class="widget-img widget-img-xl rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-21-thumb.jpg)"
							></div>
							<div class="widget-img widget-img-lg rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-22-thumb.jpg)"
							></div>
							<div class="widget-img widget-img rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-23-thumb.jpg)"
							></div>
							<div class="widget-img widget-img-sm rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-24-thumb.jpg)"
							></div>
							<div class="widget-img widget-img-xs rounded bg-inverse float-start me-5px mb-5px"
								style="background-image: url(/assets/img/gallery/gallery-25-thumb.jpg)"
							></div>
						</div>
					</div>
					<!-- END col-6 -->
				</div>
				<!-- END row -->
				
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetCard class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET CARD</b>
				</div>
				<!-- BEGIN row -->
				<div class="row">
					<!-- BEGIN col-6 -->
					<div class="col-sm-6">
						<!-- BEGIN widget-card -->
						<a href="javascript:;" class="widget-card rounded mb-20px" v-bind:class="{ 'inverse-mode': inverseMode }">
							<div class="widget-card-cover rounded" style="background-image: url(/assets/img/gallery/gallery-portrait-11-thumb.jpg)"></div>
							<div class="widget-card-content">
								<b class="text-white">Download and get free trial.</b>
							</div>
							<div class="widget-card-content bottom">
								<i class="fab fa-pushed fa-5x text-indigo"></i>
								<h4 class="text-white mt-10px"><b>Apple Draw<br /> Photo Booth</b></h4>
								<h5 class="fs-12px text-white-transparent-7 mb-0"><b>EASILY DRAW ON PHOTOS</b></h5>
							</div>
						</a>
						<!-- END widget-card -->
					</div>
					<!-- END col-6 -->
					<!-- BEGIN col-6 -->
					<div class="col-sm-6">
						<!-- BEGIN widget-card -->
						<a href="javascript:;" class="widget-card rounded mb-20px" v-bind:class="{ 'inverse-mode': inverseMode }">
							<div class="widget-card-cover rounded"></div>
							<div class="widget-card-content">
								<h5 class="fs-12px" v-bind:class="{ 'text-white-transparent-7': inverseMode, 'text-black-transparent-7': !inverseMode }"><b>MAKING A DIFFERENCE</b></h5>
								<h4 class="mb-10px text-pink"><b>Apple Heart<br /> Study App</b></h4>
								<i class="fa fa-heartbeat fa-5x text-pink-transparent-5"></i>
							</div>
							<div class="widget-card-content bottom">
								<b class="fs-12px" v-bind:class="{ 'text-white-transparent-7': inverseMode, 'text-black-transparent-7': !inverseMode }">Opt in and help heart research.</b>
							</div>
						</a>
						<!-- END widget-card -->
					</div>
					<!-- END col-6 -->
				</div>
				<!-- END row -->
				
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetCardSquare class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET CARD SQUARE</b>
				</div>
				<!-- BEGIN row -->
				<div class="row">
					<!-- BEGIN col-3 -->
					<div class="col-xs-6 col-sm-3">
						<!-- BEGIN row -->
						<div class="row gx-1 mb-1">
							<!-- BEGIN col-6 -->
							<div class="col-6">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-1-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-2-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-6 -->
							<!-- BEGIN col-6 -->
							<div class="col-6">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-3-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-4-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-6 -->
						</div>
						<!-- END row -->
						<div class="fs-12px fw-bold text-black">Camera Roll</div>
						<div class="fs-10px fw-bold text-black-lighter mb-3">2,711</div>
					</div>
					<!-- END col-3 -->
					<!-- BEGIN col-3 -->
					<div class="col-xs-6 col-sm-3">
						<a href="javascript:;" class="widget-card rounded square mb-5px">
							<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-7-thumb.jpg)">
								<span class="widget-card-cover-icon"><i class="fa fa-play fa-lg"></i></span>
							</div>
						</a>
						<div class="fs-12px fw-bold text-black pt-2px">Videos</div>
						<div class="fs-10px fw-bold text-black-lighter mb-3">31</div>
					</div>
					<!-- END col-3 -->
					<!-- BEGIN col-3 -->
					<div class="col-xs-6 col-sm-3">
						<a href="javascript:;" class="widget-card rounded square mb-5px">
							<div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-10-thumb.jpg)">
								<div class="d-flex h-100 align-items-center justify-content-center">
									<div class="text-center">
										<div class="text-white fs-14px"><b>PORTRAITS OF</b></div>
										<div class="text-white-transparent-8 fw-bold">2021</div>
									</div>
								</div>
							</div>
						</a>
						<div class="fs-12px fw-bold text-black pt-2px">Memory 2021</div>
						<div class="fs-10px fw-bold text-black-lighter mb-3">1,239</div>
					</div>
					<!-- END col-3 -->
					<!-- BEGIN col-3 -->
					<div class="col-xs-6 col-sm-3">
						<!-- BEGIN row -->
						<div class="row gx-1 mb-5px">
							<!-- BEGIN col-3 -->
							<div class="col-3">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-1-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-2-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-3-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-4-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-3 -->
							<!-- BEGIN col-3 -->
							<div class="col-3">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-5-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-6-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-7-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-8-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-3 -->
							<!-- BEGIN col-3 -->
							<div class="col-3">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-9-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-10-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-11-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-12-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-3 -->
							<!-- BEGIN col-3 -->
							<div class="col-3">
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-13-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-14-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-15-thumb.jpg)"></div>
								</a>
								<a href="javascript:;" class="widget-card rounded square mb-1">
									<div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-16-thumb.jpg)"></div>
								</a>
							</div>
							<!-- END col-3 -->
						</div>
						<!-- END row -->
						<div class="fs-12px fw-bold text-black">Albums</div>
						<div class="fs-10px fw-bold text-black-lighter mb-15px">8</div>
					</div>
					<!-- END col-3 -->
				</div>
				<!-- END row -->
				
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetStat class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET STAT</b>
				</div>
				<!-- BEGIN row -->
				<div class="row gx-2 mb-20px">
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-teal mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">TODAY'S VISITS</div>
								<div class="stats-number">7,842,900</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 70.1%;"></div>
								</div>
								<div class="stats-desc">Better than last week (70.1%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-blue mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">TODAY'S PROFIT</div>
								<div class="stats-number">180,200</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 40.5%;"></div>
								</div>
								<div class="stats-desc">Better than last week (40.5%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-purple mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">NEW ORDERS</div>
								<div class="stats-number">38,900</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 76.3%;"></div>
								</div>
								<div class="stats-desc">Better than last week (76.3%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-black mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">NEW COMMENTS</div>
								<div class="stats-number">3,988</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 54.9%;"></div>
								</div>
								<div class="stats-desc">Better than last week (54.9%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-orange mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-file-alt fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">PENDING INVOICE</div>
								<div class="stats-number">20</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 23.5%;"></div>
								</div>
								<div class="stats-desc">More than last week (23.5%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
					<!-- BEGIN col-4 -->
					<div class="col-lg-4 col-sm-6">
						<div class="widget widget-stats bg-pink mb-7px">
							<div class="stats-icon stats-icon-lg"><i class="fa fa-exclamation-triangle fa-fw"></i></div>
							<div class="stats-content">
								<div class="stats-title">ERROR LOG</div>
								<div class="stats-number">5</div>
								<div class="stats-progress progress">
									<div class="progress-bar" style="width: 10.5%;"></div>
								</div>
								<div class="stats-desc">More than last week (10.5%)</div>
							</div>
						</div>
					</div>
					<!-- END col-4 -->
				</div>
				<!-- END row -->
				
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetChart class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET CHART</b>
				</div>
				<!-- BEGIN widget-chart -->
				<div class="widget rounded mb-4" v-bind:class="{ 'inverse-mode': inverseMode }">
					<!-- BEGIN widget-header -->
					<div class="widget-header">
						<h4 class="widget-header-title">Audience Overview</h4>
						<div class="widget-header-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-fw fa-upload"></i></a></div>
						<div class="widget-header-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-fw fa-cog"></i></a></div>
					</div>
					<!-- END widget-header -->
					<!-- BEGIN widget-content -->
					<div class="row m-0">
						<div class="col widget-chart-content">
							<div id="nv-stacked-area-chart" style="height: 108%">
								<vn-stacked-area :model="stackedAreaChart.data"
									yFormat=",.4f" 
									:height="stackedAreaChart.height"
									:xFormat="formatDate" 
									:clipEdge="stackedAreaChart.clipEdge"
									:rightAlignYAxis="stackedAreaChart.rightAlignYAxis" 
									:showControls="stackedAreaChart.showControls">
								</vn-stacked-area>
							</div>
						</div>
						<div class="col-lg-4 p-3">
							<div class="widget-chart-info">
								<h4 class="widget-chart-info-title">Total sales</h4>
								<p class="widget-chart-info-desc">Lorem ipsum dolor sit consectetur adipiscing elit.</p>
								<div class="widget-chart-info-progress">
									<b>Monthly Plan</b>
									<span class="float-end">70%</span>
								</div>
								<div class="progress h-10px">
									<div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill" style="width:70%;"></div>
								</div>
							</div>
							<hr />
							<div class="widget-chart-info">
								<h4 class="widget-chart-info-title">Task progress</h4>
								<p class="widget-chart-info-desc">Vestibulum sollicitudin in lectus a cursus.</p>
								<div class="widget-chart-info-progress">
									<b>Marketing Research</b>
									<span class="float-end">74%</span>
								</div>
								<div class="progress h-10px mb-15px">
									<div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-indigo" style="width: 74%"></div>
								</div>
								<div class="widget-chart-info-progress">
									<b>Mobile App Development</b>
									<span class="float-end">25%</span>
								</div>
								<div class="progress h-10px mb-15px">
									<div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-green" style="width: 25%"></div>
								</div>
								<div class="widget-chart-info-progress">
									<b>Website Redesign</b>
									<span class="float-end">95%</span>
								</div>
								<div class="progress h-10px">
									<div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-orange" style="width: 95%"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- END widget-content -->
				</div>
				<!-- END widget-chart -->
				
				<div class="mb-10px mt-10px fs-10px">
					<a v-b-modal.modalWidgetTable class="float-end text-gray-600 text-decoration-none me-3px fw-bold">source code</a>
					<b class="text-inverse">WIDGET TABLE</b>
				</div>
				<!-- BEGIN widget-table -->
				<div class="table-responsive">
					<!-- BEGIN widget-table -->
					<table class="table table-bordered widget-table rounded" v-bind:class="{ 'inverse-mode': inverseMode }">
						<thead>
							<tr class="text-nowrap">
								<th width="1%">Image</th>
								<th>Product Info</th>
								<th>Price</th>
								<th>Qty</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="/assets/img/product/product-6.png" width="100" />
								</td>
								<td>
									<h5 class="mb-1">Mavic Pro Combo</h5>
									<p class="fs-11px fw-bold text-gray-600 mb-3">Portable yet powerful, the Mavic Pro is your personal drone, ready to go with you everywhere.</p>
									<div class="progress h-10px rounded-pill mb-5px">
										<div class="progress-bar progress-bar-striped progress-bar-animated bg-orange fs-10px fw-bold" style="width: 30%;">30%</div>
									</div>
									<div class="clearfix fs-10px">
										status: 
										<b v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }">Shipped</b>
									</div>
								</td>
								<td class="text-nowrap">
									<b v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }">$999</b><br />
									<small v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }"><del>$1,202</del></small>
								</td>
								<td>1</td>
								<td>999.00</td>
								<td>
									<a href="javascript:;" class="btn btn-sm w-80px rounded-pill" v-bind:class="{ 'btn-default': inverseMode, 'btn-inverse': !inverseMode }">Edit</a>
								</td>
							</tr>
							<tr>
								<td>
									<img src="/assets/img/product/product-7.png" width="100" />
								</td>
								<td>
									<h5 class="mb-1">Inspire 2</h5>
									<p class="fs-11px fw-bold text-gray-600 mb-3">Cinematic aerial performance for filmmakers.</p>
									<div class="progress h-10px rounded-pill mb-5px">
										<div class="progress-bar progress-bar-striped progress-bar-animated bg-success fs-10px fw-bold" style="width: 100%;">100%</div>
									</div>
									<div class="clearfix fs-10px">
										status: 
										<b v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }">received</b>
									</div>
								</td>
								<td class="text-nowrap">
									<b v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }">$999</b><br />
									<small v-bind:class="{ 'text-white': inverseMode, 'text-inverse': !inverseMode }"><del>$1,202</del></small>
								</td>
								<td>1</td>
								<td>999.00</td>
								<td>
									<a href="javascript:;" class="btn btn-sm w-80px rounded-pill" v-bind:class="{ 'btn-default': inverseMode, 'btn-inverse': !inverseMode }">Edit</a>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- END widget-table -->
				</div>
				<!-- END table-responsive -->
			</div>
			<!-- END col-8 -->
		</div>
		<!-- END row -->
		
		<!-- begin #modal-widget-map -->
		<b-modal id="modalWidgetMap" size="lg" ref="modalWidgetMap" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Map</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetMap')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html">&lt;!-- begin widget-map --&gt;
&lt;div class="widget-map rounded<span class="hljs-string" data-id="widget-doc" data-dark-doc=" inverse-mode" data-light-doc=""></span>"&gt;
  &lt;!-- begin widget-input-container --&gt;
  &lt;div class="widget-input-container"&gt;
    &lt;div class="widget-input-icon"&gt;&lt;a href="#" class="text-inverse"&gt;&lt;i class="fa fa-ellipsis-v"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
    &lt;div class="widget-input-box"&gt;
      &lt;input type="text" class="form-control" placeholder="Search here"&gt;
    &lt;/div&gt;
    &lt;div class="widget-input-icon"&gt;&lt;a href="#" class="text-inverse"&gt;&lt;i class="fa fa-microphone"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
    &lt;div class="widget-input-divider"&gt;&lt;/div&gt;
    &lt;div class="widget-input-icon"&gt;&lt;a href="#"&gt;&lt;i class="fa fa-location-arrow"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-input-container --&gt;
  &lt;!-- begin widget-map-body --&gt;
  &lt;div class="widget-map-body"&gt;
    &lt;iframe src="-- googlemap embed url here --" width="100%" height="230" frameborder="0" style="border:0" allowfullscreen&gt;&lt;/iframe&gt;
  &lt;/div&gt;
  &lt;!-- end widget-map-body --&gt;
  &lt;!-- begin widget-map-list --&gt;
  &lt;div class="widget-map-list"&gt;
    &lt;div class="widget-list bg-none mb-4"&gt;
      &lt;!-- begin widget-list-item --&gt;
      &lt;div class="widget-list-item"&gt;
        &lt;div class="widget-list-media text-center"&gt;
          &lt;a href="#"&gt;&lt;i class="fab fa-twitter fa-3x"&gt;&lt;/i&gt;&lt;/a&gt;
        &lt;/div&gt;
        &lt;div class="widget-list-content"&gt;
          &lt;h4 class="widget-list-title"&gt;Twitter Headquater&lt;/h4&gt;
          &lt;p class="widget-list-desc"&gt;Corporate Office&lt;/p&gt;
        &lt;/div&gt;
        &lt;div class="widget-list-action"&gt;
          &lt;a href="#" data-bs-toggle="dropdown" class="text-gray-500"&gt;&lt;i class="fa fa-angle-right fa-2x"&gt;&lt;/i&gt;&lt;/a&gt;
        &lt;/div&gt;
      &lt;/div&gt;
      &lt;!-- end widget-list-item --&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-map-list --&gt;
&lt;/div&gt;
&lt;!-- begin widget-map --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-map -->
		
		<!-- begin #modal-widget-todolist -->
		<b-modal id="modalWidgetTodolist" size="lg" ref="modalWidgetTodolist" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Todolist</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetTodolist')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-todolist">&lt;!-- begin widget-todolist --&gt;
&lt;div class="widget-todolist rounded<span class="hljs-string" data-id="widget-doc" data-dark-doc=" inverse-mode" data-light-doc=""></span>"&gt;
  &lt;!-- begin widget-todolist-header --&gt;
  &lt;div class="widget-todolist-header"&gt;
    &lt;h4 class="widget-todolist-header-title"&gt;Todolist&lt;/h4&gt;
    &lt;div class="widget-todolist-header-total"&gt;
      &lt;span class="text-inverse"&gt;0&lt;/span&gt;
      &lt;small&gt;Done&lt;/small&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-todolist-header --&gt;
  
  &lt;!-- begin widget-todolist-body --&gt;
  &lt;div class="widget-todolist-body"&gt;
    &lt;!-- begin widget-todolist-item --&gt;
    &lt;div class="widget-todolist-item"&gt;
      &lt;div class="widget-todolist-input"&gt;
        &lt;div class="form-check"&gt;
          &lt;input class="form-check-input" type="checkbox" id="widget_todolist_1" /&gt;
          &lt;label class="form-check-label" for="widget_todolist_1" class="ps-15px"&gt;&nbsp;&lt;/label&gt;
        &lt;/div&gt;
      &lt;/div&gt;
      &lt;div class="widget-todolist-content"&gt;
        &lt;h6 class="mb-0"&gt;Borrow Tony's travel guide&lt;/h4&gt;
        &lt;div class="text-muted"&gt;Vacation in Rome&lt;/p&gt;
      &lt;/div&gt;
      &lt;div class="widget-todolist-icon"&gt;
        &lt;a href="#"&gt;&lt;i class="fa fa-question-circle"&gt;&lt;/i&gt;&lt;/a&gt;
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;!-- end widget-todolist-item --&gt;
    ...
    &lt;!-- begin widget-todolist-item --&gt;
    &lt;div class="widget-todolist-item"&gt;
      &lt;div class="widget-todolist-input"&gt;
        &lt;i class="fa fa-plus text-gray-500"&gt;&lt;/i&gt;
      &lt;/div&gt;
      &lt;div class="widget-todolist-content"&gt;
        &lt;input type="text" class="form-control" placeholder="Write your task here..." /&gt;
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;!-- end widget-todolist-item --&gt;
  &lt;/div&gt;
  &lt;!-- end widget-todolist-body --&gt;
&lt;/div&gt;
&lt;!-- end widget-todolist --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-todolist -->
		
		<!-- begin #modal-widget-chat-input -->
		<b-modal id="modalWidgetChatInput" size="lg" ref="modalWidgetChatInput" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Chat & Input</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetChatInput')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-chat-input">&lt;!-- begin widget-chat --&gt;
&lt;div class="widget-chat rounded<span class="hljs-string" data-id="widget-doc" data-dark-doc=" inverse-mode" data-light-doc=""></span>"&gt;
  &lt;!-- begin widget-chat-header --&gt;
  &lt;div class="widget-chat-header"&gt;
    &lt;div class="widget-chat-header-icon"&gt;
      &lt;i class="fab fa-earlybirds w-30px h-30px fs-20px bg-yellow text-inverse text-center rounded-pill" style="line-height: 30px"&gt;&lt;/i&gt;
    &lt;/div&gt;
    &lt;div class="widget-chat-header-content"&gt;
      &lt;h4 class="widget-chat-header-title"&gt;Company Discussion Group&lt;/h4&gt;
      &lt;p class="widget-chat-header-desc"&gt;55 members, 4 online&lt;/p&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-chat-header --&gt;
  
  &lt;!-- begin widget-chat-body --&gt;
  &lt;div class="widget-chat-body" data-scrollbar="true" data-height="235px"&gt;
    &lt;div class="text-center text-gray-500 m-3 fw-bold"&gt;Today&lt;/div&gt;
    &lt;div class="widget-chat-item with-media start"&gt;
      &lt;div class="widget-chat-media"&gt;
        &lt;img alt="" src="../assets/img/user/user-1.jpg" /&gt;
      &lt;/div&gt;
      &lt;div class="widget-chat-info"&gt;
        &lt;div class="widget-chat-info-container"&gt;
          &lt;div class="widget-chat-name text-indigo"&gt;Hudson Mendes&lt;/div&gt;
          &lt;div class="widget-chat-message"&gt;Should we plan for a company trip this year?&lt;/div&gt;
          &lt;div class="widget-chat-time"&gt;6:00PM&lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="widget-chat-item end"&gt;
      ...
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-chat-body --&gt;
  
  &lt;!-- begin widget-input --&gt;
  &lt;div class="widget-input"&gt;
    &lt;form action="" method="POST" name=""&gt;
      &lt;div class="widget-input-container"&gt;
        &lt;div class="widget-input-icon"&gt;&lt;a href="#" class="text-gray-500"&gt;&lt;i class="fa fa-camera"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
        &lt;div class="widget-input-box"&gt;
          &lt;input type="text" class="form-control" placeholder="Write a message..." /&gt;
        &lt;/div&gt;
        &lt;div class="widget-input-icon"&gt;&lt;a href="#" class="text-gray-500"&gt;&lt;i class="fa fa-smile"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
        &lt;div class="widget-input-divider"&gt;&lt;/div&gt;
        &lt;div class="widget-input-icon"&gt;&lt;a href="#" class="text-gray-500"&gt;&lt;i class="fa fa-microphone"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
      &lt;/div&gt;
    &lt;/form&gt;
  &lt;/div&gt;
  &lt;!-- end widget-input --&gt;
&lt;/div&gt;
&lt;!-- end widget-chat --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-chat-input -->
		
		<!-- begin #modal-widget-list -->
		<b-modal id="modalWidgetList" size="lg" ref="modalWidgetList" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget List</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetList')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-list">&lt;!-- begin widget-list --&gt;
&lt;div class="widget-list rounded mb-4"&gt;
  &lt;!-- begin widget-list-item --&gt;
  &lt;div class="widget-list-item"&gt;
    &lt;div class="widget-list-media"&gt;
      &lt;img src="../assets/img/user/user-1.jpg" width="50" class="rounded" /&gt;
    &lt;/div&gt;
    &lt;div class="widget-list-content"&gt;
      &lt;h4 class="widget-list-title"&gt;Christopher Struth&lt;/h4&gt;
      &lt;p class="widget-list-desc"&gt;Bank Transfer&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="widget-list-action"&gt;
      &lt;a href="#" data-bs-toggle="dropdown" class="text-gray-500"&gt;
        &lt;i class="fa fa-ellipsis-h fs-14px"&gt;&lt;/i&gt;
      &lt;/a&gt;
      &lt;ul class="dropdown-menu dropdown-menu-end"&gt;
        ...
      &lt;/ul&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;!-- end widget-list-item --&gt;
&lt;/div&gt;
&lt;!-- end widget-list --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-list -->
		
		<!-- begin #modal-widget-list-with-icon -->
		<b-modal id="modalWidgetListWithIcon" size="lg" ref="modalWidgetListWithIcon" title="" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget List with Icon</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetListWithIcon')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-list-with-icon">&lt;!-- begin widget-list --&gt;
&lt;div class="widget-list rounded"&gt;
  &lt;!-- begin widget-list-item --&gt;
  &lt;a href="#" class="widget-list-item"&gt;
    &lt;div class="widget-list-media icon"&gt;
      &lt;i class="fa fa-link bg-yellow text-inverse"&gt;&lt;/i&gt;
    &lt;/div&gt;
    &lt;div class="widget-list-content"&gt;
      &lt;h4 class="widget-list-title"&gt;Personal Hotspot&lt;/h4&gt;
    &lt;/div&gt;
    &lt;div class="widget-list-action text-nowrap text-gray-600 fw-bold text-decoration-none"&gt;
      Off
      &lt;i class="fa fa-angle-right text-gray-500 fa-lg"&gt;&lt;/i&gt;
    &lt;/div&gt;
  &lt;/a&gt;
  &lt;!-- end widget-list-item --&gt;
  ...
&lt;/div&gt;
&lt;!-- end widget-list --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-list -->
		
		<!-- begin #modal-widget-img-icon -->
		<b-modal id="modalWidgetImgIcon" size="lg" ref="modalWidgetImgIcon" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Image / Icon</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetImgIcon')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-img-icon">&lt;!-- begin widget-icon --&gt;
&lt;div class="widget-icon rounded bg-success text-white"&gt;
  &lt;i class="fab fa-android"&gt;&lt;/i&gt;
&lt;/div&gt;
&lt;!-- end widget-icon --&gt;

&lt;!-- begin user widget-icon --&gt;
&lt;div class="widget-icon rounded <span class="hljs-string" data-id="widget-doc" data-dark-doc="bg-inverse text-white-transparent-5" data-light-doc="bg-gray-500 text-white">bg-gray-500 text-white</span> user"&gt;
  &lt;i class="fa fa-user"&gt;&lt;/i&gt;
&lt;/div&gt;
&lt;!-- end user widget-icon --&gt;

&lt;!-- begin widget-icon-sizes --&gt;
&lt;div class="widget-icon widget-icon-xl"&gt;&lt;/div&gt;
&lt;div class="widget-icon widget-icon-lg"&gt;&lt;/div&gt;
&lt;div class="widget-icon"&gt;&lt;/div&gt;
&lt;div class="widget-icon widget-icon-sm"&gt;&lt;/div&gt;
&lt;div class="widget-icon widget-icon-xs"&gt;&lt;/div&gt;
&lt;!-- end widget-icon-sizes --&gt;

&lt;!-- begin widget-img --&gt;
&lt;div class="widget-img rounded bg-inverse" style="background-image: url(../assets/img/gallery/gallery-11-thumb.jpg)"&gt;
&lt;/div&gt;
&lt;!-- end widget-img --&gt;

&lt;!-- begin widget-img-sizes --&gt;
&lt;div class="widget-img widget-img-xl"&gt;&lt;/div&gt;
&lt;div class="widget-img widget-img-lg"&gt;&lt;/div&gt;
&lt;div class="widget-img"&gt;&lt;/div&gt;
&lt;div class="widget-img widget-img-sm"&gt;&lt;/div&gt;
&lt;div class="widget-img widget-img-xs"&gt;&lt;/div&gt;
&lt;!-- end widget-img-sizes --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-img-icon -->
		
		<!-- begin #modal-widget-card -->
		<b-modal id="modalWidgetCard" size="lg" ref="modalWidgetCard" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget List Card</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetCard')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-card">&lt;!-- begin widget-card --&gt;
&lt;a href="#" class="widget-card rounded"&gt;
  &lt;div class="widget-card-cover rounded" style="background-image: url(../assets/img/gallery/gallery-portrait-11-thumb.jpg)"&gt;&lt;/div&gt;
  &lt;div class="widget-card-content"&gt;
    &lt;b class="text-white"&gt;Download and get free trial.&lt;/b&gt;
  &lt;/div&gt;
  &lt;div class="widget-card-content bottom"&gt;
    &lt;i class="fab fa-pushed fa-5x text-indigo"&gt;&lt;/i&gt;
    &lt;h4 class="text-white mt-10px"&gt;&lt;b&gt;Apple Draw&lt;br /&gt; Photo Booth&lt;/b&gt;&lt;/h4&gt;
    &lt;h5 class="fs-12px text-white-transparent-7"&gt;&lt;b&gt;EASILY DRAW ON PHOTOS&lt;/b&gt;&lt;/h5&gt;
  &lt;/div&gt;
&lt;/a&gt;
&lt;!-- end widget-card --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-card -->
		
		<!-- begin #modal-widget-card-square -->
		<b-modal id="modalWidgetCardSquare" size="lg" ref="modalWidgetCardSquare" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget List Card Square</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetCardSquare')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-card-square">&lt;!-- begin widget-card-square --&gt;
&lt;a href="#" class="widget-card rounded square mb-5px"&gt;
  &lt;div class="widget-card-cover rounded" style="background-image: url(../assets/img/login-bg/login-bg-10.jpg)"&gt;
    &lt;div class="d-flex h-100 align-items-center justify-content-center"&gt;
      &lt;div class="text-center"&gt;
        &lt;div class="text-white fs-14px"&gt;&lt;b&gt;PORTRAITS OF&lt;/b&gt;&lt;/div&gt;
        &lt;div class="text-white-transparent-8 fw-bold"&gt;2021&lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/a&gt;
&lt;div class="fs-12px fw-bold text-black pt-2px"&gt;Memory 2021&lt;/div&gt;
&lt;div class="fs-10px fw-bold text-black-lighter mb-15px"&gt;1,239&lt;/div&gt;
&lt;!-- end widget-card-square --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-card-square -->
		
		<!-- begin #modal-widget-stat -->
		<b-modal id="modalWidgetStat" size="lg" ref="modalWidgetStat" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Stat</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetStat')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-stat">&lt;!-- begin widget-stats --&gt;
&lt;div class="widget widget-stats bg-teal mb-10px"&gt;
  &lt;div class="stats-icon stats-icon-lg"&gt;&lt;i class="fa fa-globe fa-fw"&gt;&lt;/i&gt;&lt;/div&gt;
  &lt;div class="stats-content"&gt;
    &lt;div class="stats-title"&gt;TODAY'S VISITS&lt;/div&gt;
    &lt;div class="stats-number"&gt;7,842,900&lt;/div&gt;
    &lt;div class="stats-progress progress"&gt;
      &lt;div class="progress-bar" style="width: 70.1%;"&gt;&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="stats-desc"&gt;Better than last week (70.1%)&lt;/div&gt;
  &lt;/div&gt;
&lt;/div&gt;
&lt;!-- end widget-stats --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-stat -->
		
		<!-- begin #modal-widget-chart -->
		<b-modal id="modalWidgetChart" size="lg" ref="modalWidgetChart" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Chart</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetChart')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-chart">&lt;!-- begin widget-chart --&gt;
&lt;div class="widget rounded"&gt;
  &lt;div class="widget-header"&gt;
    &lt;h4 class="widget-header-title"&gt;Audience Overview&lt;/h4&gt;
    &lt;div class="widget-header-icon"&gt;&lt;a href="#" class="text-gray-500"&gt;&lt;i class="fa fa-fw fa-upload"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
    &lt;div class="widget-header-icon"&gt;&lt;a href="#" class="text-gray-500"&gt;&lt;i class="fa fa-fw fa-cog"&gt;&lt;/i&gt;&lt;/a&gt;&lt;/div&gt;
  &lt;/div&gt;
  &lt;div class="d-flex"&gt;
    &lt;div class="flex-1 widget-chart-content"&gt;
      &lt;div id="nv-stacked-area-chart" style="height: 108%"&gt;&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="p-3" style="width: 30%;"&gt;
      &lt;div class="widget-chart-info"&gt;
        &lt;h4 class="widget-chart-info-title"&gt;Total sales&lt;/h4&gt;
        &lt;p class="widget-chart-info-desc"&gt;Lorem ipsum dolor sit consectetur adipiscing elit.&lt;/p&gt;
        &lt;div class="widget-chart-info-progress"&gt;
          &lt;b&gt;Monthly Plan&lt;/b&gt;
          &lt;span class="float-end"&gt;70%&lt;/span&gt;
        &lt;/div&gt;
        &lt;div class="progress h-10px"&gt;
          &lt;div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill" style="width:70%;"&gt;&lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;
      &lt;hr /&gt;
      &lt;div class="widget-chart-info"&gt;
        &lt;h4 class="widget-chart-info-title"&gt;Task progress&lt;/h4&gt;
        &lt;p class="widget-chart-info-desc"&gt;Vestibulum sollicitudin in lectus a cursus.&lt;/p&gt;
        &lt;div class="widget-chart-info-progress"&gt;
          &lt;b&gt;Marketing Research&lt;/b&gt;
          &lt;span class="float-end"&gt;74%&lt;/span&gt;
        &lt;/div&gt;
        &lt;div class="progress h-10px mb-15px"&gt;
          &lt;div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-indigo" style="width: 74%"&gt;&lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="widget-chart-info-progress"&gt;
          &lt;b&gt;Mobile App Development&lt;/b&gt;
          &lt;span class="float-end"&gt;25%&lt;/span&gt;
        &lt;/div&gt;
        &lt;div class="progress h-10px mb-15px"&gt;
          &lt;div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-green" style="width: 25%"&gt;&lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="widget-chart-info-progress"&gt;
          &lt;b&gt;Website Redesign&lt;/b&gt;
          &lt;span class="float-end"&gt;95%&lt;/span&gt;
        &lt;/div&gt;
        &lt;div class="progress h-10px"&gt;
          &lt;div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill bg-orange" style="width: 95%"&gt;&lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/div&gt;
&lt;!-- end widget-chart --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-chart -->
		
		<!-- begin #modal-widget-table -->
		<b-modal id="modalWidgetTable" size="lg" ref="modalWidgetTable" body-class="p-0" cancel-variant="default">
			<template slot="modal-header">
				<h4 class="modal-title">Widget Table</h4>
				<a class="btn-close" @click="$bvModal.hide('modalWidgetTable')"></a>
			</template>
			<!-- begin hljs-wrapper -->
			<div class="hljs-wrapper mb-0" v-highlight>
				<pre class="mb-0"><code class="html" id="source-widget-table">&lt;!-- begin widget-table --&gt;
&lt;table class="table table-bordered widget-table widget-table-rounded"&gt;
  &lt;thead&gt;
    &lt;tr&gt;
      &lt;th width="1%"&gt;Image&lt;/th&gt;
      &lt;th&gt;Product Info&lt;/th&gt;
      &lt;th&gt;Price&lt;/th&gt;
      &lt;th&gt;Qty&lt;/th&gt;
      &lt;th&gt;Total&lt;/th&gt;
      &lt;th&gt;&lt;/th&gt;
    &lt;/tr&gt;
  &lt;/thead&gt;
  &lt;tbody&gt;
    &lt;tr&gt;
      &lt;td&gt;
        &lt;img src="../assets/img/product/product-6.png" width="100" /&gt;
      &lt;/td&gt;
      &lt;td&gt;
        &lt;h5&gt;Mavic Pro Combo&lt;/h5&gt;
        &lt;p class="fs-11px text-gray-600 fw-bold mb-3"&gt;Portable yet powerful, the Mavic Pro is your personal drone, ready to go with you everywhere.&lt;/p&gt;
        &lt;div class="progress h-10px rounded-pill mb-5px"&gt;
          &lt;div class="progress-bar progress-bar-striped progress-bar-animated bg-orange fs-10px fw-bold" style="width: 30%;"&gt;30%&lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="clearfix fs-10px"&gt;
          status: &lt;b class="text-inverse"&gt;Shipped&lt;/b&gt;
        &lt;/div&gt;
      &lt;/td&gt;
      &lt;td class="text-nowrap"&gt;
        &lt;b class="text-inverse"&gt;$999&lt;/b&gt;&lt;br /&gt;
        &lt;small class="text-inverse"&gt;&lt;del&gt;$1,202&lt;/del&gt;&lt;/small&gt;
      &lt;/td&gt;
      &lt;td&gt;1&lt;/td&gt;
      &lt;td&gt;999.00&lt;/td&gt;
      &lt;td&gt;&lt;a href="#" class="btn <span class="hljs-string" data-id="widget-doc" data-dark-doc="btn-default" data-light-doc="btn-inverse">btn-inverse</span> btn-sm w-80px rounded-pill"&gt;Edit&lt;/a&gt;&lt;/td&gt;
    &lt;/tr&gt;
  &lt;/tbody&gt;
&lt;/table&gt;
&lt;!-- end widget-table --&gt;</code></pre>
			</div>
			<!-- end hljs-wrapper -->
		</b-modal>
		<!-- end #modal-widget-table -->
	</div>
</template>

<script>
export default {
	data() {
		var stackedAreaChartData = [{
        'key' : 'Financials',
        'color' : '#ff5b57',
        'values' : [{ x: 1138683600000, y: 13.356778764352 }, { x: 1141102800000, y: 13.611196863271 }, { x: 1143781200000, y: 6.895903006119 }, { x: 1146369600000, y: 6.9939633271352 }, { x: 1149048000000, y: 6.7241510257675 }, { x: 1151640000000, y: 5.5611293669516 }, { x: 1154318400000, y: 5.6086488714041 }, { x: 1156996800000, y: 5.4962849907033 }, { x: 1159588800000, y: 6.9193153169279 }, { x: 1162270800000, y: 7.0016334389777 }, { x: 1164862800000, y: 6.7865422443273 }, { x: 1167541200000, y: 9.0006454225383 }, { x: 1170219600000, y: 9.2233916171431 }, { x: 1172638800000, y: 8.8929316009479 }, { x: 1175313600000, y: 10.345937520404 }, { x: 1177905600000, y: 10.075914677026 }, { x: 1180584000000, y: 10.089006188111 }, { x: 1183176000000, y: 10.598330295008 }, { x: 1185854400000, y: 9.968954653301 }, { x: 1188532800000, y: 9.7740580198146 }, { x: 1191124800000, y: 10.558483060626 }, { x: 1193803200000, y: 9.9314651823603 }, { x: 1196398800000, y: 9.3997715873769 }, { x: 1199077200000, y: 8.4086493387262 }, { x: 1201755600000, y: 8.9698309085926 }, { x: 1204261200000, y: 8.2778357995396 }, { x: 1206936000000, y: 8.8585045600123 }, { x: 1209528000000, y: 8.7013756413322 }, { x: 1212206400000, y: 7.7933605469443 }, { x: 1214798400000, y: 7.0236183483064 }, { x: 1217476800000, y: 6.9873088186829 }, { x: 1220155200000, y: 6.8031713070097 }, { x: 1222747200000, y: 6.6869531315723 }, { x: 1225425600000, y: 6.138256993963 }, { x: 1228021200000, y: 5.6434994016354 }, { x: 1230699600000, y: 5.495220262512 }, { x: 1233378000000, y: 4.6885326869846 }, { x: 1235797200000, y: 4.4524349883438 }, { x: 1238472000000, y: 5.6766520778185 }, { x: 1241064000000, y: 5.7675774480752 }, { x: 1243742400000, y: 5.7882863168337 }, { x: 1246334400000, y: 7.2666010034924 }, { x: 1249012800000, y: 7.519182132226 }, { x: 1251691200000, y: 7.849651451445 }, { x: 1254283200000, y: 10.383992037985 }, { x: 1256961600000, y: 9.0653691861818 }, { x: 1259557200000, y: 9.6705248324159 }, { x: 1262235600000, y: 10.856380561349 }, { x: 1264914000000, y: 11.27452370892 }, { x: 1267333200000, y: 11.754156529088 }, { x: 1270008000000, y: 8.2870811422456 }, { x: 1272600000000, y: 8.0210264360699 }, { x: 1275278400000, y: 7.5375074474865 }, { x: 1277870400000, y: 8.3419527338039 }, { x: 1280548800000, y: 9.4197471818443 }, { x: 1283227200000, y: 8.7321733185797 }, { x: 1285819200000, y: 9.6627062648126 }, { x: 1288497600000, y: 10.187962234549 }, { x: 1291093200000, y: 9.8144201733476 }, { x: 1293771600000, y: 10.275723361713 }, { x: 1296450000000, y: 16.796066079353 }, { x: 1298869200000, y: 17.543254984075 }, { x: 1301544000000, y: 16.673660675084 }, { x: 1304136000000, y: 17.963944353609 }, { x: 1306814400000, y: 16.637740867211 }, { x: 1309406400000, y: 15.84857094609 }, { x: 1312084800000, y: 14.767303362182 }, { x: 1314763200000, y: 24.778452182432 }, { x: 1317355200000, y: 18.370353229999 }, { x: 1320033600000, y: 15.2531374291 }, { x: 1322629200000, y: 14.989600840649 }, { x: 1325307600000, y: 16.052539160125 }, { x: 1327986000000, y: 16.424390322793 }, { x: 1330491600000, y: 17.884020741105 }, { x: 1333166400000, y: 7.1424929577921 }, { x: 1335758400000, y: 7.8076213051482 }, { x: 1338436800000, y: 7.2462684949232}]
    }, {
        'key' : 'Health Care',
        'color' : '#f59c1a',
        'values' : [{ x: 1138683600000, y: 14.212410956029 }, { x: 1141102800000, y: 13.973193618249 }, { x: 1143781200000, y: 15.218233920665 }, { x: 1146369600000, y: 14.38210972745 }, { x: 1149048000000, y: 13.894310878491 }, { x: 1151640000000, y: 15.593086090032 }, { x: 1154318400000, y: 16.244839695188 }, { x: 1156996800000, y: 16.017088850646 }, { x: 1159588800000, y: 14.183951830055 }, { x: 1162270800000, y: 14.148523245697 }, { x: 1164862800000, y: 13.424326059972 }, { x: 1167541200000, y: 12.974450435753 }, { x: 1170219600000, y: 13.23247041802 }, { x: 1172638800000, y: 13.318762655574 }, { x: 1175313600000, y: 15.961407746104 }, { x: 1177905600000, y: 16.287714639805 }, { x: 1180584000000, y: 16.246590583889 }, { x: 1183176000000, y: 17.564505594809 }, { x: 1185854400000, y: 17.872725373165 }, { x: 1188532800000, y: 18.018998508757 }, { x: 1191124800000, y: 15.584518016603 }, { x: 1193803200000, y: 15.480850647181 }, { x: 1196398800000, y: 15.699120036984 }, { x: 1199077200000, y: 19.184281817226 }, { x: 1201755600000, y: 19.691226605207 }, { x: 1204261200000, y: 18.982314051295 }, { x: 1206936000000, y: 18.707820309008 }, { x: 1209528000000, y: 17.459630929761 }, { x: 1212206400000, y: 16.500616076782 }, { x: 1214798400000, y: 18.086324003979 }, { x: 1217476800000, y: 18.929464156258 }, { x: 1220155200000, y: 18.233728682084 }, { x: 1222747200000, y: 16.315776297325 }, { x: 1225425600000, y: 14.63289219025 }, { x: 1228021200000, y: 14.667835024478 }, { x: 1230699600000, y: 13.946993947308 }, { x: 1233378000000, y: 14.394304684397 }, { x: 1235797200000, y: 13.724462792967 }, { x: 1238472000000, y: 10.930879035806 }, { x: 1241064000000, y: 9.8339915513708 }, { x: 1243742400000, y: 10.053858541872 }, { x: 1246334400000, y: 11.786998438287 }, { x: 1249012800000, y: 11.780994901769 }, { x: 1251691200000, y: 11.305889670276 }, { x: 1254283200000, y: 10.918452290083 }, { x: 1256961600000, y: 9.6811395055706 }, { x: 1259557200000, y: 10.971529744038 }, { x: 1262235600000, y: 13.330210480209 }, { x: 1264914000000, y: 14.592637568961 }, { x: 1267333200000, y: 14.605329141157 }, { x: 1270008000000, y: 13.936853794037 }, { x: 1272600000000, y: 12.189480759072 }, { x: 1275278400000, y: 11.676151385046 }, { x: 1277870400000, y: 13.058852800017 }, { x: 1280548800000, y: 13.62891543203 }, { x: 1283227200000, y: 13.811107569918 }, { x: 1285819200000, y: 13.786494560787 }, { x: 1288497600000, y: 14.04516285753 }, { x: 1291093200000, y: 13.697412447288 }, { x: 1293771600000, y: 13.677681376221 }, { x: 1296450000000, y: 19.961511864531 }, { x: 1298869200000, y: 21.049198298158 }, { x: 1301544000000, y: 22.687631094008 }, { x: 1304136000000, y: 25.469010617433 }, { x: 1306814400000, y: 24.883799437121 }, { x: 1309406400000, y: 24.203843814248 }, { x: 1312084800000, y: 22.138760964038 }, { x: 1314763200000, y: 16.034636966228 }, { x: 1317355200000, y: 15.394958944556 }, { x: 1320033600000, y: 12.625642461969 }, { x: 1322629200000, y: 12.973735699739 }, { x: 1325307600000, y: 15.786018336149 }, { x: 1327986000000, y: 15.227368020134 }, { x: 1330491600000, y: 15.899752650734 }, { x: 1333166400000, y: 18.994731295388 }, { x: 1335758400000, y: 18.450055817702 }, { x: 1338436800000, y: 17.863719889669}]
    }, {
        'key' : 'Information Technology',
        'color' : '#2d353c',
        'values' : [{ x: 1138683600000, y: 13.242301508051 }, { x: 1141102800000, y: 12.863536342042 }, { x: 1143781200000, y: 21.034044171629 }, { x: 1146369600000, y: 21.419084618803 }, { x: 1149048000000, y: 21.142678863691 }, { x: 1151640000000, y: 26.568489677529 }, { x: 1154318400000, y: 24.839144939905 }, { x: 1156996800000, y: 25.456187462167 }, { x: 1159588800000, y: 26.350164502826 }, { x: 1162270800000, y: 26.47833320519 }, { x: 1164862800000, y: 26.425979547847 }, { x: 1167541200000, y: 28.191461582256 }, { x: 1170219600000, y: 28.930307448808 }, { x: 1172638800000, y: 29.521413891117 }, { x: 1175313600000, y: 28.188285966466 }, { x: 1177905600000, y: 27.704619625832 }, { x: 1180584000000, y: 27.490862424829 }, { x: 1183176000000, y: 28.770679721286 }, { x: 1185854400000, y: 29.060480671449 }, { x: 1188532800000, y: 28.240998844973 }, { x: 1191124800000, y: 33.004893194127 }, { x: 1193803200000, y: 34.075180359928 }, { x: 1196398800000, y: 32.548560664833 }, { x: 1199077200000, y: 30.629727432728 }, { x: 1201755600000, y: 28.642858788159 }, { x: 1204261200000, y: 27.973575227842 }, { x: 1206936000000, y: 27.393351882726 }, { x: 1209528000000, y: 28.476095288523 }, { x: 1212206400000, y: 29.29667866426 }, { x: 1214798400000, y: 29.222333802896 }, { x: 1217476800000, y: 28.092966093843 }, { x: 1220155200000, y: 28.107159262922 }, { x: 1222747200000, y: 25.482974832098 }, { x: 1225425600000, y: 21.208115993834 }, { x: 1228021200000, y: 20.295043095268 }, { x: 1230699600000, y: 15.925754618401 }, { x: 1233378000000, y: 17.162864628346 }, { x: 1235797200000, y: 17.084345773174 }, { x: 1238472000000, y: 22.246007102281 }, { x: 1241064000000, y: 24.530543998509 }, { x: 1243742400000, y: 25.084184918242 }, { x: 1246334400000, y: 16.606166527358 }, { x: 1249012800000, y: 17.239620011628 }, { x: 1251691200000, y: 17.336739127379 }, { x: 1254283200000, y: 25.478492475753 }, { x: 1256961600000, y: 23.017152085245 }, { x: 1259557200000, y: 25.617745423683 }, { x: 1262235600000, y: 24.061133998642 }, { x: 1264914000000, y: 23.223933318644 }, { x: 1267333200000, y: 24.425887263937 }, { x: 1270008000000, y: 35.501471156693 }, { x: 1272600000000, y: 33.775013878676 }, { x: 1275278400000, y: 30.417993630285 }, { x: 1277870400000, y: 30.023598978467 }, { x: 1280548800000, y: 33.327519522436 }, { x: 1283227200000, y: 31.963388450371 }, { x: 1285819200000, y: 30.498967232092 }, { x: 1288497600000, y: 32.403696817912 }, { x: 1291093200000, y: 31.47736071922 }, { x: 1293771600000, y: 31.53259666241 }, { x: 1296450000000, y: 41.760282761548 }, { x: 1298869200000, y: 45.605771243237 }, { x: 1301544000000, y: 39.986557966215 }, { x: 1304136000000, y: 43.846330510051 }, { x: 1306814400000, y: 39.857316881857 }, { x: 1309406400000, y: 37.675127768208 }, { x: 1312084800000, y: 35.775077970313 }, { x: 1314763200000, y: 48.631009702577 }, { x: 1317355200000, y: 42.830831754505 }, { x: 1320033600000, y: 35.611502589362 }, { x: 1322629200000, y: 35.320136981738 }, { x: 1325307600000, y: 31.564136901516 }, { x: 1327986000000, y: 32.074407502433 }, { x: 1330491600000, y: 35.053013769976 }, { x: 1333166400000, y: 26.434568573937 }, { x: 1335758400000, y: 25.305617871002 }, { x: 1338436800000, y: 24.520919418236}]
    }];
    
		return {
			inverseMode: false,
			stackedAreaChart: {
				data: stackedAreaChartData,
				rightAlignYAxis: false,
				showControls: false,
				clipEdge: false,
				height: '350px'
			}
		}
	},
	methods: {
		handleChangeWidgetTheme: function(theme) {
			if (theme == 'light') {
				this.inverseMode = false;
			} else if (theme == 'inverse') {
				this.inverseMode = true;
			}
		},
		formatDate (d){
			// eslint-disable-next-line
			return d3.time.format('%x')(new Date(d))
		}
	}
}
</script>