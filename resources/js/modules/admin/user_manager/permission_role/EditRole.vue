<template>
	<div class="edit_role">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Edit Role</h3>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" v-model="role.name" />
				</div>
				<h4>List Of Assigned Permissions</h4>
				<div class="row">
					<div class="col-3" v-for="(permission, index) in permissions" :key="permission.index">
						<div class="form-group">
							<label class="colorinput">
								<input name="permissions[]" type="checkbox" class="colorinput-input" @click="determinePermissions(permission, $event, index)" />
								<span class="colorinput-color bg-lime"></span>
								{{ permission.name }}
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-success" @click="updateRole">Update</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
// import Base_URL from '../../../api/index.js'

export default {
	name: 'EditRole',
	data () {
		return {
			role: {},
			permissions: [],
			permission_array: []
		}
	},
	methods: {
		updateRole () {
			let uri = `/admin/roles/${this.$route.params.id}`;
			console.log(uri)
			axios.patch(uri, {
				name: this.role.name,
				permissions: this.permission_array
			}).then(response => {
				this.$router.push({ name: 'Permission' });
			}).catch(error => {
				console.log(error.response.data);
			})
		},
		determinePermissions (permission, event, index) {
			if (event.target.checked) {
				this.permission_array.push({id: permission.id ,name: permission.name, checked: true});
				console.log('I am checked. ID: ' + permission.id + ' Index: ' + index);
			} else {
				for (var i = 0; i < this.permission_array.length; i++) {
					if (this.permission_array[i].id == permission.id) {
						console.log(this.permission_array[i])
						this.permission_array.splice(i, 1);
						break;
					}
				}
			}
		}
	},
	computed: {
		isChecked: function (permission, role) {
			for(var i = 0; i < this.role.permissions.length; i++) {
				if (permission.id === this.role.permissions[i].id) {
					return true;
				} else {
					return false;
				}
			}
		}
	},
	created() {
		let uri = `/admin/roles/${this.$route.params.id}/edit`;
		axios.get(uri).then(response => {
			this.role = response.data.role
		}).catch(error => {
			console.log(error)
		});

		axios.get('/admin/permissions').then(response => {
			this.permissions = response.data
		}).catch(error => {
			console.log(error)
		});
	}
}
</script>