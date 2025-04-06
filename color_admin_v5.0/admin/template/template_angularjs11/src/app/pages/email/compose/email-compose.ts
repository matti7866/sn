import { Component, ViewEncapsulation, OnInit, OnDestroy } from '@angular/core';
import appSettings from '../../../config/app-settings';
import * as global from '../../../config/globals';
import Tagify from '@yaireo/tagify';
import { Editor } from 'ngx-editor';

@Component({
  selector: 'email-compose',
  templateUrl: './email-compose.html',
  encapsulation: ViewEncapsulation.None,
  styleUrls: [ './email-compose.css' ]
})

export class EmailComposePage implements OnInit, OnDestroy {
	global = global;
  appSettings = appSettings;
  public isCollapsed = true;
  editor: Editor;
  html: '';
  subject: string = '';
  cc;
  bcc;
	
	showCc() {
		this.cc = true;
		
		var inputElement = document.querySelector('[data-render="cctags"]');
    new Tagify(inputElement);
	}
	
	showBcc() {
		this.bcc = true;
		
		var inputElement = document.querySelector('[data-render="bcctags"]');
    new Tagify(inputElement);
	}

  onTagsChanged(value) { }

  constructor() {
    this.appSettings.appContentFullHeight = true;
    this.appSettings.appContentClass = 'p-0';
  }
  
  ngOnInit() {
    this.editor = new Editor();
    this.cc = false;
    this.bcc = false;
  }
  
  ngAfterViewInit() {
    var inputElement = document.querySelector('[data-render="tags"]');
    new Tagify(inputElement);
  }

  ngOnDestroy() {
    this.appSettings.appContentFullHeight = false;
    this.appSettings.appContentClass = '';
    
    this.editor.destroy();
  }
}
