<template>
	<div class="group">
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Manage Teams</h3>
						<div class="card-options">
		          <div class="input-group">
		            <input type="text" class="form-control form-control-sm" placeholder="Search..." v-model="searchKey" @keyup="runSearch">
		            <span class="input-group-btn ml-2">
		              <button class="btn btn-sm btn-default" @click="runSearch">
		                <span class="fe fe-search"></span>
		              </button>
		            </span>
		            <span class="input-group-btn ml-2">
	                <button class="btn btn-sm btn-default" @click="refresh">
	                  <span class="fe fe-refresh-cw"></span>
	                </button>
	              </span>
		          </div>
            </div> 
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-outline table-vcenter text-nowrap card-table">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Type</th>
										<th>Users</th>
										<th class="float-right">Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(team, index) in teams.data" :key="index">
										<td>{{ team.id }}</td>
										<td>{{ team.name }}</td>
										<td>{{ teamType(team.type) }}</td>
										<td>{{ team.users_in_team.length }}</td>
										<td class="float-right">
											<a class="mr-2" href="#" @click.prevent="editTeam(team.id)"><i class="fe fe-edit"></i></a>
											<div class="item-action dropdown mr-2">
												<a href="javascript:void(0)" data-toggle="dropdown" class="icon">
													<i class="text-default fe fe-layers"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-right mr-2">
													<router-link :to="{ name: 'AssignUser', params: {id: team.id}}" class="dropdown-item"><i class="dropdown-icon fe fe-users"></i> Add Users </router-link>
												</div>
												
											</div>
											<a class="mr-2" href="#" @click.prevent="syncTeam(team.id)"><i class="fe fe-activity"></i></a>
											
										</td>
									</tr>
								</tbody>
							</table>
							 <pagination :data="teams" @pagination-change-page="runSearch" align="center">
				        <span slot="prev-nav">Previous</span>
				        <span slot="next-nav">Next</span>
				      </pagination>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card" v-show="!this.editTeamMode && !syncTeamMode">
					<div class="card-header">
						<h3 class="card-title">Create New Team</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
							<select class="form-control" v-model="team.type">
								<option :value="type.id" v-for="type in types">{{ type.name }}</option>
							</select>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Name" v-model="team.name" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Display Name" v-model="team.display_name" />
						</div>
						<div class="form-group">
							<textarea class="form-control" placeholder="Description" v-model="team.description"></textarea>
						</div>

				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Sync Modules (Edit Module)</h3>
					</div>
					<div class="card-body">
						<div id="syncModules">
							<div class="row">
								<div class="col-12">
									 <p-check class="col-5" color="success" v-model="team.chatroom_id">Chatroom</p-check>
									 <p-check class="col-5" color="success" v-model="team.messageboard_id">Messageboard</p-check>
									 <p-check class="col-5" color="success" v-model="team.taskboard_id">taskboard</p-check>
									 <p-check class="col-5" color="success" v-model="team.calendar_id">Calender</p-check>
									 <p-check class="col-5" color="success" v-model="team.checklist_id">Checklist</p-check>
									 <p-check class="col-5" color="success" v-model="team.filestack_id">Filestack</p-check>	
								</div>
							</div>
						</div>
					</div>
				</div>
						<div class="form-group">
							<button class="btn btn-success btn-block" @click="save">Save</button>
						</div>
					</div>
				</div>
				<div class="card" v-show="this.editTeamMode && !syncTeamMode">
					<div class="card-header">
						<h3 class="card-title">Edit Team</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
							<select class="form-control" v-model="team.type">
								<option :value="type.id" v-for="type in types">{{ type.name }}</option>
							</select>
						</div>
						<div class="form-group">
							<p>Name : </p>
							<input type="text" class="form-control" placeholder="Name" v-model="team.name" readonly/>
						</div>
						<div class="form-group">
							<p>Display Name : </p>
							<input type="text" class="form-control" placeholder="Display Name" v-model="team.display_name" />
						</div>
						<div class="form-group">
							<p>Description : </p>
							<textarea class="form-control" placeholder="Description" v-model="team.description"></textarea>
						</div>
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Sync Modules</h3>
							</div>
							<div class="card-body">
								<div id="syncModules">
									<div class="row">
										<div class="col-12">
												 <p-check class="col-5" color="success" v-model="team.chatroom_id">Chatroom</p-check>
												 <p-check class="col-5" color="success" v-model="team.messageboard_id">Messageboard</p-check>
												 <p-check class="col-5" color="success" v-model="team.taskboard_id">Taskboard</p-check>
												 <p-check class="col-5" color="success" v-model="team.calendar_id">Calender</p-check>
												 <p-check class="col-5" color="success" v-model="team.checklist_id">Checklist</p-check>
												 <p-check class="col-5" color="success" v-model="team.filestack_id">Filestack</p-check>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group mt-3">
							<button class="btn btn-success btn-block" @click="update(team.id)">Update</button>
						</div>
					</div>
				</div>

				<!-- temporary code to syn -->
				<div class="card" v-show="this.syncTeamMode">
					<div class="card-header">
						<h3 class="card-title">Sync Team</h3>
					</div>
					<div class="card-body">
						<div class="form-group">
							<p>Name : </p>
							<input type="text" class="form-control" placeholder="Name" v-model="team.name" readonly/>
						</div>
						<div class="form-group">
							<p>Chatrrom : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.chat_id" readonly/>
						</div>
						<div class="form-group">
							<p>Messageboard : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.msg_id" readonly/>
						</div>
						<div class="form-group">
							<p>taskBoard : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.task_id" readonly/>
						</div>
						<div class="form-group">
							<p>calender : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.calendar_id" readonly/>
						</div>
						<div class="form-group">
							<p>Checklist : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.checklist_id" readonly/>
						</div>
						<div class="form-group">
							<p>filestack : </p>
							<input type="text" class="form-control" placeholder="null" v-model="sync.filestack_id" readonly/>
						</div>
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Sync Modules (Master Entry)</h3>
					</div>
					<div class="card-body">
						<div id="syncModules">
							<div class="row">
								<div class="col-12">
										 <p-check class="col-5" color="success" v-model="team.chatroom_id">Chatroom</p-check>
										 <p-check class="col-5" color="success" v-model="team.messageboard_id">Messageboard</p-check>
										 <p-check class="col-5" color="success" v-model="team.taskboard_id">Taskboard</p-check>
										 <p-check class="col-5" color="success" v-model="team.calendar_id">Calender</p-check>
										 <p-check class="col-5" color="success" v-model="team.checklist_id">Checklist</p-check>
										 <p-check class="col-5" color="success" v-model="team.filestack_id">Filestack</p-check>	
								</div>
							</div>
						</div>
					</div>
				</div>
						<div class="form-group mt-3">
							<button class="btn btn-success btn-block" @click="updateSyncTeam(team.id)">Sync Modules</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
