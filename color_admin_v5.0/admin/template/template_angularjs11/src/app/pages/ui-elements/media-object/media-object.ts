import { Component } from '@angular/core';

@Component({
  selector: 'ui-media-object',
  templateUrl: './media-object.html'
})

export class UIMediaObjectPage {
	code1 = `<div class="d-flex">
  <a class="w-60px" href="javascript:;">
    <img src="/assets/img/user/user-1.jpg" alt="" class="mw-100 rounded" />
  </a>
  <div class="ps-3 flex-1">
    <h5 class="mb-1">...</h5>
    <p class="mb-0">...</p>
  </div>
</div>

<hr class="bg-gray-500" />`;
	
	code2 = `<div class="d-flex">
  <div class="pe-3 flex-1">
    ...
  </div>
  <a class="w-lg-300px w-100px" href="javascript:;">
    <img src="/assets/img/user/user-1.jpg" alt="" class="mw-100 rounded-pill" />
  </a>
</div>

<hr class="bg-gray-500" />`;
	
	code3 = `<div class="d-flex">
  <div class="pe-3 flex-1">
    <h5 class="mb-1">...</h5>
    <p class="mb-0">...</p>
  </div>
  <a class="w-60px" href="javascript:;">
    <img src="/assets/img/user/user-1.jpg" alt="" class="mw-100 rounded-pill" />
  </a>
</div>

<hr class="bg-gray-500" />`;
	
	code4 = `<div class="d-flex">
  <div class="pe-3 flex-1 text-end">
    ...
  </div>
  <a class="w-60px" href="javascript:;">
    <img src="/assets/img/user/user-1.jpg" alt="" class="mw-100 rounded-pill" />
  </a>
</div>

<hr class="bg-gray-500" />`;
}
