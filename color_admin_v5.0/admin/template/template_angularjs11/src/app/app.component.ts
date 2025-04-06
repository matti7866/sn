import { Component, HostListener, Renderer2, OnInit } from '@angular/core';
import { Title }     from '@angular/platform-browser';
import { Router, NavigationEnd, NavigationStart, ActivatedRoute } from '@angular/router';
import appSettings from './config/app-settings';
import * as global from './config/globals';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})

export class AppComponent implements OnInit {

  appSettings;

  ngOnInit() {
    // page settings
    this.appSettings = appSettings;
  }

	// window scroll
  appHasScroll;
  @HostListener('window:scroll', ['$event'])
  onWindowScroll($event) {
    var doc = document.documentElement;
    var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
    if (top > 0) {
      this.appHasScroll = true;
    } else {
      this.appHasScroll = false;
    }
  }

  // set page minified
  onAppSidebarMinifiedToggled(val: boolean):void {
  	this.appSettings.appSidebarMinified = !this.appSettings.appSidebarMinified;
	}

  // set app sidebar end toggled
  onAppSidebarEndToggled(val: boolean):void {
  	this.appSettings.appSidebarEndToggled = !this.appSettings.appSidebarEndToggled;
	}

  // hide mobile sidebar
  onAppSidebarMobileToggled(val: boolean):void {
  	this.appSettings.appSidebarMobileToggled = !this.appSettings.appSidebarMobileToggled;
	}

  // toggle right mobile sidebar
  onAppSidebarEndMobileToggled(val: boolean):void {
  	this.appSettings.appSidebarEndMobileToggled = !this.appSettings.appSidebarEndMobileToggled;
	}

  constructor(private titleService: Title, private router: Router, private renderer: Renderer2) {
    router.events.subscribe((e) => {
			if (e instanceof NavigationStart) {
			  if (window.innerWidth < 768) {
			    this.appSettings.appSidebarMobileToggled = false;
			    this.appSettings.appSidebarEndMobileToggled = false;
			  }
			}
    });
  }
}
