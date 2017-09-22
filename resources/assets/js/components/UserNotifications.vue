<script>
  export default {
    name: 'UserNotifications',
    data() {
      return {
        notifications: []
      }
    },
    created() {
      axios.get(`/profiles/${App.user.name}/notifications`)
        .then(res => {
          this.notifications = res.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    methods: {
      markAsRead(notification) {
        axios.delete(`/profiles/${App.user.name}/notifications/${notification.id}`)
      }
    }
  }
</script>

<template>
  <li class="dropdown" v-if="notifications.length">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <span class="glyphicon glyphicon-bell"></span>
    </a>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications" :key="notification.id">
        <a :href="notification.data.link"
           v-text="notification.data.message"
           @click="markAsRead(notification)"></a>
      </li>
    </ul>
  </li>
</template>