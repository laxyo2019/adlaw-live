<template>
	<div class="assign_permission">
		<span>
			<a href="#" @click.prevent="back"><i class="fe fe-arrow-left"></i></a>  Assign Permissions to user: { {{ user.name }} }
		</span>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<multiselect
						v-model="assigned_permissions" :options="permissions" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Select Roles" label="name" track-by="name" :preselect-first="true"
					></multiselect>
				</div>
				<div class="form-group col-3">
					<button class="form-control btn btn-primary" @click="assignPermissions(user.id)">Update</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card custom_card_style">
						<div class="card-header">
							<h3 class="card-title">All Permissions</h3>
						</div>
						<div class="card-body">
							<ul>
								<li v-for="(permission, index) in permissions" :key="index">{{ permission.name }}</li>
							</ul>
						</div>
					</div> <!-- /.card -->
				</div>
				<div class="col-md-6">
					<div class="card custom_card_style">
						<div class="card-header">
							<h3 class="card-title">Assigned Permissions</h3>
						</div>
						<div class="card-body">
							<ul>
								<li v-for="(permission, index) in assigned_permissions" :key="index">{{ permission.name }}</li>
							</ul>
						</div>
					</div> <!-- /.card -->
				</div>
			</div> <!-- /.row -->
		</div>
	</div>
</template>
<script>
import Multiselect from 'vue-multiselect'

export default {
	name: 'AssignPermissions',
	components: { Multiselect },
	data () {
		return {
			user: {},
			permissions: [],
			assigned_permissions: []
		}
	},
		created () {
		let uri = `/admin/users/${this.$route.params.id}`;
		axios.get(uri).then(response => {
			this.user = response.data
			this.assigned_permissions = response.data.user_has_direct_permissions
		}).catch(error => {
			console.log(error.response.data);
		})

		axios.get('/admin/permissions').then(response => {
			this.permissions = response.data
		}).catch(error => {
			console.log(error)
		});
	},
	methods: {
		back() {
			this.$router.push({ name: 'User' })
		},
		assignPermissions (id) {
			let uri = `/admin/users/${id}/assign_permissions`;
			axios.post(uri, {
				permissions: this.assigned_permissions
			}).then(response => {
				Vue.swal('Updated Permissions');
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
.custom_card_style::-webkit-scrollbar {
    width: 12px;
}

.custom_card_style::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    border-radius: 10px;
}

.custom_card_style::-webkit-scrollbar-thumb {
    border-radius: 10px;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
}
</style>