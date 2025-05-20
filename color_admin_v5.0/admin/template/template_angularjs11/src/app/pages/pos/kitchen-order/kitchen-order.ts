import { Component, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'kitchen-order',
  templateUrl: './kitchen-order.html'
})

export class PosKitchenOrderPage implements OnDestroy {
  appSettings = appSettings;
  time = '00:00';

  handleStartTime() {
		var today = new Date();
		var h = today.getHours();
		var m = today.getMinutes();
		var a = (h > 12) ? h - 12 : h;
		var b = (m < 10) ? "0" + m : m;
		var c = (h > 11) ? 'pm' : 'am';

		this.time = a + ":" + b + c;
		setTimeout(this.handleStartTime, 500);
	}

  constructor(private router: Router) {
    this.appSettings.appEmpty = true;
    this.appSettings.appContentFullHeight = true;
    this.handleStartTime();
  }

  ngOnDestroy() {
    this.appSettings.appEmpty = false;
    this.appSettings.appContentFullHeight = false;
  }
}
