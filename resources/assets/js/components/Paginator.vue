<script>
  export default {
    name: 'Paginator',
    props: [
      'dataSet'
    ],
    watch: {
      dataSet() {
        this.page = this.dataSet.current_page
        this.prevUrl = this.dataSet.prev_page_url
        this.nextUrl = this.dataSet.next_page_url
      },
      page() {
        this.broadcast()
          .updateUrl()
      }
    },
    data() {
      return {
        page: 1,
        prevUrl: false,
        nextUrl: false
      }
    },
    computed: {
      shouldPaginate() {
        return !!this.prevUrl || !!this.nextUrl
      }
    },
    methods: {
      broadcast() {
        return this.$emit('changed', this.page)
      },
      updateUrl() {
        history.pushState(null, null, `?page=${this.page}`)

        return this
      }
    }
  }
</script>

<template>
  <ul class="pagination" v-if="shouldPaginate">
    <li v-show="prevUrl">
      <a href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
        <span aria-hidden="true">&laquo; Anterior</span>
      </a>
    </li>

    <li v-show="nextUrl">
      <a href="#" aria-label="Previous" rel="next" @click.prevent="page++">
        <span aria-hidden="true">Próximo &raquo;</span>
      </a>
    </li>
  </ul>
</template>