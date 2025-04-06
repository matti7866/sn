import { Component, ViewEncapsulation, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';
import * as global from '../../../config/globals';

@Component({
  selector: 'email-inbox',
  templateUrl: './email-inbox.html'
})

export class EmailInboxPage implements OnDestroy {
	global = global;
  appSettings = appSettings;
  public isCollapsed = true;

  constructor() {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0';
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}
