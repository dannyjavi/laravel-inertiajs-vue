<template>
  <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>
      <!-- This element is to trick the browser into centering the modal contents. -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-headline"
      >
        <form>
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class>
              <div class="mb-4">
                <label
                  for="exampleFormControlInput1"
                  class="block text-gray-700 text-sm font-bold mb-2"
                >Motivo:</label>
                <input
                  type="text"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  id="exampleFormControlInput1"
                  placeholder="Ingresa el motivo de la consulta"
                  :value="title"
                />
                <div v-if="$page.errors.title" class="text-red-500">{{ $page.errors.title[0] }}</div>
              </div>
              <div class="mb-4">
                <label
                  for="exampleFormControlInput1"
                  class="block text-gray-700 text-sm font-bold mb-2"
                >Fecha</label>
                <input
                  disabled="true"
                  type="text"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  id="exampleFormControlInput1"
                  placeholder="Enter pass"
                  :value="start"
                />
              </div>
              <div class="mb-4">
                <label
                  for="exampleFormControlInput2"
                  class="block text-gray-700 text-sm font-bold mb-2"
                >Hora</label>
                <input
                  disabled="true"
                  type="time"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                  id="exampleFormControlInput2"
                  :value="hour"
                />
              </div>
              <!-- start select -->
              <div class="col-span-6 sm:col-span-3">
                <label
                  for="timeSesion"
                  class="block text-sm font-medium leading-5 text-gray-700"
                >Duración</label>
                <select
                  :value="session"
                  id="timeSesion"
                  class="mt-1 block form-select w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                >
                  <option value="1800">Media sesión</option>
                  <option value="3600">Sesión completa</option>
                  <option value="900">Vendaje Neuromuscular</option>
                </select>
              </div>

              <!-- end select -->
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
              <button
                wire:click.prevent="store()"
                type="button"
                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                v-show="!editMode"
                @click="$emit('save',form)"
              >Save</button>
            </span>
            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
              <button
                wire:click.prevent="store()"
                type="button"
                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                v-show="editMode"
                @click="update(form)"
              >Update</button>
            </span>
            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
              <button
                @click="closeModal()"
                type="button"
                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5"
              >Cancel</button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Modal",
  props: ["errors", "start", "hour","title","session"],
  data() {
    return {
      isOpen: true,
      editMode: false,
      form: {
        title: this.title,
        start: this.start + " " + this.hour,
        session: this.time
      }
    };
  },
  computed:{
    time(){
      return (this.session !== '') ? this.form.session = this.session : "1800"
    }
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    resetForm() {},
    editForm(data) {},
    update(data) {},
    deleteRow(data) {}
  }
};
</script>

<style>
</style>


