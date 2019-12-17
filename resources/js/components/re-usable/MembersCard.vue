<template>
	<div class="card">
	  <div class="card-header">
	    	<a href="#" class="btn btn-primary btn-sm">All</a>
	    	<a href="#" class="btn btn-secondary btn-sm ml-2">Online</a>
	    <div class="card-options">
	      <form action="" _lpchecked="1">
	        <div class="input-group">
	          <input type="text" class="form-control form-control-sm" placeholder="Search a user..." 
	            v-model="searchKey" @keyup="getResults">
	        </div>
	      </form>
	    </div>
	  </div>
	  
	  <div class="card-body o-auto" style="height: 15rem">
	    <ul class="list-unstyled list-separated" v-for="user in usersList.data" :key="user.id">
	      <li class="list-separated-item">
	        <div class="row align-items-center">
	          <div class="col-auto">
	            <span class="avatar avatar-md d-block" :style="{'background-image': 'url('+ avatars[user.id]+')'}"
	            v-if="avatars[user.id] != ''">
	            </span>
	            <span v-else class="avatar avatar-md d-block" :class="getRandomClass()">{{ getInitials(user.name) }}</span>
	          </div>
	          <div class="col">
	            <div>
	              <a href="javascript:void(0)" class="text-inherit">{{user.name}}</a>
	            </div>
	            <small class="d-block item-except text-sm text-muted h-1x">{{ user.email }}</small>
	          </div>
	          <div class="col-auto">
	            <div class="item-action dropdown">
	              <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
	              <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
	                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-tag"></i> Action </a>
	                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Another action </a>
	                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-message-square"></i> Something else here</a>
	                <div class="dropdown-divider"></div>
	                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Separated link</a>
	              </div>
	            </div>
	          </div>
	        </div>
	      </li>
	    </ul>
	  </div>
	  <pagination class="mt-4" :data="usersList" @pagination-change-page="getResults" align="center">
	    <span slot="prev-nav">Previous</span>
	    <span slot="next-nav">Next</span>
	  </pagination>
	  <!-- <div class="card-footer">
	    
	  </div> -->
	</div>
</template>

<script>
	export default {
		props: ['users', 'logged_user'],
		components: {},
		data () {
			return {
        avatars: [],
				searchKey: '',
				usersList: {}
			}
		},
		mounted() {
			this.getResults();
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
		methods: {
      refresh() {
      	this.searchKey = '';
        this.getResults();
      },
      getInitials(name) {
        let initials = name.match(/\b\w/g) || [];
        return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
      },
      getRandomClass() {
        let items = ['avatar-blue', 'avatar-azure','avatar-indigo','avatar-purple', 'avatar-pink','avatar-red',
        'avatar-orange', 'avatar-yellow', 'avatar-lime', 'avatar-green', 'avatar-teal', 'avatar-cyan'];
        return items[Math.floor(Math.random()*items.length)];
      },
      getAvatars(users) {
        let ids = [];
        users.forEach(function(e){
          ids.push(e.id);
        });
        axios.post('/user/profile/get_avatars', {users: ids}).then(response => {
          console.log(response.data);
          this.avatars = response.data;
        }).catch(error => console.log(error.response.data));
      },
			getResults(page = 1) {
				axios.post(`/users?page=${page}`, {keyword: this.searchKey})
				.then(response => {
					this.usersList = response.data;
          this.getAvatars(this.usersList.data);
				});
			},
		}
	}
</script>