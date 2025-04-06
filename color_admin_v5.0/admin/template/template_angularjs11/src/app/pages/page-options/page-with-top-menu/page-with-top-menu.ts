import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-top-menu',
  templateUrl: './page-with-top-menu.html'
})

export class PageTopMenu implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appTopMenu = true;
    this.appSettings.appSidebarNone = true;
  }

  ngOnDestroy() {
    this.appSettings.appTopMenu = false;
    this.appSettings.appSidebarNone = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarNone = true;
    this.appSettings.appTopMenu = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarNone = false;
    this.appSettings.appTopMenu = false;
  }
}
