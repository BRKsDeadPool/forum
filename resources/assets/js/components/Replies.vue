<script>
  import Reply from './Reply.vue'
  import NewReply from './NewReply.vue'

  export default {
    components: {
      Reply,
      NewReply
    },
    data() {
      return {
        items: this.data
      }
    },
    name: 'Replies',
    props: ['data'],
    methods: {
      remove(index) {
        this.items.splice(index, 1);
        this.$emit('remove');
        flash('Your reply has been deleted!')
      },
      add(reply) {
        this.items.push(reply)
        this.$emit('add')
      }
    },
    computed: {
      endpoint() {
        return `${window.location.pathname}/replies`
      }
    }
  }
</script>

<template>
  <div>
    <div v-for="(reply, i) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(i)"></reply>
    </div>

    <new-reply @created="add" :endpoint="endpoint"></new-reply>
  </div>
</template>