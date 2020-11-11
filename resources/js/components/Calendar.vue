<template>
  <div>
    <Fullcalendar ref="fullCalendar" :options="calendarOptions" />
    <transition name="fade">
      <Modal
        v-if="showModal"
        :start="dateAppt"
        :hour="hourAppt"
        :events='events'
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
  props:['events'],
  data() {
    return {
      url: "/appointment",
      dateAppt: "",
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
        dateClick: this.handleDateClick,
        events: ''
      }
    };
  },
  created(){
    this.$data.calendarOptions.events = this.events
  },
  methods: {
    handleDateClick(arg) {
      this.dateAppt = arg.dateStr.substr(0,10)
      this.hourAppt = arg.dateStr.substr(11, 8);
      this.showModal = true;
    },
    closeWindow() {
      this.showModal = false;
    },
    saveAppt(formData) {
      let dataAppt = this.setTimeSesion(formData);

      Inertia.post(this.url,dataAppt,{
        onSuccess:() =>{
        this.showModal = false;
        }
      })
      Inertia.on('error',event =>{
        event.preventDefault()
        console.log(this.$page.error);
      })
    },
    setTimeSesion(form) {
      const timeSesion = parseInt(form.session)
      const initSesion = new Date(form.start);
      let getSecondsSesion = initSesion.getSeconds() + timeSesion
      initSesion.setSeconds(getSecondsSesion)

      let time = formatTime(initSesion)

      const objApt = {
        title: form.title,
        start: form.start,
        end: time,
        session: timeSesion,
        price: ''
      }
      return objApt
    }
  }
};
</script>

<style>
.fade-enter-active {
  transition: opacity 0.5s;
}

.fade-leave-active {
  transition: all 0.6s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  transition: all 0.8s ease;
  transform: translateX(20px);
  opacity: 0;
}
</style>