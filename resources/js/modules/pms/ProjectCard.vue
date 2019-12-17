<template>
	<div class="card">
	  <a :href="`/pms/team/${team.id}`" style="text-decoration:none;">
	    <div class="card-status" :class="bgColor"></div>
	    <div class="card-header">
	      <h3 class="card-title " style="font-weight: bold" v-text="team.name"></h3>
	      <div class="card-options">
	        <!-- <span v-if="notifyCount > 0" class="badge badge-pill badge-danger">{{ notifyCount }}</span>
  	    	<span v-else><i class="text-success fa fa-check-circle"></i></span> -->
	      </div>
	    </div>
	    <!-- <div class="card-body">
	    	<div class="avatar-list">
	    		<a v-for="user in randomUsers" :href="`/user/profile/${user.id}`" :title="user.name">
	    			<span class="avatar" :style="{'background-image': 'url('+ avatars[user.id]+')'}"
	    			  v-if="avatars[user.id] != ''"></span>
	    			<span v-else class="avatar" :class="getRandomClass()">{{ getInitials(user.name) }}</span>
	    		</a>

	    	  <span v-if="extraUsers > 0" class="avatar" v-text="`+${extraUsers}`"></span>
	    	</div>
	    </div> -->
	  </a>            
	</div>
</template>

<script>
	export default {
		props: ['team', 'users'],
		data() {
			return {
				bgColor: this.getBgColor(this.team),
				// randomUsers: {},
				// extraUsers: 0,
				// notifyCount: 0
			}
		},
		mounted() {
			// this.sortUsers();
		},
		methods: {
			getBgColor(team) {
				if(team.type == 1) {
					return 'bg-orange';
				} else if(team.type == 2) {
					return 'bg-lime';
				} else if(team.type == 3) {
					return 'bg-cyan';
				}
			},
			// sortUsers() {
			// 	if(this.users.length >= 5){
			// 		this.randomUsers = this.getRandomElements(this.users, 4);
			// 		this.extraUsers = 1;
			// 	}else{
			// 		this.randomUsers = this.users;
			// 	}
			// },
			// getInitials(name) {
			//   let initials = name.match(/\b\w/g) || [];
			//   return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
			// },
			// getRandomClass() {
			//   let items = ['avatar-blue', 'avatar-azure','avatar-indigo','avatar-purple', 'avatar-pink','avatar-red',
			//   'avatar-orange', 'avatar-yellow', 'avatar-lime', 'avatar-green', 'avatar-teal', 'avatar-cyan'];
			//   return items[Math.floor(Math.random()*items.length)];
			// },
			// getRandomElements(arr, n) {
			//     var result = new Array(n),
			//         len = arr.length,
			//         taken = new Array(len);
			//     if (n > len)
			//         throw new RangeError("getRandom: more elements taken than available");
			//     while (n--) {
			//         var x = Math.floor(Math.random() * len);
			//         result[n] = arr[x in taken ? taken[x] : x];
			//         taken[x] = --len in taken ? taken[len] : len;
			//     }
			//     return result;
			// },
		}
	}
</script>