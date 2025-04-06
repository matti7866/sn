/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Bootstrap 5
Version: 5.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin/
	----------------------------
		APPS CONTENT TABLE
	----------------------------

	<!-- ======== GLOBAL SCRIPT SETTING ======== -->
	01. Handle Fixed Header Option
	02. Handle Page Container Show
	03. Handle Tooltip & Popover Activation
	04. Handle Theme Panel Expand
	05. Handle Theme Page Control
	06. Handle Payment Type Selection
	07. Handle Checkout Qty Control
	08. Handle Product Image
	09. Handle Paroller
	10. Handle Check Bootstrap Version

	<!-- ======== APPLICATION SETTING ======== -->
	Application Controller
*/


/* 01. Handle Fixed Header Option
------------------------------------------------ */
var handleHeaderFixedTop = function() {
	if ($('#header[data-fixed-top="true"]').length !== 0) {
		var targetScrollTop = $('#top-nav').height();
		var targetPaddingTop = $('#header').height();
		$(window).on('scroll', function() {
			if ($(window).scrollTop() >= targetScrollTop) {
				$('body').css('padding-top', targetPaddingTop);
				$('#header').addClass('header-fixed');
			} else {
				$('#header').removeClass('header-fixed');
				$('body').css('padding-top', '0');
			}
		});
	}
};


/* 02. Handle Page Container Show
------------------------------------------------ */
var handlePageContainerShow = function() {
	"use strict";

	var hideClass = '';
	var showClass = '';
	var removeClass = '';
	var bootstrapVersion = handleCheckBootstrapVersion();

	if (bootstrapVersion >= 3 && bootstrapVersion < 4) {
		hideClass = 'hide';
		showClass = 'in';
	} else if (bootstrapVersion >= 4 && bootstrapVersion < 5) {
		hideClass = 'd-none';
		showClass = 'show';
	}
	$('#page-container').addClass(showClass);
};


/* 03. Handle Tooltip & Popover Activation
------------------------------------------------ */
var handleTooltipPopoverActivation = function() {
	if ($('[data-bs-toggle=tooltip]').length !== 0) {
		$('[data-bs-toggle=tooltip]').tooltip();
	}
	if ($('[data-bs-toggle=popover]').length !== 0) {
		$('[data-bs-toggle=popover]').popover();
	}
};


/* 04. Handle Theme Panel Expand
------------------------------------------------ */
var handleThemePanelExpand = function() {
	$(document).on('click', '[data-click="theme-panel-expand"]', function() {
		var targetContainer = '.theme-panel';
		var targetClass = 'active';
		if ($(targetContainer).hasClass(targetClass)) {
			$(targetContainer).removeClass(targetClass);
		} else {
			$(targetContainer).addClass(targetClass);
		}
	});
};


/* 05. Handle Theme Page Control
------------------------------------------------ */
var handleThemePageControl = function() {
	if (typeof Cookies !== 'undefined') {
		$(document).on('click', '.theme-list [data-theme]', function(e) {	
			e.preventDefault();
			var targetTheme = $(this).attr('data-theme');
			var targetThemeFile = $(this).attr('data-theme-file');
			
			if ($('#theme-css-link').length === 0) {
				$('head').append('<link href="'+ targetThemeFile +'" rel="stylesheet" id="theme-css-link" />');
			} else {
				$('#theme-css-link').attr('href', targetThemeFile);
			}
			$('.theme-list [data-theme]').not(this).closest('li').removeClass('active');
			$(this).closest('li').addClass('active');
			Cookies.set('theme', $(this).attr('data-theme'));
		});
		
		if (Cookies.get('theme')) {
			if ($('.theme-list').length !== 0) {
				var targetElm = '.theme-list [data-theme="'+ Cookies.get('theme') +'"]';
				$(targetElm).trigger('click');
			}
		}
	}
};


/* 06. Handle Payment Type Selection
------------------------------------------------ */
var handlePaymentTypeSelection = function() {
	$(document).on('click', '[data-click="set-payment"]', function(e) {
		e.preventDefault();
		var targetLi = $(this).closest('li');
		var targetValue = $(this).attr('data-value');
		
		$('[data-click="set-payment"]').closest('li').not(targetLi).removeClass('active');
		$('[data-id="payment-type"]').val(targetValue);
		$(targetLi).addClass('active');
	});
};


/* 07. Handle Checkout Qty Control
------------------------------------------------ */
var handleQtyControl = function() {
	$(document).on('click', '[data-click="increase-qty"]', function(e) {
		e.preventDefault();
		var targetInput = $(this).attr('data-target');
		var targetValue = parseInt($(targetInput).val()) + 1;  
		
		$(targetInput).val(targetValue);
	});
	$('[data-click="decrease-qty"]').click(function(e) {
		e.preventDefault();
		var targetInput = $(this).attr('data-target');
		var targetValue = parseInt($(targetInput).val()) - 1;  
		    targetValue = (targetValue < 0) ? 0 : targetValue;
		
		$(targetInput).val(targetValue);
	});
};


/* 08. Handle Product Image
------------------------------------------------ */
var handleProductImage = function() {
	$(document).on('click', '[data-click="show-main-image"]', function(e) {
		e.preventDefault();

		var targetContainer = '[data-id="main-image"]';
		var targetImage = '<img src="'+ $(this).attr('data-url') +'" />';
		var targetLi = $(this).closest('li');

		$(targetContainer).html(targetImage);
		$(targetLi).addClass('active');
		$('[data-click="show-main-image"]').closest('li').not(targetLi).removeClass('active');
	});
};


/* 09. Handle Paroller
------------------------------------------------ */
var handleParoller = function() {
	if (typeof $.fn.paroller !== 'undefined') {
		if ($('[data-paroller="true"]').length !== 0) {
			$('[data-paroller="true"]').paroller();
		}
	}
};


/* 10. Handle Check Bootstrap Version
------------------------------------------------ */
var handleCheckBootstrapVersion = function() {
	return parseInt($.fn.tooltip.Constructor.VERSION);
};


/* Application Controller
------------------------------------------------ */
var App = function () {
	"use strict";
	
	return {
		//main function
		init: function () {
			handleHeaderFixedTop();
			handlePageContainerShow();
			handleTooltipPopoverActivation();
			handleThemePanelExpand();
			handleThemePageControl();
			handlePaymentTypeSelection();
			handleQtyControl();
			handleProductImage();
			handleParoller();
		}
	};
}();

$(document).ready(function() {
	App.init();
});