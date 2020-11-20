<template>
  <div>
    <Fullcalendar ref="fullCalendar" :options="calendarOptions" />
    <!--<transition name="fade">
       <Modal
        v-if="showModal"
        :form="newEvent"
        :editMode="isEdit"
        @getDataUser="getUser"
        @closeModal="closeWindow"
        @saveAppt="saveAppt"
        @deleteApt="removeAppt"
        @editAppt="updateAppt"
        @close="closeWindow"
      /> 
    </transition>-->
  </div>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import Daygrid from "@fullcalendar/daygrid";
import Interaction from "@fullcalendar/interaction";
import TimeGrid from "@fullcalendar/timegrid";
import TimeList from "@fullcalendar/list";/* 
import { Inertia } from "@inertiajs/inertia";
import NormalizeDate from "../Mixins/transformDates";
import formatTime from "../Mixins/transformTime";
import moment from "moment"; 
import Modal from "./Modal";*/

export default {
  name: "Calendar",
  components: {
    Fullcalendar,
    
  },
  props: {
    
  },
  data() {
    return {
      /* newEvent: {
        id: "",
        title: "",
        date_at: "",
        hour: "",
        user_id: "",
        session: 1800
      }, */
      /* showModal: false,
      isEdit: false, */
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
            console.log('mostrando errores: ', error.message);
          }
        }
      ];
    }
  },
  mounted() {
    this.getCalendarApi();
  },
  methods: {
    getUser(input){
      console.log('estoy en calendar: ',input);
    },
    // Recupero los eventos de la DB
    getCalendarApi() {
      this.calendarEl = this.$refs.fullCalendar.getApi();
    },
    // Función para el evento clic dentro del calendario
    handleDateClick(arg) {
      this.$emit('openModal',arg);
      //this.setModalData(arg);
    },
    /*Cargo los datos en el modal reactivo
    setModalData(dayTime) {
      this.newEvent.user_id = this.$page.user.id;
      let dateAndTime = dayTime.dateStr.split("T");
      this.newEvent.date_at = dateAndTime[0];
      this.newEvent.hour = dateAndTime[1].substr(0, 8);
      return;
    },
    // Cierro el modal
    closeWindow() {
      this.showModal = false;
      this.newEvent = this.resetModal();
    },
    refreshCalendar() {
      this.calendarEl.refetchEvents()
    },
    // guardo el evento en DB
    saveAppt(formData) {
      if (formData.title === "") {
        alert("no puedes reservar sin escribir el motivo del tratamiento");
        return;
      }
      // seteo el nuevo objeto con la hora de finalización
      let dataAppt = this.setDurationSesion(formData);
      if (!dataAppt) {
        alert(
          "No hemos podido procesar tu solicitud, inténtalo de nuevo pasados unos minutos"
        );
      }

      // Envío la petición al servidor
      Inertia.post(route("appointment.store"), dataAppt, {
        onSuccess: page => {
          if (Object.entries(page.props.errors).length === 0) {
            console.log(page);
            this.showModal = false;
            this.newEvent = this.resetModal();
            this.refreshCalendar();
          }
        }
      });
      // Capturo alguna excepción
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    },
    handleEventClick(clickInfo) {
      this.showModal = true;
      this.isEdit = true;
      this.loadModal(clickInfo);
    },
    setDurationSesion(form) {
      let dateApptComplete = form.date_at + " " + form.hour;
      let initSesion = new Date(dateApptComplete);
      let getSecondsSesion = initSesion.getSeconds() + form.session;
      initSesion.setSeconds(getSecondsSesion);

      let isDate = moment(initSesion).isValid();

      if (isDate) {
        return {
          id: form.id,
          title: form.title,
          start: dateApptComplete,
          end: formatTime(initSesion),
          session: form.session,
          user_id: form.user_id
        };
      }
      return false;
    },
    loadModal(obj) {
      this.newEvent = {
        id: obj.event.id,
        title: obj.event.title,
        date_at: obj.event.startStr.substr(0, 10),
        hour: obj.event.startStr.substr(11, 8),
        session: obj.event.extendedProps.session,
        user_id: obj.event.extendedProps.user_id
      };
    },
    resetModal() {
      return {
        id: "",
        title: "",
        date_at: "",
        hour: "",
        session: 1800,
        user_id: ""
      };
    },
    removeAppt(id) {
      Inertia.delete(route("appointment.destroy", `${id}`), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: page => {
          if (Object.entries(page.props.errors).length === 0) {
            this.showModal = false;
            this.newEvent = this.resetModal();
            let calendarApi = this.$refs.fullCalendar.getApi();
            let event = calendarApi.getEventById(id);
            event.remove();
          }
        }
      });
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    },
    // Actualizar Evento
    updateAppt(obj) {
      const copyAppt = { ...this.newEvent };
      let res = this.setDurationSesion(copyAppt);

      Inertia.put(route("appointment.update", `${res.id}`), res, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: page => {
          if (Object.entries(page.props.errors).length === 0) {
            this.showModal = false;
            this.newEvent = this.resetModal();
          }
          this.refreshCalendar();
        }
      });
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    }*/
  }
};
</script>