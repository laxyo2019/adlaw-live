<template>
	<div class="permission">
		<div class="row">
			<div class="col-12">
		    <div class="text-center">
	    	  <a href="#" class="btn btn-outline-primary btn-sm" :class="{active : permission_tab}" @click="openPermissionTab">Manage Permissions</a>
        	<a href="#" class="btn btn-outline-primary btn-sm ml-2" :class="{active : role_tab}" @click="openRoleTab">Manage Roles</a>
		    </div>
			  <div class="card-body" v-if="permission_tab">
					<div class="row" v-if="permission_create_form">
            <div class="col-md-5">
              <div class="form-group">
                <input type="text" class="form-control" v-model="permission.name" placeholder="Permission Name">
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="form-group">
                <input type="text" class="form-control" v-model="permission.display_name" placeholder="Display Name">
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="form-group">
                <input type="email" class="form-control" v-model="permission.description" placeholder="Description">
              </div>
            </div>
            <div class="col-6 offset-3">
            	<div class="text-center">
            		<button :class="[btn_loading ? 'btn-loading' : '', 'btn', 'btn-outline-success']" @click="savePermission">Save</button>
            	</div>
            </div>
        	</div>
					<div class="row" v-if="edit_permission">
						<div class="col-md-5">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="permission.name" placeholder="Permission Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-3">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="permission.display_name" placeholder="Display Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-4">
						  <div class="form-group">
						    <input type="email" class="form-control" v-model="permission.description" placeholder="Description">
						  </div>
						</div>
						<div class="col-8 offset-2">
							<div class="text-center">
								<button :class="[btn_loading ? 'btn-loading' : '', 'btn', 'btn-outline-warning']" @click="updatePermission(permission.id)">Update</button>
								<button class="btn btn-outline-dark ml-2">Cancel</button>
							</div>
						</div>
					</div>
					<div class="card-body mt-3">
						<input type="text" class="col-4 offset-4 form-control" @keyup="searchPermissions()" v-model="permissionSearch" placeholder="Search">
						<div class="tags mt-3">
							<span class="tag m-2" :class="getRandomClass()"
								v-for="(permission, index) in pLoop"
								:key="index"
								@contextmenu.prevent="$refs.menu1.open($event, { p_id: permission.id })">{{ permission.name }}</span>
						</div>
					</div>
  				<!-- <a class="ml-3" href="#" data-toggle="modal" data-target="#deletePermissionModal" @click.prevent="getPermission(permission.id)"><i class="text-danger fe fe-trash"></i></a> -->
			  </div>
			  <div class="card-body" v-if="role_tab">
					<div class="row" v-if="role_create_form">
						<div class="col-md-5">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="role.name" placeholder="Role Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-3">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="role.display_name" placeholder="Display Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-4">
						  <div class="form-group">
						    <input type="email" class="form-control" v-model="role.description" placeholder="Description">
						  </div>
						</div>
						<div class="col-6 offset-3">
          		<div class="text-center">
								<button class="btn btn-outline-success" @click="saveRole">Save</button>
							</div>
						</div>
					</div>
					<div class="row" v-if="edit_role">
						<div class="col-md-5">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="role.name" placeholder="Role Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-3">
						  <div class="form-group">
						    <input type="text" class="form-control" v-model="role.display_name" placeholder="Display Name">
						  </div>
						</div>
						<div class="col-sm-6 col-md-4">
						  <div class="form-group">
						    <input type="email" class="form-control" v-model="role.description" placeholder="Description">
						  </div>
						</div>
						<div class="col-6 offset-3">
          		<div class="text-center">
								<button class="btn btn-outline-warning" @click="updateRole(role.id)">Update</button>
							</div>
						</div>
					</div>
					<div class="card-body mt-3">
						<input type="text" class="col-4 offset-4 form-control" @keyup="searchRoles()" v-model="roleSearch" placeholder="Search">
						<div class="tags mt-3">
							<span class="tag m-2" :class="getRandomClass()" 
								v-for="(role, index) in rLoop"
								:key="index"
								@contextmenu.prevent="$refs.menu2.open($event, { r_id: role.id })">{{ role.name }}</span>
						</div>
					</div>
			  </div>
			</div>
		</div>

		<vue-context ref="menu1">
			<template slot-scope="child">
      	<li><a @click.prevent="editPermissionMode(child.data.p_id)">Edit</a></li>
      </template>
    </vue-context>

		<vue-context ref="menu2">
			<template slot-scope="child">
      	<li><a @click.prevent="editRoleMode(child.data.r_id)">Edit</a></li>
      	<li><a @click.prevent="attachPermissions(child.data.r_id)">Attach Permissions</a></li>
      </template>
    </vue-context>

	</div>
