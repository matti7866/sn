import { Component, OnInit, OnDestroy, ElementRef } from '@angular/core';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'page-with-fixed-footer',
  templateUrl: './page-with-fixed-footer.html'
})

export class PageFixedFooter implements OnInit, OnDestroy {
  appSettings = appSettings;
  
  code = `<!-- page.ts -->
import { Component, OnInit, OnDestroy, ElementRef } from '@angular/core';
import appSettings from '../../../config/app-settings';

export class PageClassName implements OnInit, OnDestroy {
  appSettings = appSettings;
  
  constructor(private elRef:ElementRef) {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0';
  }
  ngOnInit() {
    this.elRef.nativeElement.classList.add('d-flex', 'flex-column', 'h-100');
  }
  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}

<!-- page.html -->
<perfect-scrollbar class="app-content-padding flex-grow-1 overflow-hidden">
  ...
</perfect-scrollbar>

<div id="footer" class="app-footer m-0">
  &copy; 2021 Color Admin Responsive Admin Template - Sean Ngu All Rights Reserved
</div>
`;

  constructor(private elRef:ElementRef) {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0 ';
  }
  
  ngOnInit() {
  	this.elRef.nativeElement.classList.add('d-flex', 'flex-column', 'h-100');
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
  }
}
