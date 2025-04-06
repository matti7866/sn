import { Component, OnDestroy, Renderer2 } from '@angular/core';
import appSettings from '../../../config/app-settings';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import { CountdownConfig } from 'ngx-countdown';

@Component({
  selector: 'extra-coming-soon',
  templateUrl: './extra-coming-soon.html'
})

export class ExtraComingSoonPage implements OnDestroy {
  appSettings = appSettings;
	
	countdownConfig: CountdownConfig = {
		leftTime: 100000 * 10,
    format: 'dd:HH:mm:ss',
    prettyText: (text) => {
    	var timeHtml = text
        .split(':')
        .map((v) => `<span class="countdown-section"><span class="countdown-amount">${v}</span><span class="countdown-period">Days</span></span>`)
        .join('');
    	
    	return '<div class="is-countdown"><span class="countdown-row countdown-show4">'+ timeHtml +'</span></div>';
    }
  };

  constructor(private router: Router, private renderer: Renderer2) {
    this.appSettings.appEmpty = true;
  }

  ngOnDestroy() {
    this.appSettings.appEmpty = false;
  }

  formSubmit(f: NgForm) {
    this.router.navigate(['dashboard/v3']);
  }
}
