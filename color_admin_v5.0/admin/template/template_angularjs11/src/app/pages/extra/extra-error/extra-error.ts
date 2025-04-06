import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'extra-error',
  templateUrl: './extra-error.html'
})

export class ExtraErrorPage implements OnDestroy {
  appSettings = appSettings;

  constructor() {
    this.appSettings.appEmpty = true;
  }

  ngOnDestroy() {
    this.appSettings.appEmpty = false;
  }
}
