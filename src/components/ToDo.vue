<template>
   <li v-if="successfulEntriesThisMonth(entries) > 0">
      <button @click="$emit('itemShow')">
        {{ name }}
        <span>({{ successfulEntriesThisMonth(entries)}}/{{ entries.length }} successes)</span>
      </button>
    </li>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: [ "name", "entries" ],
  methods: {
    successfulEntriesThisMonth(entries) {
      var currentDate = new Date(),
          entriesThisMonth = entries.filter(function(entry) {
            var sowDate = new Date(entry.sowdate);
            return ((currentDate.getMonth() == sowDate.getMonth()) && (entry.success_yes == "success_yes"));
          });
      return entriesThisMonth.length;
    },
  },
  computed: {
  },
};
</script>

<style lang="scss">
@import '@/styles/base.scss';
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
    &:hover {
     button {
      opacity: .5;
     }
    }
    li {
      margin: 0.15em 0;
      pointer-events: none;
      &:not(:first-child) button {
        border-top: 1px solid #ddd;
      }
      button {
        @extend %btn_reset;
        pointer-events: auto;
        padding: 0.45em 0 0.25em;
        transition: opacity .2s ease;
        &:hover {
          opacity: 1;
        }
      }
      span {
        opacity: .8;
        font-size: .75em;
      }
    }
  }
}

@media screen and (min-width: 900px) {
  .log {
    position: sticky;
    top: 94px;
    width: 24em;
    min-height: 15em;
    margin-top: 1.15em;
  }
}
</style>