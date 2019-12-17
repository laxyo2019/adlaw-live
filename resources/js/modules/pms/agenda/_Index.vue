<template>
<div class="container">
	<!-- Top Bar -->
	<div class="row">
		<span class='pb-5'>
	  	<button type="button" v-if="creators.indexOf(logged_user.id) > -1"
	  		class="btn btn-pill btn-primary" href="#" @click="toggleCreateAgendaForm">
	  		<i class="fe fe-plus"></i> New Agenda</button>
	  </span>
  </div>
  <div class="card" >
  	<form v-if="env.createAgenda">
  			<div class="col-12 p-5">
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
					<div class="mb-5 form-group col-4">
						<label><b>At what time of day?</b></label>
		  				<flat-pickr class="form-control" v-model="agenda.time" 
		  				:config="flatPickrConfig_agendaTime"></flat-pickr>
					</div>
					<div class="mb-5 form-group col-4">
						<label><b>Expiry Time</b></label>
		  				<flat-pickr class="form-control" v-model="agenda.expTime" :config="flatPickrConfig_agendaTime"></flat-pickr>
					</div>
				<div class="col-4">
					<br><br>
						<div class="pretty p-icon p-smooth">
			        <input type="checkbox" v-model="agenda.restrictTime" />
			        <div class="state p-success">
			            <i class="icon typcn typcn-tick"></i>
			            <label>Restrict time for agenda responses.</label>
			        </div>
			    </div>
				</div>
				</div>
				<div class="form-group">
          <label class="form-label">How often do you want to ask?</label>
          <div class="selectgroup selectgroup-pills">
            <label class="selectgroup-item" v-for="(value, name) in agenda.days">
              <input v-model="agenda.selectedDays" type="checkbox" :name="value" :value="value" class="selectgroup-input" checked="">
              <span class="selectgroup-button">{{ name }}</span>
            </label>
          </div>
        </div>
				<!-- //Users to select  -->
				<div class="mb-5 form-group">
					<p>
						<a  v-for="subTab in subTabs"
							style="color:#fff" 
							:class="selectedTab==subTab?'btn-primary':'btn-info'" 
							class="btn btn-primary btn-sm mr-2" 
							@click.prevent="update_permissions(subTab)">
							{{subTab.toUpperCase()}}</a>
					</p>
					<br>
					<user-selector :users='team.users_in_team' :selectedUsersProp='agenda[selectedTab]' @input="agenda[selectedTab] = $event" class="" ></user-selector>
			<!-- 		<p-check v-model="agenda.users" v-for="user in team.users_in_team" class="col-xs-11 col-sm-11 col-md-5 col-lg-3 col-xl-3" 
						color="success" :value="user.id" :key="user.id">
		        {{user.name}}
          </p-check> -->
				</div>
				<div class="mb-5 text-center">
		  			<button v-if="!env.updateButton" type="submit" style="border-radius: 2rem" class="btn btn-success" @click.prevent="handleAgendaInit()">Create</button>
		  			<button v-if="env.updateButton" type="submit" style="border-radius: 2rem" class="btn btn-success" @click.prevent="handleAgendaUpdate()">Update</button>
		  			<button type="button" style="border-radius: 2rem" class="btn btn-outline-dark ml-2" 
			  			@click="toggleCreateAgendaForm()">Cancel
			  		</button>
	  			</div>
  			</div>
  	</form>
  </div>
  <div class="card" v-if="agendas.length === 0">
  	<div class="card-body">
  		<p class="text-center">Seems quite empty here...</p>
  	</div>
  </div>
  <div v-for="agenda in agendas">
		<div class="card" :class="{'card-fullscreen' : focusedAgenda.id === agenda.id}" v-if="agenda.users.indexOf(logged_user.id) > -1 || agenda.creator_id == logged_user.id">
	    <div class="card-header">
	      <h3 class="card-title agenda-title" v-text="agenda.title"></h3>
	      <div class="card-options">
	        <a href="#" title="Show Help" @click.prevent="displayHelp(agenda.description)">
	        	<i class="fe fe-bookmark"></i>
	        </a>
	        <a href="#" title="Edit Agenda" v-if="agenda.creator_id == logged_user.id || fetch_index(agenda,'edit').indexOf(logged_user.id) > -1"
	        	@click.prevent="editAgenda(agenda.id)">
	        	<i class="fe fe-edit"></i>
	        </a>
	        <a href="#"  v-if="creators.indexOf(logged_user.id) > -1 && agenda.pause"
	        	@click.prevent="togglePause(agenda.id,0)" title="click me to resume" class="text-danger">
	        	<i class="fe fe-toggle-left f-20"></i>
	        </a>
	        <a href="#" v-if="creators.indexOf(logged_user.id) > -1 && !agenda.pause"
	        @click.prevent="showModal('create-topic');modalAgendaId=agenda.id" title="click me to pause" class="text-success">
	        	<i class="fe fe-toggle-right f-20"></i>
	        </a>
	        <modal name="create-topic">
						<form>
						  <fieldset class="m-5">
								<div class="form-group">
									<label><b>At what day should this agenda resume?</b></label>
					  				<flat-pickr class="form-control" v-model="resumedDay" ></flat-pickr>
					  				<div class="validation-message" v-text="validationPause.getMessage('resume_at')">
					  				</div>
								</div>
					  		<div class="form-group text-center">
					  			<button type="submit" class="btn btn-pill btn-success" 
					  				@click.prevent="togglePause(modalAgendaId,1)">Create
					  			</button>
					  			<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
					  				@click.prevent="hideModal('create-topic');modalAgendaId=null;resumedDay=null">Cancel
					  			</button>
					  		</div>
						  </fieldset>
						</form>					
					</modal>
	      </div>
	    </div>
	    <div class="card-body" style="overflow-y:auto">
    		<span v-if="agenda.id === focusedAgenda.id">
    			<agenda-component
    				@unfocus="focusedAgenda={}"
    				:openedAgenda="agenda"
    				:action="action"
    				:team="team"
    				:logged_user="logged_user"
    				>
    			</agenda-component>
    		</span>
				<span v-else>
				  <p class="text-center m-0">
				  	<span v-if="checkuser(agenda.users)">
				  		<a href="#" @click.prevent="focusAgendaResponses(agenda,'UserResponses')" class="text-decoration-none">
						  	<span v-if="!checkTodaysResponse(agenda.responses)">
						  		Add Your Response
						  	</span>
						  	<span v-else>
						  		View Response History
						  	</span>
					  	</a>
				  	</span>
				  	<span v-if="checkuser(agenda.users) && (agenda.creator_id == logged_user.id || fetch_index(agenda,'view').indexOf(logged_user.id) > -1)" style="color:#3490dc" class="ml-2 mr-2">|</span>
						<!-- response history of all users for creator-->
				  	<a href="#" v-if="agenda.creator_id == logged_user.id || fetch_index(agenda,'view').indexOf(logged_user.id) > -1" @click.prevent="focusAgendaResponses(agenda,'creatorResponses')"
				  		class=" text-decoration-none">View all reponses
				  	</a>
				  </p>  
				</span>
	    </div>
	  </div>
	</div>
