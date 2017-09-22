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
      fetch(page) {
        axios.get(this.url(page))
          .then(this.refresh)
      },
      refresh({data}) {
        this.dataSet = data
        this.items = data.data

        window.scrollTo(0, 0)
      },
      url(page) {
        if (!page) {
          let query = location.search.match(/page=(\d+)/)

          page = query ? query[1] : 1
        }
        return `${window.location.pathname}/replies?page=${page}`
      },
    }
  }
</script>

<template>
  <div>
    <div v-for="(reply, i) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(i)"></reply>
    </div>

    <paginator :data-set="dataSet" @changed="fetch"></paginator>

    <new-reply @created="add"></new-reply>
  </div>
</template>