import { group, animate, query, style, trigger, transition, state } from '@angular/animations';
import { Component, Output, EventEmitter, AfterViewInit, HostListener, ViewChild } 		 from '@angular/core';
import appSettings from '../../config/app-settings';
import appMenus from '../../config/app-menus';
import * as global 			 from '../../config/globals';

@Component({
  selector: 'top-menu',
  templateUrl: './top-menu.component.html'
})

export class TopMenuComponent implements AfterViewInit {
  @ViewChild('topMenuContainer', { static: true }) topMenuContainer;

  menus = appMenus;
  navControlStart = false;
  navControlEnd = false;
  navWidth = 0;
  navMarginLeft = 0;
  navMarginRight = 0;
  appSettings = appSettings;
	mobileMode = (window.innerWidth <= 767) ? true : false;
	desktopMode = (window.innerWidth <= 767) ? false : true;

  controlStart() {
    var widthLeft = this.navMarginLeft;
    var containerWidth = this.topMenuContainer.nativeElement.clientWidth;
    var finalScrollWidth = 0;

    if (widthLeft <= containerWidth) {
      finalScrollWidth = 0;
      this.navControlStart = false;
    } else {
      finalScrollWidth = widthLeft - containerWidth + 88;
    }

    if (!document.body.classList.contains('rtl-mode')) {
      this.navMarginLeft = finalScrollWidth;
      this.navMarginRight = 0;
      this.navControlEnd = true;
    } else {
      this.navMarginRight = finalScrollWidth;
      this.navMarginLeft = 0;
      this.navControlEnd = true;
    }
  }

  controlEnd() {
    var containerWidth = this.topMenuContainer.nativeElement.clientWidth - 88;
    var widthLeft = this.navWidth + (-this.navMarginLeft) - containerWidth;
    var finalScrollWidth = 0;

    if (widthLeft <= containerWidth) {
      finalScrollWidth = widthLeft - (-this.navMarginLeft) + 128;
      this.navControlEnd = false;
    } else {
      finalScrollWidth = containerWidth - (-this.navMarginLeft) - 128;
    }

    if (finalScrollWidth !== 0) {
      if (!document.body.classList.contains('rtl-mode')) {
        this.navMarginLeft = finalScrollWidth;
        this.navMarginRight = 0;
      } else {
        this.navMarginRight = finalScrollWidth;
        this.navMarginLeft = 0;
      }
      this.navControlStart = true;
    }
  }

  @HostListener('window:resize', ['$event'])
  onResize(event) {
    if (window.innerWidth <= 767) {
      this.mobileMode = true;
      this.desktopMode = false;
    } else {
      this.mobileMode = false;
      this.desktopMode = true;
    }
  }

  ngAfterViewInit() {
    setTimeout(() => {
      var windowWidth = this.topMenuContainer.nativeElement.clientWidth - 128;
      var listFullWidth = 0;
      var listPrevWidth = 0;
      var listActive = false;

      const navList = Array.from(document.querySelectorAll('.app-top-menu .menu > .menu-item'));
      navList.forEach(function(list) {
        const elm = list as any;
        listFullWidth += elm.offsetWidth;
        listPrevWidth += (!listActive) ? elm.offsetWidth : 0;
        listActive = (elm.classList.contains('active')) ? true : listActive;
      });

      this.navWidth = listFullWidth;

      listPrevWidth = (!listActive) ? 0 : listPrevWidth;

      if (listPrevWidth >= windowWidth) {
          var finalScrollWidth = listPrevWidth - windowWidth + 128;
          if (!document.body.classList.contains('rtl-mode')) {
            this.navMarginLeft = finalScrollWidth;
            this.navMarginRight = 0;
          } else {
            this.navMarginRight = finalScrollWidth;
            this.navMarginLeft = 0;
          }
      }

      this.navControlEnd = (listPrevWidth != listFullWidth && listFullWidth >= windowWidth) ? true : false;
      this.navControlStart = (listPrevWidth >= windowWidth && listFullWidth >= windowWidth) ? true : false;
    });
  }

	expandCollapseSubmenu(currentMenu, allMenu, active) {
		if (currentMenu.state == 'expand' || (active.isActive && !currentMenu.state)) {
			currentMenu.state = 'collapse';
		} else {
			currentMenu.state = 'expand';
		}
	}
}
