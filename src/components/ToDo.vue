<template>
  <section class="log">
    <h2>Sowing this month..</h2>
    <ol>
      <li v-for="item in toDo" :key="item.id">
        {{ item.name }}
      </li>
    </ol>
  </section>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: ["growList"],
  methods: {
    currentMonth: function() {
      var currentDate = new Date();
      return currentDate.getMonth();
    },
  },
  computed: {
    toDo() {
      var toDoList = this.growList.map((element) => {
        return {
          ...element,
          entries: element.entries.filter(function(subElement) {
            var currentDate = new Date(),
              sowDate = new Date(subElement.sowdate);
            return currentDate.getMonth() == sowDate.getMonth();
          }),
        };
      });
      toDoList = toDoList.filter((element) => {
        return element.entries.length > 0;
      });
      return toDoList;
    },
  },
};
</script>

<style lang="scss" scoped>
.log {
  width: 100%;
  margin-top: 2em;
  background: #f3f3f3;
  border-radius: 5px;
  padding: 1.5em;
  ol {
    margin: 1em 0 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    li {
      margin: 0.15em 0;
      padding: 0.45em 0 0.25em;
      &:not(:first-child) {
        border-top: 1px solid #ddd;
      }
    }
  }
}

@media screen and (min-width: 900px) {
  .log {
    width: 24em;
    min-height: 15em;
    margin-top: 1.15em;
  }
}
</style>
