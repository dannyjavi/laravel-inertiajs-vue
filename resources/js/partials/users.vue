<template>
  <div>
    <div v-for="(user,index) in userList[0]" :key="index" class="flex flex-col w-full" @click.prevent="$emit('click', user)">
      <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-teal-100">
        <div
          class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative hover:border-teal-100"
        >
          <div class="w-6 flex flex-col items-center">
            <div
              class="flex relative w-5 h-5 bg-orange-500 justify-center items-center m-1 mr-2 mt-1 rounded-full"
            >
              <img class="rounded-full" alt="A" :src="user.profile_photo_url" />
            </div>
          </div>
          <div class="w-full items-center flex">
            <div class="mx-2 -mt-1">
              {{ user.name }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "users",
  props: {
    userList:{
      type: Array,
    }
  }
};
</script>