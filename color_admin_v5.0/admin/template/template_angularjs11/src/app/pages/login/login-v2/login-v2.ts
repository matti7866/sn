import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'login-v2',
  templateUrl: './login-v2.html'
})

export class LoginV2Page implements OnInit, OnDestroy {
  bg;
  bgList;
  app;
  appSettings = appSettings;

  constructor(private router: Router) {
    this.appSettings.appEmpty = true;
  }

  ngOnDestroy() {
    this.appSettings.appEmpty = false;
  }

  ngOnInit() {
    this.bg = '/assets/img/login-bg/login-bg-17.jpg';
    this.bgList = [
      { 'bg': '/assets/img/login-bg/login-bg-17.jpg', active: true },
      { 'bg': '/assets/img/login-bg/login-bg-16.jpg' },
      { 'bg': '/assets/img/login-bg/login-bg-15.jpg' },
      { 'bg': '/assets/img/login-bg/login-bg-14.jpg' },
      { 'bg': '/assets/img/login-bg/login-bg-13.jpg' },
      { 'bg': '/assets/img/login-bg/login-bg-12.jpg' }
    ];
  }

  changeBg(list) {
    this.bg = list.bg;
    list.active = true;

    for (let bList of this.bgList) {
			if (bList != list) {
				bList.active = false;
			}
		}
  }

  formSubmit(f: NgForm) {
    this.router.navigate(['dashboard/v3']);
  }
}
