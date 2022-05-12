<template>
  <div id="app">
    <Planner />
    <Error />
  </div>
</template>

<script>
import Error from "./../components/Error";
import Planner from "./../components/Planner";

export default {
  data() {
    return {
      title: process.env.VUE_APP_TITLE,
    };
  },
  components: {
    Error,
    Planner,
  },
  methods: {},
};
</script>

<style lang="scss">
* {
  box-sizing: border-box;
}
body {
  margin: 0;
  color: #333;
  background: #fff;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font: 400 1em/1.2 "Nunito", sans-serif;
}
h1,
h2,
h3,
h4,
h5,
h6 {
  margin: 0;
}
#app {
  padding: 0;
}
h1 {
  font: 600 3.5em/1.2 "Caveat", sans-serif;
}
</style>
