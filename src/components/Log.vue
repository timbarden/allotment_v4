<template>
    <section class="log">
      <h2>Recent changes</h2>
      <ol>
        <li 
            v-for="item in list" 
            :key="item.id">
                {{ item }}
        </li>
      </ol>
    </section>
</template>

<script>
    export default {
        data() {
            return {
            };
        },
        props: [ "list" ],
        methods: {
        
        }
    }
</script>

<style lang="scss" scoped>
.log {
    margin-top: 2em;
    background: #F3F3F3;
    border-radius: 5px;
    padding: 1.5em;
    ol {
        margin: 1em 0 0;
        padding: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        li {
            margin: .15em 0;
            padding: .45em 0 .25em;
            &:not(:first-child){
                border-top: 1px solid #DDD;
            }
        }
    }
}

@media screen and (min-width: 900px) {
  .log {
    width: calc(40% - 3em);
    min-height: 15em;
    margin-top: 1.15em;
    align-self: flex-start;
  }
}
</style>