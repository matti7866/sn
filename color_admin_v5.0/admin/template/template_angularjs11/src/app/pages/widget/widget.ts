import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import * as global from '../../config/globals';

@Component({
  selector: 'widget',
  templateUrl: './widget.html'
})

export class WidgetPage implements OnInit {
  closeResult: string;
  lightMode = true;
  darkMode = false;
  lineChartData = [{
    "name":"Congo",
    "series":[{"value":2377,"name":"Thu 15"},{"value":4567,"name":"Sat 17"},{"value":2865,"name":"Mon 19"},{"value":2060,"name":"Wed 21"},{"value":3287,"name":"Fri 23"}]},{"name":"Micronesia","series":[{"value":5234,"name":"Thu 15"},{"value":2876,"name":"Sat 17"},{"value":4297,"name":"Mon 19"},{"value":2558,"name":"Wed 21"},{"value":2371,"name":"Fri 23"}]},{"name":"Malaysia","series":[{"value":2369,"name":"Thu 15"},{"value":5229,"name":"Sat 17"},{"value":3457,"name":"Mon 19"},{"value":4401,"name":"Wed 21"},{"value":2835,"name":"Fri 23"}]},{"name":"Yemen","series":[{"value":2099,"name":"Thu 15"},{"value":4383,"name":"Sat 17"},{"value":6724,"name":"Mon 19"},{"value":2870,"name":"Wed 21"},{"value":5753,"name":"Fri 23"}]},{"name":"Åland Islands","series":[{"value":4907,"name":"Thu 15"},{"value":2428,"name":"Sat 17"},{"value":5384,"name":"Mon 19"},{"value":5966,"name":"Wed 21"},{"value":2605,"name":"Fri 23"}]
  }];
  lineChartColor = { domain: [global.COLOR_BLUE, global.COLOR_GREEN, global.COLOR_PURPLE, global.COLOR_BLACK] };

  widgetLightMode() {
    this.lightMode = true;
    this.darkMode = false;
  }
  widgetDarkMode() {
    this.darkMode = true;
    this.lightMode = false;
  }
  constructor(private modalService: NgbModal) {

  }

  ngOnInit() {
  }

