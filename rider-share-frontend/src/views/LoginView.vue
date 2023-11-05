<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div
      class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
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
            Login to your account
          </h1>
          <form
            v-if="!waitingOnVerification"
            class="space-y-4 md:space-y-6"
            action="#"
            @submit.prevent="handleLogin"
          >
            <div>
              <label
                for="email"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter email</label
              >
              <input
                type="email"
                name="email"
                v-model="credentials.email"
                id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="name@company.com"
                required=""
              />
            </div>
            <div>
              <label
                for="phone"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter Phone</label
              >
              <input
                type="text"
                v-maska
                data-maska="# (###) ###-####"
                name="phone"
                id="phone"
                v-model="credentials.phone"
                placeholder="1 (234) 567-8910"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div class="text-center lg:text-left">
              <button
                type="submit"
                @submit.prevent="handleLogin"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Continue
              </button>
            </div>
          </form>
          <form
            v-else
            class="space-y-4 md:space-y-6"
            action="#"
            @submit.prevent="handleVerification"
          >
            <div>
              <label
                for="login_code"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Enter Login Code</label
              >
              <input
                type="text"
                name="login_code"
                id="login_code"
                v-model="credentials.login_code"
                placeholder="******"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required=""
              />
            </div>
            <div class="text-center lg:text-left">
              <button
                type="submit"
                @submit.prevent="handleVerification"
                data-te-ripple-init
                data-te-ripple-color="light"
                class="inline-block w-full rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-md transition duration-150 ease-in-out hover:bg-neutral-900 hover:shadow-lg focus:bg-neutral-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-neutral-900 active:shadow-lg"
              >
                Verify
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { vMaska } from "maska";
import { ref, reactive, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const credentials = reactive({
  phone: null,
  email: null,
});
const waitingOnVerification = ref(false);

onMounted(() => {
  if (localStorage.getItem("token")) {
    router.push({
      name: "landing",
    });
  }
});

const getformattedCredentials = () => {
  return {
    phone: credentials.phone
      .replaceAll(" ", "")
      .replace("(", "")
      .replace("-", "")
      .replace(")", ""),
    email: credentials.email,
    login_code: credentials.login_code,
  };
};
const handleLogin = () => {
  axios
    .post("http://127.0.0.1:8000/api/login", getformattedCredentials())
    .then((response) => {
      console.log(response.data);
      waitingOnVerification.value = true;
    })
    .catch((error) => {
      alert(error.response.data.message);
    });
};

const handleVerification = () => {
  axios
    .post("http://127.0.0.1:8000/api/login/verify", getformattedCredentials())
    .then((response) => {
      localStorage.setItem("token", response.data);
      router.push({
        name: "landing",
      });
      console.log(response.data);
    })
    .catch((error) => {
      alert(error.response.data.message);
    });
};
</script>
<style>
</style>
