@extends('layouts.default')

@section('title', 'Form X-Editable Page')

@push('css')
	<link href="/assets/plugins/x-editable-bs4/dist/bootstrap4-editable/css/bootstrap-editable.css" rel="stylesheet" />
	<link href="/assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.css" rel="stylesheet" />
	<link href="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
	<script src="/assets/plugins/x-editable-bs4/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
	<script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.js"></script>
	<script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
	<script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
	<script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/wysihtml5/wysihtml5.js"></script>
	<script src="/assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script src="/assets/plugins/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js"></script>
	<script src="/assets/plugins/select2/dist/js/select2.full.min.js"></script>
	<script src="/assets/plugins/jquery-mockjax/dist/jquery.mockjax.min.js"></script>
	<script src="/assets/plugins/moment/min/moment.min.js"></script>
	<script src="/assets/js/demo/form-editable.demo.js"></script>
	<script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
	<script src="/assets/js/demo/render.highlight.js"></script>
	<script src="/assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
	<script src="/assets/js/demo/render.highlight.js"></script>
@endpush

@section('content')
	<!-- BEGIN breadcrumb -->
	<ol class="breadcrumb float-xl-end">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item"><a href="javascript:;">Form Stuff</a></li>
		<li class="breadcrumb-item active">X-Editable</li>
	</ol>
	<!-- END breadcrumb -->
	<!-- BEGIN page-header -->
	<h1 class="page-header">X-Editable <small>header small text goes here...</small></h1>
	<!-- END page-header -->
	
	<!-- BEGIN row -->
	<div class="row">
		<!-- BEGIN col-8 -->
		<div class="col-xl-8">
			<!-- BEGIN panel -->
			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Form Editable</h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<!-- BEGIN table-responsive -->
				<div class="table-responsive">
					<table id="user" class="table table-bordered table-panel mb-0">
						<thead>
							<tr>
								<th width="20%">Field Name</th>
								<th width="40%">Field Value</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="bg-light">Username</td>
								<td><a href="javascript:;" id="username" data-type="text" data-pk="1" data-title="Enter Username">superuser </a></td>
								<td><span class="text-black-lighter">Simple text field </span></td>
							</tr>
							<tr>
								<td class="bg-light">Firstname</td>
								<td><a href="javascript:;" id="firstname" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter your Firstname"></a></td>
								<td><span class="text-black-lighter">Required text field, originally empty </span></td>
							</tr>
							<tr>
								<td class="bg-light">Sex</td>
								<td><a href="javascript:;" id="sex" data-type="select" data-pk="1" data-value="" data-title="Select sex"></a></td>
								<td><span class="text-black-lighter">Select, loaded from js array. Custom display</span></td>
							</tr>
							<tr>
								<td class="bg-light">Group</td>
								<td><a href="javascript:;" id="group" data-type="select" data-pk="1" data-value="5" data-source="/groups" data-title="Select group">Admin</a></td>
								<td><span class="text-black-lighter">Select, loaded from server. <strong>No buttons</strong> mode </span></td>
							</tr>
							<tr>
								<td class="bg-light">Error While Loading</td>
								<td><a href="javascript:;" id="status" data-type="select" data-pk="1" data-value="0" data-source="/status" data-title="Select status">Active </a></td>
								<td><span class="text-black-lighter">Error when loading list items</span></td>
							</tr>
							<tr>
								<td class="bg-light">Plan vacation?</td>
								<td><a href="javascript:;" id="vacation" data-type="date" data-viewformat="dd.mm.yyyy" data-pk="1" data-placement="right" data-title="When you want vacation to start?">25.02.2021</a></td>
								<td><span class="text-black-lighter">Datepicker </span></td>
							</tr>
							<tr>
								<td class="bg-light">Date of birth</td>
								<td><a href="javascript:;" id="dob" data-type="combodate" data-value="1984-05-15" data-format="YYYY-MM-DD" data-viewformat="DD/MM/YYYY" data-template="D / MMM / YYYY" data-pk="1" data-title="Select Date of birth"></a></td>
								<td><span class="text-black-lighter">Date field (combodate) </span></td>
							</tr>
							<tr>
								<td class="bg-light">Setup event</td>
								<td><a href="javascript:;" id="event" data-type="combodate" data-template="D MMM YYYY HH:mm" data-format="YYYY-MM-DD HH:mm" data-viewformat="MMM D, YYYY, HH:mm" data-pk="1" data-title="Setup event date and time"></a></td>
								<td><span class="text-black-lighter">Datetime field (combodate)</span></td>
							</tr>
							<tr>
								<td class="bg-light">Meeting start</td>
								<td><a href="javascript:;" id="meeting_start" data-type="datetime" data-pk="1" data-url="/post" data-placement="right" data-title="Set date & time">15/03/2021 12:45</a></td>
								<td><span class="text-black-lighter">Bootstrap datetime</span></td>
							</tr>
							<tr>
								<td class="bg-light">Comments</td>
								<td>
									<a href="javascript:;" id="comments" data-type="textarea" data-pk="1" data-placeholder="Your comments here..." data-original-title="Enter comments">awesome<br />user!</a></td>
								<td>
									<span class="text-black-lighter">
									Textarea. Buttons below. Submit by <i>ctrl+enter</i>
									</span>
								</td>
							</tr>
							<tr>
								<td class="bg-light">Type State</td>
								<td><a href="javascript:;" id="state" data-type="typeaheadjs" data-pk="1" data-placement="right" data-title="Start typing State.."></a></td>
								<td><span class="text-black-lighter">Twitter typeahead.js</span></td>
							</tr>
							<tr>
								<td class="bg-light">Fresh fruits</td>
								<td><a href="javascript:;" id="fruits" data-type="checklist" data-value="1,2" data-title="Select fruits"></a></td>
								<td><span class="text-black-lighter">Checklist</span></td>
							</tr>
							<tr>
								<td class="bg-light">Tags</td>
								<td><a href="javascript:;" id="tags" data-type="select2" data-pk="1" data-title="Enter tags">html, javascript </a></td>
								<td><span class="text-black-lighter">Select2 (tags mode) </span></td>
							</tr>
							<tr>
								<td class="bg-light">Country</td>
								<td><a href="javascript:;" id="country" data-type="select2" data-pk="1" data-value="BS" data-title="Select country"></a></td>
								<td><span class="text-black-lighter">Select2 (dropdown mode)</span></td>
							</tr>
							<tr>
								<td class="bg-light">Address</td>
								<td><a href="javascript:;" id="address" data-type="address" data-pk="1" data-title="Please, fill address"></a></td>
								<td><span class="text-black-lighter">Your custom input, several fields</span></td>
							</tr>
							<tr>
								<td class="bg-light">Notes</td>
								<td>
									<div id="note" data-pk="1" data-type="wysihtml5" data-toggle="manual" data-title="Enter notes">
										<h3>WYSIWYG</h3>
										 WYSIWYG means <i>What You See Is What You Get</i>.<br />
										 But may also refer to:
										<ul>
											<li>
												 WYSIWYG (album), a 2000 album by Chumbawamba
											</li>
											<li>
												 "Whatcha See is Whatcha Get", a 1971 song by The Dramatics
											</li>
											<li>
												 WYSIWYG Film Festival, an annual Christian film festival
											</li>
										</ul>
										<p><i>Source:</i><a href="http://en.wikipedia.org/wiki/WYSIWYG_%28disambiguation%29">wikipedia.org</a></p>
									</div>
								</td>
								<td>
									<a href="javascript:;" id="pencil"><i class="fa fa-pencil"></i> [edit]</a>
									<br />
									<span class="text-black-lighter">Wysihtml5 (bootstrap only).<br />Toggle by another element</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- END table-responsive -->
				<!-- BEGIN hljs-wrapper -->
				<div class="hljs-wrapper">
				<pre><code class="html">&lt;!-- required files --&gt;
