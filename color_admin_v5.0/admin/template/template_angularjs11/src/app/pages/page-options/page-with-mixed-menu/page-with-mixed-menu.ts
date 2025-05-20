import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-mixed-menu',
  templateUrl: './page-with-mixed-menu.html'
})

export class PageMixedMenu implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appTopMenu = true;
  }

  ngOnDestroy() {
    this.appSettings.appTopMenu = false;
  }
}`;

  constructor() {
    this.appSettings.appTopMenu = true;
  }

  ngOnDestroy() {
    this.appSettings.appTopMenu = false;
  }
}
