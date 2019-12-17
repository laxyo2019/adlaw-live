<template>
	<div class="container">
			<div class="card-header">
				<a style="color:#467fcf" class="" @click.prevent="$emit('unfocus')">
					<i class="fa fa-arrow-left"></i>
				</a>
				<b class="col-2">{{taskbaord.title}}</b>
				<div class="col-10 text-right">
					<a style="color:#fff" class="btn btn-primary btn-sm mr-2" @click.prevent="permissionsIndex='create'; permissionsType()">CREATE</a>
					<a style="color:#fff" class="btn btn-primary btn-sm mr-2" @click.prevent="permissionsIndex='edit'; permissionsType()">EDIT</a>
					<a style="color:#fff" class="btn btn-primary btn-sm mr-2" @click.prevent="permissionsIndex='delete'; permissionsType()">DELETE</a>
				</div>
			</div>
			<div class="card-body">
				<p class="text-center">
					<span class=" mr-2"><b>Permissions <i class="fa fa-arrow-right m-2">	</i>{{permissionsIndex.toUpperCase()}} </b> </span>		
					<button class="pull-right btn btn-success" @click="updatePermissions">Update</button>
				</p>
				<hr>
				<div class="clearfix"></div>
	      <user-selector :users='usersInTeam' 
	      	:selectedUsersProp='selectedUsers' 
	      	@input="selectedUsers = $event"></user-selector>
			</div>
	</div>
</template>
<script>
import Validation from '../../../utils/Validation.js';
export default {
	props: ['focusedTaskboard'],
	components : {
		
	},
	data () {
		return {
			permissionsIndex:'create',
			taskbaord : this.focusedTaskboard,
			validation: new Validation(),
			usersInTeam:{},
			selectedUsers:[],
		}
	},
	mounted() {
		this.getTeamUsers();
	},
	methods: {
		permissionsType(){
			let permissionsType = this.permissionsIndex;
			let users= (JSON.parse(this.focusedTaskboard.permissions))[permissionsType];
			this.selectedUsers = users;
		},
		getTeamUsers(){
			axios.post(`/admin/taskboards/getusers`,this.taskbaord).then(response=>{
				console.log(response.data);
				this.usersInTeam = response.data;
			}).catch(error=>{

			});
		},
		updatePermissions(){
			axios.post('/admin/taskbaord/update_permissions',{
					permissionsIndex:this.permissionsIndex,
					id:this.taskbaord.id,
					users:this.selectedUsers})
			.then(response => {
					this.taskbaord.permissions = response.data.permissions;
					Vue.toasted.show("Updated Successfully", {
							type : 'success',
							icon : 'check',
							duration: 2000
					});
			}).catch(error => {
				console.log(error);
			});
		},
	},
	created () {
		this.permissionsType();
	}
}
</script>