&lt;link href="/assets/plugins/x-editable-bs4/dist/bootstrap4-editable/css/bootstrap-editable.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" /&gt;
&lt;link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" /&gt;

&lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/x-editable-bs4/dist/bootstrap4-editable/js/bootstrap-editable.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/typeaheadjs.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/x-editable-bs4/dist/inputs-ext/wysihtml5/wysihtml5.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/select2/dist/js/select2.full.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/jquery-mockjax/dist/jquery.mockjax.min.js"&gt;&lt;/script&gt;
&lt;script src="/assets/plugins/moment/min/moment.min.js"&gt;&lt;/script&gt;

&lt;!-- html --&gt;
&lt;a href="javascript:;" id="username" data-type="text" data-pk="1" data-title="Enter Username"&gt;
  superuser
&lt;/a&gt;

&lt;!-- script --&gt;
&lt;script&gt;
  $.fn.editable.defaults.mode = 'inline';
  $.fn.editable.defaults.inputclass = 'form-control input-sm';
  $.fn.editable.defaults.url = '/post';
  
  $('#username').editable();
&lt;/script&gt;</code></pre>
				</div>
				<!-- END hljs-wrapper -->
			</div>
			<!-- END panel -->
		</div>
		<!-- END col-8 -->
		<!-- BEGIN col-4 -->
		<div class="col-xl-4">
			<div class="alert alert-yellow alert-dismissible fade show">
				<div class="fs-14px"><i class="fa fa-info-circle"></i> <b>Popover</b> edit mode is not supported in Bootstrap 5 yet.</div>
				<hr />
				<div>We are now implement it with the inline edit mode until the plugin support with the popover in Bootstrap 5.</div>
				<button class="btn-close" data-bs-dismiss="alert"></button>
			</div>
			<h4 class="mb-15px"><b>Console</b><br /><small>(all ajax requests here are emulated)</small></h4>
			<div>
				<textarea id="console" rows="20" class="form-control" placeholder="start click on the editable text field and made the changes"></textarea>
			</div>
		</div>
		<!-- END col-4 -->
	</div>
	<!-- END row -->
@endsection