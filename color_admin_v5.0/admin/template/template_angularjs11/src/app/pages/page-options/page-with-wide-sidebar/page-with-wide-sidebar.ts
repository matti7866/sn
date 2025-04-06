import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-wide-sidebar',
  templateUrl: './page-with-wide-sidebar.html'
})

export class PageSidebarWide implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarWide = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarWide = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarWide = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarWide = false;
  }
}
