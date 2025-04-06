import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-transparent-sidebar',
  templateUrl: './page-with-transparent-sidebar.html'
})

export class PageSidebarTransparent implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarTransparent = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarTransparent = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarTransparent = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarTransparent = false;
  }
}
