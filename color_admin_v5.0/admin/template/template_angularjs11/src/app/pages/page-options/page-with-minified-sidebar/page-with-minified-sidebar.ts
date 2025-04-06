import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-minified-sidebar',
  templateUrl: './page-with-minified-sidebar.html'
})

export class PageSidebarMinified implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarMinified = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarMinified = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarMinified = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarMinified = false;
  }
}
