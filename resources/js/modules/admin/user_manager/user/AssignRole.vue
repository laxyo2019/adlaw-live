<template>
	<div class="assign_role">
		<span>
			<a href="#" @click.prevent="back"><i class="fe fe-arrow-left"></i></a>  Assign Roles to user: { {{ user.name }} }
		</span>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<multiselect
						v-model="assigned_roles" :options="roles" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Select Roles" label="name" track-by="name" :preselect-first="true"
					></multiselect>
				</div>
				<div class="form-group col-3">
					<button class="form-control btn btn-primary" @click="assignRoles(user.id)">Update</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card custom_card_style">
						<div class="card-header">
							<h3 class="card-title">All Roles</h3>
						</div>
						<div class="card-body">
							<ul>
								<li v-for="(role, index) in roles" :key="index">{{ role.name }}</li>
							</ul>
						</div>
					</div> <!-- /.card -->
				</div>
				<div class="col-md-6">
					<div class="card custom_card_style">
						<div class="card-header">
							<h3 class="card-title">Assigned Roles</h3>
						</div>
						<div class="card-body">
							<ul>
								<li v-for="(role, index) in assigned_roles" :key="index">{{ role.name }}</li>
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
	name: 'AssignRole',
	components: { Multiselect },
	data () {
		return {
			user: {},
			roles: [],
			assigned_roles: []
		}
	},
	created () {
		let uri = `/admin/users/${this.$route.params.id}`;
		axios.get(uri).then(response => {
			this.user = response.data
			this.assigned_roles = response.data.user_has_roles
		}).catch(error => {
			console.log(error.response.data);
		})

		axios.get('/admin/roles').then(response => {
			this.roles = response.data
		}).catch(error => {
			console.log(error.response.data)
		});
	},
	methods: {
		back() {
			this.$router.push({ name: 'User' });
		},
		assignRoles (id) {
			let uri = `/admin/users/${id}/assign_roles/`;
			axios.post(uri, {
				roles: this.assigned_roles
			}).then(response => {
				Vue.swal('Roles Updated');
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