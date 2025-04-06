import { Component, Input, Output, EventEmitter } 		 from '@angular/core';
import appSettings from '../../config/app-settings';

@Component({
  selector: 'float-sub-menu',
  templateUrl: './float-sub-menu.component.html'
})

export class FloatSubMenuComponent {
  appSettings = appSettings;

	@Input() menus;
	@Input() top;
	@Input() left;
	@Input() right;
	@Input() bottom;
	@Input() lineTop;
	@Input() lineBottom;
	@Input() arrowTop;
	@Input() arrowBottom;

	@Output() remainAppSidebarFloatSubMenu = new EventEmitter();
	@Output() hideAppSidebarFloatSubMenu = new EventEmitter();
	@Output() calculateFloatSubMenuPosition = new EventEmitter();

	expandCollapseSubmenu(currentMenu, allMenu, active) {
		for (let menu of allMenu) {
			if (menu != currentMenu) {
				menu.state = 'collapse';
			}
		}
		if (active.isActive) {
		  currentMenu.state = (currentMenu.state && currentMenu.state == 'collapse') ? 'expand' : 'collapse';
		} else {
		  currentMenu.state = (currentMenu.state && currentMenu.state == 'expand') ? 'collapse' : 'expand';
		}
		this.calculateFloatSubMenuPosition.emit();
	}

	remainMenu() {
	  this.remainAppSidebarFloatSubMenu.emit(true);
	}

	hideMenu() {
	  this.hideAppSidebarFloatSubMenu.emit(true);
	}
}
