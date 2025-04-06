import { Component } from '@angular/core';

@Component({
  selector: 'ui-buttons',
  templateUrl: './buttons.html'
})

export class UIButtonsPage {
	code1 = `<!-- button -->
<button type="button" class="btn btn-primary">
  Primary
</button>

<!-- outline button -->
<button type="button" class="btn btn-outline-primary">
  Outline Primary
</button>`;
	
	code2 = `<!-- dropdown -->
<div class="btn-group" ngbDropdown>
  <button class="btn btn-default">Dropdown</button>
  <button class="btn btn-default dropdown-toggle" ngbDropdownToggle>
    <i class="fa fa-caret-down"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end" ngbDropdownMenu>
    ...
  </ul>
</div>

<!-- dropup -->
<div class="btn-group dropup">
  ...
</div>`;
	
	code3 = `<a href="#" class="btn btn-primary btn-lg">Large Button</a>
<a href="#" class="btn btn-primary">Default Button</a>
<a href="#" class="btn btn-primary btn-sm">Small Button</a>
<a href="#" class="btn btn-primary btn-xs">Extra Small</a>`;
	
	code4 = `<a href="#" class="btn btn-default disabled">Disabled Button</a>
<a href="#" class="btn btn-default active">Active Button</a>
<a href="#" class="btn btn-primary d-block">Block Button</a>`;
	
	code5 = `<a href="#" class="btn btn-lg btn-primary">
  <span class="d-flex align-items-center text-left">
    <i class="fab fa-twitter fa-3x me-3 text-black"></i>
    <span>
      <span class="d-block"><b>Twitter Bootstrap</b></span>
      <span class="d-block fs-12px opacity-7">Version 4.0</span>
    </span>
  </span>
</a>`;
	
	code6 = `<div class="btn-group">
  <button class="btn btn-white">Left</button>
  <button class="btn btn-white active">Middle</button>
  <button class="btn btn-white">Right</button>
</div>

<div class="btn-toolbar">
  <div class="btn-group">...</div>
</div>`;
	
	code7 = `<!-- button group vertical -->
<div class="btn-group-vertical">
  <button class="btn btn-purple">Button</button>
  <button class="btn btn-purple active">Button</button>
  <button class="btn btn-purple">Button</button>
</div>

<!-- button group justified -->
<div class="btn-group w-100">
  ...
</div>`;
	
	code8 = `<a href="#" class="btn btn-default btn-icon btn-lg">
  <i class="fa fa-expand"></i>
</a>
<a href="#" class="btn btn-default btn-icon">...</a>
<a href="#" class="btn btn-default btn-icon btn-sm">...</a>
<a href="#" class="btn btn-default btn-icon btn-xs">...</a>`;
}