  open(content) {
    this.modalService.open(content, { size: 'lg' }).result.then((result) => {
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
  
  code1 = `<div class="widget-map rounded mb-4">
  <!-- BEGIN widget-input-container -->
  <div class="widget-input-container">
    <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-ellipsis-v"></i></a></div>
    <div class="widget-input-box">
      <input type="text" class="form-control" placeholder="Search here">
    </div>
    <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-microphone"></i></a></div>
    <div class="widget-input-divider"></div>
    <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-location-arrow"></i></a></div>
  </div>
  <!-- END widget-input-container -->
  <!-- BEGIN widget-map-body -->
  <div class="widget-map-body">
    <iframe class="d-block" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.5650178360584!2d-122.41879278478642!3d37.77679637975903!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c6c8f4459%3A0xb10ed6d9b5050fa5!2sTwitter+HQ!5e0!3m2!1sen!2smy!4v1524046379645" width="100%" height="230" frameborder="0" style="border:0" allowfullscreen></iframe>
  </div>
  <!-- END widget-map-body -->
  <!-- BEGIN widget-map-list -->
  <div class="widget-map-list">
    <div class="widget-list bg-none">
      <!-- BEGIN widget-list-item -->
      <div class="widget-list-item">
        <div class="widget-list-media text-center">
          <a href="javascript:;"><i class="fab fa-twitter fa-3x"></i></a>
        </div>
        <div class="widget-list-content">
          <h4 class="widget-list-title">Twitter Headquater</h4>
          <p class="widget-list-desc">Corporate Office</p>
        </div>
        <div class="widget-list-action">
          <a href="javascript:;" class="text-gray-500"><i class="fa fa-angle-right fa-2x"></i></a>
        </div>
      </div>
      <!-- END widget-list-item -->
    </div>
  </div>
  <!-- END widget-map-list -->
</div>`;
  
  code2 = `<div class="widget-todolist rounded mb-4">
  <!-- BEGIN widget-todolist-header -->
  <div class="widget-todolist-header">
    <div class="widget-todolist-header-title">Todolist</div>
    <div class="widget-todolist-header-total"><span>0</span><small>Done</small></div>
  </div>
  <!-- END widget-todolist-header -->
  <!-- BEGIN widget-todolist-body -->
  <div class="widget-todolist-body">
    <!-- BEGIN widget-todolist-item -->
    <div class="widget-todolist-item">
      <div class="widget-todolist-input">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="widget_todolist_1" />
        </div>
      </div>
      <div class="widget-todolist-content">
        <h6 class="mb-2px">Borrow Tony's travel guide</h6>
        <div class="text-gray-500 fw-bold fs-11px">Vacation in Rome</div>
      </div>
      <div class="widget-todolist-icon">
        <a href="javascript:;"><i class="fa fa-question-circle"></i></a>
      </div>
    </div>
    <!-- END widget-todolist-item -->
    ...
  </div>
  <!-- END widget-todolist-body -->
</div>`;
  
  code3 = `<div class="widget-chat rounded mb-4" [ngClass]="{'inverse-mode': darkMode}">
  <!-- BEGIN widget-chat-header -->
  <div class="widget-chat-header">
    <div class="widget-chat-header-icon">
      <i class="fab fa-earlybirds w-30px h-30px fs-20px bg-yellow text-inverse d-flex align-items-center justify-content-center rounded"></i>
    </div>
    <div class="widget-chat-header-content">
      <h4 class="widget-chat-header-title">Company Discussion Group</h4>
      <p class="widget-chat-header-desc">55 members, 4 online</p>
    </div>
  </div>
  <!-- END widget-chat-header -->
  <!-- BEGIN widget-chat-body -->
  <perfect-scrollbar style="height: 235px;" class="widget-chat-body">
    <!-- BEGIN widget-chat-item -->
    <div class="widget-chat-item with-media start">
      <div class="widget-chat-media">
        <img alt="" src="/assets/img/user/user-1.jpg" />
      </div>
      <div class="widget-chat-info">
        <div class="widget-chat-info-container">
          <div class="widget-chat-name text-indigo">Hudson Mendes</div>
          <div class="widget-chat-message">Should we plan for a company trip this year?</div>
          <div class="widget-chat-time">6:00PM</div>
        </div>
      </div>
    </div>
    ...
    <!-- END widget-chat-item -->
  </perfect-scrollbar>
  <!-- END widget-chat-body -->
  <!-- BEGIN widget-input -->
  <div class="widget-input">
    <form action="" method="POST" name="">
      <div class="widget-input-container">
        <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-camera"></i></a></div>
        <div class="widget-input-box">
          <input type="text" class="form-control" placeholder="Write a message..." />
        </div>
        <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-smile"></i></a></div>
        <div class="widget-input-divider"></div>
        <div class="widget-input-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-microphone"></i></a></div>
      </div>
    </form>
  </div>
  <!-- END widget-input -->
</div>`;
  
  code4 = `<div class="widget-list rounded mb-4">
  <a href="javascript:;" class="widget-list-item">
    <div class="widget-list-media icon">
      <i class="fa fa-plane bg-inverse text-white"></i>
    </div>
    <div class="widget-list-content">
      <h4 class="widget-list-title">Airplane Mode</h4>
    </div>
    <div class="widget-list-action text-end">
      <i class="fa fa-angle-right fa-lg text-gray-500"></i>
    </div>
  </a>
  ...
</div>`;
  
  code5 = `<div class="widget-list rounded mb-4" [ngClass]="{'inverse-mode': darkMode}">
  <a href="javascript:;" class="widget-list-item">
    <div class="widget-list-media icon">
      <i class="fa fa-plane bg-inverse text-white"></i>
    </div>
    <div class="widget-list-content">
      <h4 class="widget-list-title">Airplane Mode</h4>
    </div>
    <div class="widget-list-action text-end">
      <i class="fa fa-angle-right fa-lg text-gray-500"></i>
    </div>
  </a>
</div>`;
  
  code6 = `<div class="widget-icon rounded bg-indigo me-5px mb-5px text-white">
  <i class="fab fa-digital-ocean"></i>
</div>`;
  
  code7 = `<!-- BEGIN widget-card -->
<a href="javascript:;" class="widget-card rounded mb-20px">
  <div class="widget-card-cover rounded" style="background-image: url(/assets/img/gallery/gallery-portrait-11-thumb.jpg)"></div>
  <div class="widget-card-content">
    <b class="text-white">Download and get free trial.</b>
  </div>
  <div class="widget-card-content bottom">
    <i class="fab fa-pushed fa-5x text-indigo"></i>
    <h4 class="text-white mt-10px"><b>Apple Draw<br /> Photo Booth</b></h4>
    <h5 class="fs-12px text-white-transparent-7 mb-0"><b>EASILY DRAW ON PHOTOS</b></h5>
  </div>
</a>
<!-- END widget-card -->`;
  
  code8 = `<a href="javascript:;" class="widget-card rounded square mb-1">
  <div class="widget-card-cover" style="background-image: url(/assets/img/login-bg/login-bg-1-thumb.jpg)"></div>
</a>`;
  
  code9 = `<div class="widget widget-stats bg-teal mb-7px">
  <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
  <div class="stats-content">
    <div class="stats-title">TODAY'S VISITS</div>
    <div class="stats-number">7,842,900</div>
    <div class="stats-progress progress">
      <div class="progress-bar" style="width: 70.1%;"></div>
    </div>
    <div class="stats-desc">Better than last week (70.1%)</div>
  </div>
</div>`;
  
  code10 = `<!-- BEGIN widget-chart -->
<div class="widget rounded mb-4">
  <!-- BEGIN widget-header -->
  <div class="widget-header">
    <h4 class="widget-header-title">Audience Overview</h4>
    <div class="widget-header-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-fw fa-upload"></i></a></div>
    <div class="widget-header-icon"><a href="javascript:;" class="text-gray-500"><i class="fa fa-fw fa-cog"></i></a></div>
  </div>
  <!-- END widget-header -->
  <!-- BEGIN widget-content -->
  <div class="row m-0">
    <div class="col-lg-8 widget-chart-content">
      <ngx-charts-line-chart
        [animations]="false"
        [scheme]="lineChartColor"
        [results]="lineChartData"
        [xAxis]="true"
        [yAxis]="true"
        [legend]="true"
        [showXAxisLabel]="true"
        [xAxisLabel]="'Census Date'"
        [showYAxisLabel]="true"
        [yAxisLabel]="'GDP Per Capita'"
        [roundDomains]="true"
        [autoScale]="true">
      </ngx-charts-line-chart>
    </div>
    <div class="col-lg-4 p-3">
      <div class="widget-chart-info">
        <h4 class="widget-chart-info-title">Total sales</h4>
        <p class="widget-chart-info-desc">Lorem ipsum dolor sit consectetur adipiscing elit.</p>
        <div class="widget-chart-info-progress">
          <b>Monthly Plan</b>
          <span class="float-end">70%</span>
        </div>
        <div class="progress h-10px">
          <div class="progress-bar progress-bar-striped progress-bar-animated rounded-pill" style="width:70%;"></div>
        </div>
      </div>
      <hr />
      ...
    </div>
  </div>
  <!-- END widget-content -->
</div>
<!-- END widget-chart -->`;
  
  code11 = `<!-- BEGIN widget-table -->
<table class="table table-bordered widget-table rounded">
  <thead>
    <tr class="text-nowrap">
      <th width="1%">Image</th>
      <th>Product Info</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Total</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <img src="/assets/img/product/product-6.png" width="100" />
      </td>
      <td>
        <h5 class="mb-1">Mavic Pro Combo</h5>
        <p class="fs-11px fw-bold text-gray-600 mb-3">Portable yet powerful, the Mavic Pro is your personal drone, ready to go with you everywhere.</p>
        <div class="progress h-10px rounded-pill mb-5px">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-orange fs-10px fw-bold" style="width: 30%;">30%</div>
        </div>
        <div class="clearfix fs-10px">
          status: 
          <b class="text-inverse">Shipped</b>
        </div>
      </td>
      <td class="text-nowrap">
        <b class="text-inverse">$999</b><br />
        <small class="text-inverse"><del>$1,202</del></small>
      </td>
      <td>1</td>
      <td>999.00</td>
      <td>
        <a href="javascript:;" class="btn btn-inverse btn-sm w-80px rounded-pill">Edit</a>
      </td>
    </tr>
  </tbody>
</table>
<!-- END widget-table -->`;
}
