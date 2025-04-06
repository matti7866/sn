import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';
import * as global from '../../../config/globals';

@Component({
  selector: 'boxed-layout-with-mixed-menu',
  templateUrl: './boxed-layout-with-mixed-menu.html'
})

export class BoxedLayoutMixedMenu implements OnDestroy {
	global = global;
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appTopMenu = true;
    document.body.className = document.body.className + ' boxed-layout';
  }

  ngOnDestroy() {
    this.appSettings.appTopMenu = false;
    document.body.className = document.body.className.replace('boxed-layout', '');
  }
}`;

  constructor() {
    this.appSettings.appTopMenu = true;
    document.body.className = document.body.className + ' boxed-layout';
  }

  ngOnDestroy() {
    this.appSettings.appTopMenu = false;
    document.body.className = document.body.className.replace('boxed-layout', '');
  }
}
