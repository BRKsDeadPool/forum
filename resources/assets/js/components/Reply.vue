<script>
  import Favorite from './Favorite.vue'
  import moment from 'moment'

  export default {
    components: {
      Favorite
    },
    props: [
      'data'
    ],
    data() {
      return {
        editing: false,
        id: this.data.id,
        body: this.data.body
      }
    },
    computed: {
      signedIn() {
        return window.App.signedIn
      },
      canUpdate() {
        return this.authorize(user => parseInt(this.data.user_id) === parseInt(user.id))
      },
      ago() {
        return moment(this.data.created_at).fromNow() + '...'
      }
    },
    methods: {
      update() {
        let vm = this
        axios.patch('/replies/' + this.data.id, {
          body: this.body
        })
          .then(res => {
            vm.editing = false
            flash('Updated!')
          })
      },
      destroy() {
        let vm = this
        axios.delete('/replies/' + this.data.id)
          .then(res => {
            vm.$emit('deleted', this.data.id)
          })
      }
    }
  }
</script>

<template>
  <div :id="'reply-' + id" class="panel panel-default">
    <div class="panel-heading">
      <div class="level">
        <h5 class="flex">
          <a :href="`/profiles/${data.owner.name}`"
             v-text="data.owner.name">
          </a>
          respondeu <span v-text="ago"></span>
        </h5>

        <div v-if="signedIn">
          <favorite :reply="data"></favorite>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" v-model="body"></textarea>
        </div>
        <button class="btn btn-xs btn-primary" @click="update">Atualizar</button>
        <button class="btn btn-xs btn-link" @click="editing = false">Cancelar</button>
      </div>
      <div v-else v-text="body"></div>
    </div>
    <div class="panel-footer level" v-if="canUpdate">
      <button class="btn btn-xs mr-1" @click="editing = true">Editar</button>
      <button class="btn btn-danger btn-xs mr-1" @click="destroy">Remover</button>
    </div>
  </div>
</template>