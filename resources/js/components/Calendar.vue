<template>
  <div>
    <Fullcalendar ref="fullCalendar" :options="calendarOptions" />
  </div>
</template>

<script>
import EventBus from '../bus/event-bus'
import Fullcalendar from "@fullcalendar/vue";
import Daygrid from "@fullcalendar/daygrid";
import Interaction from "@fullcalendar/interaction";
import TimeGrid from "@fullcalendar/timegrid";
import TimeList from "@fullcalendar/list";

export default {
  name: "Calendar",
  components: {
    Fullcalendar   
  },
  props: {},
  data() {
    return {
      calendarOptions: {
        plugins: [Daygrid, Interaction, TimeGrid, TimeList],
        headerToolbar: {
          left: "prev next today",
          center: "title",
          right: "dayGridMonth timeGridWeek timeGridDay"
        },
        locale: "es",
        contentHeight: "auto", // for scrolling
        nowIndicator: true, // marker time
        initialView: "timeGridWeek",
        slotMinTime: "09:00:00",
        dayMaxEvents: true, // adición de un link para ir al dia exacto, útil cuando hay muchos eventos en la vista
        allDaySlot: false,
        expandRows: true,
        height: "100%",
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick
      }
    };
  },
  beforeMount() {
    this.$data.calendarOptions.events = {
        url: route('appointment.index')
      }
    if (this.$page.user.email === "d@d.es") {
      this.$data.calendarOptions.eventSources = [
        {
          url: "myEvents", // private events
          color: "#1ABC9C",
          failure: error => {
            // Si sucede algo inesperado lo mostramos por consola
            console.log('mostrando errores: ', error.message);
          }
        }
      ];
    }
  },
  mounted() {
    this.getCalendarApi();
    EventBus.$on('refresh',function(){
      this.refreshCalendar()
    }.bind(this))
  },  
  methods: {
    // Recupero los eventos de la DB
    getCalendarApi() {
      this.calendarEl = this.$refs.fullCalendar.getApi();
    },
    // Función para el evento clic dentro del calendario
    handleDateClick(arg) {
      this.$emit('openModal',arg);
    },
    // Cierro el modal
    closeWindow() {
      this.showModal = false;
      this.newEvent = this.resetModal();
    },
    // Hacemos que se ejecute el metodo interno de fullCalendar eventSource
    refreshCalendar() {
      this.calendarEl.refetchEvents()
    },
    // Acción del click sobre un evento
    handleEventClick(clickInfo) {
      this.$emit('handleEventClick',clickInfo)
    },
  }
};
</script>