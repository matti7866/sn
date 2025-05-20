import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-light-sidebar',
  templateUrl: './page-with-light-sidebar.html'
})

export class PageSidebarLight implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appSidebarLight = true;
    this.appSettings.appHeaderInverse = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarLight = false;
    this.appSettings.appHeaderInverse = false;
  }
}`;

  constructor() {
    this.appSettings.appSidebarLight = true;
    this.appSettings.appHeaderInverse = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarLight = false;
    this.appSettings.appHeaderInverse = false;
  }
}
