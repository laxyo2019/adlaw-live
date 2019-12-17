<template>
  <div class="my-3 my-md-5">
    <div class="container">
			<card-component>
        <template slot="title">Site Config</template>
        <template slot="options">
          <router-link class="btn btn-primary btn-sm" to="/">Global Tags</router-link>
        </template>
        <template slot="body">
          <router-view></router-view>
        </template>
			</card-component>
    </div>
  </div>
</template>

<script>
import VueRouter from 'vue-router'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'GlobalTags',
    component: require('./routes/site_config/ManageGlobalTags').default
  }
]  

const router = new VueRouter({
  routes
})

export default {
  router
}
</script>