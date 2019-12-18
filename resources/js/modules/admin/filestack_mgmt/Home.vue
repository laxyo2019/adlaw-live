<template>
	<div class="my-3 my-md-5">
    <div class="" v-if="isEmpty(focusedFilestack)">
		<div class="box">
			<div class="box-header">
				<b class="col-md-2">Filestacks</b>
						<div class="form-group col-md-6">
							<input type="text" class="mt-2 form-control" placeholder="Search a filestack..." v-model="searchKey" @keyup="getResults">
						</div>
						<div class="col-md-4 text-right">
							<a style="color:#fff" class="btn btn-warning btn-sm mr-2" @click.prevent="openForm=true">
								<i class="fa fa-plus"></i>
								New Filestack
							</a>
							<a style="color:#fff" class="btn btn-primary btn-sm mr-2" v-for="tag in tags" @click.prevent="searchKey='';type=tag.ident;focusedEditFilestack={};getResults()" v-if="tag.name != 'team'">{{tag.name.toUpperCase()}}</a>

							<!-- <a style="color:#fff" class="btn btn-info btn-sm mr-2" title="Update Permissions column" @click.prevent="updateIndex()">
								<i class="fa fa-wrench"></i>
							</a> -->

						</div>
					</div>
					<div class="box-body" v-if="openForm">
						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<label class="" for="">Title :</label>
								<input v-if="newFilestack.type==2" type="text" class="form-control" v-model="newFilestack.title">
								<div v-if="newFilestack.type==2" class="validation-message" v-text="validation.getMessage('title')"></div>
							   <multiselect  v-if="newFilestack.type==1"  v-model="newFilestack.userTitle" 
								  :options="filestackUsers" 
								  placeholder="Pick some" 
								  label="name" 
								  track-by="name" 
								  :preselect-first="true">
							  </multiselect>
							  <div v-if="newFilestack.type==1" class="validation-message" v-text="validation.getMessage('userTitle')"></div>
							</div>
							<div class="col-sm-6 col-xs-12">
								<label class="" for="">Type :</label>
								<br>
								<p-radio v-model="newFilestack.type" v-for="tag in filteredTags" class="col-xs-11 col-sm-11 col-md-5 col-lg-3 col-xl-3" 
									color="success" :value="tag.ident" :key="tag.ident">
					        {{tag.name.toUpperCase()}}
			          </p-radio>
			          <div class="validation-message" v-text="validation.getMessage('type')"></div>
							</div>
						</div>
						<br>
						<div class="clearfix"></div>
						<div class="text-center">
									<button class="btn btn-pill btn-success" @click="storeFilestack()">Create</button>
									<button class="btn  btn-pill btn-danger" @click="openForm=false;newFilestack=emptyNewFilestack()">Cancel</button>
							</div>
					</div>
					<!-- edit title of filestack -->
					<div class="box-body" v-if="!isEmpty(focusedEditFilestack)">
						<div class="row">
							<label class="offset-3 col-sm-1 col-xs-12" for="">Title :</label>
							<div class="form-group col-sm-4 com-xs-12">
								<input type="text" class="form-control" v-model="focusedEditFilestack.title">
							</div>
						</div>
						<div class="text-center">
									<button class="btn btn-pill btn-sm btn-success" @click="updateFilestack()">Update</button>
									<button class="btn btn-sm btn-pill btn-danger" @click="focusedEditFilestack={}">Cancel</button>
							</div>
					</div>
					<!-- list of all filestacks -->
					<div class="box-body">
						<div>
							<div class="table-responsive" style="min-height: 15rem;">
								<table class="table table-hover table-outline table-vcenter text-nowrap box-table">
									<thead>
										<tr>
											<th>#</th>
											<th>Title</th>
											<th>Description</th>
											<th>Created At</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(filestack, index) in filestacks.data" :key="index">
											<td>{{ filestack.id }}</td>  	
											<td>{{ filestack.title }}</td>
											<td>{{ filestack.description }}</td>
											<td>{{ filestack.created_at }}</td>
											<td class="text-right">
													<a style="color:#fff" class="btn btn-info btn-sm mr-2"  @click.prevent="editFilestack(filestack,'edit')">
														<i class="fa fa-edit"></i>
														Edit
													</a>
													<a style="color:#fff" class="btn btn-success btn-sm mr-2"  @click.prevent="editFilestack(filestack)">
														<i class="fa fa-cogs"></i>
														Permissions
													</a>
													<a style="color:#fff" class="btn btn-danger btn-sm"  @click.prevent="deleteFilestack(filestack)">
														<i class="fa fa-trash"></i>
														Delete
													</a>
											</td>
										</tr>
									</tbody>
								</table>
								 <pagination :data="filestacks" @pagination-change-page="getResults" align="right">
				        		<span slot="prev-nav">Previous</span>
										<span slot="next-nav">Next</span>
				      	</pagination>
							</div>
						</div>
					</div>
				</div>
    </div>
    <div class="" v-else>
    		<FilestackComponent	
					@unfocus="focusedFilestack={}"
					:users="users"
					:focusedFilestack="focusedFilestack"
    		></FilestackComponent>
    </div>
  </div>
