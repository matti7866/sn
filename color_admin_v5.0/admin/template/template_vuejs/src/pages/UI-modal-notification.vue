<template>
	<div>
		<!-- BEGIN breadcrumb -->
		<ol class="breadcrumb float-xl-end">
			<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
			<li class="breadcrumb-item"><a href="javascript:;">UI Elements</a></li>
			<li class="breadcrumb-item active">Modal & Notification</li>
		</ol>
		<!-- END breadcrumb -->
		<!-- BEGIN page-header -->
		<h1 class="page-header">Modal & Notification <small>header small text goes here...</small></h1>
		<!-- END page-header -->
		
		<!-- BEGIN row -->
		<div class="row">
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel>
					<template slot="header">
						<h4 class="panel-title">Vue Notification <span class="badge bg-success">NEW</span></h4>
					</template>
					
					<table class="table">
						<thead>
							<tr>
								<th>Description</th>
								<th>Demo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b class="text-inverse">Bottom Left</b> Success Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-left', 'success')" class="btn btn-sm btn-inverse">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Bottom Left</b> Warning Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-left', 'warn')" class="btn btn-sm btn-primary">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Bottom Left</b> Error Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-left', 'error')" class="btn btn-sm btn-info">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Bottom Right</b> Success Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-right', 'success')" class="btn btn-sm btn-success">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Bottom Right</b> Warning Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-right', 'warn')" class="btn btn-sm btn-warning">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Bottom Right</b> Error Animation</td>
								<td><a href="javascript:;" v-on:click="show('bottom-right', 'error')" class="btn btn-sm btn-danger">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Top Center</b> Custom style</td>
								<td><a href="javascript:;" v-on:click="show('custom-style')" class="btn btn-sm btn-default">Demo</a></td>
							</tr>
							<tr>
								<td><b class="text-inverse">Top Left</b> Custom template</td>
								<td><a href="javascript:;" v-on:click="show('custom-template')" class="btn btn-sm btn-white">Demo</a></td>
							</tr>
						</tbody>
					</table>
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;a v-on:click="show('bottom-left', 'success')"&gt;Success Message&lt;/&gt;
&lt;a v-on:click="show('bottom-left', 'warn')"&gt;Warning Message&lt;/&gt;
&lt;a v-on:click="show('bottom-right', 'error')"&gt;Error Message&lt;/&gt;

&lt;!-- bottom left vue-notification --&gt;
&lt;notifications group="bottom-left" position="bottom left" :speed="500" /&gt;

&lt;script&gt;
  export default {
    methods: {
      show (group, type = '') {
        const text = `
          This is notification text!
          &lt;br&gt;
          Date: ${new Date()}
        `
        this.$notify({
          group,
          title: `Test ${type} notification #${this.id++}`,
          text,
          type,
          data: {
            randomNumber: Math.random()
          }
        })
      }
    }
  }
&lt;/script&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
			<!-- BEGIN col-6 -->
			<div class="col-xl-6">
				<!-- BEGIN panel -->
				<panel title="Modal">
					<table class="table">
						<thead>
							<tr>
								<th>Description</th>
								<th>Demo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Default Modal Dialog Box.</td>
								<td><button v-b-modal.modalDialog class="btn btn-sm btn-success">Demo</button></td>
							</tr>
							<tr>
								<td>Modal Dialog Box with fade out animation.</td>
								<td><button v-b-modal.modalWithoutAnimation class="btn btn-sm btn-default">Demo</button></td>
							</tr>
							<tr>
								<td>Modal Dialog Box with full width white background.</td>
								<td><button v-b-modal.modalMessage class="btn btn-sm btn-primary">Demo</button></td>
							</tr>
							<tr>
								<td>Modal Dialog Box with alert message.</td>
								<td><button v-b-modal.modalAlert class="btn btn-sm btn-danger">Demo</button></td>
							</tr>
						</tbody>
					</table>
					<!-- #modal-dialog -->
					<b-modal id="modalDialog" cancel-variant="default">
						<template slot="modal-header">
							<h4 class="modal-title">Modal Dialog</h4>
							<a class="btn-close" @click="$bvModal.hide('modalDialog')"></a>
						</template>
						<p>
							Modal body content here...
						</p>
					</b-modal>
					
					<!-- #modal-without-animation -->
					<b-modal id="modalWithoutAnimation" no-fade cancel-variant="default">
						<template slot="modal-header">
							<h4 class="modal-title">Modal Without Animation</h4>
							<a class="btn-close" @click="$bvModal.hide('modalWithoutAnimation')"></a>
						</template>
						<p>
							Modal body content here...
						</p>
					</b-modal>
					
					<!-- #modal-message -->
					<b-modal id="modalMessage" cancel-variant="default" class="modal-message">
						<template slot="modal-header">
							<h4 class="modal-title">Modal Message Header</h4>
							<a class="btn-close" @click="$bvModal.hide('modalMessage')"></a>
						</template>
						<p>Text in a modal</p>
						<p>Do you want to turn on location services so GPS can use your location ?</p>
					</b-modal>
					
					<!-- #modal-alert -->
					<b-modal id="modalAlert" cancel-variant="white" ok-variant="danger">
						<template slot="modal-header">
							<h4 class="modal-title">Alert Header</h4>
							<a class="btn-close" @click="$bvModal.hide('modalAlert')"></a>
						</template>
						<div class="alert alert-danger m-b-0">
							<h5><i class="fa fa-info-circle"></i> Alert Header</h5>
							<p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
						</div>
					</b-modal>
					
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;!-- #modal-dialog --&gt;
&lt;b-modal id="modalDialog" cancel-variant="default"&gt;
  &lt;template slot="modal-header"&gt;
    &lt;h4 class="modal-title"&gt;Modal Dialog&lt;/h4&gt;
    &lt;a class="btn-close" @click="$bvModal.hide('modalDialog')"&gt;&lt;/a&gt;
  &lt;/template&gt;
  &lt;p&gt;
    Modal body content here...
  &lt;/p&gt;
