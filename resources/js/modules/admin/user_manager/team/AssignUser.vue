<template>
	<div class="assign_users">
		<span>
			<a href="#" @click.prevent="back"><i class="fe fe-arrow-left"></i></a>  Add Users to { {{ team.name }} } Team
		</span>
		<div class="card-body">
			<div class="row">
				<div class="col">
					 	<!-- <p-check class="col-3" color="success"
					  	v-for="user in users"
					  	v-model="added_users"
					  	:value="user.id"
					  	:key="user.id"
					  	>
				         {{user.name}}
				    </p-check> -->

						<user-selector 
							:users="users"
							:selectedUsersProp="added_users" 
							@input="agenda[selectedTab] = $event">
						</user-selector>
			
				</div>
				<div class="form-group col-3">
					<button class="form-control btn btn-primary" @click="selectAll()">Select all Users</button>
					<button class="form-control btn btn-danger mt-2 mb-2" @click="deSelectAll()">Deselect all Users</button>
					<button class="form-control btn btn-success" @click="addUsers(team.id)">Update</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Multiselect from 'vue-multiselect'

export default {
	name: 'AssignUser',
	components: { Multiselect },
	data () {
		return {
			team: {},
			added_users: [],
			users: []
		}
	},
	created () {
		let uri = `/admin/teams/${this.$route.params.id}`;
		axios.get(uri).then(response => {
			this.team = response.data;
			this.added_users = response.data.users_in_team.map((e) => { return e.id });
		}).catch(error => {
			console.log(error.response);
		});

		axios.get('/admin/users').then(response => {
			this.users = response.data;
		}).catch(error => {
			console.log(error.response.data);
		})
	},
	methods: {
		deSelectAll(){
			this.added_users = [];
		},
		selectAll(){
			this.added_users = this.users.forEach((e) => { return e.id });
		},
		back() {
			this.$router.push({ name: 'Team' });
		},
		addUsers (id) {
			let uri = `/admin/teams/${id}/add_users/`;
			axios.post(uri, {
				users: this.added_users
			}).then(response => {
				Vue.swal('Users Updated');
			}).catch(error => {
				console.log(error.response.data);
			})
		}
	}
}
</script>
<style>
.custom_card_style {
	max-height: 400px;
	overflow-y: auto;
}
.custom_card_style ul {
	list-style-type: none;
	padding: 0;
}
.custom_card_style ul li {
	width: 100%;
	padding: 5px;
	color: #000;
}
.custom_card_style ul li:hover {
	background: #f2f4f7;
	cursor: pointer;
}
</style>