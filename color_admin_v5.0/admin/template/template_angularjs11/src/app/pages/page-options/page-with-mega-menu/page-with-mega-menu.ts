import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-mega-menu',
  templateUrl: './page-with-mega-menu.html'
})

export class PageMegaMenu implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- page.ts -->
import { Component, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnDestroy {
  constructor() {
    this.appSettings.appHeaderMegaMenu = true;
  }

  ngOnDestroy() {
    this.appSettings.appHeaderMegaMenu = false;
  }
}`;

  constructor() {
    this.appSettings.appHeaderMegaMenu = true;
  }

  ngOnDestroy() {
    this.appSettings.appHeaderMegaMenu = false;
  }
}
