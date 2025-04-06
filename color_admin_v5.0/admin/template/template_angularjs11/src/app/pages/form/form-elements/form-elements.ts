import { Component, Injectable } from '@angular/core';

@Component({
  selector: 'form-elements',
  templateUrl: './form-elements.html',
})

export class FormElementsPage {
	code1 = `<!-- email -->
<input type="email" class="form-control" placeholder="Enter email" />

<!-- select -->
<select class="form-select">...</select>

<!-- multiple select -->
<select multiple class="form-select">...</select>

<!-- textarea -->
<textarea class="form-control" rows="3"></textarea>`;

	code2 = `<!-- form-control-lg -->
<input class="form-control" type="text" placeholder="Readonly input here…" readonly />

<!-- readonly plaintext -->
<input type="text" readonly class="form-control-plaintext" value="email@example.com" />

<!-- password -->
<input type="password" readonly class="form-control" placeholder="Password" />
`;

	code3 = `<!-- html -->
<input type="range" class="form-range" min="0" max="50" id="customRange" />`;
	
	code4 = `<!-- html -->
<div class="form-floating">
  <input type="email" class="form-control fs-15px" id="floatingInput" />
  <label for="floatingInput" class="d-flex align-items-center fs-13px">
    Email address
  </label>
</div>`;
	
	code5 = `<!-- large size -->
<input class="form-control form-control-lg" type="text" />
<select class="form-select form-select-lg">...</select>

<!-- default -->
<input class="form-control" type="text" />
<select class="form-select">...</select>

<!-- small size -->
<input class="form-control form-control-sm" type="text" />
<select class="form-select form-select-sm">...</select>
`;
	
	code6 = `<!-- valid -->
<div class="row mb-3">
  <label class="form-label col-form-label col-md-3">Valid Input</label>
  <div class="col-md-9">
    <input type="text" class="form-control is-valid" />
    <div class="valid-feedback">Looks good!</div>
    <div class="valid-tooltip">Looks good!</div>
  </div>
</div>

<!-- invalid -->
<div class="row mb-3">
  <label class="form-label col-form-label col-md-3">...</label>
  <div class="col-md-9">
    <input type="text" class="form-control is-invalid" />
    <div class="invalid-feedback">...</div>
    <div class="invalid-tooltip">...</div>
  </div>
</div>`;
	
	code7 = `<!-- default -->
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="checkbox1" checked />
  <label class="form-check-label" for="checkbox1">Checkbox</label>
</div>

<!-- inline -->
<div class="form-check form-check-inline">
  ...
</div>

<!-- switches -->
<div class="form-check form-switch">
  ...
</div>

<!-- valid -->
<div class="form-check">
  <input class="form-check-input is-valid" />
</div>

<!-- invalid -->
<div class="form-check">
  <input class="form-check-input is-invalid" />
</div>`;
	
	code8 = `<!-- default -->
<div class="form-check">
  <input class="form-check-input" type="radio" id="radio1" checked />
  <label class="form-check-label" for="radio1">Radio</label>
</div>

<!-- inline -->
<div class="form-check form-check-inline">
  ...
</div>

<!-- valid -->
<div class="form-check">
  <input class="form-check-input is-valid" />
</div>

<!-- invalid -->
<div class="form-check">
  <input class="form-check-input is-invalid" />
</div>`;
	
	code9 = `<div class="input-group">
  <span class="input-group-text">$</span>
  <input type="text" class="form-control" />
  <span class="input-group-text">.00</span>
</div>

<div class="input-group">
  <input type="text" class="form-control">
  <button type="button" class="btn btn-indigo" data-bs-toggle="dropdown">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    ...
  </ul>
</div>`;
	
	code10 = `<!-- input-group-lg -->
<div class="input-group input-group-lg">...</div>

<!-- default -->
<div class="input-group">...</div>

<!-- input-group-sm -->
<div class="input-group input-group-sm">...</div>`;
	
	code11 = `<div class="mb-3">
  <label class="form-label">...</label>
  <input class="form-control" type="text" />
</div>`;
	
	code12 = `<div class="row mb-3">
  <label class="form-label col-form-label col-md-3">...</label>
  <div class="col-md-7">
    <input type="text" class="form-control" placeholder="" />
  </div>
</div>`;
	
	code13 = `<form class="row row-cols-lg-auto g-3 align-items-center">
  <div class="col-12">...</div>
  <div class="col-12">...</div>
  ...
</form>`;
}
