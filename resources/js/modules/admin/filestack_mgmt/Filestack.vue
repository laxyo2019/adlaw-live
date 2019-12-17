<template>
	<div class="container">
		<div class="card">
			<div class="card-header">
				<a style="color:#467fcf" class="" @click.prevent="$emit('unfocus')">
					<i class="fa fa-arrow-left"></i>
				</a>
				<b class="col-2">{{filestack.title}}</b>
				<div class="col-10 text-right">
						<a v-for="tab in tabs" 
							style="color:#fff"  
							:class="activeTab==tab?'btn-primary':'btn-info'" class="btn btn-sm mr-2" 
							@click.prevent="activeTabFun(tab)">
							{{tab.toUpperCase()}}</a>
				</div>
			</div>
			<div class="card-body">
			<div class="row">
					<p class="col-3">
					<span class=" mr-2"><b>Permissions <i class="fa fa-arrow-right m-2">	</i>{{activeTab.toUpperCase()}} </b> </span>		
				</p>
				<p class="col-9 pt-1 text-right">
					<a  v-for="subTab in subTabs[activeTab]"
						style="color:#fff" 
						:class="activeSubTab==subTab?'btn-primary':'btn-info'" 
						class="btn btn-primary btn-sm mr-2" 
						@click.prevent="activeSubTabFun(subTab)">
						{{subTab.toUpperCase()}}</a>
				</p>
			</div>
	      <user-selector :users='allUsers' :selectedUsersProp='selectedUsers' @input="selectedUsers = $event"></user-selector>
	      <div class="text-center">
	      	<button class="btn btn-success btn-pill" @click="updatePermissions">Update</button>
	      </div>
			</div>
		</div>
	</div>
</template>
<script>

export default{
	props: ['users','focusedFilestack'],
	components : {
		
	},
	data (){
		return{
			tabs:['users','file','folder'],
			subTabs:{
				'file':['upload','download','move','copy','delete'],
				'folder':['create','move','edit','delete']
			},
			activeSubTab:'',
			activeTab : 'users',
			permissionsIndex:'users',
			selectedUsers:[],
			allUsers:{},
			filestack : this.focusedFilestack,
		}
	},	
	created(){
		this.getAllUsers();
		this.permissionsType('users');
	},
	methods:{	
		activeTabFun(tab){
			this.activeTab = tab;
			if(tab == 'users'){
				this.permissionsIndex = 'users';
				this.permissionsType('users');
			}
			else if(tab == 'file'){
				this.activeSubTab = 'upload';
				this.permissionsIndex = 'upload';
				this.permissionsType('file');
			}else if(tab == 'folder'){
				this.activeSubTab = 'create';
				this.permissionsIndex = 'create';
				this.permissionsType('folder');
			}
		},
		activeSubTabFun(subTab){
			this.activeSubTab = subTab;
			this.permissionsIndex = subTab;
			this.permissionsType(this.activeTab);
		},
		updatePermissions(){
			axios.post('/admin/filestack-mgmt/update_permissions',{
					permissionsIndex:this.permissionsIndex,
					id:this.filestack.id,
					active_tab:this.activeTab,
					users:this.selectedUsers})
			.then(response => {
					this.filestack.permissions = response.data.permissions;
					Vue.toasted.show("Updated Successfully", {
							type : 'success',
							icon : 'check',
							duration: 2000
					});
			}).catch(error => {
				console.log(error);
			});
		},
		permissionsType(type){
			let permissionsType = this.permissionsIndex;
			let users = [];
			if(this.permissionsIndex == 'users'){
				users = (JSON.parse(this.filestack.permissions))[permissionsType];
			}else if(this.activeTab=='file'){
				users = (JSON.parse(this.filestack.permissions)['file'][this.activeSubTab]);
			}else{
				users = (JSON.parse(this.filestack.permissions)['folder'][this.activeSubTab]);
			}
			console.log('user',users);
			 this.selectedUsers = users;
		},
		getAllUsers(){
			axios.post('/admin/filestack-mgmt/users')
				.then(response => {
					this.allUsers = response.data;
				});
		},
	},
	mounted(){

	},

}
</script>