</div>
</template>
<script>
	import Validation from '../../../utils/Validation.js';
	import AgendaComponent from './Agenda.vue'; 
	import VModal from 'vue-js-modal';
	import flatPickr from 'vue-flatpickr-component';
	Vue.use(VModal)
	export default {
		props: ['team', 'logged_user', 'checklist','meta'],
		components : {
			AgendaComponent, flatPickr, AgendaComponent
		},
		created(){
			if(!this.isEmpty(this.meta)){
				this.focusAgendaResponses(this.meta,this.meta['type']);
			}
			console.log(this.meta);
			this.agendas = this.checklist.agendas;
			  var currentDate = new Date();
				var date = currentDate.getDate();
				var month = currentDate.getMonth(); //Be careful! January is 0 not 1
				var year = currentDate.getFullYear();
				if(month.length==2){
					this.dateString = year + "-" +(month + 1) + "-" + date;
				}else{
					this.dateString = year + "-" +0+(month + 1) + "-" + date;
				}
		},
		data() {
			return {
				subTabs: ['users','viewers','can_edit','commenters','can_delete'],
				selectedTab: 'users',
				resumedDay:null,
				modalAgendaId:null,
				validation: new Validation(),
				validationPause: new Validation(),
				dateString:'',
				creators: [4,1,2,5],
				flatPickrConfig_agendaTime:{
		  			enableTime: true,
		  			noCalendar: true,
						dateFormat: "H",
						time_24hr:true,
		  		},
		  	action:null,
		  	agenda: this.emptyAgendaForm(),
		  	focusedAgenda:{},
		  	focusedCreatorAgenda:false,
		  	agendas:[],
		  	env: {
		  			createAgenda:false,
		  			EditID:0,
	        	updateButton:false,
		  		},
		  	users:[],
			}
		},
		methods: {
			fetch_index(agenda,index){
				return JSON.parse(agenda.permissions)[index];
			},
			update_permissions(subtab){
				this.selectedTab = subtab;
			},
			showModal(modalName) {
		    this.$modal.show(modalName);
		  },
		  hideModal(modalName) {
		    this.$modal.hide(modalName);
		  },
			togglePause(id,pause){
				  window.axios.post(`/pms/agenda/toggle_pause`, {id:id,pause:pause,resume_at:this.resumedDay}).then(response => {
		     	this.agendas.forEach((v, k) => {
						 if (v.id == id)
						 {
						 	v.pause = pause;
						 	 Vue.set(this.agendas,k, v);
						 }
					});
					if(pause==1){
						this.hideModal('create-topic');
						this.modalAgendaId=null;
						this.resumedDay=null;
						this.validationPause = new Validation(); 
					}
		    	Vue.toasted.show(response.data, {
							type : 'success',
							icon : 'check',
							duration: 2000
					});
		 		})
		 	.catch(error =>{
		 		 if (error.response.status == 422) {
	          this.validationPause.setMessages(error.response.data.errors);
	        }else{
	        	console.log(error.response.data)
	        }
		 			
		 		});
			},
			checkuser(users){
				let returnVal = false;
				let id = this.logged_user.id;
				JSON.parse(users).forEach((e) => {
					if(e == id){
						returnVal = true;
					}
				});
				return returnVal;
			},
			displayHelp(data) {
				Vue.swal(data);
			},
			emptyAgendaForm(){
				return {
	        title: '',
        	description: '',
        	time:'10',
        	expTime:'12',
        	restrictTime:true,
        	selectedDays: [1,2,3,4,5,6],
        	days: {
        		'Mon':1,
        		'Tue':2,
        		'Wed':3,
        		'Thu':4,
        		'Fri':5,
        		'Sat':6,
        		'Sun':7
        	},
        	users:[],
        	viewers:[],
        	can_edit:[],
        	commenters:[],
        	can_delete:[],
	      }
			},
			showModal(modalName) {
		    this.$modal.show(modalName);
		  },
		  hideModal(modalName) {
		    this.$modal.hide(modalName);
		    this.validationPause = new Validation();
		  },
		  hideAgendaModal(modalName){
		  	this.hideModal(modalName);
		  },
		  toggleCreateAgendaForm(){
		  	let form = this.env.createAgenda;
		  	if(form){
		  		this.validation = new Validation();
		  		this.agenda = this.emptyAgendaForm();
		  		this.env.updateButton = false;
		  		this.env.EditID = 0;
		  	}
		  	this.env.createAgenda = !this.env.createAgenda;
		  },
		  editAgenda(id){
		  	window.scroll({
				 top: 0, 
				 left: 0, 
				 behavior: 'smooth' 
				});
				this.selectedTab = 'users';
		    window.axios.get(`/pms/agenda/${id}`).then(response => {
		     	this.env.createAgenda = true;
		     	this.env.updateButton = true;
		     	this.env.EditID = response.data.id;
		     	this.agenda.title =  response.data.title;
		     	this.agenda.description =  response.data.description
		     	this.agenda.restrictTime =  response.data.restrict_time==0 ? false:true
		     	this.agenda.time = response.data.time;
		     	this.agenda.expTime = response.data.expiry_time;
		     	this.agenda.users =  JSON.parse(response.data.users);
		     	this.agenda.commenters =  JSON.parse(response.data.permissions)['comment'];
		     	this.agenda.viewers =  JSON.parse(response.data.permissions)['view'];
		     	this.agenda.can_edit =  JSON.parse(response.data.permissions)['edit'];
		     	this.agenda.can_delete =  JSON.parse(response.data.permissions)['delete'];
		     	this.agenda.selectedDays = JSON.parse(response.data.days);
		 		})				
		 	.catch(error => {
	        // console.log('error', error.response);
	      });
		  },
		  handleAgendaUpdate(){
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
		 		})
		 	.catch(error =>{
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
		  handleAgendaInit(){
		  	let postData = this.agenda;
	  		postData.team_id = this.team.id; // add additional param
		     window.axios.post('/pms/agenda', postData).then(response => {
		     	// console.log('save',response.data);
		        this.agenda = this.emptyAgendaForm();
		        this.toggleCreateAgendaForm();
		        this.agendas.unshift(response.data);
		        	this.validation = new Validation();
		        Vue.swal({
						  type: 'success',
						  title: 'Create',
						  text: 'Created Successfully.',		
						})
		 		}).catch(error =>{
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
			isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},				
      focusAgendaResponses(agenda,type = 	null) {
		  	if(this.isEmpty(this.focusedAgenda)) {
		  		this.focusedAgenda = agenda;
		  		this.action = type;
				} else {
					this.focusedAgenda = {};
				}
      },
      checkTodaysResponse(responses){
      	// var count = false;
      	// var todayDate = this.dateString;
      	// responses.forEach(function(e){
      	// 	if(e.created_at.split(' ')[0] == todayDate && this.logged_user.id==e.responder_id){
      	// 		count=true;
      	// 	}
      	// });
      	// return count;
      }
		}
	}
// document.querySelector(".flatpickr-minute").closest("div").style.display = 'none';
</script>
<style>
	.numInputWrapper:has(> .flatpickr-minute){
		display:none!important;
	}
	.f-20{
		font-size:20px !important;
	}
</style>