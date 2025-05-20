import React from 'react';
import { Link } from 'react-router-dom';
import GoogleMapReact from 'google-map-react';

class ExtraTimeline extends React.Component {
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/extra/timeline">Home</Link></li>
					<li className="breadcrumb-item"><Link to="/extra/timeline">Extra</Link></li>
					<li className="breadcrumb-item active">Timeline</li>
				</ol>
				<h1 className="page-header">Timeline <small>header small text goes here...</small></h1>
				<div className="timeline">
					<div className="timeline-item">
						<div className="timeline-time">
							<span className="date">today</span>
							<span className="time">04:20</span>
						</div>
						<div className="timeline-icon">
							<Link to="/extra/timeline">&nbsp;</Link>
						</div>
						<div className="timeline-content">
							<div className="timeline-header">
								<div className="userimage"><img alt="" src="/assets/img/user/user-1.jpg" /></div>
								<div className="username">
									<Link to="/extra/timeline">John Smith <i className="fa fa-check-circle text-blue ms-1"></i></Link>
									<div className="text-muted fs-12px">8 mins <i className="fa fa-globe-americas opacity-5 ms-1"></i></div>
								</div>
								<div>
									<Link to="/extra/timeline" className="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
										<i className="fa fa-ellipsis-h text-gray-600"></i>
									</Link>
									<div className="dropdown-menu dropdown-menu-end">
										<Link to="/extra/timeline" className="dropdown-item d-flex align-items-center">
											<i className="fa fa-fw fa-bookmark fa-lg"></i> 
											<div className="flex-1 ps-1">
												<div>Save Post</div>
												<div className="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
											</div>
										</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</Link>
									</div>
								</div>
							</div>
							<div className="timeline-body">
								<div className="mb-3">
									<div className="mb-2">
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc faucibus turpis quis tincidunt luctus.
										Nam sagittis dui in nunc consequat, in imperdiet nunc sagittis.
									</div>
									<div className="row gx-1">
										<div className="col-6">
											<div className="ratio ratio-4x3">
												<img alt="" src="/assets/img/gallery/gallery-14.jpg" className="d-block mw-100" />
											</div>
										</div>
										<div className="col-3">
											<div className="ratio ratio-4x3 mb-3px">
												<img alt="" src="/assets/img/gallery/gallery-12.jpg" className="d-block mw-100" />
											</div>
											<div className="ratio ratio-4x3">
												<img alt="" src="/assets/img/gallery/gallery-16.jpg" className="d-block mw-100" />
											</div>
										</div>
										<div className="col-3">
											<div className="ratio ratio-4x3 mb-3px">
												<img alt="" src="/assets/img/gallery/gallery-15.jpg" className="d-block mw-100" />
											</div>
											<div className="ratio ratio-4x3">
												<img alt="" src="/assets/img/gallery/gallery-11.jpg" className="d-block mw-100" />
											</div>
										</div>
									</div>
								</div>
							
								<div className="d-flex align-items-center text-gray-600 mb-2">
									<div className="d-flex align-items-center">
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-danger"></i>
											<i className="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
										</span>
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-blue"></i>
											<i className="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
										</span>
										<span className="ms-1">4.3k</span>
									</div>
									<div className="d-flex align-items-center ms-auto">
										<div>259 Shares</div>
										<div className="ms-3">21 Comments</div>
									</div>
								</div>
							
								<hr className="my-10px" />
								<div className="d-flex align-items-center fw-bold">
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-thumbs-up fa-fw me-3px"></i> Like
									</Link>
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-comments fa-fw me-3px"></i> Comment</Link> 
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-share fa-fw me-3px"></i> Share
									</Link>
								</div>
								<hr className="mt-10px mb-3" />
							
								<form action="" className="d-flex align-items-center">
									<div><img alt="" src="/assets/img/user/user-13.jpg" height="35" className="rounded-pill" /></div>
									<div className="ps-2 flex-1">
										<div className="position-relative">
											<input type="text" className="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
											<div className="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-smile fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-camera fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-film fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-sticky-note fa-fw fa-lg d-block"></i></Link>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div className="timeline-item">
						<div className="timeline-time">
							<span className="date">yesterday</span>
							<span className="time">20:17</span>
						</div>
						<div className="timeline-icon">
							<Link to="/extra/timeline">&nbsp;</Link>
						</div>
						<div className="timeline-content">
							<div className="timeline-header">
								<div className="userimage"><img alt="" src="/assets/img/user/user-2.jpg" /></div>
								<div className="username">
									<Link to="/extra/timeline">Darren Parrase</Link>
									<div className="text-muted fs-12px">24 mins <i className="fa fa-globe-americas opacity-5 ms-1"></i></div>
								</div>
								<div>
									<Link to="/extra/timeline" className="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
										<i className="fa fa-ellipsis-h text-gray-600"></i>
									</Link>
									<div className="dropdown-menu dropdown-menu-end">
										<Link to="/extra/timeline" className="dropdown-item d-flex align-items-center">
											<i className="fa fa-fw fa-bookmark fa-lg"></i> 
											<div className="flex-1 ps-1">
												<div>Save Post</div>
												<div className="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
											</div>
										</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</Link>
									</div>
								</div>
							</div>
							<div className="timeline-body">
								<div className="mb-3">
									<div className="mb-2">Location: United States</div>
									<div className="h-250px">
										<GoogleMapReact defaultCenter={{lat: 25.304304, lng: -90.06591800000001}} defaultZoom={5}></GoogleMapReact>
									</div>
								</div>
							
