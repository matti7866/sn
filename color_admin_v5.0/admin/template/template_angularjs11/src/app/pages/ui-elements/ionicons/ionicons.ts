import { Component, ViewEncapsulation } from '@angular/core';

@Component({
	selector: 'ui-ionicons',
	templateUrl: './ionicons.html',
	encapsulation: ViewEncapsulation.None,
	styleUrls: [ './ionicons.css']
})

export class UIIoniconsPage {
	code = `<!-- css -->
@import "~ionicons/dist/css/ionicons.min.css";

<!-- material -->
<i class="ion ion-md-add-circle-outline"></i>

<!-- ios -->
<i class="ion ion-ios-add-circle-outline"></i>

<!-- logo -->
<i class="ion ion-logo-android"></i>`;
}
