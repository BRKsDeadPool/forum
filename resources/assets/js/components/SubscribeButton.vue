<script>
  export default {
    name: 'SubscribeButton',
    props: {
      active: {
        type: Boolean
      }
    },
    computed: {
      classes() {
        return ['btn', this.active ? 'btn-primary' : 'btn-default']
      }
    },
    methods: {
      subscribe() {
        axios[(this.active ? 'delete' : 'post')](`${location.pathname}/subscriptions`)
          .then(res => {
            this.active = !this.active
          });
      }
    }
  }
</script>

<template>
  <button :class="classes" @click="subscribe">Subscribe</button>
</template>