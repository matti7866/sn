import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-without-sidebar',
  templateUrl: './page-without-sidebar.html'
})

export class PageWithoutSidebar implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarNone = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarNone = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarNone = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarNone = false;
  }
}