&lt;/b-modal&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
				<!-- BEGIN panel -->
				<panel title="Modal">
					<template slot="header">
						<h4 class="panel-title">Bootstrap SweetAlert <span class="label label-success">NEW</span></h4>
					</template>
					
					<p class="lead fs-20px mb-10px text-inverse">
						SweetAlert for Bootstrap. A beautiful replacement for JavaScript's "alert"
					</p>
					<hr />
					<p class="">
						Try any of those!
					</p>
					<a href="javascript:;" v-on:click="swalNotification('question')" class="btn btn-primary me-1">Primary</a>
					<a href="javascript:;" v-on:click="swalNotification('info')" class="btn btn-info me-1">Info</a>
					<a href="javascript:;" v-on:click="swalNotification('success')" class="btn btn-success me-1">Success</a>
					<a href="javascript:;" v-on:click="swalNotification('warning')" class="btn btn-warning me-1">Warning</a>
					<a href="javascript:;" v-on:click="swalNotification('error')" class="btn btn-danger me-1">Danger</a>
					
					<!-- BEGIN hljs-wrapper -->
					<div class="hljs-wrapper" v-highlight slot="outsideBody">
						<pre><code class="html">&lt;a v-on:click="swalNotification('question')" class="btn btn-primary"&gt;Primary&lt;/a&gt;

&lt;script&gt;
  export default {
    methods: {
      swalNotification(swalType) {
        var btnClass = (swalType == 'error') ? 'danger' : swalType;
          btnClass = (swalType == 'question') ? 'primary' : btnClass;
        this.$swal({
          title: 'Are you sure?',
          text: 'You will not be able to recover this imaginary file!',
          type: swalType,
          showCancelButton: true,
          buttonsStyling: false,
          confirmButtonText: swalType.charAt(0).toUpperCase() + swalType.substr(1).toLowerCase(),
          cancelButtonText: 'Cancel',
          confirmButtonClass: 'btn me-5px btn-'+ btnClass +'',
          cancelButtonClass: 'btn btn-default',
        })
      }
    }
  }
&lt;/script&gt;</code></pre>
					</div>
					<!-- END hljs-wrapper -->
				</panel>
				<!-- END panel -->
			</div>
			<!-- END col-6 -->
		</div>
		<!-- END row -->
		
		<!-- bottom left vue-notification -->
		<notifications group="bottom-left" position="bottom left" :speed="500" />

		<!-- bottom right animation example -->
		<notifications group="bottom-right" position="bottom right" :speed="500" />

		<!-- Custom style example -->
		<notifications group="custom-style" position="top center" classes="n-light" :max="3" :width="400"/>

		<!-- Custom template example -->
		<notifications group="custom-template" :duration="5000" :width="500" animation-name="v-fade-left" position="top left">
			<template slot="body" slot-scope="props">
				<div class="notification custom">
					<div class="custom-icon">
						<i class="icon fa fa-clipboard-check"></i>
					</div>
					<div class="custom-content">
						<div class="custom-title">
							{{props.item.title}}
							<p>
								Random number: {{props.item.data.randomNumber}}
							</p>
						</div>
						<div class="custom-text" v-html="props.item.text"></div>
					</div>
					<div class="custom-close" @click="props.close">
						<i class="icon fa fa-times"></i>
					</div>
				</div>
			</template>
		</notifications>
	</div>
</template>

<script>
	export default {
		methods: {
			show (group, type = '') {
				const text = `
					This is notification text!
					<br>
					Date: ${new Date()}
				`
				this.$notify({
					group,
					title: `Test ${type} notification #${this.id++}`,
					text,
					type,
					data: {
						randomNumber: Math.random()
					}
				})
			},
			clean (group) {
				this.$notify({ group, clean: true })
			},
			swalNotification(swalType) {
				var btnClass = (swalType == 'error') ? 'danger' : swalType;
					btnClass = (swalType == 'question') ? 'primary' : btnClass;
				this.$swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					type: swalType,
					showCancelButton: true,
					buttonsStyling: false,
					confirmButtonText: swalType.charAt(0).toUpperCase() + swalType.substr(1).toLowerCase(),
					cancelButtonText: 'Cancel',
					confirmButtonClass: 'btn me-5px btn-'+ btnClass +'',
					cancelButtonClass: 'btn btn-default',
				})
			}
		}
	}
</script>