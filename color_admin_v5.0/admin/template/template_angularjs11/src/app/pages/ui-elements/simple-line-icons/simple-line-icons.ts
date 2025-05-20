import { Component, ViewEncapsulation } from '@angular/core';

@Component({
  selector: 'ui-simple-line-icons',
  templateUrl: './simple-line-icons.html',
  encapsulation: ViewEncapsulation.None,
  styleUrls: [ './simple-line-icons.css' ]
})

export class UISimpleLineIconsPage {
	code = `<!-- css -->
@import "~simple-line-icons/css/simple-line-icons.css";

<!-- icon -->
<i class="icon-user"></i>`;
}
