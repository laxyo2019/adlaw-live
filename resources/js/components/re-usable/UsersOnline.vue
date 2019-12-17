<template>
	<div class="dropdown d-flex">
	  <a class="nav-link icon" title="Online Users" data-toggle="dropdown">
	    <i class="fe fe-user"></i>
	    <span class="badge badge-pill badge-info" v-text="users.length"></span>
	  </a>
	  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
	    <a href="#" class="dropdown-item d-flex" v-for="user in users">
				<strong>{{ user.name }}</strong>
	    </a>
	  </div>
	</div>
</template>

<script>
	export default {
		mounted() {
			Echo.join('UserOnline')
	    .here((users) => {
	    	console.log(users);
		    this.users = users;
	    })
	    .joining((user) => {
	    	this.users.push(user);

	    	if(this.onlineTimer) {
	    		clearTimeout(this.onlineTimer);
	    	}

	    	this.onlineTimer = setTimeout(() => this.userOnline(user), 1000*10);
	    })
	    .leaving((user) => {
	    	this.users = this.users.filter(u => (u.id !== user.id));

	    	if(this.offlineTimer) {
	    		clearTimeout(this.offlineTimer);
	    	}

	    	this.offlineTimer = setTimeout(() => this.userOffline(user), 1000*10);
	    });
		},
		data() {
			return {
				users: [],
				onlineTimer: false,
				offlineTimer: false
			}
		},
		methods: {
			userOnline(user) {
	      Vue.toasted.success(`${user.name} is online now`, {
	      	duration: 2000
	      });
			},
			userOffline(user) {
				Vue.toasted.show(`${user.name} went offline`, {
					duration: 2000
				});
			}
		}
	}
</script>