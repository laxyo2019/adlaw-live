<template>
	<div class="assign_permission">
		<div class="card">
			<div class="card-header">
				<a href="#" class="mr-2" @click.prevent="back"><i class="fa fa-arrow-left fa-lg"></i></a>  
					Assign Permissions to Role: <span class="tag tag-blue ml-2">{{ role.name }}</span>
			</div>
			<div class="card-body">
				<user-selector :users="permissions" 
					:selectedUsersProp="assigned_permissions"
					@input="assigned_permissions = $event">
				</user-selector>
				<div class="text-center mt-3">
					<button class="btn btn-outline-primary" @click="assignPermissions(role.id)">Update</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Multiselect from 'vue-multiselect'
export default {
	name: 'AssignPermission',
	components: { Multiselect },
	data () {
		return {
			role: {},
			permissions: [],
			assigned_permissions: []
		}
	},
	created () {
		let uri = `/admin/roles/${this.$route.params.id}`;
		axios.get(uri).then(response => {
			this.role = response.data
			this.assigned_permissions = response.data.role_has_permissions
		}).catch(error => {
			console.log(error.response.data);
		})

		axios.get('/admin/permissions').then(response => {
			this.permissions = response.data
		}).catch(error => {
			console.log(error.response.data)
		});
	},
	methods: {
		back() {
			this.$router.push({ name: 'Permission' })
		},
		assignPermissions (id) {
			let uri = `/admin/roles/${id}/assign_permissions`;
			axios.post(uri, {
				permissions: this.assigned_permissions
			}).then(response => {
				Vue.swal('Permissions Updated')
			}).catch(errors => {
				console.log(errors.response.data)
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