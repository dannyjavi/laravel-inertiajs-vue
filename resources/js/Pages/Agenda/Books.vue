<template>
  <app-layout @blur="closeWindow">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calendar</h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <Calendar @openModal="dayClick" @handleEventClick="setEvent" />
        </div>
      </div>
    </div>
    <transition name="fade">
      <Modal
        v-if="showModal"
        :userApt="getTerm"
        :users="allUsers"
        :form="newEvent"
        :edit-mode="isEdit"
        @searchUser="searchUserDB"
        @closeModal="closeWindow"
        @saveAppt="saveAppt"
        @editAppt="updateAppt"
        @removeApt="deleteAppt"
      />
    </transition>
  </app-layout>
</template>

<script>
/* Componentes */
import Modal from "../../components/Modal";
import Calendar from "../../components/Calendar";
import AppLayout from "@/Layouts/AppLayout";

/* librerias */
import { Inertia } from "@inertiajs/inertia";
import NormalizeDate from "../../Mixins/transformDates";
import formatTime from "../../Mixins/transformTime";
import moment from "moment";

//import del bus para comunicación entre eventos
import EventBus from "../../bus/event-bus";

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
      userEvent: "",
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
  beforeCreate(){
    if(this.$page.user.id === ''){
      window.location('login')
    }
  },
  computed: {
    allUsers() {
      if (this.notResults.length === 1 && this.search.length === 0) {
        return this.notResults;
      }
      return this.search;
    },
    getTerm() {
      return this.userEvent;
    }
  },
  methods: {
    searchUserDB(user) {
      Inertia.replace(route("events", { q: user }), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: page => {
          // compruebo si tenemos resultados en caso de no tener muestro el mensaje
          if (page.props.search.length === 0) {
            if (this.notResults.length === 0) {
              this.notResults.push([
                {
                  name: "El usuario no se encuentra registrado en el sistema.",
                  profile_photo_url:
                    "https://cdn.pixabay.com/photo/2015/06/09/16/12/error-803716_960_720.png"
                }
              ]);
            }
            return;
          }
        }
      });
    },
    // Función para el evento clic dentro del calendario
    dayClick(arg) {
      this.showModal = true;
      this.setModalData(arg);
    },
    // Cargo los datos en el modal reactivo
    setModalData(dayTime) {
      
      let dateAndTime = dayTime.dateStr.split("T");
      this.newEvent.date_at = dateAndTime[0];
      this.newEvent.hour = dateAndTime[1].substr(0, 8);
      return;
    },
    // Cerramos el modal
    closeWindow() {
      this.showModal = false;
      this.newEvent = this.resetModal();
      return;
    },
    // Reinicio el objeto a sus valores iniciales
    resetModal() {
      this.userEvent = ''
      return {
        id: "",
        title: "",
        date_at: "",
        hour: "",
        user_id: "",
        session: 1800
      };
    },
    // Guardamos en db el evento
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
            this.showModal = false;
            this.newEvent = this.resetModal();
            EventBus.$emit("refresh");
          }
        }
      });
      // Capturo alguna excepción
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    },
    // Configuramos el tiempo que dura la sesión
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
    // Acción a ejecutar trás el clic en un evento existente
    async setEvent(clickInfo) {
      let user = clickInfo.event.extendedProps.user_id;

      if (this.$page.user.isAdmin) {
        let req = await fetch(`/users/${user}/event`);
        let dataJson = await req.json();
        this.userEvent = dataJson[0].name;
      }
      if(this.$page.user.isAdmin || this.$page.user.id === user){
        this.showModal = true;
        this.isEdit = true;
        this.loadModal(clickInfo);
      }else{
        alert('No puedes ejecutar esta acción')
      }
    },
    // Cargamos los datos del calendario al modal
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
    // Actualizamos el evento en DB
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
            EventBus.$emit("refresh");
            return;
          }
        }
      });
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    },
    // Borramos el evento de la DB
    deleteAppt(userId) {
      let data  = {
        _method: 'delete',
        id: userId
      }
      Inertia.delete(route('appointment.destroy',userId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: page => {
          if (Object.entries(page.props.errors).length === 0) {
            this.showModal = false;
            this.newEvent = this.resetModal();
            EventBus.$emit("refresh");
          }
        }
      });
      Inertia.on("error", event => {
        event.preventDefault();
        console.log(event.detail.error);
      });
    }
  }
};
</script>

<style>
</style>