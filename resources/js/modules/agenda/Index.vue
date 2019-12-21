<template>
	<div class="">
		<div class="row">
			<div class="col-md-10 offset-1">
				<div class="card mb-n2">
					<div class="card-header">
						<a href="/lawfirm" title="Back"><i class="fa fa-arrow-left fa-lg"></i></a>
						<span class="card-title ml-2" >
						<b> Daily Agenda</b>
						</span>				
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card mb-3">
				<div class="card-body">
					<div class="row mb-4">
						<div class="col-md-12">
							<span class='pb-5'>
						  	<button type="button" v-if="creators.indexOf(logged_user.id) > -1"
						  		class="btn btn-pill btn-primary" href="#" @click="toggleCreateAgendaForm">
						  		<i class="fe fe-plus"></i> New Agenda
						  	</button>
						  	</span>							
						</div>
				  </div>
				  <div class="card mt-4" v-if="env.createAgenda">
				  	<form>
				  		<div class="col-md-12 p-5">
									<div class="mb-5 form-group">
										<label><b>What question do you want to ask?</b></label>
					  				<input type="text" name="title" class="form-control" v-model="agenda.title"
						  				placeholder="Agenda Title" required autofocus>
						  			<div class="validation-message" v-text="validation.getMessage('title')"></div>
									</div>
									<div class="mb-5 form-group">
										<label><b>Textarea</b></label>
						  			<textarea name="description" cols="30" rows="5" class="form-control" v-model="agenda.description"
						  			placeholder="Agenda Description"></textarea>
						  			<div class="validation-message" v-text="validation.getMessage('description')"></div>
									</div>
									<div class="row">
										<div class="mb-5 form-group col-md-4">
										<label><b>At what time of day?</b></label>
							  			<flat-pickr class="form-control" v-model="agenda.required_at"
					  					:config="flatPickrConfig_agendaTime"></flat-pickr>	
					  					<div class="validation-message" v-text="validation.getMessage('required_at')">
								  		</div>
										</div>
										<div class="mb-5 form-group col-md-4">
										<label><b>Expiry Time</b></label>
							  			<flat-pickr class="form-control" v-model="agenda.expires_at"
					  				:config="flatPickrConfig_agendaTime"></flat-pickr>
										</div>
										<div class="mb-5 form-group col-md-4">
											<label><b>Hours</b></label>
											<multiselect v-model="agenda.hours" 
												:options="maxHours" 
												:multiple="false" 
												:preselect-first="true">
											</multiselect>
							  			<p class="text-warning">**fill if the agenda is for day end.</p>
										</div>
									</div>
									<div class="form-group">
										<div class="pretty p-icon p-smooth">
								      		<input type="checkbox" v-model="agenda.restrictTime" />
								        	<div class="state p-success">
							            		<i class="icon typcn typcn-tick"></i>
							            		<label>Restrict time for agenda responses.</label>
								        	</div>
								    	</div>
									</div>
									<div class="form-group">
					          <label class="form-label">How often do you want to ask?</label>
					          <br>
					          <div class="selectgroup selectgroup-pills">
					            <label class="selectgroup-item" v-for="(value, name) in agenda.days">
					              <input v-model="agenda.selectedDays" type="checkbox" :name="value" :value="value" class="selectgroup-input" checked="">
					              <span class="selectgroup-button">{{ name }}</span>
					            </label>
					          </div>
					        </div>
					        <!-- //Users to select  -->
									<div class="mb-5 form-group">
										<p><a v-for="subTab in subTabs" style="color:#fff" 
												:class="selectedTab==subTab?'btn-primary':'btn-info'" 
												class="btn btn-primary btn-sm mr-2 mt-2" 
												@click.prevent="update_permissions(subTab)">
												{{subTab.toUpperCase()}}
										</a></p>
										
										<br>
										<user-selector :users='allUsers' :selectedUsersProp='agenda[selectedTab]' @input="agenda[selectedTab] = $event" class="" ></user-selector>
									</div>
									<div class="mb-5 text-center">
							  			<button v-if="!env.updateButton" type="submit" class="btn btn-pill btn-success" @click.prevent="agendaStore()">Create</button>
							  			<button v-if="env.updateButton" type="submit" class="btn btn-pill btn-success" @click.prevent="handleAgendaUpdate()">Update</button>
							  			<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
								  			@click="toggleCreateAgendaForm()">Cancel
								  		</button>
						  		</div>
				  			</div>
				  	</form>
				  </div>
				  <div class="card" v-if="agendasLoop.length === 0">
				  	<div class="card-body">
				  		<p class="text-center">Seems quite empty here...</p>
				  	</div>
				  </div>
					<div v-for="agenda in agendasLoop">
						<div class="card mt-4" :class="{'card-fullscreen' : focusedAgenda.id === agenda.id}">
							<div class="card-header">
								<h4 class="agenda-title">
									<a href="#" class="mr-2" v-if="!isEmpty(focusedAgenda)" @click.prevent="focusedAgenda={}">
										<i class="fa fa-arrow-left"></i>
									</a> {{ agenda.title }}
							        <a href="#" title="Edit Agenda" v-if="agenda_permission(agenda, 'can_edit')"
							        	@click.prevent="editAgenda(agenda.id)" class="pull-right mr-2">
							        	<i class="fe fe-edit"></i>
						       		</a>					
									<a href="#" title="Show Help" @click.prevent="displayHelp(agenda.description)" class="pull-right mr-2">
							        	<i class="fe fe-bookmark"></i>
							        </a>
								</h4>
							
							</div>
							<div class="card-body" style="overflow-y:auto">
				    		<span v-if="agenda.id === focusedAgenda.id">
				    			<agenda-component
				    				:openedAgenda = "focusedAgenda"
				    				:action = "action"
				    				:team = "team"
				    				:logged_user = "logged_user">
				    			</agenda-component>
				    		</span>

								<span v-else>
								  <p class="text-center m-0">
								  	<span v-if="agenda_permission(agenda, 'can_respond')">
								  		<a href="#"  
								  		@click.prevent="addResponse(agenda)"
								  		class="text-decoration-none btn btn-sm btn-primary mr-2" 
								  		>
										  	Add your response
									  	</a>
								  	</span>
								  	<span v-if="agenda_permission(agenda, 'can_view')">
								  		<a href="#"  class="text-decoration-none btn btn-sm btn-primary mr-2"
								  		@click.prevent="focusAgendaResponses(agenda,'creatorResponses')"
								  		>
										  	View all responses
									  	</a>
								  	</span>
								  </p>  
								</span>
				    	</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Validation from '../../utils/Validation.js';
	import flatPickr from 'vue-flatpickr-component';	
	import AgendaComponent from './Agenda.vue'; 
	export default {
		components : {
			flatPickr, AgendaComponent
		},
		props: ['focus-agenda', 'team', 'agendas', 'logged_user', 'can_create_agenda', 'users'],
		created(){
			this.agendasLoop = this.agendas;
			this.allUsers = this.users;
			this.creators = this.can_create_agenda;
			if(this.focusAgenda != 0){
				this.focusedAgenda = this.focusAgenda;
				this.action = this.focusAgenda.action_type;
			}
		},
		data() {
			return {
				action : {},		
				valError : {},	
				allUsers:[],   //if team is not empty then users_in_team otherwise all users
				focusedAgenda: {},
				agendasLoop : [],	
				creators : [],
				selectedTab:'can_respond',
				subTabs: ['can_respond','can_view','can_edit','can_comment','response_add_notify'],
				maxHours: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],
				agenda: this.emptyAgendaForm(),
				validation: new Validation(),
				env: {
	  			createAgenda:false,
	  			EditID:0,
        	updateButton:false,
	  		},
	  		isEmpty(obj) {
			    for(var key in obj) {
		        if(obj.hasOwnProperty(key))
		            return false;
			    }
			    return true;
				},

				addResponse(agenda){
					window.axios.post(`/pms/agenda/checks/is_strict`, {
						'agenda': agenda
					}).then(response => {
						console.log('success', response.data);
						this.focusAgendaResponses(agenda, 'UserResponses');
			 		}).catch(error => {
		         console.log('error', error.response);
		         if (error.response.status == 422) {
							Vue.swal({
						  	type: 'warning',
						  	title: '',
						  	text: 'Submit time out of range',		
							})
						}
		      });
				},

	  		focusAgendaResponses(agenda, type = null) {
			  	if(this.isEmpty(this.focusedAgenda)) {
						window.axios.get(`/pms/agenda/${agenda.id}`).then(response => {
							//console.log(response.data);
							this.focusedAgenda = response.data;
							this.action = type;
				 		}).catch(error => {
			         console.log('error', error.response);
			      });
					} else {
						this.focusedAgenda = {};
					}
	      },
	      
	  		flatPickrConfig_agendaTime:{
	  			enableTime: true,
	  			noCalendar: true,
					dateFormat: "H:i",
					time_24hr: true,
					minuteIncrement: 30,
					allowInput: false,
	  		},
			}
		},
		methods: {
			update_permissions(subtab) {
				console.log(subtab);
				this.selectedTab = subtab;
			},

			agenda_permission(agenda, type) {
				let permissions = JSON.parse(agenda.permissions);

				if (permissions[type].indexOf(this.logged_user.id) > -1) {
					return true;
				}

			 	if (type == 'can_edit' && agenda.creator_id == this.logged_user.id) {
				 	return true;
			 	}

			 	return false;
			},

			editAgenda(id) {
				window.scroll({top: 0,left: 0,behavior: 'smooth'});
				this.selectedTab = 'can_respond';
				window.axios.get(`/pms/agenda/${id}/edit`).then(response => {
					console.log(response.data);
		     	this.env.createAgenda = true;
		     	this.env.updateButton = true;
		     	this.env.EditID = response.data.id;
		     	this.agenda.title =  response.data.title;
		     	this.agenda.description =  response.data.description
		     	this.agenda.restrictTime =  response.data.is_strict
		     	this.agenda.required_at = response.data.required_at;
		     	this.agenda.expires_at = response.data.expires_at;
		     	this.agenda.can_respond =  JSON.parse(response.data.permissions)['can_respond'];
		     	this.agenda.can_view =  JSON.parse(response.data.permissions)['can_view'];
		     	this.agenda.can_edit =  JSON.parse(response.data.permissions)['can_edit'];
		     	this.agenda.can_comment =  JSON.parse(response.data.permissions)['can_comment'];
		     	this.agenda.response_add_notify =  JSON.parse(response.data.permissions)['response_add_notify'];
		     	this.agenda.selectedDays = JSON.parse(response.data.days);
		 		})				
		 	.catch(error => {
	        // console.log('error', error.response);
	      });
			},

			handleAgendaUpdate() {  // Update Agenda Mast
		  	let postData = this.agenda;
		     window.axios.patch(`/pms/agenda/${this.env.EditID}`, postData).then(response => {
		     	//console.log('save',response.data);
		     	this.agendas.forEach((v, k) => {
						 if (v.id == response.data.id)
						 {
						 	Vue.set(this.agendas,k, response.data);
						 }
					});
		     	this.toggleCreateAgendaForm();
		     	Vue.swal({
					  type: 'success',
					  title: 'Update',
					  text: 'Updated Successfully.'
					})
		 		}).catch(error => {
		 			 if (error.response.status == 422) {
		 			 	Vue.swal({
						  type: 'warning',
						  title: '',
						  text: 'Please fill all required fields.',		
						})
	          this.validation.setMessages(error.response.data.errors);
	        } else {
        	 	console.log(error.response.data)
	        }
		 		});
			},
			displayHelp(data) {
				Vue.swal(data);
			},
			agendaStore(){    //Create Agenda mast
  			let postData = this.agenda;
	  		postData.team = this.team; // add additional param
		     window.axios.post('/pms/agenda', postData).then(response => {
		        this.agenda = this.emptyAgendaForm();
		        this.toggleCreateAgendaForm();
		        this.agendasLoop.unshift(response.data);
		        this.validation = new Validation();
		        Vue.swal({
						  type: 'success',
						  title: 'Create',
						  text: 'Created Successfully.',		
						})
		 		}).catch(error => {
		 			console.log(error.response.data);
		 			 if (error.response.status == 422) {
		 			 	Vue.swal({
						  type: 'warning',
						  title: '',
						  text: 'Please fill all required fields.',		
						})
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	 console.log(error.response.data)
	        }
		 		});
			},
			toggleCreateAgendaForm(){
		  	let form = this.env.createAgenda;
		  	if(form){
		  		this.validation = new Validation();
		  		this.agenda = this.emptyAgendaForm();
		  		this.env.updateButton = false;
		  		this.env.EditID = 0;
		  		this.update_permissions('users');
		  	}
		  	this.env.createAgenda = !this.env.createAgenda;
		  },			
			emptyAgendaForm(){
				return {
	        title: '',
        	description: '',
        	required_at:'10',
        	expires_at:'12',
        	restrictTime:true,
        	selectedDays: [1,2,3,4,5,6],
        	days:{
        		'Mon':1,
        		'Tue':2,
        		'Wed':3,
        		'Thu':4,
        		'Fri':5,
        		'Sat':6,
        		'Sun':7
        	},
        	hours:0,
        	can_respond:[],
        	can_view:[],
        	can_edit:[],
        	can_comment:[],
        	response_add_notify:[],
	      }
			},
		}
	}
</script>