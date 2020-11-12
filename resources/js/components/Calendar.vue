<template>
  <div>
    <Fullcalendar ref="fullCalendar" :options="calendarOptions" />
    <transition name="fade">
      <Modal
        v-if="showModal"
        :start="dateAppt"
        :title="title"
        :session="timeSesion"
        :hour="hourAppt"
        :events="events"
        @closeModal="closeWindow"
        @save="saveAppt"
      />
    </transition>
  </div>
</template>

<script>
import Fullcalendar, { formatDate } from "@fullcalendar/vue";
import Daygrid from "@fullcalendar/daygrid";
import Interaction from "@fullcalendar/interaction";
import TimeGrid from "@fullcalendar/timegrid";
import TimeList from "@fullcalendar/list";
import { Inertia } from "@inertiajs/inertia";
import FormatDate from "../Mixins/transformDates";
import formatTime from "../Mixins/transformTime";
import Modal from "./Modal";

export default {
  name: "Calendar",
  components: {
    Fullcalendar,
    Modal
  },
  props: ["events"],
  data() {
    return {
      url: "/appointment",
      dateAppt: "",
      hourAppt: "",
      title: "",
      timeSesion: "",
      endTime: "",
      showModal: false,
      calendarOptions: {
        plugins: [Daygrid, Interaction, TimeGrid, TimeList],
        headerToolbar: {
          left: "prev next today",
          center: "title",
          right: "dayGridMonth timeGridWeek timeGridDay"
        },
        locale: "es",
        initialView: "timeGridWeek",
        events: "",
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick
      }
    };
  },
  created() {
    this.getEvents();
  },
  methods: {
    getEvents() {
      this.$data.calendarOptions.events = this.events;
    },
    handleDateClick(arg) {
      this.dateAppt = arg.dateStr.substr(0, 10);
      this.hourAppt = arg.dateStr.substr(11, 8);
      this.showModal = true;
    },
    closeWindow() {
      this.showModal = false;
    },
    saveAppt(formData) {
      let dataAppt = this.setTimeSesion(formData);

      Inertia.post(this.url, dataAppt, {
        onSuccess: page => {
          if (Object.entries(page.props.errors).length === 0) {
            this.showModal = false;
          }
          this.getEvents();
        }
      });
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    },
    handleEventClick(clickInfo) {
      this.showModal = true;
      this.loadModal(clickInfo)
    },
    setTimeSesion(form) {
      const timeSesion = parseInt(form.session);
      const initSesion = new Date(form.start);
      let getSecondsSesion = initSesion.getSeconds() + timeSesion;
      initSesion.setSeconds(getSecondsSesion);

      let time = formatTime(initSesion);

      const objApt = {
        title: form.title,
        start: form.start,
        end: time,
        session: timeSesion,
        price: ""
      };
      return objApt;
    },
    loadModal(obj){
      this.title = obj.event.title;
      this.dateAppt = obj.event.startStr.substr(0, 10)
      this.hourAppt = obj.event.startStr.substr(11, 8)
      this.timeSesion = obj.event.extendedProps.session
    }
  }
};
</script>