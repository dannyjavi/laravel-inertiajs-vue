<template>
  <div>
    <Fullcalendar
      ref="fullCalendar"
      :options="calendarOptions"
    />
<transition name='fade'>    
    <Modal v-if='showModal' :startTime='dateAppt' :endTime='endTime' @closeModal='closeWindow' @save='saveAppt'/>
    </transition>
  </div>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import Daygrid from "@fullcalendar/daygrid";
import Interaction from "@fullcalendar/interaction";
import TimeGrid from "@fullcalendar/timegrid";
import TimeList from "@fullcalendar/list";
import { Inertia } from "@inertiajs/inertia";
import formatDate from '../Mixins/transformDates'

import Modal from './Modal'

export default {
  name: "Calendar",
  components: {
    Fullcalendar,
    Modal
  },
  data() {
    return {
      url: '/appointment',
      dateAppt: '',
      endTime: '',
      showModal: false,
      calendarOptions: {
        plugins: [Daygrid, Interaction,TimeGrid, TimeList],
        headerToolbar: {
          left: "prev next today",
          center: "title",
          right: "dayGridMonth timeGridWeek timeGridDay"
        },
        locale: "es",
        initialView: "timeGridWeek",
        dateClick: this.handleDateClick,
        events: [
          { title: 'event 1', date: '2020-11-10 07:00:00' },
          { title: 'event 2', date: '2020-11-10T08:30:00' }
        ]
      }
    }
  },
  methods:{
    handleDateClick(arg){
      this.dateAppt = formatDate(new Date(arg.dateStr))
      this.endTime = arg.dateStr.substr(11, 8)
      this.showModal = true
    },
    closeWindow() {
      this.showModal = false
    },
    saveAppt(formData){
      Inertia.post(this.url,formData,{
        onSuccess:() =>{
          console.warn('cita guardada');
        }
      })
      Inertia.on('error',event =>{
        event.preventDefault()
        console.log(this.$page.error);
      })
    }
  }
};
</script>

<style>
.fade-enter-active{
    transition: opacity .5s;
}

.fade-leave-active {
transition: all .6s ease-out;
}

.fade-enter-from, .fade-leave-to {
  transition: all .8s ease ;
  transform: translateX(20px);
  opacity: 0;
}
</style>