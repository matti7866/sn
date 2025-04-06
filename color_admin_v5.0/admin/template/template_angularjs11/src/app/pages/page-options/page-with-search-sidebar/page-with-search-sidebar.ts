import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-search-sidebar',
  templateUrl: './page-with-search-sidebar.html'
})

export class PageSidebarSearch implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarSearch = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarSearch = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarSearch = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarSearch = false;
  }
}

