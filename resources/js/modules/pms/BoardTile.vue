<template>
	<div class="card mb-3">
<!-- 
	  <div class="card-header">
	    <h3 class="card-title">{{ title }}</h3>
	    <div class="card-options">
	    	<span v-if="notifyCount > 0" class="badge badge-pill badge-danger">{{ notifyCount }}</span>
	    	<span v-else><i class="text-success fa fa-check-circle"></i></span>
	    </div>
	  </div> -->
<!-- <a :href=" module == 'checklist' ? `/pms/team/agenda/view/${team.id}` : `/pms/team/${team.id}/${module}/${team[module+'_id']}`" class="text-decoration-none">
	    <div class="card-body p-3 text-center m-t-b-10">
	      <div class="text-right text-green"> -->


		<a  :href=" module == 'checklist' ? `/pms/team/agenda/view/${team.id}` : `/pms/team/${team.id}/${module}/${team[module+'_id']}`" class="text-decoration-none">
		  <div class="card-header">
		    <h3 class="card-title" style="font-weight: bold;"><i :class="icon"></i> {{ title }}</h3>
		    <div class="card-options">
		    	<span v-if="notifyCount > 0" class="badge badge-pill badge-danger">{{ notifyCount }}</span>
		    	<span v-else><i class="text-success fa fa-check-circle"></i></span>
		    </div>
		  </div>
		</a>
	  
	 <!-- div class="card-body p-3 text-center m-t-b-10">
	      <div class="text-right text-green">
	        <i class="fe fe-chevron-up"></i>
	      </div>
	      <div class="h1 m-0"><i :class="icon"></i></div>
	      <div class="text-muted mb-4" v-text="stat"></div>
	    </div> -->
	</div>
</template>

<script>
	export default {
		props: ['logged_user', 'team', 'module', 'icon', 'title', 'stats'],
		data() {
			return {
				stat: '',
				notifyCount: 0,
			}
		},
		mounted() {
			switch(this.module) {
				case 'taskboard':
				this.getTaskCounts(4); //pending
					break;
				case 'messageboard':
				this.getPostCounts(); //total
					break;
			}
		},
		methods: {
			getTaskCounts(status) {
				axios.post('/pms/tasks/get-counts', {
					logged_user_id: this.logged_user.id,
					board_id: this.team.taskboard_id,
					status: status
				}).then(response => {
					console.log(response.data);
					this.notifyCount = response.data.pending;
					this.stat = response.data.stat;
				}).catch(error => console.log(error.response.data));
			},
			getPostCounts() {
				axios.post('/pms/posts/get-counts', {
					board_id: this.team.messageboard_id
				}).then(response => {
					console.log(response.data);
					this.stat = response.data.stat;
				}).catch(error => console.log(error.response.data));
			},
		}
	}
</script>
<style>
	.m-t-b-10{
		margin:12px 0 !important;
	}
</style>