<template>
	<div>
		<!-- BEGIN profile -->
		<div class="profile">
			<div class="profile-header">
				<!-- BEGIN profile-header-cover -->
				<div class="profile-header-cover"></div>
				<!-- END profile-header-cover -->
				<!-- BEGIN profile-header-content -->
				<div class="profile-header-content">
					<!-- BEGIN profile-header-img -->
					<div class="profile-header-img">
						<img src="/assets/img/user/user-13.jpg" alt="" />
					</div>
					<!-- END profile-header-img -->
					<!-- BEGIN profile-header-info -->
					<div class="profile-header-info">
						<h4 class="mt-0 mb-1">Sean Ngu</h4>
						<p class="mb-2">UXUI + Frontend Developer</p>
						<a href="#" class="btn btn-xs btn-yellow">Edit Profile</a>
					</div>
					<!-- END profile-header-info -->
				</div>
				<!-- END profile-header-content -->
				<!-- BEGIN profile-header-tab -->
				<ul class="profile-header-tab nav nav-tabs">
					<li class="nav-item"><a class="nav-link" v-on:click="show('post')" v-bind:class="{ 'active': tab.post }">POSTS</a></li>
					<li class="nav-item"><a class="nav-link" v-on:click="show('about')" v-bind:class="{ 'active': tab.about }">ABOUT</a></li>
					<li class="nav-item"><a class="nav-link" v-on:click="show('photo')" v-bind:class="{ 'active': tab.photo }">PHOTOS</a></li>
					<li class="nav-item"><a class="nav-link" v-on:click="show('video')" v-bind:class="{ 'active': tab.video }">VIDEOS</a></li>
					<li class="nav-item"><a class="nav-link" v-on:click="show('friend')" v-bind:class="{ 'active': tab.friend }">FRIENDS</a></li>
				</ul>
				<!-- END profile-header-tab -->
			</div>
		</div>
		<!-- END profile -->
		<!-- BEGIN profile-content -->
		<div class="profile-content">
			<!-- BEGIN tab-content -->
			<div class="tab-content p-0">
				<!-- BEGIN #profile-post tab -->
				<div class="tab-pane fade" v-bind:class="{ 'show active': tab.post }">
					<!-- BEGIN timeline -->
					<div class="timeline">
						<!-- BEGIN timeline-item -->
						<div class="timeline-item">
							<!-- BEGIN timeline-time -->
							<div class="timeline-time">
								<span class="date">today</span>
								<span class="time">04:20</span>
							</div>
							<!-- END timeline-time -->
							<!-- BEGIN timeline-icon -->
							<div class="timeline-icon">
								<a href="javascript:;">&nbsp;</a>
							</div>
							<!-- END timeline-icon -->
							<!-- BEGIN timeline-content -->
							<div class="timeline-content">
								<!-- BEGIN timeline-header -->
								<div class="timeline-header">
									<div class="userimage"><img src="/assets/img/user/user-1.jpg" alt="" /></div>
									<div class="username">
										<a href="javascript:;">John Smith <i class="fa fa-check-circle text-blue ms-1"></i></a>
										<div class="text-muted fs-12px">8 mins <i class="fa fa-globe-americas opacity-5 ms-1"></i></div>
									</div>
									<div>
										<a href="#" class="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
											<i class="fa fa-ellipsis-h text-gray-600"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="#" class="dropdown-item d-flex align-items-center">
												<i class="fa fa-fw fa-bookmark fa-lg"></i> 
												<div class="flex-1 ps-1">
													<div>Save Post</div>
													<div class="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
												</div>
											</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</a>
										</div>
									</div>
								</div>
								<!-- END timeline-header -->
					
								<!-- BEGIN timeline-body -->
								<div class="timeline-body">
									<!-- timeline-post -->
									<div class="mb-3">
										<div class="mb-2">
											Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc faucibus turpis quis tincidunt luctus.
											Nam sagittis dui in nunc consequat, in imperdiet nunc sagittis.
										</div>
										<div class="row gx-1">
											<div class="col-6">
												<div class="ratio ratio-4x3">
													<img src="/assets/img/gallery/gallery-14.jpg" class="d-block mw-100" />
												</div>
											</div>
											<div class="col-3">
												<div class="ratio ratio-4x3 mb-3px">
													<img src="/assets/img/gallery/gallery-12.jpg" class="d-block mw-100" />
												</div>
												<div class="ratio ratio-4x3">
													<img src="/assets/img/gallery/gallery-16.jpg" class="d-block mw-100" />
												</div>
											</div>
											<div class="col-3">
												<div class="ratio ratio-4x3 mb-3px">
													<img src="/assets/img/gallery/gallery-15.jpg" class="d-block mw-100" />
												</div>
												<div class="ratio ratio-4x3">
													<img src="/assets/img/gallery/gallery-11.jpg" class="d-block mw-100" />
												</div>
											</div>
										</div>
									</div>
						
									<!-- timeline-stats -->
									<div class="d-flex align-items-center text-gray-600 mb-2">
										<div class="d-flex align-items-center">
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-danger"></i>
												<i class="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
											</span>
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-blue"></i>
												<i class="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
											</span>
											<span class="ms-1">4.3k</span>
										</div>
										<div class="d-flex align-items-center ms-auto">
											<div>259 Shares</div>
											<div class="ms-3">21 Comments</div>
										</div>
									</div>
						
									<!-- timeline-action -->
									<hr class="my-10px" />
									<div class="d-flex align-items-center fw-bold">
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-thumbs-up fa-fw me-3px"></i> Like
										</a>
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-comments fa-fw me-3px"></i> Comment</a> 
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-share fa-fw me-3px"></i> Share
										</a>
									</div>
									<hr class="mt-10px mb-3" />
						
									<!-- timeline-input -->
									<form action="" class="d-flex align-items-center">
										<div><img src="/assets/img/user/user-13.jpg" height="35" class="rounded-pill" /></div>
										<div class="ps-2 flex-1">
											<div class="position-relative">
												<input type="text" class="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
												<div class="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-smile fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-camera fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-film fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-sticky-note fa-fw fa-lg d-block"></i></a>
												</div>
											</div>
										</div>
									</form>
								</div>
								<!-- END timeline-body -->
							</div>
							<!-- END timeline-content -->
						</div>
						<!-- END timeline-item -->
						<!-- BEGIN timeline-item -->
						<div class="timeline-item">
							<!-- BEGIN timeline-time -->
							<div class="timeline-time">
								<span class="date">yesterday</span>
								<span class="time">20:17</span>
							</div>
							<!-- END timeline-time -->
							<!-- BEGIN timeline-icon -->
							<div class="timeline-icon">
								<a href="javascript:;">&nbsp;</a>
							</div>
							<!-- END timeline-icon -->
							<!-- BEGIN timeline-content -->
							<div class="timeline-content">
								<!-- BEGIN timeline-header -->
								<div class="timeline-header">
									<div class="userimage"><img src="/assets/img/user/user-2.jpg" alt="" /></div>
									<div class="username">
										<a href="javascript:;">Darren Parrase</a>
										<div class="text-muted fs-12px">24 mins <i class="fa fa-globe-americas opacity-5 ms-1"></i></div>
									</div>
									<div>
										<a href="#" class="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
											<i class="fa fa-ellipsis-h text-gray-600"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="#" class="dropdown-item d-flex align-items-center">
												<i class="fa fa-fw fa-bookmark fa-lg"></i> 
												<div class="flex-1 ps-1">
													<div>Save Post</div>
													<div class="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
												</div>
											</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</a>
										</div>
									</div>
								</div>
								<!-- END timeline-header -->
					
								<!-- BEGIN timeline-body -->
								<div class="timeline-body">
									<!-- timeline-post -->
									<div class="mb-3">
										<div class="mb-2">Location: United States</div>
										<div class="ratio ratio-16x9">
											<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3316.5242683789525!2d-84.36843278503017!3d33.772957280682846!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f50410c04ff6d9%3A0x2710fc547d72ec83!2sTwitter%20Headquarters!5e0!3m2!1sen!2smy!4v1623766084359!5m2!1sen!2smy" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
										</div>
									</div>
						
									<!-- timeline-stats -->
									<div class="d-flex align-items-center text-gray-600 mb-2">
										<div class="d-flex align-items-center">
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-danger"></i>
												<i class="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
											</span>
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-blue"></i>
												<i class="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
											</span>
											<span class="ms-1">269</span>
										</div>
										<div class="d-flex align-items-center ms-auto">
											<div>2 Shares</div>
											<div class="ms-3">9 Comments</div>
										</div>
									</div>
						
									<!-- timeline-action -->
									<hr class="my-10px" />
									<div class="d-flex align-items-center fw-bold">
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-thumbs-up fa-fw me-3px"></i> Like
										</a>
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-comments fa-fw me-3px"></i> Comment</a> 
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-share fa-fw me-3px"></i> Share
										</a>
									</div>
									<hr class="mt-10px mb-3" />
						
									<!-- timeline-input -->
									<form action="" class="d-flex align-items-center">
										<div><img src="/assets/img/user/user-13.jpg" height="35" class="rounded-pill" /></div>
										<div class="ps-2 flex-1">
											<div class="position-relative">
												<input type="text" class="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
												<div class="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-smile fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-camera fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-film fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-sticky-note fa-fw fa-lg d-block"></i></a>
												</div>
											</div>
										</div>
									</form>
								</div>
								<!-- END timeline-body -->
							</div>
							<!-- END timeline-content -->
						</div>
						<!-- END timeline-item -->
						<!-- BEGIN timeline-item -->
						<div class="timeline-item">
							<!-- BEGIN timeline-time -->
							<div class="timeline-time">
								<span class="date">24 February 2021</span>
								<span class="time">08:17</span>
							</div>
							<!-- END timeline-time -->
							<!-- BEGIN timeline-icon -->
							<div class="timeline-icon">
								<a href="javascript:;">&nbsp;</a>
							</div>
							<!-- END timeline-icon -->
							<!-- BEGIN timeline-content -->
							<div class="timeline-content">
								<!-- BEGIN timeline-header -->
								<div class="timeline-header">
									<div class="userimage"><img src="/assets/img/user/user-3.jpg" alt="" /></div>
									<div class="username">
										<a href="javascript:;">Richard Leong <i class="fa fa-check-circle text-blue ms-1"></i></a>
										<div class="text-muted fs-12px">12 hours <i class="fa fa-globe-americas opacity-5 ms-1"></i></div>
									</div>
									<div>
										<a href="#" class="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
											<i class="fa fa-ellipsis-h text-gray-600"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="#" class="dropdown-item d-flex align-items-center">
												<i class="fa fa-fw fa-bookmark fa-lg"></i> 
												<div class="flex-1 ps-1">
													<div>Save Post</div>
													<div class="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
												</div>
											</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</a>
										</div>
									</div>
								</div>
								<!-- END timeline-header -->
								<!-- BEGIN timeline-body -->
								<div class="timeline-body">
									<!-- timeline-post -->
									<div class="lead mb-3">
										<i class="fa fa-quote-left fa-fw float-start opacity-5 me-3 mb-3 mt-3 fa-lg"></i>
										Quisque sed varius nisl. Nulla facilisi. Phasellus consequat sapien sit amet nibh molestie placerat. Donec nulla quam, ullamcorper ut velit vitae, lobortis condimentum magna. Suspendisse mollis in sem vel mollis.
										<i class="fa fa-quote-right fa-fw float-end opacity-5 ms-3 mt-n3 fa-lg"></i>
									</div>
						
									<!-- timeline-stats -->
									<div class="d-flex align-items-center text-gray-600 mb-2">
										<div class="d-flex align-items-center">
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-danger"></i>
												<i class="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
											</span>
											<span class="fa-stack fs-10px">
												<i class="fa fa-circle fa-stack-2x text-blue"></i>
												<i class="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
											</span>
											<span class="ms-1">550</span>
										</div>
										<div class="d-flex align-items-center ms-auto">
											<div>121 Shares</div>
											<div class="ms-3">40 Comments</div>
										</div>
									</div>
						
									<!-- timeline-action -->
									<hr class="my-10px" />
									<div class="d-flex align-items-center fw-bold">
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-thumbs-up fa-fw me-3px"></i> Like
										</a>
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-comments fa-fw me-3px"></i> Comment</a> 
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-share fa-fw me-3px"></i> Share
										</a>
									</div>
									<hr class="mt-10px mb-3" />
						
									<!-- timeline-input -->
									<form action="" class="d-flex align-items-center">
										<div><img src="/assets/img/user/user-13.jpg" height="35" class="rounded-pill" /></div>
										<div class="ps-2 flex-1">
											<div class="position-relative">
												<input type="text" class="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
												<div class="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-smile fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-camera fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-film fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-sticky-note fa-fw fa-lg d-block"></i></a>
												</div>
											</div>
										</div>
									</form>
								</div>
								<!-- END timeline-body -->
							</div>
							<!-- END timeline-content -->
						</div>
						<!-- END timeline-item -->
						<!-- BEGIN timeline-item -->
						<div class="timeline-item">
							<!-- BEGIN timeline-time -->
							<div class="timeline-time">
								<span class="date">10 January 2021</span>
								<span class="time">20:43</span>
							</div>
							<!-- END timeline-time -->
							<!-- BEGIN timeline-icon -->
							<div class="timeline-icon">
								<a href="javascript:;">&nbsp;</a>
							</div>
							<!-- END timeline-icon -->
							<!-- BEGIN timeline-content -->
							<div class="timeline-content">
								<!-- BEGIN timeline-header -->
								<div class="timeline-header">
									<div class="userimage"><img src="/assets/img/user/user-4.jpg" alt="" /></div>
									<div class="username">
										<a href="javascript:;">Lelouch Wong <i class="fa fa-check-circle text-blue ms-1"></i></a>
										<div class="text-muted fs-12px">1 days ago <i class="fa fa-globe-americas opacity-5 ms-1"></i></div>
									</div>
									<div>
										<a href="#" class="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
											<i class="fa fa-ellipsis-h text-gray-600"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-end">
											<a href="#" class="dropdown-item d-flex align-items-center">
												<i class="fa fa-fw fa-bookmark fa-lg"></i> 
												<div class="flex-1 ps-1">
													<div>Save Post</div>
													<div class="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
												</div>
											</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</a>
											<div class="dropdown-divider"></div>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</a>
											<a href="#" class="dropdown-item"><i class="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</a>
										</div>
									</div>
								</div>
								<!-- END timeline-header -->
								<!-- BEGIN timeline-body -->
								<div class="timeline-body">
									<!-- timeline-post -->
									<div class="mb-3">
										<h4 class="mb-1">
											795 Folsom Ave, Suite 600 San Francisco, CA 94107
										</h4>
										<div class="mb-2">In hac habitasse platea dictumst. Pellentesque bibendum id sem nec faucibus. Maecenas molestie, augue vel accumsan rutrum, massa mi rutrum odio, id luctus mauris nibh ut leo.</div>
										<div class="row gx-1">
											<div class="col-6">
												<img src="/assets/img/gallery/gallery-4.jpg" alt="" class="mw-100 d-block" />
											</div>
											<div class="col-6">
												<img src="/assets/img/gallery/gallery-5.jpg" alt="" class="mw-100 d-block" />
											</div>
										</div>
									</div>
						
									<!-- timeline-action -->
									<hr class="my-10px" />
									<div class="d-flex align-items-center fw-bold">
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-thumbs-up fa-fw me-3px"></i> Like
										</a>
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-comments fa-fw me-3px"></i> Comment</a> 
										<a href="javascript:;" class="flex-fill text-decoration-none text-center text-gray-600">
											<i class="fa fa-share fa-fw me-3px"></i> Share
										</a>
									</div>
									<hr class="mt-10px mb-3" />
						
									<!-- timeline-input -->
									<form action="" class="d-flex align-items-center">
										<div><img src="/assets/img/user/user-13.jpg" height="35" class="rounded-pill" /></div>
										<div class="ps-2 flex-1">
											<div class="position-relative">
												<input type="text" class="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
												<div class="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-smile fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-camera fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="fa fa-film fa-fw fa-lg d-block"></i></a>
													<a href="#" class="btn bg-none text-gray-600 shadow-none px-1"><i class="far fa-sticky-note fa-fw fa-lg d-block"></i></a>
												</div>
											</div>
										</div>
									</form>
								</div>
								<!-- END timeline-body -->
							</div>
							<!-- END timeline-content -->
						</div>
						<!-- END timeline-item -->
						<!-- BEGIN timeline-item -->
						<div class="timeline-item">
							<!-- BEGIN timeline-icon -->
							<div class="timeline-icon">
								<a href="javascript:;">&nbsp;</a>
							</div>
							<!-- END timeline-icon -->
							<!-- BEGIN timeline-content -->
							<div class="timeline-content">
								<!-- BEGIN timeline-body -->
								<div class="timeline-body">
									<div class="d-flex align-items-center">
										<div class="spinner-border spinner-border-sm me-3" role="status">
											<span class="visually-hidden">Loading...</span>
										</div>
										Loading...
									</div>
								</div>
								<!-- END timeline-body -->
							</div>
							<!-- BEGIN timeline-content -->
						</div>
						<!-- END timeline-item -->
					</div>
					<!-- END timeline -->
				</div>
				<!-- END #profile-post tab -->
				<!-- BEGIN #profile-about tab -->
				<div class="tab-pane fade" v-bind:class="{ 'show active': tab.about }">
					<!-- BEGIN table -->
					<div class="table-responsive form-inline">
						<table class="table table-profile align-middle">
							<thead>
								<tr>
									<th></th>
									<th>
										<h4>Sean Ngu <small>UXUI + Frontend Developer</small></h4>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="highlight">
									<td class="field">Mood</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add Mood Message</a></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr>
									<td class="field">Mobile</td>
									<td><i class="fa fa-mobile fa-lg me-5px"></i> +1-(847)- 367-8924 <a href="javascript:;" class="ms-5px text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Edit</a></td>
								</tr>
								<tr>
									<td class="field">Home</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add Number</a></td>
								</tr>
								<tr>
									<td class="field">Office</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add Number</a></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr class="highlight">
									<td class="field">About Me</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add Description</a></td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr>
									<td class="field">Country/Region</td>
									<td>
										<select class="form-select w-200px" name="region">
											<option value="US" selected>United State</option>
											<option value="AF">Afghanistan</option>
											<option value="AL">Albania</option>
											<option value="DZ">Algeria</option>
											<option value="AS">American Samoa</option>
											<option value="AD">Andorra</option>
											<option value="AO">Angola</option>
											<option value="AI">Anguilla</option>
											<option value="AQ">Antarctica</option>
											<option value="AG">Antigua and Barbuda</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="field">City</td>
									<td>Los Angeles</td>
								</tr>
								<tr>
									<td class="field">State</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add State</a></td>
								</tr>
								<tr>
									<td class="field">Website</td>
									<td><a href="javascript:;" class="text-decoration-none fw-bold"><i class="fa fa-plus fa-fw"></i> Add Webpage</a></td>
								</tr>
								<tr>
									<td class="field">Gender</td>
									<td>
										<select class="form-select w-200px" name="gender">
											<option value="male">Male</option>
											<option value="female">Female</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="field">Birthdate</td>
									<td>
										<div class="d-flex align-items-center">
											<select class="form-select w-80px" name="day">
												<option value="04" selected>04</option>
											</select>
											<span class="mx-2">-</span>
											<select class="form-select w-80px" name="month">
												<option value="11" selected>11</option>
											</select>
											<span class="mx-2">-</span>
											<select class="form-select w-100px" name="year">
												<option value="1989" selected>1989</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td class="field">Language</td>
									<td class="with-form-control">
										<select class="form-select w-200px" name="language">
											<option value="" selected>English</option>
										</select>
									</td>
								</tr>
								<tr class="divider">
									<td colspan="2"></td>
								</tr>
								<tr class="highlight">
									<td class="field">&nbsp;</td>
									<td class="">
										<button type="submit" class="btn btn-primary w-150px">Update</button>
										<button type="submit" class="btn btn-white border-0 w-150px ms-5px">Cancel</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- END table -->
				</div>
				<!-- END #profile-about tab -->
				<!-- BEGIN #profile-photos tab -->
				<div class="tab-pane fade" v-bind:class="{ 'show active': tab.photo }">
					<h4 class="mb-3">Photos (70)</h4>
					
					<div class="row gx-1">
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-1-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-2-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-3-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-4-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-5-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-6-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-7-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-8-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-9-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-10-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-11-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-12-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-13-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-14-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-15-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-16-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-17-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-18-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-19-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-20-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-21-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-22-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-23-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-24-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-25-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-26-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-27-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-28-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-29-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-30-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-31-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-32-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-33-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-34-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-35-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-36-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-37-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-38-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-39-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-40-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-41-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-42-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-43-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-44-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-45-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-46-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-47-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-48-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-49-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-50-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-51-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-52-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-53-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-54-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-55-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-56-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-57-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-58-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-59-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-60-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-61-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-62-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-63-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-64-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-65-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-66-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-67-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-68-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-69-thumb.jpg);"></div></a></div>
						<div class="col-lg-1 col-sm-2 col-3"><a href="javascript:;" class="widget-card widget-card-rounded square mb-1"><div class="widget-card-cover" style="background-image: url(/assets/img/gallery/gallery-70-thumb.jpg);"></div></a></div>
					</div>
				</div>
				<!-- END #profile-photos tab -->
				<!-- BEGIN #profile-videos tab -->
				<div class="tab-pane fade" v-bind:class="{ 'show active': tab.video }">
					<h4 class="mb-3">Videos (16)</h4>
					<!-- BEGIN row -->
					<div class="row gx-1">
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=RQ5ljyGg-ig" data-lity>
							<img src="https://img.youtube.com/vi/RQ5ljyGg-ig/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=5lWkZ-JaEOc" data-lity>
							<img src="https://img.youtube.com/vi/5lWkZ-JaEOc/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=9ZfN87gSjvI" data-lity>
							<img src="https://img.youtube.com/vi/9ZfN87gSjvI/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=w2H07DRv2_M" data-lity>
							<img src="https://img.youtube.com/vi/w2H07DRv2_M/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=PntG8KEVjR8" data-lity>
							<img src="https://img.youtube.com/vi/PntG8KEVjR8/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=q8kxKvSQ7MI" data-lity>
							<img src="https://img.youtube.com/vi/q8kxKvSQ7MI/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=cutu3Bw4ep4" data-lity>
							<img src="https://img.youtube.com/vi/cutu3Bw4ep4/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=gCspUXGrraM" data-lity>
							<img src="https://img.youtube.com/vi/gCspUXGrraM/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=COtpTM1MpAA" data-lity>
							<img src="https://img.youtube.com/vi/COtpTM1MpAA/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=8NVkGHVOazc" data-lity>
							<img src="https://img.youtube.com/vi/8NVkGHVOazc/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=QgQ7MWLsw1w" data-lity>
							<img src="https://img.youtube.com/vi/QgQ7MWLsw1w/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=Dmw0ucCv8aQ" data-lity>
							<img src="https://img.youtube.com/vi/Dmw0ucCv8aQ/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=r1d7ST2TG2U" data-lity>
							<img src="https://img.youtube.com/vi/r1d7ST2TG2U/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=WUR-XWBcHvs" data-lity>
							<img src="https://img.youtube.com/vi/WUR-XWBcHvs/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=A7sQ8RWj0Cw" data-lity>
							<img src="https://img.youtube.com/vi/A7sQ8RWj0Cw/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
						<!-- BEGIN col-3 -->
						<div class="col-md-3 col-sm-4 mb-1">
							<a href="https://www.youtube.com/watch?v=IMN2VfiXls4" data-lity>
							<img src="https://img.youtube.com/vi/IMN2VfiXls4/mqdefault.jpg" class="d-block w-100" />
							</a>
						</div>
						<!-- END col-3 -->
					</div>
					<!-- END row -->
				</div>
				<!-- END #profile-videos tab -->
				<!-- BEGIN #profile-friends tab -->
				<div class="tab-pane fade" v-bind:class="{ 'show active': tab.friend }">
					<h4 class="mb-3">Friend List (14)</h4>
					<!-- BEGIN row -->
					<div class="row gx-1">
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-1.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">James Pittman</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-2.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Mitchell Ashcroft</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-3.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Ella Cabena</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-4.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Declan Dyson</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-5.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">George Seyler</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-6.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Patrick Musgrove</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-7.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Taj Connal</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-8.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Laura Pollock</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-9.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Dakota Mannix</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-10.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Timothy Woolley</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-11.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Benjamin Congreve</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-12.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Mariam Maddock</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-13.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Blake Gerrald</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
						<!-- BEGIN col-6 -->
						<div class="col-xl-4 col-lg-6 mb-1">
							<div class="p-2 d-flex align-items-center bg-white rounded">
								<a href="javascript:;">
									<img src="/assets/img/user/user-14.jpg" alt="" class="rounded" width="64" />
								</a>
								<div class="flex-1 ps-3">
									<b class="text-inverse">Gabrielle Bunton</b>
								</div>
								<div>
									<a href="javascript:;" class="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-h fa-lg"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;" class="dropdown-item">Action 1</a>
										<a href="javascript:;" class="dropdown-item">Action 2</a>
										<a href="javascript:;" class="dropdown-item">Action 3</a>
										<div class="dropdown-divider"></div>
										<a href="javascript:;" class="dropdown-item">Action 4</a>
									</div>
								</div>
							</div>
						</div>
						<!-- END col-6 -->
					</div>
					<!-- END row -->
				</div>
				<!-- END #profile-friends tab -->
			</div>
			<!-- END tab-content -->
		</div>
		<!-- END profile-content -->
	</div>
</template>

<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	data() {
		return {
			tab: {
				post: true,
				about: false,
				video: false,
				photo: false,
				friend: false
			}
		}
	},
	methods: {
		show: function(x) {
			this.tab.post = false;
			this.tab.about = false;
			this.tab.video = false;
			this.tab.photo = false;
			this.tab.friend = false;
			
			switch (x) {
				case 'about':
					this.tab.about = true;
					break;
				case 'video':
					this.tab.video = true;
					break;
				case 'photo':
					this.tab.photo = true;
					break;
				case 'friend':
					this.tab.friend = true;
					break;
				default:
					this.tab.post = true;
					break;
			}
		}
	},
	created() {
		AppOptions.appContentClass = 'p-0';
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appContentClass = '';
		next();
	}
}
</script>