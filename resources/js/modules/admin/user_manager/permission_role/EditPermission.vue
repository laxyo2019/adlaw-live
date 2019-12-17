<template>
	<div class="edit_permission">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Edit Permission</h3>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" v-model="permission.name" />
				</div>
				<div class="form-group">
					<button class="btn btn-success" @click="updatePermission">Update</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>

export default {
	name: 'EditPermission',
	data () {
		return {
			permission: {}
		}
	},
	methods: {
		updatePermission () {
			let uri = `/admin/permissions/${this.$route.params.id}`;
			console.log(uri)
			axios.patch(uri, this.permission).then(response => {
				this.$router.push({ name: 'Permission' });
			}).catch(error => {
				console.log(error);
			})
		}
	},
	created() {
		let uri = `/admin/permissions/${this.$route.params.id}/edit`;
		axios.get(uri).then(response => {
			this.permission = response.data.permission
		}).catch(error => {
			console.log(error)
		});
	}
}
</script>