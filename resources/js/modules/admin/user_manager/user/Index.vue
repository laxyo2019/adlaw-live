<template>
	<div class="user">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Manage Users</h3>
						<div class="card-options">
              <div class="input-group">
                <input @keyup="getResults" type="text" class="form-control form-control-sm" placeholder="Search..." v-model="searchKey">
                <span class="input-group-btn ml-2">
                  <button class="btn btn-sm btn-default" @click="getResults">
                    <span class="fe fe-search"></span>
                  </button>
                </span>
                <span class="input-group-btn ml-2">
                  <button class="btn btn-sm btn-default" @click="refresh">
                    <span class="fe fe-refresh-cw"></span>
                  </button>
                </span>
                 <span class="input-group-btn ml-2">
                  <a class="btn btn-sm btn-primary " href="/export_users">
                     Users Export
                  </a>
                </span>
              </div>
            </div>
					</div>
					<div class="card-body">
						<div class="row" v-if="!is_edit_mode">
							<div class="col-6 offset-3">
							  <div class="form-group">
							    <input type="text" class="form-control" v-model="name" placeholder="Name">
							    <div class="validation-message" v-text="validation.getMessage('name')"></div>
							  </div>
							  <div class="form-group">
							    <input type="text" class="form-control" v-model="email" placeholder="Email">
							    <div class="validation-message" v-text="validation.getMessage('email')"></div>
							  </div>
							</div>
							<div class="col-6 offset-3">
		        		<div class="text-center">
									<button class="btn btn-outline-success" @click="save">Save</button>
								</div>
							</div>
						</div>
						<div class="row" v-if="is_edit_mode">
							<div class="col-6 offset-3">
							  <div class="form-group">
							    <input type="text" class="form-control" v-model="user.name" placeholder="Name">
							    <div class="validation-message" v-text="validationEdit.getMessage('name')"></div>
							  </div>
							  <div class="form-group">
							    <input type="text" class="form-control" v-model="user.email" placeholder="Email">
							    <div class="validation-message" v-text="validationEdit.getMessage('email')"></div>
							  </div>
							</div>
							<div class="col-6 offset-3">
								<div class="text-center">
									<button class="btn btn-outline-warning" @click="update(user.id)">Update</button>
								</div>
							</div>
						</div>
						<br>
						<div class="table-responsive">
							<table class="table table-hover table-outline table-vcenter text-nowrap card-table">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Email</th>
										<th class="float-right">Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(user, index) in users.data" :key="index">
										<td>{{ user.id }}</td>  	
										<td>{{ user.name }}</td>
										<td>{{ user.email }}</td>
										<td class="float-right">
											
											<router-link :to="{ name: 'ViewUser', params: {id: user.id}}" >
												<i class="text-default fe fe-eye"></i>
											</router-link>

											<div class="item-action dropdown ml-3">
												<a style="text-decoration:none;" href="javascript:void(0)" data-toggle="dropdown"><i class="text-default fe fe-user-check"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<router-link class="dropdown-item" :to="{ name: 'AssignRole', params: {id: user.id}}" >
														Assign Roles
													</router-link>

													<router-link class="dropdown-item" :to="{ name: 'AssignPermissions', params: {id: user.id}}">
													 Assign Permissions
													</router-link>
												</div>
											</div>
											

											<div class="item-action dropdown ml-3">
												<a href="javascript:void(0)" data-toggle="dropdown"><i class="text-default fe fe-command"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<a class='dropdown-item' href="#" @click.prevent="editUser(user.id)">
														<i class="dropdown-icon fe fe-edit"></i> Edit User
													</a>
													<a class="dropdown-item" href="#" @click.prevent="sendCredentials(user.id)"><i class="dropdown-icon fe fe-send"></i> Reset Password</a>
													<a class="dropdown-item" href="#" @click.prevent="vacantUser(user.id)"><i class="dropdown-icon fa fa-crosshairs"></i> Vacant User</a>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						 	<pagination :data="users" @pagination-change-page="getResults" align="right">
			        	<span slot="prev-nav">Previous</span>
								<span slot="next-nav">Next</span>
	      			</pagination>
						</div>

					</div>
				</div>
			</div>
				
		</div>
	</div>
