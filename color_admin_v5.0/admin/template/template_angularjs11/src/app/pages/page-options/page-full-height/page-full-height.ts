import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';
import * as global from '../../../config/globals';

@Component({
  selector: 'page-full-height',
  templateUrl: './page-full-height.html'
})

export class PageFullHeight implements OnDestroy {
	global = global;
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0';
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}`;

  constructor() {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0';
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}