const BASE_URL = '/admin/teams';
export default {
	name: 'Team',
	data () {
		return {
			types: [],
			searchKey: '',
			teams: {},
			errors: [],
			syncTeamMode:false,
			team: this.emptyTeamForm(),
			sync: this.emptySyncIds(),
			editTeamMode: false,
			modules: {
				'chatroom': false,
				'messageboard': false,
				'taskboard': false,
				'calendar': false,
				'checklist': false,
				'filestack': false
			}
		}
	},
	created () {
		let uri = `${BASE_URL}`;
		axios.get(uri).then(response => {
			this.types = response.data.types;
		}).catch(error => {
			console.log(error.response.data);
		})
		this.runSearch();
	},
	methods: {
		refresh(){
		 	this.searchKey='';
			this.runSearch();
		},
		runSearch(page=1) {
			window.axios.post(`${BASE_URL}/search?page=${page}`, {'keyword': this.searchKey}).then(response => {
				this.teams = response.data;
			}).catch(error => {
					// Vue.swal(error.response.data.message)
			});
		},
		teamType(type) {
			let x = {
				1 : 'HQ',
				2 : 'General',
				3 : 'Private'
			};

			return x[type];
  	},
		emptyTeamForm() {
			return {
				'type': '',
				'name': '',
				'display_name': '',
				'description':'',
				'chatroom_id':true,
				'messageboard_id':true,
				'taskboard_id':true,
				'calendar_id':true,
				'checklist_id':true,
				'filestack_id':true,
			}
		},
		emptySyncIds(){
			return{
				'chat_id': 'empty',
				'msg_id':'empty',
				'task_id':'empty',
				'calendar_id':'empty',
				'checklist_id':'empty',
				'filestack_id':'empty',
			}
		},
		save () {
			let uri = `${BASE_URL}`;
			axios.post(uri, {team:this.team, name:this.team.name ,type:this.team.type}).then(response => {
				console.log(response.data);
					this.teams.unshift(response.data);
					this.team = this.emptyTeamForm();
					Vue.swal({
						  type: 'success',
						  title: 'Created',
						  text: 'Created new team successfully.'
						})
			}).catch(error => {
				if(error.response.status==422){	 
					Vue.swal({
						  type: 'error',
						  title: 'Invalid Entry',
						  text: 'Name|type required where name must be unique'
						})
				}else{
					console.log(error)
					this.errors = error.response.data.errors
				}
			})
		},
		getTeam (id) {
			let uri = `${BASE_URL}/${id}`;
			axios.get(uri).then(response => {
				console.log(response.data)
				this.teams = response.data;
			}).catch(error => {
				console.log(error.response.data)
			})
		},	
		updateSyncTeam (id) {
			console.log(id);
			let uri = `${BASE_URL}/updatesyncdata`;
			axios.post(uri, {
				id:id,
				name: this.team.name,
				chatroom_id: this.team.chatroom_id,
				messageboard_id: this.team.messageboard_id,
				taskboard_id: this.team.taskboard_id,
				calendar_id: this.team.calendar_id,
				checklist_id: this.team.checklist_id,
				filestack_id: this.team.filestack_id
		
			}).then(response => {
				this.teams = response.data;
				this.team = this.emptyTeamForm();
				Vue.swal({
						  type: 'success',
						  title: 'Update',
						  text: 'Updated successfully.'
						})
			}).catch(error => {
				console.log(error.response.data)
			});
			this.syncTeamMode = false;
		},
		syncTeam (id) {
			this.syncTeamMode = true;
			let uri = `${BASE_URL}/${id}/getsyncdata`;
			axios.get(uri).then(response => {
				console.log(response.data);
				this.team.id = response.data.id;
				this.sync.chat_id = response.data.chatroom_id;
				this.sync.msg_id = response.data.messageboard_id;
				this.sync.task_id = response.data.taskboard_id;
				this.sync.calendar_id = response.data.calendar_id;
				this.sync.checklist_id = response.data.checklist_id;
				this.sync.filestack_id = response.data.filestack_id;

				let modules = JSON.parse(response.data.modules);
				this.team.chatroom_id = modules['chatroom'];
				this.team.messageboard_id = modules['messageboard'];
				this.team.taskboard_id = modules['taskboard'];
				this.team.calendar_id = modules['calender'];
				this.team.checklist_id = modules['checklist'];
				this.team.filestack_id = modules['filestack'];
				this.team.name = response.data.name;

			}).catch(error => {
				console.log(error.response.data)
			})
		},
		editTeam(id) {
			this.syncTeamMode=false;
			this.editTeamMode = true;
			let uri = `${BASE_URL}/${id}/edit`;
			axios.get(uri).then(response => {
				this.team = response.data;
				let modules = JSON.parse(response.data.modules);
				this.team.chatroom_id = modules['chatroom'];
				this.team.messageboard_id = modules['messageboard'];
				this.team.taskboard_id = modules['taskboard'];
				this.team.calendar_id = modules['calender'];
				this.team.checklist_id = modules['checklist'];
				this.team.filestack_id = modules['filestack'];

			}).catch(error => {
				console.log(error.response.data)
			})
		},
		update (id) {
			let uri = `${BASE_URL}/${id}`;
			axios.patch(uri, {
				display_name: this.team.display_name,
				type: this.team.type,
				description: this.team.description,
				chatroom_id: this.team.chatroom_id,
				messageboard_id: this.team.messageboard_id,
				taskboard_id: this.team.taskboard_id,
				calendar_id: this.team.calendar_id,
				checklist_id: this.team.checklist_id,
				filestack_id: this.team.filestack_id
			}).then(response => {
				this.teams = response.data;
				this.team = this.emptyTeamForm();
				Vue.swal({
						  type: 'success',
						  title: 'Update',
						  text: 'Updated successfully.'
						})
			}).catch(error => {
				console.log(error.response.data)
			});
			this.editTeamMode = false
		},
		// destroy (id) {
		// 	let uri = '/admin/teams/'+id;
		// 	axios.delete(uri).then(response => {
		// 		this.teams = response.data
		// 	}).catch(error => {
		// 		console.log(error.response.data)
		// 	})
		// }
	}
}
</script>