import { Component, OnDestroy } from '@angular/core';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import appSettings from '../../../config/app-settings';

@Component({
  selector: 'customer-order',
  templateUrl: './customer-order.html'
})

export class PosCustomerOrderPage implements OnDestroy {
  appSettings = appSettings;
  posMobileSidebarToggled = false;
  closeResult: string;

  open(content) {
    this.modalService.open(content, { windowClass: 'modal-pos-item', size: 'lg' }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }

	togglePosMobileSidebar() {
	  this.posMobileSidebarToggled = !this.posMobileSidebarToggled;
	}

  constructor(private modalService: NgbModal) {
    this.appSettings.appEmpty = true;
    this.appSettings.appContentFullHeight = true;
  }

  ngOnDestroy() {
    this.appSettings.appEmpty = false;
    this.appSettings.appContentFullHeight = false;
  }
}