</template>
<script>
import Validation from '../../../../utils/Validation.js';
export default {
	name: 'User',
	data () {
		return {
			validation: new Validation(),
			validationEdit: new Validation(),
			searchKey: null,
			users: {},
			name: '',
			email: '',
			errors: [],
			is_edit_mode: false,
			user: {},
		}
	},
	mounted() {
			this.getResults();
		},
	methods: {
		export_file(){
			axios.get(`/export_users`).then(response =>{
				console.log(response.data);
			}).catch(error=>{
				console.log(error);
			});
		},
		vacantUser(id){
			axios.post('/vacant_user',{id:id}).then(response=>{
				console.log(response);
				if(response.status==201){
					this.users.data.forEach((v, k) => {
						 if (v.id == response.data.id)
						 Vue.set(this.users.data,k, response.data);
					});
					Vue.toasted.show('vacant User!!', {
							type : 'success',
							icon : 'check',
							duration: 2000
					});
				}else{
					Vue.toasted.show('vacant Already!!', {
							type : 'error',
							icon : 'warning',
							duration: 2000
					});
				}
			}).catch(error=>{
				console.log('error');
			});
		},
		getResults(page = 1) {
				axios.post('/users?page=' + page,{keyword:this.searchKey})
					.then(response => {
						this.users = response.data;
					});
			},
		refresh() {
			this.searchKey = null;
			this.getResults();
		},
		save() {
			let uri = '/admin/users';
			axios.post(uri, {
				name: this.name,
				email: this.email,
				// password: this.password,
				// password_confirmation: this.password_confirmation
			}).then(response => {
				this.users.data.unshift(response.data);
				this.name = '',
				this.email = '',
				this.validation = new Validation();
				Vue.toasted.show(`User Created!!`, {
					type : 'success',
					icon : 'check',
					duration: 2000
				});
			}).catch(error => {
	        if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	console.log(error.response.data);
	        }
	      });
		},
		getUser(id) {
			let uri = '/admin/users/'+id;
			axios.get(uri).then(response => {
				// console.log(response.data);
				this.get_user = response.data
			}).catch(error => {
				console.log(error.response)
			})
		},
		sendCredentials(user_id){
			let uri = '/admin/send-credentials/'+user_id;
			axios.get(uri).then(response => {
				console.log(response.data);
			})
		},
		editUser(user_id) {
			this.is_edit_mode = true
			let uri = '/admin/users/'+user_id+'/edit';
			axios.get(uri).then(response => {
				console.log(response.data)
				this.user = response.data

			}).catch(error => {
				console.log(error.response.data)
			})
		},
		update(user_id) {
			let uri = '/admin/users/'+user_id;
			axios.patch(uri, {
				name: this.user.name,
				email: this.user.email,
			}).then(response => {
				this.users.data.forEach((v, k) => {
					 if (v.id == response.data.id)
					 Vue.set(this.users.data,k, response.data);
				});
				this.is_edit_mode = false
				Vue.toasted.show(`User Updated!!`, {
					type : 'success',
						icon : 'check',
					duration: 2000
				});
			}).catch(error => {
	        if (error.response.status == 422) {
	          this.validationEdit.setMessages(error.response.data.errors);
	        }else{
	        	console.log(error.response.data);
	        }
	      });
		},
		// destroy (user_id) {
		// 	let uri = '/admin/users/'+user_id;
		// 	axios.delete(uri).then(response => {
		// 		// console.log(response)
		// 		this.users = response.data
		// 	}).catch(error => {
		// 		console.log(error.response.data)
		// 	}) 
		// },
	},
	created () {
		// let uri = '/admin/users';
		// axios.get(uri).then(response => {
		// 	this.users = response.data
		// }).catch(error => {
		// 	console.log(error)
		// });
	}
}
</script>