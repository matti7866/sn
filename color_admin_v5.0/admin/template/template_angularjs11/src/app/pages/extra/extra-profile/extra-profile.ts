import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'extra-profile',
  templateUrl: './extra-profile.html'
})

export class ExtraProfilePage implements OnDestroy {
  appSettings = appSettings;

  tabs = {
    postTab: true,
		aboutTab: false,
		photoTab: false,
		videoTab: false,
		friendTab: false
  };

  showTab(e) {
    for (let key in this.tabs) {
      if (key == e) {
        this.tabs[key] = true;
      } else {
  		  this.tabs[key] = false;
      }
  	}
  };

  constructor() {
    this.appSettings.appContentClass = 'p-0';
  }

  ngOnDestroy() {
    this.appSettings.appContentClass = '';
  }
}
