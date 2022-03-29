<template>
   <li v-if="successfulEntriesThisMonth(entries) > 0">
      <button @click="$emit('itemShow')">
        {{ name }}
        <span>({{ entrySuccess(entries)}}/{{ entries.length }} successes)</span>
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
    entrySuccess(entries){
      var entriesSuccessful = entries.filter(function(entry) {
        return entry.success_yes == "success_yes";
      });
      return entriesSuccessful.length;
    }
  },
  computed: {
  },
};
</script>