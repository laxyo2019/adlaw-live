<template>
	<div class="container">
  	<div class="col-10 offset-1">
  		<div class="card mb-n2">
				<div class="card-header">
					<a href="/" title="Back"><i class="fa fa-arrow-left fa-lg"></i></a>
					<span class="card-title mx-auto">
						<b>Tasks Management</b>
					</span>
					<a href="#" @click.prevent="initiateOrReset()" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
	      </div>
  		</div>
  	</div>
		<div class="col-12">
	    <div class="card mb-3">
	      <div class="card-body">
				
					<!-- Task Search / Add Task / Team Filter Starts-->
	  			<div class="row mb-5">
					  <div class="col-4">
				  	  <div class="input-group">
				  	    <select class="form-control" v-model="preset" @change="runPreset">
				  	    	<option value="">-- Presets --</option>
				  	    	<option :value="{'searchKey': '', 'f1': '', 'f2': `${this.user.id}`, 'f3': 'PENDING', 'f4': ''}">
				  	    		All + Created by Me + Pending
				  	    	</option>
				  	    	<option :value="{'searchKey': '', 'f1': '', 'f2': `${this.user.id}`, 'f3': 'AWAITING', 'f4': ''}">
				  	    		All + Created by Me + Awaiting
				  	    	</option>
				  	    	<option :value="{'searchKey': '', 'f1': '', 'f2': `${this.user.id}`, 'f3': 'COMPLETED', 'f4': ''}">
				  	    		All + Created by Me + Completed
				  	    	</option>
				  	    </select>
				  	  </div>
				  	</div>
				  	<div class="col-4 text-center">
  						<button type="button"
  				  		class="btn btn-pill btn-primary" @click="createTask">
  				  		<i class="fe fe-plus"></i> Add Task
  				  	</button>
  					</div> 
				  	<div class="col-4">
				  	  <div class="input-group">
				  	    <input type="text" class="form-control" placeholder="Search a task..." v-model="filter.searchKey" 
				  	    	@keyup="runTasksFilter">
				  	  </div>
				  	</div>
	  	    </div>
	  	    <!-- Task Search / Add Task / Team Filter Ends-->

	  	    <!-- Create/Edit Task Form Starts -->
	    		<span v-if="env.createTask || env.editTask">
	    			<div class="row pl-2 pr-2 pt-5">
	    				<div class="form-group col-12">
	    					<label for=""><b>Task Title</b></label>
	    	  			<input type="text" name="title" class="form-control" v-model="task.title"
	    	  				placeholder="Give your task title..." required autofocus>
	    					<div class="validation-message" v-text="validation.getMessage('title')"></div>
	    	  		</div>

	    				<div class="form-group col-12">
	    					<label for=""><b>Task Description</b></label>
	    					<vue-editor v-model="task.description" :editor-toolbar="customToolbar" />
	    					<div class="validation-message" v-text="validation.getMessage('description')"></div>
	    	  		</div>

	    	  		<div class="form-group col-6">
	    	  			<label for=""><b>Task Tags</b></label>
	    		  		<!-- <multiselect v-model="topicSelector.value" 
	    			  		:options="topicSelector.options" 
	    			  		:close-on-select="true" 
	    			  		:clear-on-select="false"
	    			  		label="title" 
	    			  		track-by="title"> 
	    		  		</multiselect> -->
	    		  		<vue-tags-input
  		  		      v-model="tag"
  		  		      :tags="tags"
  		  		      @tags-changed="newTags => tags = newTags"
  		  		    />

	    					<!-- <div class="validation-message" v-text="validation.getMessage('topic_id')"></div> -->
	    					<!-- Apply permission -->
	    		  		<!-- <a href="#" class="ml-2 mt-1 text-decoration-none"
	    		  			@click.prevent="showModal('create-topic')">Add a new topic</a> -->
	    	  		</div>

	    				<div class="form-group col-6">
	    					<label for=""><b>Task Assignee</b></label>
	    		  		<multiselect v-model="assigneeSelector.value" 
	    			  		:options="assigneeSelector.options" 
	    			  		:close-on-select="true" 
	    			  		:clear-on-select="false"
	    			  		label="name" 
	    			  		track-by="name"> 
	    		  		</multiselect>
	    					<div class="validation-message" v-text="validation.getMessage('assignee_id')"></div>
	    				</div>
	    				
	    				<div class="form-group col-4">
	    					<label for=""><b>Set a start date</b></label>
	    					<flat-pickr class="form-control" v-model="task.start_date" 
	    						:config="dateConfig"></flat-pickr>
	    					<div class="validation-message" v-text="validation.getMessage('start_date')"></div>
	    				</div>

	    				<div class="form-group col-4">
	    					<label for=""><b>Set a due date</b></label>
	    					<flat-pickr class="form-control" v-model="task.due_date" 
	    						:config="dateConfig"></flat-pickr>
	    					<div class="validation-message" v-text="validation.getMessage('due_date')"></div>
	    				</div>

	    				<div class="form-group col-4">
	    					<label for=""><b>Set a priority</b></label>
	    					<select class="form-control" v-model="task.priority">
	    						<option value="">-- Select --</option>
	    						<option value="1">Very Low</option>
	    						<option value="2">Low</option>
	    						<option value="3">Medium</option>
	    						<option value="4">High</option>	
	    						<option value="5">Very High</option>
	    					</select>
	    				</div>
	    				<div class="col-12">
	    					<p-check class="p-icon p-smooth" 
	    							color="success" 
	    							v-if="!((env.createTask && showCreatebutton)||showLoadingButton)"
	    							v-model="keepHistory">
	    	        	<i slot="extra" class="icon typcn typcn-tick"></i>
	    	       	Keep history
	    	    	</p-check>
	    				</div>
	    	  		<div class="form-group col-12 text-center">
	    	  			<span>
	    	  				<button type="submit"  class="btn btn-pill btn-success" @click.prevent="taskStore()"
	    	  					v-if="env.createTask && showCreatebutton">Create</button>
	    	  				<button v-else-if="showLoadingButton" type="button" class="btn btn-pill btn-primary btn-loading"></button>
	      					<button type="submit" class="btn btn-pill btn-warning" @click.prevent="taskUpdate()"
	    	  					v-else>Update</button>
	    	  			</span>

	    	  			<span>

	    			  		<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
	    			  			@click="cancel">Cancel
	    			  		</button>
	    	  			</span>
	    	  			
	    	  		</div>
	    			</div>

	    	  	<!-- <modal name="create-topic">
	    			  <form>
	    				  <fieldset class="m-5">
	    				    <legend>Create Topic</legend>
	    						<div class="form-group">
	    							<input type="text" class="form-control" placeholder="Title" v-model="topic.title">
	    						</div>
	    						<div class="form-group mb-5">
	    							<textarea type="text" class="form-control" placeholder="Description" cols="30" rows="3" v-model="topic.description"></textarea>
	    						</div>
	    			  		<div class="form-group text-center">
	    			  			<button type="submit" class="btn btn-pill btn-success" 
	    			  				@click="storeTopic()">Create
	    			  			</button>
	    			  			<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
	    			  				@click="hideModal('create-topic'); topic=emptyTopicForm">Cancel
	    			  			</button>
	    			  		</div>
	    				  </fieldset>
	    				</form>
	    			</modal> -->
	    			<hr>
	    		</span>
	    		<!-- Create/Edit Task Form Ends -->

					<!-- Task Filters Starts -->
			  	<div class="row task__filters m-2 mb-5" v-show="!env.createTask">
		  	  	<div class="col">
		  	  		<label for=""><b>Teams Toggle</b></label>
  	  	    	<select v-model="filter.f4" class="form-control selectFilter" @change="toggleTeam">
  	  	    		<option value="">--- All Teams ---</option>
  	  	    		<option v-for="team in teamLoop" :value="team.id" v-text="team.name"></option>
  	  	    	</select>
		  	  	</div>
			  		<div class="col">
							<label for=""><b>Task assigned to ...</b></label>
					  	<select v-model="filter.f1" class="form-control selectFilter" @change="runTasksFilter">
					  		<option value="">--- All Assignees ---</option>
					  		<option v-for="user in userLoop" :value="user.id" v-text="user.name"></option>
					  	</select>
			  		</div>
			  		<div class="col">
			  			<label for=""><b>Task created by ...</b></label>
					  	<select v-model="filter.f2" class="form-control selectFilter" @change="runTasksFilter">
					  		<option value="">--- All Creators ---</option>
					  		<option v-for="user in userLoop" :value="user.id" v-text="user.name"></option>
					  	</select>
			  		</div>
			  		<div class="col">
			  			<label for=""><b>Task Status</b></label>
					  	<select v-model="filter.f3" class="form-control selectFilter" @change="runTasksFilter">
					  		<option value="">--- All Statuses ---</option>
					  		<option value="PENDING">Pending</option>
					  		<option value="AWAITING">Awaiting</option>
					  		<option value="COMPLETED">Completed</option>
					  		<option value="MISSED">Missed</option>
					  		<option value="CLOSED">Closed</option>
					  	</select>
			  		</div>
			  	</div>
			  	<!-- Task Filters Ends -->

					<!-- Tasks Loop Starts -->
					<div class="card" v-if="taskLoop.length === 0">
				  	<div class="card-body">
				  		<p class="text-center">No tasks found...</p>
				  	</div>
				  </div>

					<div class="card" v-if="missedTasks.length > 0">
				  	<div class="card-body">
				  		<p style="font-weight: bold; font-size: 1.2em;" class="text-center text-danger animated infinite pulse">
				  			You have {{ missedTasks.length }} missed tasks!
				  		</p>
				  	</div>
				  </div>

					<div v-for="task in taskLoop" :key="task.id">
						<div class="card" :class="{'card-fullscreen' : (env.taskOpen && task.id === focusedTask.id)}">
							<div class="card-status card-status-left" :class="operationTask(task, 'taskBackground')"></div>
						        <div class="card-header">
						          <span class="card-title" @click="focusTask(task.id)"> {{ task.title }} 
									<span v-if="task.status == 'PENDING'">
										<span class="text-danger" v-if="new Date() < new Date(task.due_date)">
											({{ task.due_date | moment("from")}})
										</span>
										<span class="text-danger" v-else>
											(task expired)
										</span>
									</span>
			        			</span>
				          <div class="card-options">
				          	<div class="item-action dropdown">
								<span class="text-muted">
									<span title="Creator">{{task.creator.name}}</span>
										<i class="fe fe-arrow-right"></i> 
									<span title="Assignee">{{task.assignee.name}}</span>
								</span>
									<a v-if="verticalIcon(task)" style="text-decoration:none;" href="javascript:void(0)" data-toggle="dropdown"><i class="fa fa-bars"></i></a>
									<div class="dropdown-menu dropdown-menu-right">
										<a href="#" class="dropdown-item" 
											@click.prevent="updateStatus(task, operationTask(task,'updateStatusValue'))">
											{{ operationTask(task, 'dropDownData') }}
										</a>
									</div>
							</div>
				          </div>
			        </div>
			        <div class="card-body" v-if="env.taskOpen && task.id === focusedTask.id" style="overflow-y: auto">
			      		<TaskOne :task="focusedTask" :logged_user="user" :supers="supers" 
			      			:team="focusedTeam" @edit-task="taskEdit"
		      			></TaskOne>
			        </div>
			      </div>
					</div>
					<!-- Tasks Loop Ends -->

				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import flatPickr from 'vue-flatpickr-component';
	import VueTagsInput from '@johmun/vue-tags-input';
  import Validation from '../../utils/Validation.js';
  import TaskOne from './TaskOne';
	import moment from 'moment';

	export default {
		props: ['users', 'user', 'tasks', 'teams'],
		components: {
    	TaskOne, VueTagsInput, flatPickr
  	},
  	created() {
  		this.assigneeSelector = this.emptySelector(this.userLoop);
  	},
		data(){
			return{
				supers: [2],
				validation: new Validation(),
				// Tag
				tag: '',
      			tags: [],
      			userLoop: [],
      			model:'App\\Models\\Task',

      	// Team
      	team: {},
				teamLoop: [],
				focusedTeam: {},

				// Task
				task: this.emptyTaskForm(),
				taskLoop: [],
				focusedTask: {},
				missedTasks: [],

				// Environment
				env: {
	  			createTask: false,
	  			editTask: false,
	  			createTopic: false,
	  			taskOpen: false
	  		},
	  		dateConfig:{enableTime: true, dateFormat: "Y-m-d H:i:s"},
	  		customToolbar: [
				  ["bold", "italic", "underline"],
				  [{ list: "ordered" }, { list: "bullet" }],
				  ["image", "code-block"]
				],
				showCreatebutton:true,
	  		showLoadingButton:false,
	  		assigneeSelector: this.emptySelector,
	  		keepHistory:true,

	  		// Filters
	  		filter: this.initialFilter(),
	  		preset: ''
			}
		},
		mounted() {
			this.initiateOrReset();
		},
		methods: {
			initiateOrReset() {
				this.userLoop = this.users;
				this.teamLoop = this.teams;
				this.taskLoop = this.tasks;
				this.filter = this.initialFilter();
				this.preset = '';
				this.popMissedTasks();
			},

			emptySelector(options1) {
    		return {
	  			value: {id: ''},
        	options: options1
    		}
      },

      runPreset() {
      	this.filter = this.preset;
      	this.runTasksFilter();
      },

			initialFilter() {
				return {
					searchKey: '',
					f1: this.user.id, // assignee
					f2: '', // creator
					f3: 'PENDING', // status
					f4: '' // teams 
				}
			},

			popMissedTasks() {
				let x = [];
				this.taskLoop.forEach(function(e) {
					if(e.status === 'MISSED')
					{
						x.push(e);
					}
				});
				this.missedTasks = x;
			},

			toggleTeam() {
				let team_id = this.filter.f4;
				if(team_id == "") {
					this.userLoop = this.users;
					this.focusedTeam = {};
				} else {
					let x = {}; 
					this.teamLoop.forEach(function(e){
						if(e.id == team_id){
							x = e;
						}
					});
					this.focusedTeam = x;
					this.userLoop = x.users_in_team;

					this.runTasksFilter();
				}
			},

	  	emptyTaskForm() {
	      return {
	        title: '',
        	description: '',
        	due_date: null,
        	start_date: null,
        	priority: 3,
	      }
	    },

			createTask() {
      	if(this.env.createTask){
      		this.validation = new Validation();
      	}
      	if(this.env.editTask){
      		this.env.editTask = !this.env.editTask;
      		this.env.createTask = false;
      		this.validation = new Validation();
      		this.showCreatebutton = true;
      	}else{
      		this.env.createTask = !this.env.createTask;
      	}
      	// this.task = this.emptyTopicForm();
      	// this.topicSelector = this.emptySelector(this.board.topics);
				this.assigneeSelector = this.emptySelector(this.userLoop);
			},

	  	taskStore() {
	  		this.showCreatebutton = false; this.showLoadingButton = true;

	  		let postData = this.task;
	  		postData.team_id = (this.isEmpty(this.focusedTeam)) ? null : this.focusedTeam.id;
	  		postData.tags = this.tags;
	  		postData.assignee_id = this.assigneeSelector.value.id;
	  		console.log('postData', postData);

	      window.axios.post('/pms/tasks', postData).then(response => {
	        console.log('response', response.data);
	        this.taskLoop.unshift(response.data.message);
	        //empty filter
	        this.filter.f1 = this.filter.f2 = this.filter.f3 = this.filter.searchKey = "";
	        this.env.createTask = false;
	        this.task = this.emptyTaskForm();
			    this.assigneeSelector = this.emptySelector(this.userLoop);
	      })
	      .catch(error => {
	        Vue.swal({
					  type: 'error',
					  title: 'Are you kidding me?!',
					  text: error.response.data.message,		
					  // footer: '<a href>Why do I have this issue?</a>'
					})
	        if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }
	      });

      	this.showCreatebutton = true; this.showLoadingButton = false;
	  	},

	  	taskEdit() {
	  		this.task = this.focusedTask;
				// this.tags = (this.focusedTask.tags != null) ? JSON.parse(this.focusedTask.tags) : [];
  		 	this.assigneeSelector.value = this.focusedTask.assignee;
	  		this.showCreatebutton = false; 
	  		this.env.taskOpen = false;
	  		this.env.editTask = true;
	  	},

	  	taskUpdate() {
	  		this.showLoadingButton = true;

	  		let postData = this.task;
	  		postData.keepHistory = this.keepHistory;
	  		postData.tags = this.tags;
	  		postData.assignee_id = this.assigneeSelector.value.id;
	  		console.log('postData', postData);

	      window.axios.patch(`/pms/tasks/${this.task.id}`, postData).then(response => {
	      	Vue.toasted.show('Updated successfully', {duration:2000});
	      	this.focusedTask = response.data.message;
					this.env.taskOpen = true;
					this.cancel();
	      }).catch(error => {
	      	 if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	console.log(error);
	        }
	      });
     		this.showLoadingButton = false;
	  	},

	  	updateStatus(task, status) {
	  		let id = task.id;
	  		Vue.swal({
	  			title: '',
	  			text: "Do you wish to continue?",
	  			type: 'warning',
	  			showCancelButton: true,
	  			confirmButtonColor: '#3085d6',
	  			cancelButtonColor: '#d33',
	  			confirmButtonText: 'Yeah, sure!'
	  		}).then((result) => {
	  			if(result.value) {
			  		axios.patch(`/pms/tasks/${id}/change-status`, {
			  			id: id, status: status, team_id: this.focusedTeam.id
			  		}).then(response => {
			  			this.taskLoop.forEach((v, k) => {
								if (v.id == id)
								Vue.set(this.taskLoop, k, response.data.message);
								Vue.swal('Task Updated!');
							});
			  		}).catch(error => console.log(error.response.data));
	  			}
				});
	  	},

      cancel() {
      	this.validation = new Validation();
      	this.env.editTask = false;
      	this.env.createTask = false;
      	this.showCreatebutton = true;
      	this.task = this.emptyTaskForm();
      	// this.topicSelector = this.emptySelector(this.board.topics);
				this.assigneeSelector = this.emptySelector(this.userLoop);
      },

			filterTasks(team) {
				this.focusedTeam = team;
				this.taskLoop = this.tasks.filter(t => (t.board_id == this.focusedTeam.taskboard_id));
			},

			isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},

      emptySelector(options1) {
    		return {
	  			value: {id: ''},
        	options: options1
    		}
      },

			runTasksFilter() {
				let team_id = (this.isEmpty(this.focusedTeam)) ? 0 : this.focusedTeam.id;
				window.axios.post('/pms/get-tasks', {
					team_id: team_id,
					assignee_id: this.filter.f1,
					creator_id: this.filter.f2,
					status: this.filter.f3,
					keyword: this.filter.searchKey
				}).then(response => {
					console.log(response.data.message);
					this.taskLoop = response.data.message.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));
				}).catch(error => console.log(error.response.data));
			},

			focusTask(id) {
		  	if(this.isEmpty(this.focusedTask)) {
					window.axios.get(`/pms/tasks/${id}`).then(response => {
						this.focusedTask = response.data.message;
						this.env.taskOpen = true;
					});
				} else {
					this.focusedTask = {};
					this.env.taskOpen = false;
				}
      },

	  	operationTask(task, module)
	  	{
  			switch(module){
		  		case 'taskBackground':
			  		switch(task.status) {
						  case 'COMPLETED':
						    return 'bg-green';
						    break;
						  case 'MISSED':
						    return 'bg-red';
						    break;
				      case 'AWAITING':
						    return 'bg-yellow';
						    break; 
						  default:
						    return 'bg-blue';
					}
		  		break;
		  		case 'updateStatusValue':
		  			if(this.user.id === task.creator_id && task.status == "AWAITING" && this.user.id !== task.assignee_id){
		  				return "COMPLETED";
		  			}else if(this.user.id === task.assignee_id && task.status == "PENDING"){
		  				return "AWAITING";
		  			}
		  		break;
		  		case 'dropDownData':
		  			if(this.is_created_by_me(task.creator_id) && this.taskAwaiting(task.status) && !this.is_assigned_to_me(task.assignee_id)){
		  				return "Verify & Sign";
		  			}else if(this.is_assigned_to_me(task.assignee_id) && (this.taskPending(task.status) || this.taskMissed(task.status)) ){
		  				return "Submit as completed";
		  			}
		  		break;
  			}
	  	},

	  	verticalIcon(task){
	  		if(
  					(this.is_created_by_me(task.creator_id) && this.taskAwaiting(task.status) && !this.is_assigned_to_me(task.assignee_id))
	  				||
	  				(this.is_assigned_to_me(task.assignee_id) && (this.taskPending(task.status) || this.taskMissed(task.status)) )
  				)
  					{
	  					return true;
	  				}
  			else
  			{
	  			return false;
	  		}
	  	},

	  	is_created_by_me(creator)
	  	{
	  		return (this.user.id === creator) ? true : false
	  	},
	  	is_assigned_to_me(assignee)
	  	{
	  		return (this.user.id === assignee) ? true : false
	  	},

	  	taskPending(status)
	  	{
	  		return (status == 'PENDING') ? true : false;
	  	},
	  	taskAwaiting(status)
	  	{
	  		return (status == 'AWAITING') ? true : false;
	  	},
	  	taskCompleted(status)
	  	{
	  		return (status == 'COMPLETED') ? true : false;
	  	},
	  	taskMissed(status)
	  	{
	  		return (status == 'MISSED') ? true : false;
	  	},
	  	taskClosed(status)
	  	{
	  		return (status == 'CLOSED') ? true : false;
	  	},


		}		
	}
</script>

<style scoped>

	.task-detail-label {
		font-size: 0.9rem;
	}
	.detail-separator {
		border-right: solid 1px #000;
		min-height: 3rem;
	}
	.task-preview {
  	display: flex;
  	justify-content: center;
	}
	.text {
		text-align: center;
	}
	.block {
    display: flex;
    flex-direction: column;
    margin: 0px 5px;
	}
	.card-title {
		font-weight: bold;
		cursor: pointer;
	}
	.selectFilter {
		border-radius: 2rem;
	}
</style>