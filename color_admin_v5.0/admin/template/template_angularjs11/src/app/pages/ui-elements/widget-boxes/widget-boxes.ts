import { Component } from '@angular/core';
import * as global from '../../../config/globals';

@Component({
  selector: 'ui-widget-boxes',
  templateUrl: './widget-boxes.html'
})

export class UIWidgetBoxesPage {
	global = global;

	model = 1;
	
  code1 = `<panel title="Panel (Default)">
  <div>
    <p>Panel Content Here</p>
  </div>
</panel>`;

  code2 = `<panel>
  <ng-container header>
    <h4 class="panel-title">Panel Header with Dropdown</h4>
    <div class="btn-group my-n1" ngbDropdown>
      <button type="button" class="btn btn-success btn-xs">Action</button>
      <button type="button" class="btn btn-success btn-xs dropdown-toggle" ngbDropdownToggle><b class="caret"></b></button>
      <div class="dropdown-menu dropdown-menu-end" ngbDropdownMenu>
        ...
      </div>
    </div>
  </ng-container>
  ...
</panel>`;

  code3 = `<panel>
  <ng-container header>
    <h4 class="panel-title">Panel Header with Radio Button</h4>
    <div class="btn-group btn-group-toggle my-n1">
      ...
    </div>
  </ng-container>
  ...
</panel>`;

	code4 = `<panel>
  <ng-container header>
    <h4 class="panel-title">Panel Header with Progress Bar</h4>
    <div class="progress h-10px bg-gray-700 w-150px">
      <div class="progress-bar progress-bar-striped bg-success progress-bar-animated fw-bold" style="width: 40%">40%</div>
    </div>
  </ng-container>
</panel>`;

  code5 = `<panel>
  <ng-container header>
    <h4 class="panel-title">Panel Header with Badge <span class="badge bg-success ms-1">NEW</span> </h4>
  </ng-container>
</panel>`;

  code6 = `<panel title="Panel with Alert Box" bodyClass="p-0">
  <div class="alert alert-success alert-dismissible fade show rounded-0 mb-0">
    ...
  </div>
</panel>`;

	code7 = `<panel panelClass="panel-hover-icon" title="Hover View Icon">
  ...
</panel>`;

	code8 = `<panel title="Panel with Scrollbar">
  <perfect-scrollbar style="height: 280px">
    ...
  </perfect-scrollbar>
</panel>`;

	code9 = `<panel title="Panel with Toolbar & Footer" bodyClass="p-0">
  <div class="panel-toolbar">
    <div class="btn-group me-5px">
      ...
    </div>
    <div class="btn-group">
      ...
    </div>
  </div>
</panel>`;
	
  code10 = `<panel panelClass="panel-with-tabs" noButton="true" variant="default">
  <ng-container header>
    <h4 class="panel-title">Panel with Tabs</h4>
    <ul class="nav nav-tabs" ngbNav #nav="ngbNav">
      <li class="nav-item" ngbNavItem>
        <a href="javascript:;" ngbNavLink class="nav-link"><i class="fa fa-home"></i> <span class="d-none d-md-inline">Home</span></a>
        <ng-template ngbNavContent>
          <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
        </ng-template>
      </li>
      <li class="nav-item" ngbNavItem>
        <a href="javascript:;" ngbNavLink class="nav-link"><i class="fa fa-user"></i> <span class="d-none d-md-inline">Profile</span></a>
        <ng-template ngbNavContent>
          <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
        </ng-template>
      </li>
    </ul>
  </ng-container>
  <div class="tab-content" [ngbNavOutlet]="nav">
  </div>
</panel>`;

	code11 = `<panel title="Panel (Default)" panelClass="panel-default">
  ...
</panel>`;

	code12 = `<panel title="Panel Success" headerClass="bg-teal-700 text-white">
  ...
</panel>`;

	code13 = `<panel title="Panel Warning" headerClass="bg-orange-700 text-white">
  ...
</panel>`;
	
	code14 = `<panel title="Panel Danger" headerClass="bg-red-700 text-white">
  ...
</panel>`;
	
	code15 = `<panel title="Panel Inverse">
  ...
</panel>`;
	
	code16 = `<panel title="Panel Primary" headerClass="bg-blue-700">
  ...
</panel>`;
	
	code17 = `<panel title="Panel Info" headerClass="bg-cyan-700">
  ...
</panel>`;
	
	code18 = `<panel bodyClass="bg-gray-800 text-white" panelClass="text-white" title="Full Color Panel">
  ...
</panel>`;
	
	code19 = `<panel bodyClass="bg-blue" panelClass="text-white" headerClass="bg-blue-700" title="Full Color Panel">
  ...
</panel>`;
	
	code20 = `<panel bodyClass="bg-teal" panelClass="text-white" headerClass="bg-teal-700" title="Full Color Panel">
  ...
</panel>`;
	
	code21 = `<panel bodyClass="bg-orange" panelClass="text-white" headerClass="bg-orange-700" title="Full Color Panel">
  ...
</panel>`;
	
	code22 = `<panel bodyClass="bg-red" panelClass="text-white" headerClass="bg-red-700" title="Full Color Panel">
  ...
</panel>`;
	
	code23 = `<panel bodyClass="bg-cyan" panelClass="text-white" headerClass="bg-cyan-700" title="Full Color Panel">
  ...
</panel>`;
}
