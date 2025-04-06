import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-right-sidebar',
  templateUrl: './page-with-right-sidebar.html'
})

export class PageSidebarRight implements OnDestroy {
  appSettings = appSettings;

  constructor() {
    this.appSettings.appSidebarEnd = true;
  }

  ngOnDestroy() {
    this.appSettings.appSidebarEnd = false;
  }
}