								<div className="d-flex align-items-center text-gray-600 mb-2">
									<div className="d-flex align-items-center">
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-danger"></i>
											<i className="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
										</span>
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-blue"></i>
											<i className="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
										</span>
										<span className="ms-1">269</span>
									</div>
									<div className="d-flex align-items-center ms-auto">
										<div>2 Shares</div>
										<div className="ms-3">9 Comments</div>
									</div>
								</div>
							
								<hr className="my-10px" />
								<div className="d-flex align-items-center fw-bold">
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-thumbs-up fa-fw me-3px"></i> Like
									</Link>
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-comments fa-fw me-3px"></i> Comment</Link> 
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-share fa-fw me-3px"></i> Share
									</Link>
								</div>
								<hr className="mt-10px mb-3" />
							
								<form action="" className="d-flex align-items-center">
									<div><img alt="" src="/assets/img/user/user-13.jpg" height="35" className="rounded-pill" /></div>
									<div className="ps-2 flex-1">
										<div className="position-relative">
											<input type="text" className="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
											<div className="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-smile fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-camera fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-film fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-sticky-note fa-fw fa-lg d-block"></i></Link>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div className="timeline-item">
						<div className="timeline-time">
							<span className="date">24 February 2021</span>
							<span className="time">08:17</span>
						</div>
						<div className="timeline-icon">
							<Link to="/extra/timeline">&nbsp;</Link>
						</div>
						<div className="timeline-content">
							<div className="timeline-header">
								<div className="userimage"><img alt="" src="/assets/img/user/user-3.jpg" /></div>
								<div className="username">
									<Link to="/extra/timeline">Richard Leong <i className="fa fa-check-circle text-blue ms-1"></i></Link>
									<div className="text-muted fs-12px">12 hours <i className="fa fa-globe-americas opacity-5 ms-1"></i></div>
								</div>
								<div>
									<Link to="/extra/timeline" className="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
										<i className="fa fa-ellipsis-h text-gray-600"></i>
									</Link>
									<div className="dropdown-menu dropdown-menu-end">
										<Link to="/extra/timeline" className="dropdown-item d-flex align-items-center">
											<i className="fa fa-fw fa-bookmark fa-lg"></i> 
											<div className="flex-1 ps-1">
												<div>Save Post</div>
												<div className="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
											</div>
										</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</Link>
									</div>
								</div>
							</div>
							<div className="timeline-body">
								<div className="lead mb-3">
									<i className="fa fa-quote-left fa-fw float-start opacity-5 me-3 mb-3 mt-3 fa-lg"></i>
									Quisque sed varius nisl. Nulla facilisi. Phasellus consequat sapien sit amet nibh molestie placerat. Donec nulla quam, ullamcorper ut velit vitae, lobortis condimentum magna. Suspendisse mollis in sem vel mollis.
									<i className="fa fa-quote-right fa-fw float-end opacity-5 ms-3 mt-n3 fa-lg"></i>
								</div>
							
								<div className="d-flex align-items-center text-gray-600 mb-2">
									<div className="d-flex align-items-center">
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-danger"></i>
											<i className="fa fa-heart fa-stack-1x fa-inverse fs-11px"></i>
										</span>
										<span className="fa-stack fs-10px">
											<i className="fa fa-circle fa-stack-2x text-blue"></i>
											<i className="fa fa-thumbs-up fa-stack-1x fa-inverse fs-11px bottom-0 mb-1px"></i>
										</span>
										<span className="ms-1">550</span>
									</div>
									<div className="d-flex align-items-center ms-auto">
										<div>121 Shares</div>
										<div className="ms-3">40 Comments</div>
									</div>
								</div>
							
								<hr className="my-10px" />
								<div className="d-flex align-items-center fw-bold">
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-thumbs-up fa-fw me-3px"></i> Like
									</Link>
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-comments fa-fw me-3px"></i> Comment</Link> 
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-share fa-fw me-3px"></i> Share
									</Link>
								</div>
								<hr className="mt-10px mb-3" />
							