</template>
<script>
import Validation from '../../../utils/Validation.js';
import FilestackComponent from './Filestack.vue';
export default {
	props: [],
	components : {
		FilestackComponent
	},
	data () {
		return {
			validation: new Validation(),
			openForm:false,
			tags:{},
			filteredTags:[],
			type:2,
			filestackUsers:[],
			users:{},
			newFilestack:this.emptyNewFilestack(),
			filestacks: {},
			searchKey:null,
			focusedFilestack:{},
			focusedEditFilestack:{}
		}
	},
	mounted() {
			this.getResults();
			this.get_tags();
			this.get_filestackUsers();
		},
	methods: {
		get_filestackUsers(){
			axios.post('/filestacks/get_users').then(response => {
				console.log(response);
				 this.filestackUsers = response.data;
				// Vue.toasted.show("Updated Successfully", {
				// 		type : 'success',
				// 		icon : 'check',
				// 		duration: 2000
				// });
			}).catch(error=>{
				console.log(error);
			});
		},
		updateIndex(){
			axios.post(`/filestacks/updateIndex`).then(response => {
				Vue.toasted.show("Updated Successfully", {
						type : 'success',
						icon : 'check',
						duration: 2000
				});
			}).catch(error=>{
		       console.log(error.response.data)
			});
		},
		storeFilestack(){
			axios.post(`/filestacks`,this.newFilestack).then(response => {
				Vue.toasted.show("Created Successfully", {
						type : 'success',
						icon : 'check',
						duration: 2000
				});
				location.reload();
			}).catch(error=>{
				 if (error.response.status == 422) {
		          this.validation.setMessages(error.response.data.errors);
		        }else{
		        	console.log(error.response.data)
		        }
			});
		},
		emptyNewFilestack(){
			return {
				type:1,
				title:'',
				userTitle:null
			}
		},
		updateFilestack(){
			axios.patch(`/filestacks/${this.focusedEditFilestack.id}`,this.focusedEditFilestack).then(response => {
				 console.log('udate',response.data);
				Vue.toasted.show("Updated Successfully", {
						type : 'success',
						icon : 'check',
						duration: 2000
				});
			}).catch(error=>{
				console.log(error);
			});
		},
		deleteFilestack(filestack){
			Vue.swal({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  if (result.value) {
			   	axios.delete(`/filestacks/${filestack.id}`).then(response => {
						this.filestacks.data = this.filestacks.data.filter(f => (f.id !=filestack.id));
					}).catch(error=>{
						console.log(error);
					});
			  }
			})
		},
		get_tags(){
			axios.post('/filestack-mgmt/tags')
				.then(response => {
					this.tags = response.data;
					this.filteredTags = this.tags.filter(t => (t.ident !=3));
				});
		},
		isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},
		editFilestack(filestack, action=null){
			if(action==null){
				this.focusedFilestack = filestack;
			}else{
				this.focusedEditFilestack = filestack;
			}

		},
		getResults(page = 1) {
			axios.post('/filestacks/paginate?page=' + page,{keyword:this.searchKey,type:this.type})
				.then(response => {
					this.filestacks = response.data;
				});
		},
	},
	created () {
		
	}
}
</script>
