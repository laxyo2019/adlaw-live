<template>
  <div class="my-3 my-md-5">
    <div class="container">
			<div class="card">
        <div class="card-header">
          <router-link class="btn btn-outline-primary btn-sm" to="/">Roles & Permissions</router-link>
          <router-link class="btn btn-outline-primary btn-sm ml-2" to="/users">Users</router-link>
          <router-link class="btn btn-outline-primary btn-sm ml-2" to="/teams">Teams</router-link>
        </div>
        <div class="card-body">
          <router-view></router-view>
        </div>
			</div>
    </div>
  </div>
</template>

<script>

import VueRouter from 'vue-router'
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Permission',
    component: require('./permission_role/Index').default
  },
  {
    path: '/permission/edit/:id',
    name: 'EditPermission',
    component: require('./permission_role/EditPermission').default
  },
  {
    path: '/role/edit/:id',
    name: 'EditRole',
    component: require('./permission_role/EditRole').default
  },
  {
    path: '/users',
    name: 'User',
    component: require('./user/Index').default
  },
  {
    path: '/user/view/:id',
    name: 'ViewUser',
    component: require('./user/View').default
  },
  {
    path: '/user/edit/:id',
    name: 'EditUser',
    component: require('./user/Edit').default
  },
  {
    path: '/role/:id',
    name: 'AssignPermission',
    component: require('./permission_role/AssignPermission.vue').default
  },
  {
    path: '/user/assign_permissions/:id',
    name: 'AssignPermissions',
    component: require('./user/AssignPermission.vue').default
  },
  {
    path: '/user/assign_roles/:id',
    name: 'AssignRole',
    component: require('./user/AssignRole.vue').default
  },
  {
    path: '/teams',
    name: 'Team',
    component: require('./team/Index.vue').default
  },
  {
    path: '/team/assign_users/:id',
    name: 'AssignUser',
    component: require('./team/AssignUser.vue').default
  }
]  

const router = new VueRouter({
  routes
})

export default {
  router,
  data() {
    return {
      window: {
        width: 0,
        height: 0
      }
    }
  },
  created() {
    window.addEventListener('resize', this.handleResize)
    this.handleResize();
  },
  destroyed() {
    window.removeEventListener('resize', this.handleResize)
  },
  methods: {
    handleResize() {
      this.window.width = window.innerWidth;
      this.window.height = window.innerHeight;
    }
  }
}
</script>
