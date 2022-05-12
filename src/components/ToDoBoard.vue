<template>
    <div> 
        <section class="log" v-for="board in objBoards" :key="board.title">
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
  components: {
    ToDoBoardItem,
  },
  computed: {
  },
  methods: {
  },
  props: [ "growList", "objBoards", "itemShow" ],
};
</script>