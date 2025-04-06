<template>
	<!-- BEGIN mailbox -->
	<div class="mailbox">
		<!-- BEGIN mailbox-sidebar -->
		<div class="mailbox-sidebar">
			<div class="mailbox-sidebar-header d-flex justify-content-center">
				<b-button v-b-toggle.emailNav data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
					<i class="fa fa-cog"></i>
				</b-button>
				<router-link to="/email/compose" class="btn btn-inverse ps-40px pe-40px btn-sm">
					Compose
				</router-link>
			</div>
			<b-collapse class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
				<!-- BEGIN scrollbar -->
				<vue-custom-scrollbar class="h-100">
					<div class="nav-title"><b>FOLDERS</b></div>
					<ul class="nav nav-inbox">
						<li><router-link to="/email/inbox"><i class="fa fa-hdd fa-lg fa-fw me-2"></i> Inbox <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">52</span></router-link></li>
						<li><router-link to="/email/inbox"><i class="fa fa-flag fa-lg fa-fw me-2"></i> Important</router-link></li>
						<li><router-link to="/email/inbox"><i class="fa fa-envelope fa-lg fa-fw me-2"></i> Sent</router-link></li>
						<li><router-link to="/email/inbox"><i class="fa fa-save fa-lg fa-fw me-2"></i> Drafts</router-link></li>
						<li><router-link to="/email/inbox"><i class="fa fa-trash-alt fa-lg fa-fw me-2"></i> Trash</router-link></li>
					</ul>
					<div class="nav-title"><b>LABEL</b></div>
					<ul class="nav nav-inbox">
						<li><a href="javascript:;"><i class="fa fa-fw fa-lg fs-12px me-2 fa-circle text-inverse"></i> Admin</a></li>
						<li><a href="javascript:;"><i class="fa fa-fw fa-lg fs-12px me-2 fa-circle text-blue"></i> Designer & Employer</a></li>
						<li><a href="javascript:;"><i class="fa fa-fw fa-lg fs-12px me-2 fa-circle text-success"></i> Staff</a></li>
						<li><a href="javascript:;"><i class="fa fa-fw fa-lg fs-12px me-2 fa-circle text-warning"></i> Sponsorer</a></li>
						<li><a href="javascript:;"><i class="fa fa-fw fa-lg fs-12px me-2 fa-circle text-danger"></i> Client</a></li>
					</ul>
				</vue-custom-scrollbar>
				<!-- END scrollbar -->
			</b-collapse>
		</div>
		<!-- END mailbox-sidebar -->
		<!-- BEGIN mailbox-content -->
		<div class="mailbox-content">
			<div class="mailbox-content-header">
				<!-- BEGIN btn-toolbar -->
				<div class="btn-toolbar align-items-center">
					<div class="btn-group me-2">
						<a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-fw fa-envelope"></i> <span class="hidden-xs">Send</span></a>
						<a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-fw fa-paperclip"></i> <span class="hidden-xs">Attach</span></a>
					</div>
					<div>
						<a href="#" class="btn btn-white btn-sm" data-bs-toggle="dropdown"><i class="fa fa-fw fa-ellipsis-h"></i></a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="javascript:;" class="dropdown-item">Save draft</a>
							<a href="javascript:;" class="dropdown-item">Show From</a>
							<a href="javascript:;" class="dropdown-item">Check names</a>
							<a href="javascript:;" class="dropdown-item">Switch to plain text</a>
							<a href="javascript:;" class="dropdown-item">Check for accessibility issues</a>
						</div>
					</div>
					<div class="ms-auto">
						<router-link to="/email/inbox" class="btn btn-white btn-sm"><i class="fa fa-fw fa-times"></i> <span class="hidden-xs">Discard</span></router-link>
					</div>
				</div>
				<!-- END btn-toolbar -->
			</div>
			<div class="mailbox-content-body">
				<!-- BEGIN scrollbar -->
				<vue-custom-scrollbar class="h-100">
					<!-- BEGIN form -->
					<form action="/" method="POST" name="email_to_form" class="mailbox-form">
						<!-- BEGIN mailbox-to -->
						<div class="mailbox-to">
							<label class="control-label">To:</label>
							<input-tag v-model="tagsArray"></input-tag>
							<div class="mailbox-float-link">
								<a href="javascript:;" v-on:click.prevent="handleCc('cc')" v-show="!email.cc" class="me-5px">Cc</a>
								<a href="javascript:;" v-on:click.prevent="handleCc('bcc')" v-show="!email.bcc">Bcc</a>
							</div>
						</div>
						<!-- END mailbox-to -->
						
						<!-- BEGIN email to -->
						<div class="mailbox-to" v-show="email.cc">
							<label class="control-label">Cc:</label>
							<div class="email-to-input">
								<input-tag></input-tag>
							</div>
						</div>
						<!-- END email to -->
						
						<!-- BEGIN email to -->
						<div class="mailbox-to" v-show="email.bcc">
							<label class="control-label">Bcc:</label>
							<div class="email-to-input">
								<input-tag></input-tag>
							</div>
						</div>
						<!-- END email to -->

						<!-- BEGIN mailbox-subject -->
						<div class="mailbox-subject">
							<input type="text" class="form-control" placeholder="Subject" />
						</div>
						<!-- END mailbox-subject -->
						<!-- BEGIN mailbox-input -->
						<div class="mailbox-input">
							<Vueditor v-bind:style="{ height: editor.height + 'px' }"></Vueditor>
						</div>
						<!-- END mailbox-input -->
					</form>
					<!-- END form -->
				</vue-custom-scrollbar>
				<!-- END scrollbar -->
			</div>
			<div class="mailbox-content-footer d-flex align-items-center justify-content-end">
				<button type="submit" class="btn btn-white ps-40px pe-40px me-5px">Discard</button>
				<button type="submit" class="btn btn-primary ps-40px pe-40px">Send</button>
			</div>
		</div>
		<!-- END mailbox-content -->
	</div>
	<!-- END mailbox -->
</template>

<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	data() {
		AppOptions.appContentFullHeight = true;
		AppOptions.appContentClass = 'p-0';

		return { 
			editor: {
				height: (window.innerHeight > 600) ? window.innerHeight - 315 : 300
			},
			tagsArray: [
				'bootstrap@gmail.com', 'google@gmail.com'
			],
			email: {
				cc: false,
				bcc: false
			}
		}
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appContentFullHeight = false;
		AppOptions.appContentClass = '';
		next();
	},
	methods: {
		handleCc: function(type) {
			if (type == 'cc') {
				this.email.cc = true;
			} else if (type == 'bcc') {
				this.email.bcc = true;
			}
		}
	}
}
</script>