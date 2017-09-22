<script>
  export default {
    name: 'NewReply',
    computed: {
      signedIn() {
        return window.App.signedIn
      },
      endpoint() {
        return `${window.location.pathname}/replies`
      }
    },
    data() {
      return {
        body: '',
      }
    },
    methods: {
      addReply() {
        if (!this.body.trim()) return
        let vm = this
        axios.post(this.endpoint, {body: this.body})
          .then(({data}) => {
            vm.body = ''
            vm.$emit('created', data)
            flash('Your Reply Has Been Posted!')
            vm.$refs.textarea.focus()
          })
      }
    }
  }
</script>

<template>
  <div>
    <form v-if="signedIn" @submit.prevent="addReply">
      <div class="form-group">
                    <textarea rows="5"
                              name="body"
                              id="body"
                              class="form-control"
                              placeholder="Have something to say ?"
                              required
                              v-model="body"
                              ref="textarea"></textarea>
      </div>
      <button type="submit" class="btn btn-default">Post</button>
    </form>
    <p class="text-center" v-else>
      Please <a href="/login">sign in</a> to participate in this discussion
    </p>
  </div>
</template>