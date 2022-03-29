<template>
    <li class="planner__list__item">
        <button class="planner__list__item__edit" @click="$emit('itemShow')">
            {{ name }}
            <span v-if="entries">({{ entries.length }} 
                <span v-if="entries.length == 1">entry</span>
                <span v-else>entries</span>)
            </span>
        </button>
        <button class="planner__list__item__delete btn--sq" @click="$emit('itemDelete')">
            <img src="../assets/trash-outline.svg" alt="Delete" />
        </button>
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
@import '@/styles/base.scss';
.planner__list {
    &__item {
        @extend %flex_center;
        justify-content: space-between;
        margin-top: 1.25em;
        border-radius: 5px;
        position: relative;
        &__edit {
            width: 100%;
            background: #FFF;
            border-radius: inherit;
            text-align: left;
            border: 1px solid #DDD;
            border-left-width: 20px;
            border-left-color: inherit;
            padding: 1.25em 4.25em 1.25em 1.25em;
            box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.03);
            transition: box-shadow .2s ease;
            &:hover {
                box-shadow: 3px 3px 3px 0 rgba(0, 0, 0, 0.1);
            }
            > span {
                opacity: 0.3;
                font-size: 0.8em;
                white-space: nowrap;
            }
        }
        &__delete {
            position: absolute;
            top: 0;
            right: 1em;
            bottom: 0;
            margin: auto 0;
        }
    }
}
</style>