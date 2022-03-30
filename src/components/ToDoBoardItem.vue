<template>
    <li v-if="entryMatchType(entries, boardType) > 0">
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
  props: [ "name", "entries", "boardType" ],
  methods: {
    entryMatchType(entries, boardtype) {
        console.clear();
        console.log(this.boardType);
        var currentDate = new Date(),
            entriesMatch = entries.filter(function(entry) {
                var thisDate = new Date(entry[boardtype]);
                return ((currentDate.getMonth() == thisDate.getMonth()) && (entry.success_yes == "success_yes"));
            });
        return entriesMatch.length;
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