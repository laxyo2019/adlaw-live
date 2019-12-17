<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="border-right: solid 2px #eee;">
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
						<span v-if="!env.taskEditHistory" style="width: 70%; display: inline-block;background: #dae5f5;" class="text-center font-weight-bold">
								Task Description
						</span>
						<span style="width: 70%; display: inline-block;background: #dae5f5;" class="text-center font-weight-bold" v-else >Task Edit History</span>
						<span class="float-right" v-if="task.creator_id === logged_user.id">
							<a href="#" title="Edit" class="text-decoration-none" @click.prevent="$emit('edit-task')">
								<i class="fa fa-pencil-square" style="font-size:20px"></i></a>
							<a href="#" title="Delete" class="text-decoration-none ml-2" @click="taskDelete">
								<i class="text-danger fa fa-trash" style="font-size:20px"></i>
							</a>
							<a href="#" title="Delete" class="text-decoration-none ml-2" @click="env.taskEditHistory=!env.taskEditHistory">
								<i class="text-warning fa fa-history" style="font-size:20px"></i>
							</a>
						</span>
					</div>				
					<div v-if="!env.taskEditHistory" class="mb-3 mt-3" style="height:85vh; overflow:scroll;">
						<div class="form-group">
							<label><b>TITLE:</b></label>
							<span>{{ task.title }}</span>
						</div>
		  			<div class="form-group">
		  				<label><b>ASSIGNEE:</b></label>
							<span>{{ task.assignee.name }}</span>|
							<label><b>TOPIC:</b></label>
							<span>
								{{ fetchTopic(task.topic_id) }}
							</span>
		  			</div>
		  			<div class="form-group">
		  				<label><b>START DATE:</b></label>
							<span>
								{{ task.start_date | moment("D-MM-YYYY, h:mm") }} 
							</span>
							|
							<label><b>DUE DATE:</b></label>
							<span>
								{{ task.due_date | moment("D-MM-YYYY, h:mm") }} 
								( <span class="text-danger">{{ task.due_date | moment("from") }}</span> )
							</span>
		  			</div>
		  			<div class="form-group">
		  				<label><b>STATUS:</b></label>
		  				<span>{{ taskStatus(task.status) }}</span>
		  				|
		  				<label><b>PRIORITY:</b></label>
		  				<span>{{ priorityStatus(task.priority) }}</span>
		  			</div>
						<div class="form-group">
							<label><b>DESCRIPTION:</b></label>
							<p v-html="task.description"></p>
						</div>
					</div>	
					<div v-else class="mb-3 mt-3" style="height:85vh; overflow:scroll;">
						<span v-for="history in historyInfo">
							<div class="form-group">
								<label><b>TITLE:</b></label>
								<span>{{ history.title }}</span>
							</div>
							<div class="form-group">
			  				<label><b>ASSIGNEE:</b></label>
								<span>{{ fetchAssignee(history.assignee_id)}}</span>|
								<label><b>TOPIC:</b></label>
								<span>
									{{ fetchTopic(history.topic_id) }}
								</span>
			  			</div>
			  			<div class="form-group">
			  				<label><b>PRIORITY:</b></label>
			  				<span>{{ priorityStatus(history.priority) }}</span>
		  			</div>
			  			<div class="form-group">
		  				<label><b>START DATE:</b></label>
							<span>
								{{ history.start_date | moment("D-MM-YYYY, h:mm") }} 
							</span>
							|
							<label><b>DUE DATE:</b></label>
							<span>
								{{ history.due_date | moment("D-MM-YYYY, h:mm") }} 
								( <span class="text-danger">{{ history.due_date | moment("from") }}</span> )
							</span>
		  			</div>
							<div class="form-group">
								<label><b>DESCRIPTION:</b></label>
								<p v-html="history.description"></p>
							</div>
							<hr>
						</span>
					</div>
				</span>
				<!-- comment form -->
				<!-- <div class="form-group">
					<vue-editor v-model="comment.body" :editor-toolbar="customToolbar" />
				</div> -->
	  		<!-- <div class="form-group text-center">
	  			<button type="submit" class="btn btn-pill btn-success" 
  					@click.prevent="commentStore()">Post Comment</button>
	  			<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
  					@click="comment=emptyCommentForm()">Clear</button>
	  		</div> -->
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
				<comments-component :can_delete="can_delete" :entity_id="task.id" :logged_user="logged_user" :team_id="team.id" :comment_type=1></comments-component>
				<!-- <p class="text-center">
					{{ comments.length }} {{ comments.length == 1 ? "Comment" : "Comments"}}
				</p>
				<div style="height:100vh; overflow:auto;">
					<div class="alert alert-avatar alert-primary alert-dismissible pr-2" v-for="comment in comments">
					  <span class="avatar" style="background-image: url())"></span>
				  	<span class="font-weight-bold">{{ comment.user.name }}</span>
				  	<span class="text-muted float-right">
				  		<i class="fe fe-clock"></i> {{ comment.created_at | moment("from") }}
				  	</span>

					 <p v-html="comment.body"></p>
					</div>
				</div> -->
				
			</div>
		</div>
	</div>
</template>

<script>
	import { VueEditor } from 'vue2-editor'
	export default {
		props: ['task', 'logged_user','team','boardTopics'],
		data () {
			return {
				can_delete:[],
				env: {
					taskEditHistory: false,
				},
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
			this.can_delete = [this.task.creator.id];
			this.openedTask = this.task;
			this.comments = this.task.comments;
			this.historyInfo = JSON.parse(this.task.activity_log);
		},
		methods: {
			fetchTopic(id){
				let x='';
				let baordTopics = this.boardTopics;
				baordTopics.forEach(function(e){
					if(id==e.id){
						x=e.title;
					}
				});
				return x;
			},
			fetchAssignee(id){
				let x='';
				let users = this.team.users_in_team;
				users.forEach(function(e){
					if(id==e.id){
						x=e.name;
					}
				});
				return x;
			},
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
	.task-detail-label {
		font-size: 0.9rem;
	}
</style>