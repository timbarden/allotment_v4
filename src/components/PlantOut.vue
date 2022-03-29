<template>
   <li v-if="plantOutThisMonth(entries) > 0">
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
    plantOutThisMonth(entries) {
      var currentDate = new Date(),
          entriesThisMonth = entries.filter(function(entry) {
            var thisPlantDate = new Date(entry.plantdate);
            return ((currentDate.getMonth() == thisPlantDate.getMonth()) && (entry.success_yes == "success_yes"));
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