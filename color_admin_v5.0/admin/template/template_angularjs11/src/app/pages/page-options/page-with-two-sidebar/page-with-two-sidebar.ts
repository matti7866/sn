import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-two-sidebar',
  templateUrl: './page-with-two-sidebar.html',
})

export class PageTwoSidebar implements OnDestroy {
  appSettings = appSettings;
	code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarTwo = true;
    this.appSettings.appSidebarEndToggled = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarTwo = false;
    this.appSettings.appSidebarEndToggled = false;
  }
}`;
  constructor() {
    this.appSettings.appSidebarTwo = true;
    this.appSettings.appSidebarEndToggled = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarTwo = false;
    this.appSettings.appSidebarEndToggled = false;
  }
}
