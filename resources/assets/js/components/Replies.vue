<script>
  import Reply from './Reply.vue'
  import NewReply from './NewReply.vue'
  import Collection from '../mixins/Collection'

  export default {
    mixins: [
      Collection
    ],
    components: {
      Reply,
      NewReply
    },
    data() {
      return {
        dataSet: null
      }
    },
    created() {
      this.fetch()
    },
    name: 'Replies',
    methods: {
      fetch() {
        axios.get(this.url())
          .then(this.refresh)
      },
      refresh({data}) {
        this.dataSet = data
        this.items = data.data
      },
      url() {
        return `${window.location.pathname}/replies`
      },
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

    <paginator :data-set="dataSet"></paginator>

    <new-reply @created="add" :endpoint="endpoint"></new-reply>
  </div>
</template>