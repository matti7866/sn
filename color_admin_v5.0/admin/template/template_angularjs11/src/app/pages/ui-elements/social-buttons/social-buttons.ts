import { Component, ViewEncapsulation } from '@angular/core';

@Component({
  selector: 'ui-social-buttons',
  templateUrl: './social-buttons.html',
  encapsulation: ViewEncapsulation.None,
  styleUrls: [ './social-buttons.css' ]
})

export class UISocialButtonsPage {
	code = `<!-- css -->
@import "~bootstrap-social/bootstrap-social.css";

<!-- html -->
<a href="javascript:;" class="btn btn-social btn-adn">
  <span class="fab fa-adn"></span> Sign in with App.net
</a>
`;
}