								<form action="" className="d-flex align-items-center">
									<div><img alt="" src="/assets/img/user/user-13.jpg" height="35" className="rounded-pill" /></div>
									<div className="ps-2 flex-1">
										<div className="position-relative">
											<input type="text" className="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
											<div className="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-smile fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-camera fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-film fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-sticky-note fa-fw fa-lg d-block"></i></Link>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div className="timeline-item">
						<div className="timeline-time">
							<span className="date">10 January 2021</span>
							<span className="time">20:43</span>
						</div>
						<div className="timeline-icon">
							<Link to="/extra/timeline">&nbsp;</Link>
						</div>
						<div className="timeline-content">
							<div className="timeline-header">
								<div className="userimage"><img alt="" src="/assets/img/user/user-4.jpg" /></div>
								<div className="username">
									<Link to="/extra/timeline">Lelouch Wong <i className="fa fa-check-circle text-blue ms-1"></i></Link>
									<div className="text-muted fs-12px">1 days ago <i className="fa fa-globe-americas opacity-5 ms-1"></i></div>
								</div>
								<div>
									<Link to="/extra/timeline" className="btn btn-lg btn-white border-0 rounded-pill w-40px h-40px p-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
										<i className="fa fa-ellipsis-h text-gray-600"></i>
									</Link>
									<div className="dropdown-menu dropdown-menu-end">
										<Link to="/extra/timeline" className="dropdown-item d-flex align-items-center">
											<i className="fa fa-fw fa-bookmark fa-lg"></i> 
											<div className="flex-1 ps-1">
												<div>Save Post</div>
												<div className="mt-n1 text-gray-500"><small>Add this to your saved items</small></div>
											</div>
										</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-edit fa-lg me-1"></i> Edit post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-user fa-lg me-1"></i> Edit audience</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-bell fa-lg me-1"></i> Turn off notifications for this post</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-language fa-lg me-1"></i> Turn off translations</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-calendar-alt fa-lg me-1"></i> Turn date</Link>
										<div className="dropdown-divider"></div>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-archive fa-lg me-1"></i> Move to archive</Link>
										<Link to="/extra/timeline" className="dropdown-item"><i className="fa fa-fw fa-trash-alt fa-lg me-1"></i> Move to Recycle bin</Link>
									</div>
								</div>
							</div>
							<div className="timeline-body">
								<div className="mb-3">
									<h4 className="mb-1">
										795 Folsom Ave, Suite 600 San Francisco, CA 94107
									</h4>
									<div className="mb-2">In hac habitasse platea dictumst. Pellentesque bibendum id sem nec faucibus. Maecenas molestie, augue vel accumsan rutrum, massa mi rutrum odio, id luctus mauris nibh ut leo.</div>
									<div className="row gx-1">
										<div className="col-6">
											<img alt="" src="/assets/img/gallery/gallery-4.jpg" className="mw-100 d-block" />
										</div>
										<div className="col-6">
											<img alt="" src="/assets/img/gallery/gallery-5.jpg" className="mw-100 d-block" />
										</div>
									</div>
								</div>
							
								<hr className="my-10px" />
								<div className="d-flex align-items-center fw-bold">
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-thumbs-up fa-fw me-3px"></i> Like
									</Link>
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-comments fa-fw me-3px"></i> Comment</Link> 
									<Link to="/extra/timeline" className="flex-fill text-decoration-none text-center text-gray-600">
										<i className="fa fa-share fa-fw me-3px"></i> Share
									</Link>
								</div>
								<hr className="mt-10px mb-3" />
							
								<form action="" className="d-flex align-items-center">
									<div><img alt="" src="/assets/img/user/user-13.jpg" height="35" className="rounded-pill" /></div>
									<div className="ps-2 flex-1">
										<div className="position-relative">
											<input type="text" className="form-control rounded-pill ps-3 py-2 fs-13px  bg-gray-200" placeholder="Write a comment..." />
											<div className="position-absolute end-0 top-0 bottom-0 d-flex align-items-center px-2">
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-smile fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-camera fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="fa fa-film fa-fw fa-lg d-block"></i></Link>
												<Link to="/extra/timeline" className="btn bg-none text-gray-600 shadow-none px-1"><i className="far fa-sticky-note fa-fw fa-lg d-block"></i></Link>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div className="timeline-item">
						<div className="timeline-icon">
							<Link to="/extra/timeline">&nbsp;</Link>
						</div>
						<div className="timeline-content">
							<div className="timeline-body">
								<div className="d-flex align-items-center">
									<div className="spinner-border spinner-border-sm me-3" role="status">
										<span className="visually-hidden">Loading...</span>
									</div>
									Loading...
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		)
	}
}

export default ExtraTimeline;