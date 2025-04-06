import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../config/app-settings';

@Component({
    selector: 'map',
    templateUrl: './map.html'
})

export class MapPage implements OnDestroy {
  appSettings = appSettings;

  constructor() {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0 position-relative';
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}
