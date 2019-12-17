<template>
	<div class="taskboard__wrapper">
		<div class="row">
			<div class="form-group col-4">
				<button type="button"
		  		class="btn btn-pill btn-primary" @click="createTask">
		  		<i class="fe fe-plus"></i> New Task
		  	</button>
			</div> 

		  <div class="col-4">
	  	  <div class="input-group">
	  	    <input type="text" class="form-control" placeholder="Search a task..." v-model="filter.searchKey" 
	  	    	@keyup="runTasksFilter">
	  	  </div>
	  	</div>
	  	<div class="col-4">
	  	  <button type="button"
		  		class="btn btn-pill btn-primary float-right" @click="openDash">
		  		<i class="fe fe-zap"></i> Dash
		  	</button>
	  	</div>
    </div>

    <div class="card card-fullscreen" id="dashCard" v-show="dashActive">
      <div class="card-header">
        <h3 class="card-title">Dash - {{ team.name }} [ {{ new Date() | moment('DD-MM-YYYY') }} ]</h3>
        <div class="card-options">
        	<a href="#" title="close" @click.prevent="dashActive = false">
        		<i style="font-size: 1.5rem" class="fa fa-remove mr-3"></i>
        	</a>
        </div>
      </div>
      <div class="card-body">
        <TaskDash />
      </div>
    </div>

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
	  			<label for=""><b>Task Topic</b></label>
		  		<multiselect v-model="topicSelector.value" 
			  		:options="topicSelector.options" 
			  		:close-on-select="true" 
			  		:clear-on-select="false"
			  		label="title" 
			  		track-by="title"> 
		  		</multiselect>

					<div class="validation-message" v-text="validation.getMessage('topic_id')"></div>
					<!-- Apply permission -->
		  		<a href="#" class="ml-2 mt-1 text-decoration-none"
		  			@click.prevent="showModal('create-topic')">Add a new topic</a>
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

	  	<modal name="create-topic">
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
			</modal>
			<hr>
		</span>

  	<div class="row task__filters m-2 mb-3" v-show="!env.createTask">
  		<div class="col">
				<label for=""><b>Task assigned to ...</b></label>
		  	<select v-model="filter.f1" class="form-control selectFilter" @change="runTasksFilter">
		  		<option value="">--- All ---</option>
		  		<option v-for="user in team.users_in_team" :value="user.id" v-text="user.name"></option>
		  	</select>
  		</div>
  		<div class="col">
  			<label for=""><b>Task created by ...</b></label>
		  	<select v-model="filter.f2" class="form-control selectFilter" @change="runTasksFilter">
		  		<option value="">--- All ---</option>
		  		<option v-for="user in team.users_in_team" :value="user.id" v-text="user.name"></option>
		  	</select>
  		</div>
  		<div class="col">
  			<label for=""><b>Task Status</b></label>
		  	<select v-model="filter.f3" class="form-control selectFilter" @change="runTasksFilter">
		  		<option value="">--- All ---</option>
		  		<option value="4">Pending</option>
		  		<option value="1">Completed</option>
		  		<option value="2">Expired</option>
		  		<option value="3">Awaiting</option>
		  	</select>
  		</div>
  	</div>

	  <div class="card" v-if="tasks.length === 0">
	  	<div class="card-body">
	  		<p class="text-center">Seems quite empty here...</p>
	  	</div>
	  </div>
	  <!-- Tasks Loop -->
		<div v-for="task in tasks">
			<div class="card" :class="{'card-fullscreen' : (env.taskOpen && task.id === focusedTask.id)}">
				<div class="card-status card-status-left" :class="operationTask(task, 'taskBackground')"></div>
        <div class="card-header">
        	<!-- Task Icon -->
        	<!-- <div class="mr-2">
        		<span><i :class="operationTask(task, 'taskIcon')"></i></span>
        	</div> -->
        	<!-- Task Title -->
          <span class="card-title" @click="focusTask(task.id)">{{ task.title }} 
						<span v-if="task.status==4">
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
									<a href="#" class="dropdown-item" @click.prevent="updateStatus(task, operationTask(task,'updateStatusValue'))">
										{{operationTask(task,'dropDownData')}}
									</a>
								</div>
						</div>
          </div>
        </div>
        <div class="card-body" v-if="env.taskOpen && task.id === focusedTask.id" style="overflow-y: auto">
      		<task-component :task="focusedTask" 
      		:logged_user="user" 
      		:team='team' 	
      		:boardTopics='board.topics'
      		@edit-task="taskEdit"></task-component>
        </div>
      </div>
		</div>
	</div>
</template>

