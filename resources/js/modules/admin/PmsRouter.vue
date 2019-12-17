<template>
  <div class="container">
    <card-component>
      <template slot="title">Laxyo Mission Control</template>
      <template slot="options">
        <router-link class="btn btn-secondary btn-sm" to="/">Home</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/tasks">TaskBoard</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/posts">MessageBoard</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/schedules">Schedules</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/docs">Files</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/checkins">Agenda</router-link>
        <router-link class="btn btn-secondary btn-sm ml-2" to="/chats">Teamchat</router-link>
      </template>
      <template slot="body">
        <router-view></router-view>
      </template>
    </card-component>  
  </div>
</template>

<style>
.router-link-exact-active {
  color: #fff;
  background-color: #316cbe;
  border-color: #2f66b3;  
}
</style>


<script>
import VueRouter from 'vue-router'
Vue.use(VueRouter)

const routes = [
  {
    path: '/tasks',
    name: 'Task',
    component: require('./routes/pms/ManageTaskboard.vue').default,
  },
  {
    path: '/posts',
    name: 'Post',
    component: require('./routes/pms/ManageMessageboard.vue').default
  },
  {
    path: '/schedules',
    name: 'Schedule',
    component: require('./routes/pms/ManageCalender.vue').default
  },
  {
    path: '/files',
    name: 'File',
    component: require('./routes/pms/ManageFilestack.vue').default
  },
  {
    path: '/agenda',
    name: 'Agenda',
    component: require('./routes/pms/ManageChecklist.vue').default
  },
  {
    path: '/teamchat',
    name: 'TeamChat',
    component: require('./routes/pms/ManageChatroom.vue').default
  }
]  

const router = new VueRouter({
  routes
})

export default {
  router,
  
  data() {
    return {

    }
  },
  created() {
    
  }
  
}
</script>
