<template>
	<div class="editUser">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Edit User</h3>
			</div>
			<div class="card-body">
				<div v-if="this.errors">
					<div v-for="error in errors" :key="error.index">
						<p class="text-danger">{{ error[0] }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" v-model="user.name" placeholder="Name" />
				</div>
				<div class="form-group">
					<label class="form-label">Email</label>
					<input type="email" name="email" class="form-control" v-model="user.email" placeholder="Email" />
				</div>
				<div class="form-group">
					<button class="btn btn-success" @click="update">Update</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	name: 'EditUser',
	data () {
		return {
			user: {},
			errors: []
		}
	},
	methods: {
		update () {
			let uri = `/admin/users/${this.$route.params.id}`;
			axios.patch(uri, {
				name: this.user.name,
				email: this.user.email,
			}).then(response => {
				this.$router.push({ name: 'User' })
			}).catch(error => {
				this.errors = error.response.data.errors
				console.log(error.response.data.errors)
			})
		}
	},
	created () {
		let uri = `/admin/users/${this.$route.params.id}/edit`;
		axios.get(uri).then(response => {
			console.log(response.data)
			this.user = response.data
		}).catch(error => {
			console.log(error.response)
		})
	}
}
</script>