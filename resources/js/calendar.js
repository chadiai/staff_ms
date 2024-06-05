import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

function view(event) {
    if (!event) return false;
    return (window.innerWidth >= 1050);
}

function calendar(event) {
    const calendarEl = document.getElementById("calendar");
    let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        initialView: view(event) ? 'timeGridWeek' : 'listWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: event ? 'timeGridWeek,dayGridMonth,listWeek' : 'dayGridMonth,listWeek',
        },
        slotMinTime: "06:00:00",
        slotMaxTime: "29:59:00",
        windowResize: function (view) {
            if (window.innerWidth >= 1050) {
                if (!event){
                    calendar.changeView('listWeek');
                } else {
                    calendar.changeView('timeGridWeek');
                }

            } else {
                calendar.changeView('listWeek');
            }
        },
        allDaySlot: false,
        contentHeight: 'auto',
        events: 'events',
        firstDay: 1,
        eventClick: function (info) {
            Livewire.emit('setEvent', info.event.groupId, info.event.id)
        }
    });
    calendar.render();
}

window.addEventListener('calendar:render', event => {
    calendar(event.detail.event);
})

if (/schedule/.test(window.location.href) && !/meals/.test(window.location.href)) {
    let event = true;
    if (/absence/.test(window.location.href)) {
        event = false;
    }
    document.addEventListener('DOMContentLoaded', calendar(event));
}
