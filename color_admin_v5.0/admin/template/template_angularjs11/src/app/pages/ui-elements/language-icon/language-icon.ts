import { Component, ViewEncapsulation, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'ui-language-icon',
  templateUrl: './language-icon.html',
  encapsulation: ViewEncapsulation.None,
  styleUrls: [ './language-icon.css' ]
})

export class UILanguageIconPage implements OnDestroy {
  appSettings = appSettings;
  code = `<!-- css -->
@import "~flag-icon-css/css/flag-icon.min.css";

<!-- html -->
<div class="flag-icon flag-icon-ad" title="ad"></div>
`;

  constructor() {
    this.appSettings.appHeaderLanguageBar = true;
  }

  ngOnDestroy() {
    this.appSettings.appHeaderLanguageBar = false;
  }
}