</template>
<script>
	import { VueContext } from 'vue-context';
	export default {
		name: 'Permission',
		components: {
			VueContext
		},
		data () {
			return {
				roleSearch: '',
				permissionSearch: '',
				permission_tab: true,
				permission_create_form: true,
				role_tab: false,
				role_create_form: false,
				edit_permission: false,
				edit_role: false,
				permissions: [],
				pLoop: [],
				roles: [],
				rLoop: [],
				permission_error: false,
				role_error: false,
				btn_loading: false,
				permission: this.emptyPermissionForm(),
				role: this.emptyRoleForm()
			}
		},
		created () {
			axios.get('/admin/permissions').then(response => {
				this.permissions = this.pLoop = response.data
			}).catch(error => {
				console.log(error)
			});

			axios.get('/admin/roles').then(response => {
				this.roles = this.rLoop = response.data;
			}).catch(error => {
				console.log(error.response.data)
			});
		},
		methods: {
			searchPermissions(){
				let searchReg = new RegExp(this.permissionSearch, "i");
				this.pLoop = this.permissions.filter((e) => { return e.name.match(searchReg) });
			},
			searchRoles(){
				let searchReg = new RegExp(this.roleSearch, "i");
				this.rLoop = this.roles.filter((e) => { return e.name.match(searchReg) });
			},
			attachPermissions(id) {
				this.$router.push({ name: 'AssignPermission', params: { id: id } })
			},
			getRandomClass() {
        let items = ['tag-blue', 'tag-azure','tag-indigo','tag-purple', 'tag-pink','tag-red',
        'tag-orange', 'tag-yellow', 'tag-lime', 'tag-green', 'tag-teal', 'tag-cyan'];
        return items[Math.floor(Math.random()*items.length)];
      },
			emptyRoleForm() {
				return {
					'name': '',
					'display_name': '',
					'description': ''
				}
			},
			emptyPermissionForm() {
				return {
					'name': '',
					'display_name': '',
					'description': ''
				}
			},
			openPermissionTab() {
				this.permission_tab = true;
				this.permission_create_form = true;
				this.role_tab = false;
				this.role_create_form = false;
				this.edit_permission = false;
				this.edit_role = false;
			},
			openRoleTab () {
				this.permission_tab = false;
				this.permission_create_form = false;
				this.role_tab = true;
				this.role_create_form = true;
				this.edit_permission = false;
				this.edit_role = false;
			},
			savePermission () {
					this.btn_loading = true;
					axios.post('/admin/permissions', {permission: this.permission}).then(response => {
						this.permissions.unshift(response.data);
						this.permission = this.emptyPermissionForm();
					}).catch(errors => {
						console.log(errors)
						// this.role_errors = errors
					})
					this.btn_loading = false;
			},
			editPermissionMode(id) {
				this.permission_tab = true;
				this.permission_create_form = false;
				this.role_tab = false;
				this.role_create_form = false;
				this.edit_permission = true;
				this.edit_role = false;

				let uri = '/admin/permissions/'+id+'/edit';
				axios.get(uri).then(response => {
					this.permission = response.data;
				}).catch(error => {
					console.log(error.response.data)
				});
			},
			updatePermission(id) {
				this.btn_loading = true;
				let uri = '/admin/permissions/'+id;
				axios.patch(uri, { permission: this.permission }).then(response => {
					this.permissions = response.data;
					this.permission = this.emptyPermissionForm();
				}).catch(error => {
					console.log(error.response.data)
				})
				this.btn_loading = false;
				this.permission_tab = true;
				this.permission_create_form = true;
				this.role_tab = false;
				this.role_create_form = false;
				this.edit_permission = false;
				this.edit_role = false;
			},
			// getPermission (id) {
			// 	let uri = '/admin/permissions/'+id+'/edit';
			// 	axios.get(uri).then(response => {
			// 		this.permission = response.data
			// 	}).catch(error => {
			// 		console.log(error.response.data)
			// 	});
			// },
			// deletePermission (id) {
			// 	let uri = '/admin/permissions/'+id
			// 	axios.delete(uri).then(response => {
			// 		this.permissions = response.data
			// 	}).catch(error => {
			// 		console.log(error)
			// 	})
			// },
			saveRole () {
				this.role_error = false
				axios.post('/admin/roles', {role: this.role}).then(response => {
					this.roles.unshift(response.data);
					this.role = this.emptyRoleForm();
				}).catch(error => {
					console.log(error.response.data)
				})
			},
			editRoleMode (id) {
				this.permission_tab = false
				this.permission_create_form = false
				this.role_tab = true
				this.role_create_form = false
				this.edit_permission = false
				this.edit_role = true

				let uri = '/admin/roles/'+id+'/edit';
				axios.get(uri).then(response => {
					this.role = response.data
				}).catch(error => {
					console.log(error.response.data)
				});
			},
			// getRole(id) {
			// 	let uri = '/admin/roles/'+id+'/edit';
			// 	axios.get(uri).then(response => {
			// 		this.role = response.data.role;
			// 	}).catch(error => {
			// 		console.log(error.response.data)
			// 	});
			// },
			updateRole (id) {
				let uri = '/admin/roles/'+id;
				console.log(uri)
				axios.patch(uri, {role: this.role}).then(response => {
					this.roles = response.data;
					this.role = this.emptyRoleForm();
				}).catch(error => {
					console.log(error.response.data);
				})
				this.permission_tab = false
				this.permission_create_form = false
				this.role_tab = true
				this.role_create_form = true
				this.edit_permission = false
				this.edit_role = false
			},
			// deleteRole (id) {
			// 	let uri = '/admin/roles/'+id
			// 	axios.delete(uri).then(response => {
			// 		this.roles = response.data
			// 	}).catch(error => {
			// 		console.log(error)
			// 	})

			// }
		},
		watch: {
			// roles() {
			// 	this.roleSearch = '';
			// 	this.searchRoles();
			// },
			// permissions() {
			// 	this.permissionSearch = '';
			// 	this.searchPermissions();
			// }
		}
	}
</script>