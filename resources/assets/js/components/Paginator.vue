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
        return this.prevUrl || this.nextUrl
      }
    }
  }
</script>

<template>
  <ul class="pagination" v-if="shouldPaginate">
    <li v-show="prevUrl">
      <a href="#" aria-label="Previous" rel="prev">
        <span aria-hidden="true">&laquo; Anterior</span>
      </a>
    </li>

    <!-- TODO: EP=38; TM=17:27 -->

    <li v-show="nextUrl">
      <a href="#" aria-label="Previous" rel=next">
        <span aria-hidden="true">Próximo &raquo;</span>
      </a>
    </li>
  </ul>
</template>