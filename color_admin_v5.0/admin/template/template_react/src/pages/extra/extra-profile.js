import React from 'react';
import { Link } from 'react-router-dom';
import { AppSettings } from './../../config/app-settings.js';
import GoogleMapReact from 'google-map-react';

class ExtraProfile extends React.Component {
	static contextType = AppSettings;

	constructor(props) {
		super(props);

		this.showTab = this.showTab.bind(this);
		this.state = {
			tabPost: true,
			tabAbout: false,
			tabPhoto: false,
			tabVideo: false,
			tabFriend: false
		}
	}
	componentDidMount() {
		this.context.handleSetAppContentClass('p-0');
	}

	componentWillUnmount() {
		this.context.handleSetAppContentClass('');
	}

	showTab(e, tab) {
		e.preventDefault();
		this.setState(state => ({
			tabPost: (tab === 'post') ? true : false,
			tabAbout: (tab === 'about') ? true : false,
			tabPhoto: (tab === 'photo') ? true : false,
			tabVideo: (tab === 'video') ? true : false,
			tabFriend: (tab === 'friend') ? true : false
		}));
	}
	
	render() {
		return (
			<div>
				<div className="profile">
					<div className="profile-header">
						<div className="profile-header-cover"></div>
						<div className="profile-header-content">
							<div className="profile-header-img">
								<img src="/assets/img/user/user-13.jpg" alt="" />
							</div>
							<div className="profile-header-info">
								<h4 className="mt-0 mb-1">Sean Ngu</h4>
								<p className="mb-2">UXUI + Frontend Developer</p>
								<Link to="/extra/profile" className="btn btn-xs btn-yellow">Edit Profile</Link>
							</div>
						</div>
						<ul className="profile-header-tab nav nav-tabs">
							<li className="nav-item"><Link to="/extra/profile" onClick={(e) => this.showTab(e, 'post')} className={'nav-link ' + (this.state.tabPost ? 'active ': '')}>POSTS</Link></li>
							<li className="nav-item"><Link to="/extra/profile" onClick={(e) => this.showTab(e, 'about')} className={'nav-link ' + (this.state.tabAbout ? 'active ': '')}>ABOUT</Link></li>
							<li className="nav-item"><Link to="/extra/profile" onClick={(e) => this.showTab(e, 'photo')} className={'nav-link ' + (this.state.tabPhoto ? 'active ': '')}>PHOTOS</Link></li>
							<li className="nav-item"><Link to="/extra/profile" onClick={(e) => this.showTab(e, 'video')} className={'nav-link ' + (this.state.tabVideo ? 'active ': '')}>VIDEOS</Link></li>
							<li className="nav-item"><Link to="/extra/profile" onClick={(e) => this.showTab(e, 'friend')} className={'nav-link ' + (this.state.tabFriend ? 'active ': '')}>FRIENDS</Link></li>
						</ul>
					</div>
				</div>
				<div className="profile-content">
					<div className="tab-content p-0">
						<div className={'tab-pane fade ' + (this.state.tabPost ? 'show active ': '')}>
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
						<div className={'tab-pane fade ' + (this.state.tabAbout ? 'show active ': '')}>
							<div className="table-responsive form-inline">
								<table className="table table-profile align-middle">
									<thead>
										<tr>
											<th></th>
											<th>
												<h4>Sean Ngu <small>UXUI + Frontend Developer</small></h4>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr className="highlight">
											<td className="field">Mood</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add Mood Message</Link></td>
										</tr>
										<tr className="divider">
											<td colSpan="2"></td>
										</tr>
										<tr>
											<td className="field">Mobile</td>
											<td><i className="fa fa-mobile fa-lg me-5px"></i> +1-(847)- 367-8924 <Link to="/extra/profile" className="ms-5px text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Edit</Link></td>
										</tr>
										<tr>
											<td className="field">Home</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add Number</Link></td>
										</tr>
										<tr>
											<td className="field">Office</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add Number</Link></td>
										</tr>
										<tr className="divider">
											<td colSpan="2"></td>
										</tr>
										<tr className="highlight">
											<td className="field">About Me</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add Description</Link></td>
										</tr>
										<tr className="divider">
											<td colSpan="2"></td>
										</tr>
										<tr>
											<td className="field">Country/Region</td>
											<td>
												<select className="form-select w-200px" name="region" defaultValue="US">
													<option value="US">United State</option>
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
											<td className="field">City</td>
											<td>Los Angeles</td>
										</tr>
										<tr>
											<td className="field">State</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add State</Link></td>
										</tr>
										<tr>
											<td className="field">Website</td>
											<td><Link to="/extra/profile" className="text-decoration-none fw-bold"><i className="fa fa-plus fa-fw"></i> Add Webpage</Link></td>
										</tr>
										<tr>
											<td className="field">Gender</td>
											<td>
												<select className="form-select w-200px" name="gender" defaultValue="">
													<option value="male">Male</option>
													<option value="female">Female</option>
												</select>
											</td>
										</tr>
										<tr>
											<td className="field">Birthdate</td>
											<td>
												<div className="d-flex align-items-center">
													<select className="form-select w-80px" name="day" defaultValue="">
														<option value="04">04</option>
													</select>
													<span className="mx-2">-</span>
													<select className="form-select w-80px" name="month" defaultValue="">
														<option value="11">11</option>
													</select>
													<span className="mx-2">-</span>
													<select className="form-select w-100px" name="year" defaultValue="">
														<option value="1989">1989</option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td className="field">Language</td>
											<td className="with-form-control">
												<select className="form-select w-200px" name="language" defaultValue="">
													<option value="">English</option>
												</select>
											</td>
										</tr>
										<tr className="divider">
											<td colSpan="2"></td>
										</tr>
										<tr className="highlight">
											<td className="field">&nbsp;</td>
											<td className="">
												<button type="submit" className="btn btn-primary w-150px">Update</button>
												<button type="submit" className="btn btn-white border-0 w-150px ms-5px">Cancel</button>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div className={'tab-pane fade ' + (this.state.tabPhoto ? 'show active ': '')}>
							<h4 className="mb-3">Photos (70)</h4>
					
							<div className="row gx-1">
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-1-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-2-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-3-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-4-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-5-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-6-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-7-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-8-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-9-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-10-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-11-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-12-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-13-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-14-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-15-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-16-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-17-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-18-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-19-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-20-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-21-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-22-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-23-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-24-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-25-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-26-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-27-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-28-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-29-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-30-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-31-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-32-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-33-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-34-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-35-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-36-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-37-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-38-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-39-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-40-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-41-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-42-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-43-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-44-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-45-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-46-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-47-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-48-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-49-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-50-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-51-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-52-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-53-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-54-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-55-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-56-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-57-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-58-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-59-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-60-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-61-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-62-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-63-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-64-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-65-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-66-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-67-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-68-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-69-thumb.jpg)'}}></div></Link></div>
								<div className="col-lg-1 col-sm-2 col-3"><Link to="/extra/profile" className="widget-card widget-card-rounded square mb-1"><div className="widget-card-cover" style={{backgroundImage: 'url(/assets/img/gallery/gallery-70-thumb.jpg)'}}></div></Link></div>
							</div>
						</div>
						<div className={'tab-pane fade ' + (this.state.tabVideo ? 'show active ': '')}>
							<h4 className="mb-3">Videos (16)</h4>
							<div className="row gx-1">
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=RQ5ljyGg-ig">
										<img alt="" src="https://img.youtube.com/vi/RQ5ljyGg-ig/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=5lWkZ-JaEOc">
										<img alt="" src="https://img.youtube.com/vi/5lWkZ-JaEOc/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=9ZfN87gSjvI">
										<img alt="" src="https://img.youtube.com/vi/9ZfN87gSjvI/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=w2H07DRv2_M">
										<img alt="" src="https://img.youtube.com/vi/w2H07DRv2_M/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=PntG8KEVjR8">
										<img alt="" src="https://img.youtube.com/vi/PntG8KEVjR8/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=q8kxKvSQ7MI">
										<img alt="" src="https://img.youtube.com/vi/q8kxKvSQ7MI/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=cutu3Bw4ep4">
										<img alt="" src="https://img.youtube.com/vi/cutu3Bw4ep4/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=gCspUXGrraM">
										<img alt="" src="https://img.youtube.com/vi/gCspUXGrraM/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=COtpTM1MpAA">
									<img alt="" src="https://img.youtube.com/vi/COtpTM1MpAA/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=8NVkGHVOazc">
										<img alt="" src="https://img.youtube.com/vi/8NVkGHVOazc/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=QgQ7MWLsw1w">
										<img alt="" src="https://img.youtube.com/vi/QgQ7MWLsw1w/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=Dmw0ucCv8aQ">
										<img alt="" src="https://img.youtube.com/vi/Dmw0ucCv8aQ/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=r1d7ST2TG2U">
										<img alt="" src="https://img.youtube.com/vi/r1d7ST2TG2U/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=WUR-XWBcHvs">
										<img alt="" src="https://img.youtube.com/vi/WUR-XWBcHvs/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=A7sQ8RWj0Cw">
										<img alt="" src="https://img.youtube.com/vi/A7sQ8RWj0Cw/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
								<div className="col-md-3 col-sm-4 mb-1">
									<Link to="https://www.youtube.com/watch?v=IMN2VfiXls4">
										<img alt="" src="https://img.youtube.com/vi/IMN2VfiXls4/mqdefault.jpg" className="d-block w-100" />
									</Link>
								</div>
							</div>
						</div>
						<div className={'tab-pane fade ' + (this.state.tabFriend ? 'show active ': '')}>
							<h4 className="mb-3">Friend List (14)</h4>
							<div className="row gx-1">
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-1.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">James Pittman</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-2.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Mitchell Ashcroft</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-3.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Ella Cabena</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-4.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Declan Dyson</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-5.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">George Seyler</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-6.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Patrick Musgrove</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-7.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Taj Connal</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-8.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Laura Pollock</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-9.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Dakota Mannix</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-10.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Timothy Woolley</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-11.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Benjamin Congreve</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-12.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Mariam Maddock</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-13.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Blake Gerrald</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
								<div className="col-xl-4 col-lg-6 mb-1">
									<div className="p-2 d-flex align-items-center bg-white rounded">
										<Link to="/extra/profile">
											<img src="../assets/img/user/user-14.jpg" alt="" className="rounded" width="64" />
										</Link>
										<div className="flex-1 ps-3">
											<b className="text-inverse">Gabrielle Bunton</b>
										</div>
										<div>
											<Link to="/extra/profile" className="btn btn-white border-0 w-40px h-40px text-gray-500 rounded-pill d-flex align-items-center justify-content-center" data-bs-toggle="dropdown"><i className="fa fa-ellipsis-h fa-lg"></i></Link>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		)
	}
}

export default ExtraProfile;