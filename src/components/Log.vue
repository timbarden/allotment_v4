<template>
  <section class="log">
    <h2>Recent changes</h2>
    <ol>
      <li v-for="item in list" :key="item.id">
        {{ item }}
      </li>
    </ol>
  </section>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: ["list"],
  methods: {},
};
</script>