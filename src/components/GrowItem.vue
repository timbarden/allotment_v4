<template>
    <li>
        <div>
          {{ name }}
                <span v-if="entries">({{ entries.length }} 
                <span v-if="entries.length == 1">entry</span>
                <span v-else>entries</span>)
            </span>
        </div>
        <div class="planner__item__btns">
            <button class="btn--sq" @click="$emit('itemShow')">
                <img src="../assets/pencil-outline.svg" alt="Edit" />
            </button>
            <button class="btn--sq" @click="$emit('itemDelete')">
                <img src="../assets/trash-outline.svg" alt="Delete" />
            </button>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
            };
        },
        props: [ "name", "entries" ],
        methods: {
        
        }
    }
</script>

<style lang="scss" scoped>
.planner__list {
    li {
        padding: 0.7em 0.7em 0.7em 1em;
        margin-top: 1.25em;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0.3em 0.3em 0.5em 0 rgba(0, 0, 0, 0.05);
        border-left: 20px solid #000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        div > span {
            opacity: 0.3;
            font-size: 0.8em;
        }
    }
}
.planner__item__btns button {
  margin-left: 0.5em;
}
</style>