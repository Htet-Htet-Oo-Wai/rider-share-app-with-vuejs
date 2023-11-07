<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div
      class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0"
    >
      <a
        href="#"
        class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white"
      >
        <img
          class="w-8 h-8 mr-2"
          src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
          alt="logo"
        />
        Rider Share App
      </a>
      <div
        class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
      >
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1
            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
          >
            Driver and Car Details
          </h1>
          <form
            class="space-y-4 md:space-y-6"
            action="#"
            @submit.prevent="handleSaverDriver"
          >
            <div>
              <label
                for="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter name</label
              >
              <input
                type="text"
                name="name"
                id="name"
                v-model="driverDetails.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Full Name"
                required=""
              />
            </div>
            <div>
              <label
                for="year"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter year</label
              >
              <input
                type="number"
                name="year"
                v-model="driverDetails.year"
                id="year"
                placeholder="Car Year"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div>
              <label
                for="make"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter make</label
              >
              <input
                type="text"
                name="make"
                id="make"
                v-model="driverDetails.make"
                placeholder="Car Make"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div>
              <label
                for="model"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter model</label
              >
              <input
                type="text"
                name="model"
                id="model"
                v-model="driverDetails.model"
                placeholder="Car Model"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div>
              <label
                for="color"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter color</label
              >
              <input
                type="text"
                name="color"
                id="color"
                v-model="driverDetails.color"
                placeholder="Car Color"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div>
              <label
                for="license_plate"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter license plate</label
              >
              <input
                type="text"
                name="license_plate"
                id="license_plate"
                v-model="driverDetails.license_plate"
                placeholder="Car License Plate"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div class="text-center lg:text-left">
              <button
                type="submit"
                @click.prevent="handleSaverDriver"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Continue
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { reactive } from "vue";
import http from "@/helpers/http.js";
import { useRouter } from "vue-router";

const router = useRouter();
const driverDetails = reactive({
  name: "",
  year: null,
  make: "",
  model: "",
  color: "",
  license_plate: "",
});
const handleSaverDriver = () => {
  http()
    .post("/api/driver", driverDetails)
    .then((response) => {
      router.push({
        name: "standBy",
      });
    })
    .catch((error) => {
      console.error(error);
    });
};
</script>
<style>
</style>
