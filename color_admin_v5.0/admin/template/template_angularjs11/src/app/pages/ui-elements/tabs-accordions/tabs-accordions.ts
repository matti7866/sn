import { Component } from '@angular/core';

@Component({
  selector: 'ui-tabs-accordions',
  templateUrl: './tabs-accordions.html'
})

export class UITabsAccordionsPage {

	code1 = `<ul class="nav nav-tabs" ngbNav #nav="ngbNav">
  <li class="nav-item" ngbNavItem>
    <a href="javascript:;" class="nav-link" ngbNavLink>Tab 1</a>
    <ng-template ngbNavContent>
      ...
    </ng-template>
  </li>
</ul>

<div class="tab-content bg-white p-3 rounded" [ngbNavOutlet]="pill">
</div>`;

	code2 = `<ul class="nav nav-pills" ngbNav #pill="ngbNav">
  <li class="nav-item" ngbNavItem>
    <a href="javascript:;" class="nav-link" ngbNavLink>Pills 1</a>
    <ng-template ngbNavContent>
      ...
    </ng-template>
  </li>
</ul>

<div class="tab-content bg-white p-3 rounded" [ngbNavOutlet]="pill">
</div>`;
	
	code3 = `<ngb-accordion #acc="ngbAccordion" [closeOthers]="true" activeIds="ngb-panel-0">
  <ngb-panel cardClass="border-0 bg-gray-700 text-white rounded-0">
    <ng-template ngbPanelHeader>
      <button ngbPanelToggle class="btn btn-inverse bg-gray-900 d-block w-100 rounded-0 text-start border-0 py-10px px-3">
        <i class="fa fa-circle fa-fw text-blue me-2 fs-8px"></i> Collapsible Group Item #1
      </button>
    </ng-template>
    <ng-template ngbPanelContent>
      ...
    </ng-template>
  </ngb-panel>
</ngb-accordion>
`;
}
