import React from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import bootstrapPlugin from '@fullcalendar/bootstrap';
import { Link } from 'react-router-dom';

class Calendar extends React.Component {
	constructor(props) {
		super(props);
		
		var date = new Date();
		var currentYear = date.getFullYear();
		var currentMonth = date.getMonth() + 1;
				currentMonth = (currentMonth < 10) ? '0' + currentMonth : currentMonth;
		
		this.state = {
			plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin, bootstrapPlugin ],
			headerToolbar: {
				left: 'dayGridMonth,timeGridWeek,timeGridDay',
				center: 'title',
				right: 'prev,next today'
			},
			buttonText: {
				today:    'Today',
				month:    'Month',
				week:     'Week',
				day:      'Day'
			},
			views: {
				timeGrid: {
					eventLimit: 6
				}
			},
			events: [{
				title: 'Trip to London',
				start: currentYear + '-'+ currentMonth +'-01',
				end: currentYear + '-'+ currentMonth +'-05',
				color: '#00acac'
			},{
				title: 'Meet with Irene Wong',
				start: currentYear + '-'+ currentMonth +'-02T06:00:00',
				color: '#348fe2'
			},{
				title: 'Mobile Apps Brainstorming',
				start: currentYear + '-'+ currentMonth +'-10',
				end: currentYear + '-'+ currentMonth +'-12',
				color: '#fb5597'
			},{
				title: 'Stonehenge, Windsor Castle, Oxford',
				start: currentYear + '-'+ currentMonth +'-05T08:45:00',
				end: currentYear + '-'+ currentMonth +'-06T18:00',
				color: '#8753de'
			},{
				title: 'Paris Trip',
				start: currentYear + '-'+ currentMonth +'-12',
				end: currentYear + '-'+ currentMonth +'-16'
			},{
				title: 'Domain name due',
				start: currentYear + '-'+ currentMonth +'-15',
				color: '#348fe2'
			},{
				title: 'Cambridge Trip',
				start: currentYear + '-'+ currentMonth +'-19'
			},{
				title: 'Visit Apple Company',
				start: currentYear + '-'+ currentMonth +'-22T05:00:00',
				color: '#00ACAC'
			},{
				title: 'Exercise Class',
				start: currentYear + '-'+ currentMonth +'-22T07:30:00',
				color: '#f59c1a'
			},{
				title: 'Live Recording',
				start: currentYear + '-'+ currentMonth +'-22T03:00:00',
				color: '#348fe2'
			},{
				title: 'Announcement',
				start: currentYear + '-'+ currentMonth +'-22T15:00:00',
				color: '#ff5b57'
			},{
				title: 'Dinner',
				start: currentYear + '-'+ currentMonth +'-22T18:00:00'
			},{
				title: 'New Android App Discussion',
				start: currentYear + '-'+ currentMonth +'-25T08:00:00',
				end: currentYear + '-'+ currentMonth +'-25T10:00:00',
				color: '#ff5b57'
			},{
				title: 'Marketing Plan Presentation',
				start: currentYear + '-'+ currentMonth +'-25T12:00:00',
				end: currentYear + '-'+ currentMonth +'-25T14:00:00',
				color: '#348fe2'
			},{
				title: 'Chase due',
				start: currentYear + '-'+ currentMonth +'-26T12:00:00',
				color: '#f59c1a'
			},{
				title: 'Heartguard',
				start: currentYear + '-'+ currentMonth +'-26T08:00:00',
				color: '#f59c1a'
			},{
				title: 'Lunch with Richard',
				start: currentYear + '-'+ currentMonth +'-28T14:00:00',
				color: '#348fe2'
			},{
				title: 'Web Hosting due',
				start: currentYear + '-'+ currentMonth +'-30',
				color: '#348fe2'
			}]
		}
	}
	
	render() {
		return (
			<div>
				<ol className="breadcrumb float-xl-end">
					<li className="breadcrumb-item"><Link to="/calendar">Home</Link></li>
					<li className="breadcrumb-item active">Calendar</li>
				</ol>
				<h1 className="page-header">Calendar <small>header small text goes here...</small></h1>
				<hr />
				
				<FullCalendar 
					plugins={[ dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin, bootstrapPlugin ]}
					initialView="dayGridMonth"
					themeSystem="bootstrap"
					headerToolbar={this.state.headerToolbar}
					buttonText={this.state.buttonText}
					events={this.state.events}
					views={this.state.views}
				/>
			</div>
		)
	}
}

export default Calendar;