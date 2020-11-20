<template>
  <app-layout @blur="closeWindow">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calendar {{ allUsers }}</h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <Calendar @openModal="dayClick" />
        </div>
      </div>
    </div>
    <Modal
      v-if="showModal"
      :users="allUsers"
      :form="newEvent"
      :edit-mode="isEdit"
      @searchUser="searchUserDB"
      @closeModal="closeWindow"
      @saveAppt="saveAppt"
      @deleteApt="removeAppt"
      @editAppt="updateAppt"
    />
  </app-layout>
</template>

<script>
import Calendar from "../../components/Calendar";
import AppLayout from "@/Layouts/AppLayout";
/* librerias */
import { Inertia } from "@inertiajs/inertia";
import NormalizeDate from "../../Mixins/transformDates";
import formatTime from "../../Mixins/transformTime";
import moment from "moment";

import Modal from "../../components/Modal";

export default {
  name: "Books",
  props: {
    search: Array
  },
  components: {
    Calendar,
    AppLayout,
    Modal
  },
  data() {
    return {
      showModal: false,
      isEdit: false,
      newEvent: {
        id: "",
        title: "",
        date_at: "",
        hour: "",
        user_id: "",
        session: 1800
      },
      notResults: []
    };
  },
  computed: {
    allUsers() {
      console.warn('midiendo largo: ',this.notResults.length);
      if (this.notResults.length === 1 && this.search.length === 0) {
        return this.notResults;
      }
      return this.search;
    }
  },
  methods: {
    searchUserDB(user) {
      if (this.search === "") console.log("Buscar paciente");

      Inertia.replace(route("events", { q: user }), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: page => {
          // compruebo si tenemos resultados en caso de no tener muestro el mensaje
          if (page.props.search.length === 0) {
            console.warn("Paciente no encontrado");
            if(this.notResults.length === 0){
              console.log('ahora guardo el mensaje');
              this.notResults.push([
              {
                name: "no hay resultados",
                profile_photo_url:
                  "https://cdn.pixabay.com/photo/2015/06/09/16/12/error-803716_960_720.png"
              }])
            }
            return;
          }
        }
      });
      /* Inertia.get('/events', {q: user }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess:(page)=>{
          // compruebo que el array está vacío
          if(page.props.search.length === 0){
            console.warn('Paciente no encontrado');
            return
          }
          console.error('Hay pacientes: ', page.props.search[0]);
        }
      }); */

      /* if (this.search !== null | this.search === null) {
        console.warn('Buscando paciente ...');
        if (this.search.hasOwnProperty("data") && this.search.data.length >= 0) {
          console.log("Hay coincidencias");
        }else{
          console.warn('No hay coincidencias para ',user)
        }
      } */
    },
    // Función para el evento clic dentro del calendario
    dayClick(arg) {
      this.showModal = true;
      //this.setModalData(arg);
    },
    //Cargo los datos en el modal reactivo
    setModalData(dayTime) {
      this.newEvent.user_id = this.$page.user.id;
      let dateAndTime = dayTime.dateStr.split("T");
      this.newEvent.date_at = dateAndTime[0];
      this.newEvent.hour = dateAndTime[1].substr(0, 8);
      return;
    },
    closeWindow() {
      this.showModal = false;
      this.newEvent = this.resetModal();
      return;
    },
    resetModal() {
      return {
        session: 1800
      };
    },
    saveAppt() {},
    removeAppt() {},
    updateAppt() {}
  }
};
</script>

<style>
</style>