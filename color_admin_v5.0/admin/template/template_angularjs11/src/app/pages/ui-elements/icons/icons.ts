import { Component } from '@angular/core';

@Component({
  selector: 'ui-icons',
  templateUrl: './icons.html'
})

export class UIIconsPage {
	code1 = `<i class="fas fa-camera-retro fa-xs"></i>
<i class="fas fa-camera-retro fa-sm"></i>
<i class="fas fa-camera-retro fa-lg"></i>
<i class="fas fa-camera-retro fa-2x"></i>
<i class="fas fa-camera-retro fa-3x"></i>
<i class="fas fa-camera-retro fa-5x"></i>
<i class="fas fa-camera-retro fa-7x"></i>
<i class="fas fa-camera-retro fa-10x"></i>`;
	
	code2 = `<i class="fas fa-home fa-fw"></i> Home
<i class="fas fa-info fa-fw"></i> Info
<i class="fas fa-book fa-fw"></i> Library
<i class="fas fa-pencil-alt fa-fw"></i> Applications
<i class="fas fa-cog fa-fw"></i> Settings`;
	
	code3 = `<div class="fa-3x">
  <i class="fas fa-spinner fa-spin"></i>
  <i class="fas fa-circle-notch fa-spin"></i>
  <i class="fas fa-sync fa-spin"></i>
  <i class="fas fa-cog fa-spin"></i>
  <i class="fas fa-spinner fa-pulse"></i>
</div>`;
	
	code4 = `<div class="fa-3x">
  <i class="fas fa-arrow-alt-circle-right"></i>
  <i class="fas fa-arrow-alt-circle-right fa-rotate-90"></i>
  <i class="fas fa-arrow-alt-circle-right fa-rotate-180"></i>
  <i class="fas fa-arrow-alt-circle-right fa-rotate-270"></i>
  <i class="fas fa-arrow-alt-circle-right fa-flip-horizontal"></i>
  <i class="fas fa-arrow-alt-circle-right fa-flip-vertical"></i>
</div>`;
	
	code5 = `<ul class="fa-ul">
  <li>
    <span class="fa-li"><i class="fas fa-check-square"></i></span>
    List icons can
  </li>
  <li>
    <span class="fa-li"><i class="fas fa-check-square"></i></span>
    be used to
  </li>
  <li>
    <span class="fa-li"><i class="fas fa-spinner fa-pulse"></i></span>
    replace bullets
  </li>
  <li>
    <span class="fa-li"><i class="far fa-square"></i></span>
    in lists
  </li>
</ul>`;
	
	code6 = `<i class="fas fa-quote-left fa-2x float-start me-3 fa-border"></i>
Gatsby believed in the green light, the orgastic future that year by year recedes before us. It eluded us then, but that’s no matter — tomorrow we will run faster, stretch our arms further... And one fine morning — So we beat on, boats against the current, borne back ceaselessly into the past.
`;
	
	code7 = `<span class="fa-stack fa-2x text-primary">
  <i class="far fa-square fa-stack-2x"></i>
  <i class="fab fa-twitter fa-stack-1x"></i>
</span>
<span class="fa-stack fa-2x">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-flag fa-stack-1x fa-inverse"></i>
</span>
<span class="fa-stack fa-2x">
  <i class="fa fa-square fa-stack-2x"></i>
  <i class="fa fa-terminal fa-stack-1x fa-inverse"></i>
</span>
<span class="fa-stack fa-2x">
  <i class="fa fa-camera fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x"></i>
</span>
<span class="fa-stack fa-2x">
  <i class="far fa-circle fa-stack-2x"></i>
  <i class="fa fa-cog fa-stack-1x"></i>
</span>`;
}