<script>
	import TaskComponent from './Task.vue';
	import TaskDash from './TaskDash.vue';
	import VModal from 'vue-js-modal'
	import flatPickr from 'vue-flatpickr-component';
  import 'flatpickr/dist/flatpickr.css';
  import { VueEditor } from 'vue2-editor'
  import Validation from '../../../utils/Validation.js';
  import moment from 'moment';
	Vue.use(VModal)
	export default {
		props: ['team', 'board', 'user','meta'],
	  components: {
	  	TaskComponent,
	  	TaskDash, 
	  	flatPickr,
	  	VueEditor,
	    // vueDropzone: vue2Dropzone
	  },	  
	  created() {
	    this.topicSelector = this.emptySelector(this.board.topics);
	    this.assigneeSelector = this.emptySelector(this.team.users_in_team);
	    this.topics = this.team.topics;

	    if(!this.isEmpty(this.meta)) {
	    	this.emptyFilter();
	  		this.clearTasksFilter();
	    	let id = this.meta[0];
					window.axios.get(`/pms/tasks/${id}`).then(response => {
						this.focusedTask = response.data;
						this.env.taskOpen = true;
					});
				}
				
	  },
	  data() {
	  	return {
	  		leads:[],
	  		admins:[],
	  		superadmins:[],
	  		keepHistory:true,
	  		showCreatebutton:true,
	  		showLoadingButton:false,
				dashActive: false,
	  		filter: {
	  			searchKey: '',
	  			f1: this.user.id,
	  			f2: '',
	  			f3: 4,
	  			f4: ''
	  		},
				customToolbar: [
				  ["bold", "italic", "underline"],
				  [{ list: "ordered" }, { list: "bullet" }],
				  ["image", "code-block"]
				],
	  		validation: new Validation(),
	  		dateConfig:{enableTime: true, dateFormat: "Y-m-d H:i:s"},
	  		topicSelector: this.emptySelector,
	  		assigneeSelector: this.emptySelector,
	  		tasks: [],
	  		topics: [],
	  		task: this.emptyTaskForm(),
	  		topic: this.emptyTopicForm(),
	  		env: {
	  			createTask: false,
	  			editTask: false,
	  			createTopic: false,
	  			taskOpen: false
	  		},
	  		focusedTask: {}
	  	}
	  },
	  mounted() {
	  	this.runTasksFilter();
	  	if(this.user.id==2){
	  		this.emptyFilter();
	  		this.clearTasksFilter();
	  	}
	  },
	  methods: {
	  	openDash() {
	  		this.dashActive = !this.dashActive;
	  	},
	  	emptyFilter(){
	  		this.filter.f1 = "";
	  		this.filter.f2 = "";
	  		this.filter.f3 = "";
	  	},
		  showModal(modalName) {
		    this.$modal.show(modalName);
		  },
		  hideModal(modalName) {
		    this.$modal.hide(modalName);
		  },
	  	isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
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
	    emptyTopicForm() {
     		return {
	        title: '',
        	description: ''
      	}
      },
      emptySelector(options1) {
    		return {
	  			value: {id: ''},
        	options: options1
    		}
      },
      focusTask(id) {
		  	if(this.isEmpty(this.focusedTask)) {
					window.axios.get(`/pms/tasks/${id}`).then(response => {
						this.focusedTask = response.data;
						this.env.taskOpen = true;
					});
				} else {
					this.focusedTask = {};
					this.env.taskOpen = false;
				}
      },
      cancel(){
      	this.validation = new Validation();
      	this.env.editTask=false;
      	this.env.createTask=false;
      	this.showCreatebutton=true;
      	this.task = this.emptyTopicForm();
      	this.topicSelector = this.emptySelector(this.board.topics);
				this.assigneeSelector = this.emptySelector(this.team.users_in_team);
      },
      createTask() {
      	if(this.env.createTask){
      		this.validation = new Validation();
      	}
      	if(this.env.editTask){
      		this.env.editTask = !this.env.editTask;
      		this.env.createTask=false;
      		this.validation = new Validation();
      		this.showCreatebutton=true;
      	}else{
      		this.env.createTask = !this.env.createTask;
      	}
      	this.task = this.emptyTopicForm();
      	this.topicSelector = this.emptySelector(this.board.topics);
				this.assigneeSelector = this.emptySelector(this.team.users_in_team);
      },
	  	taskStore() {
	  		this.showCreatebutton = false;
	  		this.showLoadingButton = true;
	  		let postData = this.task;
	  		postData.team_id = this.team.id;
	  		postData.board_id = this.board.id; // add additional param
	  		postData.topic_id = this.topicSelector.value.id; // add additional param
	  		postData.assignee_id = this.assigneeSelector.value.id; // add additional param
	  		console.log('postData', postData);

	      window.axios.post('/pms/tasks', postData).then(response => {
	        console.log('response', response.data);
	        this.tasks = response.data;
	        //empty filter
	        this.filter.f1 = this.filter.f2 = this.filter.f3 = this.filter.searchKey = "";
	        this.env.createTask = false;
	        this.task = this.emptyTaskForm();
	        this.topicSelector = this.emptySelector(this.board.topics);
			    this.assigneeSelector = this.emptySelector(this.team.users_in_team);
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
      	this.showCreatebutton = true;
				this.showLoadingButton = false;
	  	},
	  	taskEdit() {
	  		this.task = this.focusedTask;
	  		this.topicSelector.options.forEach((v, k) => {
					 if (v.id == this.focusedTask.topic_id)
					 this.topicSelector.value = v;
				});

	  		// this.topicSelector.value 
	  		this.assigneeSelector.value = this.focusedTask.assignee;
	  		this.showCreatebutton = false; 
	  		this.env.taskOpen = false;
	  		this.env.editTask = true;
	  	},
	  	storeTopic(){
	  		let postData = this.topic;
	  		postData.board_id = this.board.id;
	  		window.axios.post('/pms/topics', postData).then(response => {
    	    console.log('response', response.data);
	        this.topicSelector.options.push(response.data);
					this.hideModal('create-topic');
					this.topic = this.emptyTopicForm ();
	      }).catch(error => console.log('error', error.response));
			
	  	},
	  	clearTasksFilter() {
	  		this.filter.searchKey='';
	  		window.axios.post('/pms/get-tasks', {
	  			board_id: this.board.id,
	  			assignee_id: this.filter.f1,
	  			creator_id: this.filter.f2,
	  			status: parseInt(this.filter.f3),
	  			keyword: ''
	  		}).then(response => {
	  			console.log(response.data);
	  			this.tasks = response.data.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));
	  		}).catch(error => console.log(error.response.data));
	  	},
	  	runTasksFilter() {
	  		window.axios.post('/pms/get-tasks', {
	  			board_id: this.board.id,
	  			assignee_id: this.filter.f1,
	  			creator_id: this.filter.f2,
	  			status: parseInt(this.filter.f3),
	  			keyword: this.filter.searchKey
	  		}).then(response => {
	  			console.log(response.data);
	  			this.tasks = response.data.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));
	  		}).catch(error => console.log(error.response.data));
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
	  	taskUpdate() {
	  		this.showLoadingButton = true;

	  		let postData = this.task;
	  		postData.keepHistory = this.keepHistory;
	  		postData.topic_id = this.topicSelector.value.id; // add additional param
	  		postData.assignee_id = this.assigneeSelector.value.id; // add additional param
	  		console.log('postData', postData);

	      window.axios.patch(`/pms/tasks/${this.task.id}`, postData).then(response => {
	      	Vue.toasted.show('Updated successfully', {duration:2000});
	      	this.focusedTask = response.data;
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
			  			id: id, status: status, team_id: this.team.id
			  		}).then(response => {
			  			this.tasks.forEach((v, k) => {
								if (v.id == id)
								Vue.set(this.tasks, k, response.data);
								Vue.swal('Task Updated!');
							});
			  		}).catch(error => console.log(error.response.data));
	  			}

				});

	  	},
	  	operationTask(task,module)
	  	{
  			switch(module){
		  		case 'taskBackground':
			  		switch(task.status) {
						  case 1:
						    return 'bg-green';
						    break;
						  case 2:
						    return 'bg-red';
						    break;
				      case 3:
						    return 'bg-yellow';
						    break; 
						  default:
						    return 'bg-blue';
					}
		  		break;
		  		case 'updateStatusValue':
		  			if(this.user.id === task.creator_id && task.status == 3 && this.user.id !== task.assignee_id){
		  				return 1;
		  			}else if(this.user.id === task.assignee_id && task.status == 4){
		  				return 3;
		  			}
		  		break;
		  		case 'dropDownData':
		  			if(this.user.id === task.creator_id && task.status == 3 && this.user.id !== task.assignee_id){
		  				return "Verify & Sign";
		  			}else if(this.user.id === task.assignee_id && task.status == 4){
		  				return "Submit as completed";
		  			}
		  		break;
  			}
	  	},
	  	verticalIcon(task){
	  		if((this.user.id === task.creator_id && task.status == 3 && this.user.id !== task.assignee_id)||(this.user.id === task.assignee_id && task.status == 4)){
	  			return true;
	  		}else{
	  			return false;
	  		}
	  	}
	  },
	  computed: {
	  	//
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