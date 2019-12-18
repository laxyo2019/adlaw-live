<template>
	<div class="row">
		<div class="col-md-12">
			
				<input style="padding:20px" @keyup="searchUser()" v-model="searchkey" class="form-control" placeholder="Search User..." >
			
		</div>
		<div class="col-md-12" style="height:300px; margin-top:10px; overflow-y: auto;">
			<p-check class="text-left col-md-10"
		        v-model="selectedUsers"
		        v-for="user in allUsers"
		        color="success"
		        :value="user.id"
		        :key="user.id">
		        {{user.name}}
			</p-check>
		</div>
	</div>
</template>
<script>
	export default {
		props: ['users', 'selectedUsersProp'],
		data() {
			return { 
				searchkey: '',
				selectedUsers: this.selectedUsersProp,
				allUsers: this.users,
			}
		},
		watch: {
		  selectedUsersProp: function (selectedUsersProp) {		  	
      	this.selectedUsers = this.selectedUsersProp;
	    },

	    // this function is written because the prop is coming late from parent (as it's a db request) and it keeps populating the loop
	    users: function (users) {
	      this.allUsers = this.users;
	      this.searchkey = '';
	      this.searchUser();
	    },
	    selectedUsers(selectedUsers) {
	    	this.$emit('input', this.selectedUsers);
	    }
	  },
		methods: {
			searchUser(){
				let searchReg = new RegExp(this.searchkey, "i");
				this.allUsers = this.users.filter((e) => { return e.name.match(searchReg) });
			}
		}
	}
</script>