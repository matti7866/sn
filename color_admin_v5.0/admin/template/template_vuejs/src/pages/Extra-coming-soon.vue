<template>
	<!-- BEGIN coming-soon -->
	<div class="coming-soon">
		<!-- BEGIN coming-soon-header -->
		<div class="coming-soon-header">
			<div class="bg-cover"></div>
			<div class="brand">
				<span class="logo"></span> <b>Color</b> Admin
			</div>
			<div class="desc">
				Our website is almost there and it’s rebuilt from scratch! A lot of great new features <br />and improvements are coming.
			</div>
			<div class="timer">
				<div id="timer" class="is-countdown">
					<countdown :time="2 * 24 * 60 * 60 * 1000">
						<template slot-scope="props">
							<span class="countdown-row countdown-show4">
								<span class="countdown-section">
									<span class="countdown-amount">{{ props.days }}</span>
									<span class="countdown-period">Days</span>
								</span>
								<span class="countdown-section">
									<span class="countdown-amount">{{ props.hours }}</span>
									<span class="countdown-period">Hours</span>
								</span>
								<span class="countdown-section">
									<span class="countdown-amount">{{ props.minutes }}</span>
									<span class="countdown-period">Minutes</span>
								</span>
								<span class="countdown-section">
									<span class="countdown-amount">{{ props.seconds }}</span>
									<span class="countdown-period">Seconds</span>
								</span>
							</span>
						</template>
					</countdown>
				</div>
			</div>
		</div>
		<!-- END coming-soon-header -->
		<!-- BEGIN coming-soon-content -->
		<div class="coming-soon-content">
			<div class="desc">
				We are launching a closed <b>beta</b> soon!<br /> Sign up to try it before others and be the first to know when we <b>launch</b>.
			</div>
			<div class="input-group input-group-lg mx-auto mb-2">
				<span class="input-group-text border-0  bg-gray-200"><i class="fa fa-envelope"></i></span>
				<input type="text" class="form-control fs-13px border-0 shadow-none ps-0 bg-gray-200" placeholder="Email Address" />
				<button type="button" class="btn fs-13px btn-inverse">Notify Me</button>
			</div>
			<p class="text-gray-500 mb-5">We don't spam. Your email address is secure with us.</p>
			<p>
				<span class="me-2">Follow us on</span>
				<a href="javascript:;" class="mx-1 text-decoration-none text-inverse">
					<i class="fab fa-twitter fa-lg fa-fw text-info"></i> Twitter
				</a> 
				<a href="javascript:;" class="mx-1 text-decoration-none text-inverse">
					<i class="fab fa-facebook-square fa-lg fa-fw text-blue"></i> Facebook
				</a>
			</p>
		</div>
		<!-- END coming-soon-content -->
	</div>
	<!-- END coming-soon -->
</template>

<script>
import AppOptions from '../config/AppOptions.vue'

export default {
	created() {
		AppOptions.appEmpty = true;
	},
	beforeRouteLeave (to, from, next) {
		AppOptions.appEmpty = false;
		next();
	}
}
</script>