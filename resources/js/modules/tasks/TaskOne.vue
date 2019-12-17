<template>
	<div class="container-fluid">

		<span v-if="isEmpty(task)">
		  <div class="dimmer active">
        <div class="loader"></div>
        <div class="dimmer-content">
          Loading...
        </div>
      </div>
		</span>
		<span v-else>
			<div>
				<span v-if="!env.taskEditHistory" style="width: 80%; display: inline-block;background: #dae5f5;" class="text-center font-weight-bold">
						Task Details
				</span>
				<span style="width: 70%; display: inline-block;background: #dae5f5;" class="text-center font-weight-bold" v-else >Task Edit History</span>
				<span class="float-right" v-if="((task.creator_id === logged_user.id) || (this.supers.indexOf(logged_user.id) > -1))">
					<a href="#" title="Edit" class="btn btn-sm btn-outline-primary" @click.prevent="$emit('edit-task')">
						<i class="fa fa-edit" style="font-size:20px"></i> Edit
					</a>
					<a href="#" title="Delete" class="btn btn-sm btn-outline-danger ml-2" @click="taskDelete">
						<i class="fa fa-trash" style="font-size:20px"></i> Delete
					</a>
					<!-- <a href="#" title="Delete" class="btn btn-sm btn-outline-warning ml-2" @click="env.taskEditHistory=!env.taskEditHistory">
						<i class="fa fa-history" style="font-size:20px"></i> History
					</a> -->
				</span>
			</div>	

			<div class="mb-3 mt-3" style="height:50vh; overflow:auto;">
				<div class="form-group">
					<label><b>TITLE:</b></label>
					<span>{{ task.title }}</span>
				</div>
  			<div class="form-group">
  				<label><b>CREATOR:</b></label>
					<span>{{ task.creator.name }}</span> <i class="fa fa-arrow-right"></i>
  				<label><b>ASSIGNEE:</b></label>
					<span>{{ task.assignee.name }}</span>
  			</div>
  			<div class="form-group">
  				<label><b>START DATE:</b></label>
					<span>{{ task.start_date | moment("DD-MM-YYYY h:mm a") }} </span> <i class="fa fa-arrow-right"></i>
					<label><b>DUE DATE:</b></label>
					<span>{{ task.due_date | moment("DD-MM-YYYY h:mm a") }} 
						( <span class="text-danger">{{ task.due_date | moment("from") }}</span> )
					</span>
  			</div>
  			<div class="form-group">
  				<label><b>TAGS:</b></label>
  				<div class="tags">
  					<span class="tag" v-for="tag, index in JSON.parse(task.tags)" :key="index">{{ tag.text }}</span>
  				</div>
  			</div>
  			<div class="form-group">
  				<label><b>STATUS:</b></label>
  				<span>{{ taskStatus(task.status) }}</span>
  			</div>
  			<div class="form-group">
  				<label><b>PRIORITY:</b></label>
  				<span>{{ priorityStatus(task.priority) }}</span>
  			</div>
				<div class="form-group">
					<label><b>DESCRIPTION:</b></label>
					<p v-html="task.description"></p>
				</div>
			</div>
			<div class="row" v-if="canComment()">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
			 		<comments-component :commentable_id="openedTask.id" 
			 			:logged_user="logged_user" 
			 			:model="model">
					</comments-component>
				</div>
			</div>
		</span>
	</div>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		props: ['task', 'logged_user', 'team', 'supers'],
		data () {
			return {
				env: {
					taskEditHistory: false,
				},
				model:'App\\Models\\Task',
				openedTask: {},
				historyInfo:{},
				assignee: {},
				creator: {},
				comment: this.emptyCommentForm(),
				comments: [],
				customToolbar: [
				  ["bold", "italic", "underline"],
				  [{ list: "ordered" }, { list: "bullet" }],
				  ["image", "code-block"]
				]
			}
		},
		created() {
			this.openedTask = this.task;
			this.comments = this.task.comments;
			this.historyInfo = JSON.parse(this.task.activity_log);
		},
		methods: {
			// fetchTopic(id){
			// 	let x='';
			// 	let baordTopics = this.boardTopics;
			// 	baordTopics.forEach(function(e){
			// 		if(id==e.id){
			// 			x=e.title;
			// 		}
			// 	});
			// 	return x;
			// },
			canComment() {
				let user = this.logged_user.id;
				let creator = this.task.creator_id;
				let assignee = this.task.assignee_id;
				if(user == creator || user == assignee || this.supers.indexOf(user) > -1) {
					return true;
				}
				return false;
			},
			// fetchAssignee(id){
			// 	let x='';
			// 	let users = this.team.users_in_team;
			// 	users.forEach(function(e){
			// 		if(id==e.id){
			// 			x=e.name;
			// 		}
			// 	});
			// 	return x;
			// },
	  	isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	          return false;
		    }
		    return true;
			},
	    emptyCommentForm() {
	    	return {
	    		body: ''
	    	}
	    },
	  	taskStatus(status) {
	  		let x = {
	  			4 : 'PENDING',
	  			1 : 'COMPLETED',
	  			2 : 'EXPIRED',
	  			3 : 'AWAITING'
	  		};

	  		return x[status];
	  	},
	  	priorityStatus(status) {
	  		let x = {
	  			1 : 'Very Low',
	  			2 : 'Low',
	  			3 : 'Medium',
	  			4 : 'High',
	  			5 : 'Very High'
	  		};

	  		return x[status];
	  	},
	  	taskUpdate() {
	  		// let postData = this.openedTask;
	      window.axios.patch(`/pms/tasks/${this.openedTask.id}`, {
	      	'title': this.openedTask.title,
	      	'priority': this.openedTask.priority,
	      	'description': this.openedTask.description
	      }).then(response => {
	      	console.log(response.data);
	      	this.openedTask.title = response.data.title;
	      	this.openedTask.description = response.data.description;
	      	this.openedTask.priority = response.data.priority;
	      }).catch(error => console.log(error));
	  	},
	  	taskDelete() {
	  		Vue.swal({
	  			title: '',
	  			text: "Do you want to delete this task?",
	  			type: 'danger',
	  			showCancelButton: true,
	  			confirmButtonColor: '#3085d6',
	  			cancelButtonColor: '#d33',
	  			confirmButtonText: 'Yes'
	  		}).then((result) => {
	  			if(result.value) {
			  		window.axios.delete(`/pms/tasks/${this.openedTask.id}`).then(response => {
			  			Vue.swal(response.data);
			  			window.location.reload();
			  		}).catch(error => console.log(error.response.data));
	  			}

				});
	  	},
	    commentStore() {
	    	let postData = this.comment;
	    	postData.entity_id = this.openedTask.id;
	    	postData.type = 1;

	      window.axios.post('/pms/comments', postData).then(response => {
	        this.comments.unshift(response.data);
	        this.comment = this.emptyCommentForm();
	      })
	      .catch(error => {
	        if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }
	      });
	    }
		},
	}
</script>

<style scoped>
	/*.task-detail-label {
		font-size: 0.9rem;
	}*/
</style>