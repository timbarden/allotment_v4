<template>
    <div> 
        <section class="log" v-for="board in boards" :key="board.title">
            <h2>{{ board.title }} this month</h2>
            <ol>
                <ToDoBoardItem 
                  v-for="item in growList"
                  :key="item.id"
                  :name="item.name"
                  :entries="item.entries"
                  :boardType="board.data"
                  @itemShow="itemShow(item.id)"
                />
            </ol>
        </section>
    </div>
</template>

<script>
import ToDoBoardItem from "../components/ToDoBoardItem";

export default {
  data() {
    return {};
  },
  props: [ "growList", "boards", "itemShow" ],
  components: {
    ToDoBoardItem,
  },
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