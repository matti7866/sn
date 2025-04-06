import { Component } from '@angular/core';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'ui-modal-notification',
  templateUrl: './modal-notification.html'
})

export class UIModalNotificationPage {
  closeResult: string;
  code1 = `<ng-template #modalDialog let-c="close" let-d="dismiss">
  <div class="modal-header">
    <h4 class="modal-title">Modal Dialog</h4>
    <button type="button" class="close" (click)="d('Cross click')">×</button>
  </div>
  <div class="modal-body">
    <p>
      Modal body content here...
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-white" (click)="c('Close click')">Close</button>
    <a href="javascript:;" class="btn btn-success">Action</a>
  </div>
</ng-template>`;
  
  code2 = `// modal-notification.html
<a href="javascript:;" [swal]="['Question Type', 'description here', 'question']" class="btn btn-primary">Primary</a>`;

  constructor(private modalService: NgbModal) {

  }

  open(content) {
    this.modalService.open(content).result.then((result) => {
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
}
