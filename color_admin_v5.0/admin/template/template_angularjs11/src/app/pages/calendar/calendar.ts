import { Component } from '@angular/core';
import { CalendarOptions } from '@fullcalendar/angular';

@Component({
  selector: 'calendar',
  templateUrl: './calendar.html'
})

export class CalendarPage {
  date = new Date();
	currentYear = this.date.getFullYear();
	defaultMonth = this.date.getMonth() + 1;
	currentMonth = (this.defaultMonth < 10) ? '0' + this.defaultMonth : this.defaultMonth;

  calendarOptions: CalendarOptions = {
    headerToolbar: {
      left: 'dayGridMonth,timeGridWeek,timeGridDay',
      center: 'title',
      right: 'prev,next today'
    },
    buttonText: {
    	today:    'Today',
			currentMonth:    'Month',
			week:     'Week',
			day:      'Day'
    },
    initialView: 'dayGridMonth',
    editable: true,
    droppable: true,
  	themeSystem: 'bootstrap',
		views: {
			timeGrid: {
				eventLimit: 6 // adjust to 6 only for timeGridWeek/timeGridDay
			}
		},
  	events: [{
			title: 'Trip to London',
			start: this.currentYear + '-'+ this.currentMonth +'-01',
			end: this.currentYear + '-'+ this.currentMonth +'-05',
			color: '#00acac'
		},{
			title: 'Meet with Irene Wong',
			start: this.currentYear + '-'+ this.currentMonth +'-02T06:00:00',
			color: '#348fe2'
		},{
			title: 'Mobile Apps Brainstorming',
			start: this.currentYear + '-'+ this.currentMonth +'-10',
			end: this.currentYear + '-'+ this.currentMonth +'-12',
			color: '#fb5597'
		},{
			title: 'Stonehenge, Windsor Castle, Oxford',
			start: this.currentYear + '-'+ this.currentMonth +'-05T08:45:00',
			end: this.currentYear + '-'+ this.currentMonth +'-06T18:00',
			color: '#8753de'
		},{
			title: 'Paris Trip',
			start: this.currentYear + '-'+ this.currentMonth +'-12',
			end: this.currentYear + '-'+ this.currentMonth +'-16'
		},{
			title: 'Domain name due',
			start: this.currentYear + '-'+ this.currentMonth +'-15',
			color: '#348fe2'
		},{
			title: 'Cambridge Trip',
			start: this.currentYear + '-'+ this.currentMonth +'-19'
		},{
			title: 'Visit Apple Company',
			start: this.currentYear + '-'+ this.currentMonth +'-22T05:00:00',
			color: '#32a932'
		},{
			title: 'Exercise Class',
			start: this.currentYear + '-'+ this.currentMonth +'-22T07:30:00',
			color: '#f59c1a'
		},{
			title: 'Live Recording',
			start: this.currentYear + '-'+ this.currentMonth +'-22T03:00:00',
			color: '#348fe2'
		},{
			title: 'Announcement',
			start: this.currentYear + '-'+ this.currentMonth +'-22T15:00:00',
			color: '#ff5b57'
		},{
			title: 'Dinner',
			start: this.currentYear + '-'+ this.currentMonth +'-22T18:00:00'
		},{
			title: 'New Android App Discussion',
			start: this.currentYear + '-'+ this.currentMonth +'-25T08:00:00',
			end: this.currentYear + '-'+ this.currentMonth +'-25T10:00:00',
			color: '#ff5b57'
		},{
			title: 'Marketing Plan Presentation',
			start: this.currentYear + '-'+ this.currentMonth +'-25T12:00:00',
			end: this.currentYear + '-'+ this.currentMonth +'-25T14:00:00',
			color: '#348fe2'
		},{
			title: 'Chase due',
			start: this.currentYear + '-'+ this.currentMonth +'-26T12:00:00',
			color: '#f59c1a'
		},{
			title: 'Heartguard',
			start: this.currentYear + '-'+ this.currentMonth +'-26T08:00:00',
			color: '#f59c1a'
		},{
			title: 'Lunch with Richard',
			start: this.currentYear + '-'+ this.currentMonth +'-28T14:00:00',
			color: '#348fe2'
		},{
			title: 'Web Hosting due',
			start: this.currentYear + '-'+ this.currentMonth +'-30',
			color: '#348fe2'
		}]
	};
}
