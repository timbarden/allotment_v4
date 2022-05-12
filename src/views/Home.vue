<template>
  <div class="home">
    <h1>Welcome to Allotment Diary</h1>
  </div>
</template>

<script>
export default {
  name: "home",
  methods: {},
};
</script>

<style lang="scss">
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
.home {
  padding: 2em 5%;
  flex: auto;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(#000, 0.8);
  color: #fff;
}
</style>
