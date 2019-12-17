<template>
<div>
	<div class="row">
		<div class="col-md-12 col-xs-12 col-sm-12 m-auto">
			<div class="box box-primary">
				<div class="box-body">
					<div class="row mb-5">
						<div class="col-md-10" 
						v-if="(stack.id === logged_user.filestack_id) || (JSON.parse(stack.permissions)).folder.create.indexOf(this.logged_user.id) > -1">
						<button class="btn bg-blue" @click="createFolder">
							<i class="fe fe-folder-plus"></i> New folder
						</button>
						<button  class="btn bg-orange ml-2" v-if="(stack.id === logged_user.filestack_id) || (!isEmpty(location.folder) && !env.fileEditMode && (JSON.parse(stack.permissions)).file.upload.indexOf(this.logged_user.id) > -1)"	
							@click="triggerCreateFile">
						<i class="fe fe-file-plus"></i> Upload File
						</button>

						<!-- <button class="btn btn-purple ml-2" v-if="logged_user.id === 1"
									@click="triggerBulkCreate">Bulk Create
						</button> -->
						</div>
						<div class="col-md-2">
						<button class="btn btn-default pull-right" title="Refresh"
						v-if="!isEmpty(location.folder)" 
						@click="refresh(location.folder.id)">
						<i class="fe fe-refresh-cw"></i>
						</button>
						</div>
					
					</div>
			
			
			</div>
		</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<button type="button" v-scroll-to="{ element: '#folderForm', duration: 2000 }" style="display: none;" ref="scroll_1"></button>
			<!-- Create Folder Form -->
			<div class="row" id="folderForm" v-show="env.folderFormOpened">
				<div class="col-md-6 text-center form-group">
					<input type="text" class="col-lg-4 offset-lg-4 col-xl-4 offset-xl-4 form-control" placeholder="Name your folder..." 
						v-model="focusedFolder.title" v-if="env.folderEditMode" @keyup.enter="updateFolder">
						<input type="text" class="col-lg-4 offset-lg-4 col-xl-4 offset-xl-4 form-control" placeholder="Name your folder..." v-model="folder.title" @keyup.enter="storeFolder" v-else>
				</div>

				<div class="col-md-12 form-group">
					<button class="btn btn-pill btn-warning" @click="updateFolder"
						v-if="env.folderEditMode">Update
					</button>
					<button class="btn btn-pill btn-success" @click="storeFolder"
						v-else>Create
					</button>

					<!-- Cancel -->
					<button class="btn btn-pill btn-outline-dark ml-2" @click="env.folderFormOpened = env.folderEditMode = false">Cancel</button>
				</div>
			</div>
			<button type="button" v-scroll-to="{ element: '#fileForm', duration: 2000 }" 
					style="display: none;" ref="scroll_2"></button>
			<div class="row" id="fileForm" v-show="env.fileFormOpened">
				<div class="col-md-2 form-group">
					 <div class="form-label" >File Type</div>
					<div class="custom-controls-stacked">
					<label class="custom-control custom-radio" v-for="filetype in meta.filetypes">
					<input v-if="! (filetype.name == 'shared' && stack.type == 2)" type="radio" class="custom-control-input" name="example-radios" :value="filetype.id" v-model="upload.type">
					<div  v-if="! (filetype.name == 'shared' && stack.type == 2)"  class="custom-control-label">{{ filetype.name }}</div>
					</label>
					</div>
				</div>
			</div>
		</div>
	</div>

		<div class="row">
			<div class="col-12 mb-2">

			</div>
			<div class="col-md-12">
				<!-- <button type="button" v-scroll-to="{ element: '#folderForm', duration: 2000 }" style="display: none;" ref="scroll_1"></button>
				
				<div id="folderForm" v-show="env.folderFormOpened">
					<div class="form-group">
						<input type="text" class="col-lg-4 offset-lg-4 col-xl-4 offset-xl-4 form-control" placeholder="Name your folder..." 
						v-model="focusedFolder.title" v-if="env.folderEditMode" @keyup.enter="updateFolder">
						<input type="text" class="col-lg-4 offset-lg-4 col-xl-4 offset-xl-4 form-control" placeholder="Name your folder..." v-model="folder.title" @keyup.enter="storeFolder" v-else>
					</div>
					<div class="form-group col-12 text-center">
						
						<button class="btn btn-pill btn-warning" @click="updateFolder"
							v-if="env.folderEditMode">Update
						</button>
						<button class="btn btn-pill btn-success" @click="storeFolder"
							v-else>Create
						</button>
					
						<button class="btn btn-pill btn-outline-dark ml-2" @click="env.folderFormOpened = env.folderEditMode = false">Cancel</button>
					</div>
				</div> -->

				

				<!-- File Upload Form -->
			  <div id="fileForm" class="row pl-5 pr-5 pt-5" v-show="env.fileFormOpened">
			    <div class="form-group col-2">
			      <div class="form-label">File Type</div>
			      <div class="custom-controls-stacked">
			        <label class="custom-control custom-radio" v-for="filetype in meta.filetypes">
			          <input v-if="! (filetype.name == 'shared' && stack.type == 2)" type="radio" class="custom-control-input" name="example-radios" :value="filetype.id" v-model="upload.type">
			          <div  v-if="! (filetype.name == 'shared' && stack.type == 2)"  class="custom-control-label">{{ filetype.name }}</div>
			        </label>
			      </div>
			    </div>

			    <div class="form-group col-md-10">

			      	<div class="form-label">File Name</div>
				      <div class="">
				      	<span class="upload_file_css" v-if="upload.file != ''" v-for="(file,index) in upload.files">{{ file.name }}</span>
				     		<span class="upload_file_css" v-else v-for="(file,index) in upload.files">{{ upload.title[index] }}</span>
				      </div>

				     	<!-- <div class="form-group col-2">
					      <div class="form-label">Upload Progress</div>
					      {{ upload.progress[index] }}
					    </div> -->


			      <input type="file" ref="fileInput" @change="createFile()" class="d-none" multiple>     
			    </div>
			    

		     	<div class="form-group col-md-10 offset-1 mb-5">
			      <textarea v-if="env.fileEditMode || singleFile" v-model="upload.note" class="form-control" placeholder="Enter Note"></textarea>
			    </div>
			    <div class="form-group col-md-10 offset-1 mb-5">
			      <!-- <p-check v-if="upload.type == 11" 
			        v-model="upload.shared_with_users"
			        v-for="user in users"
			        class="col-3" 
			        color="success"
			        :value="user.id"
			        :key="user.id"
			        >{{user.name}}
			      </p-check> -->

						<user-selector 
							:users="users" v-if="upload.type == 11"
							:selectedUsersProp="shared_with_users" 
							@input="agenda[selectedTab] = $event">
						</user-selector>
			    </div>

			    <div class="form-group col-12 text-center">
			    	<button class="btn btn-pill btn-warning" 
			    		:class="[env.uploading ? 'btn-loading' : '']"
			    	  @click="updateFile()"
			    	  v-if="env.fileEditMode">
			    	  <i class="fe fe-upload mr-2"></i>Update
			    	</button>
			    	<button class="btn btn-pill btn-success" 
			    		:class="[env.uploading ? 'btn-loading' : '']" 	  
			    	  @click="storeFile()"
			    	  v-else>
			    	  <i class="fe fe-upload mr-2"></i>Store
			    	</button>

			    	<button class="btn btn-pill btn-outline-dark ml-2" 
			    		v-show="!env.uploading"
			    	  @click="env.fileFormOpened = env.fileEditMode = singleFile = false; upload = emptyFileForm();">Cancel
			    	</button>
			    </div>  
			  </div>

			  <!-- <div id="bulkCreateForm" class="row pl-5 pr-5 pt-5">
			    <div class="form-group col-12">
			      <input type="file" ref="bulkFolder" @change="bulkCreate()" multiple directory webkitdirectory>     
			    </div>
		    	<div class="form-group col-12 text-center">
			    	<button class="btn btn-pill btn-success" @click="bulkFolderStore()">
			    		<i class="fe fe-upload mr-2"></i>Upload
			    	</button>

			    	<button class="btn btn-pill btn-outline-dark ml-2" v-show="!env.uploading"
			    	  @click="env.uploadFolder = false; upload = emptyUploadForm();">Cancel</button>
			    </div>  
				</div> -->

				<nav aria-label="breadcrumb" class="sticky" >
				  <ol class="breadcrumb">
				  	<li class="breadcrumb-item">
				  		<a href="#" class="text-decoration-none" @click.prevent="backToHome">
				  			<i class="fe fe-home fa-lg"></i>
				  			<span>{{stack.title}}</span>
				  		</a>
				  	</li>
				    <li class="breadcrumb-item" :class="{active : (crumb.id === location.folder.id) }"
				    	v-for="(crumb, index) in breadcrumbs">
				    	<span v-if="crumb.id === location.folder.id" v-text="crumb.title"></span>
				    	<span v-else>
				    		<a href="#" @click.prevent="levelUp(crumb.id, index)" v-text="crumb.title"></a>
				    	</span>
				    </li>
				  </ol>
				   	<!-- #refactor  all 4 option in dropdown (can merge single and multi move doc actions)-->
				  <div class="" v-if="isEmpty(focusedMoveDoc)">
					  <button style="min-height: 100%;top: 0;right: 0;position: absolute;" 
									  class="btn btn-info" 
									  v-if="selectedDoc.length > 0"
									  @click="openGroupDocMenu=!openGroupDocMenu">
					  	<i class="fe fe-ellipsis-v"></i>
					  </button>
					  <ul style="list-style: none;
								    position: absolute;
								    top: 42px;
								    right: 0px;
								    background: rgb(255, 255, 255);
								    padding: 12px;
								    border: solid 1px #827f7f;
								    box-shadow: 4px 4px #c5c2c2;"
								    v-if="openGroupDocMenu && selectedDoc.length > 0">
					  	<!-- <li>
					  		<a :href="`/docs/files/download/${selectedDoc.toString()}`" class="d-block p-2" style="color: #585757">
								 	<i class="fa fa-download mr-2"></i>Download ({{selectedDoc.length}})
								</a>
					  	</li> -->
					  	<li>
					  		<a href="#" @click.prevent="deleteMultiDoc();openGroupDocMenu=!openGroupDocMenu" class="d-block p-2" style="color: #585757">
								 	<i class="fe fe-trash mr-2"></i>Delete ({{selectedDoc.length}})
								</a>
					  	</li>
					  	<li>
					  		<a href="#" @click.prevent="cutMultiDoc()" class="d-block p-2" style="color: #585757">
								 	<i class="fe fe-scissors mr-2"></i>Cut
								</a>
					  	</li>
					  	<!-- <li>
					  		<a href="#" @click.prevent="bulkAction='copy';openGroupDocMenu=!openGroupDocMenu" class="d-block p-2" style="color: #585757">
								 	<i class="fe fe-files-o mr-2"></i>Copy
								</a>
					  	</li> -->
					  	<li>
					  		<a href="#" @click.prevent="selectAllDoc();openGroupDocMenu=!openGroupDocMenu" class="d-block p-2" style="color: #585757">
								 	<i class="fe fe-check-square-o mr-2"></i>Select All
								</a>
					  	</li>
					  	<li>
					  		<a href="#" @click.prevent="selectedDoc=[];bulkAction='';openGroupDocMenu=!openGroupDocMenu" class="d-block p-2" style="color: #585757">
								 	<i class="fa fa-times mr-2"></i>Unselect All
								</a>
					  	</li>
					  </ul>
					</div>
				</nav>
				<!-- Loop for folders -->
				<div @contextmenu.prevent="$refs.moveFolderMenu.open">
				<span v-if="folders.length > 0">
					<div class="row row-cards">
						<div class="col-6 col-sm-4 col-lg-2" v-for="folder in folders" :key="folder.id">
			        <div class="panel" @click="levelDown(folder.id)"	style="cursor: pointer" 
			        	@contextmenu.prevent="$refs.folderMenu.open($event, folder)">
			          <div class="panel-body p-3 text-center">
			            <div class="text-right text-green">
			              -
			            </div>
			            <div class="h1 m-0"><i class="fe fe-folder"></i></div>
			            <div class="mt-1 mb-2" v-text="folder.title"></div>
			          </div>
			        </div>
			      </div>
					</div>
				</span>
				<span v-else>
					<p class="text-center">No folders yet</p>
				</span>
			</div>
				<hr>
				<!-- Loop for documents -->
				<div @contextmenu.prevent="$refs.moveDocMenu.open">
					<span v-if="docs.length > 0">
						<div class="row row-cards" v-if="!isEmpty(location.folder)">
				      <div class="col-6 col-sm-4 col-lg-2" v-for="doc in docs" :key="doc.id">
				      	<a :href="`/docs/media/${doc.file[0].id}`" class="text-decoration-none d-none" :ref="('link-' + doc.file[0].id)" >link</a>
				        <div class="panel" v-if="checkPermission(doc)" @dblclick="downloadDoc(doc)" :class="`${doc.file[0].id}`" @contextmenu.prevent="$refs.docMenu.open($event, doc)" :style="[doc.id==meta.active_file.file_id ? {'border':'solid 3px #3490dcc7'} : '']">
				        <!-- <div class="card" v-if="checkPermission(doc)" @dblclick="$refs[`${doc.file[0].id}`].click()" :class="`${doc.file[0].id}`" @contextmenu.prevent="$refs.docMenu.open($event, doc)"> -->
				          <div class="panel-body p-3 text-center" :class="[selectedDoc.indexOf(doc.id) > -1 ? 'selected_doc' : '']">
			          		<div class="text-right">
				            	<span style="cursor: pointer;" title="Edit"
				            		v-if="doc.owner_id == logged_user.id || logged_user.id == 2" @click="editFile(doc.id)">
				              	<i class="fe fe-edit"></i>
				              </span>
				            </div>
				            <span v-if="checkDownloadAbility()">
					            <a href="#" @click.prevent="selectDoc(doc.id)"  class="text-decoration-none d-block">
						            <div class="h1 m-0"><i :class="getFileIcon(doc.file[0].mime_type)"></i></div>
						            <div class="mt-1 mb-1">
													{{ doc.title }} <br>
						            	<span style="font-size: 0.8em;" class="text-muted" v-text="getFileSize(doc.file[0].size)"></span>
						            </div>
					            </a>
				            </span>
				            <span v-else>
				            	<div class="h1 m-0"><i class="fe fe-file"></i></div>
					            <div class="mt-1 mb-2" v-text="doc.title"></div>
				            </span>
				          </div>
				        </div>
				        <div class="panel" v-else>
				        	<div class="panel-body p-3 text-center">
				            <div class="text-right">
				            	-
				            </div>
				            <a href="#" class="text-decoration-none">
					            <div class="h1 m-0"><i class="fe fe-shield"></i></div>
					            <div class="text-muted mb-4">Protected</div>
				            </a>
				          </div>
				        </div>      
				      </div>
						</div>
					</span>
					<span v-else>
						<p class="text-center" style="min-height: 200px">No docs yet</p>
					</span>
				</div>
			
				<vue-context ref="moveDocMenu">
					<template slot-scope="child">
				    <li>
				    	<!-- Show paste if focusedMoveDoc is not empty -->
			        <a v-if="!isEmpty(focusedMoveDoc) && !isEmpty(location.folder) && bulkAction==''" href="#" @click.prevent="pasteDoc(focusedMoveDoc,moveAction)">Paste</a>
				    </li>
				     <li>
				    	<!-- Show paste if focusedMoveDoc is not empty -->
			        <a v-if="!isEmpty(focusedMoveDoc) && !isEmpty(location.folder) && bulkAction==''" href="#" @click.prevent="focusedMoveDoc = {};moveAction = null">Cancel</a>
				    </li>
				    <li>
			        <a v-if="bulkAction!=''" href="#" @click.prevent="pasteAllDoc()">Paste All files here</a>
				    </li>
				    <li>
			        <a v-if="bulkAction!=''" href="#" @click.prevent="selectedDoc=[]; bulkAction=''">Cancel</a>
				    </li>
			    </template>
				</vue-context>
				<vue-context ref="moveFolderMenu">
					<template slot-scope="child">
				    <li>
				    	<!-- Show paste if focusedMoveDoc is not empty -->
			        <a v-if="!isEmpty(focusedMoveFolder)" href="#" @click.prevent="pasteFolder(focusedMoveFolder)">Move here</a>
				    </li>
			    </template>
				</vue-context>
				<vue-context ref="docMenu"> 
					<template slot-scope="child" v-if="selectedDoc==''">
				    <li>
				    	<!-- #refactor -->
			        <!-- <a href="#" @click.prevent="downloadDoc(child.data)"><i class="fa fa-download mr-2"></i>Download</a> -->
			        <a href="#" @click.prevent="deleteDoc(child.data)"><i class="fe fe-trash mr-2"></i>Delete</a>
			        <a href="#" @click.prevent="moveDoc(child.data,'cut')"><i class="fe fe-scissors mr-2"></i>Cut</a>
			        <a href="#" @click.prevent="moveDoc(child.data,'copy')"><i class="fe fe-clipboard mr-2"></i>Copy</a>
			        <a href="#" @click.prevent="infoDoc(child.data)"><i class="fe fe-question-circle mr-2"></i>Get Info</a>
				    </li>
			    </template>
				</vue-context>
				<vue-context ref="folderMenu">
					<template slot-scope="child">
					    <li>
					        <a href="#" @click.prevent="editFolder(child.data)">Rename</a>
					        <a href="#" @click.prevent="deleteFolder(child.data)">Delete</a>
					        <a href="#" @click.prevent="infoFolder(child.data)">Get Info</a>
					        <a href="#" @click.prevent="cutFolder(child.data)">Move Folder</a>
					    </li>
					    <!-- <li>
				        <a href="#" @click.prevent="onClick($event.target.innerText)">Option 2</a>
					    </li> -->
			    	</template>
				</vue-context>

			</div>
		</div>
</div>
</template>

<script>
	import moment from 'moment'
	import {VueContext} from 'vue-context';
	const VueScrollTo = require('vue-scrollto');
	Vue.use(VueScrollTo)
	export default {
		props: ['docs_users', 'stack', 'parent_folders', 'logged_user', 'meta'],
    components: {
        VueContext
    },
		data() {
			return {
				bulkAction:'',
				openGroupDocMenu:false,
				selectedDoc: [], //Array to select doc to download
				singleFile: false,
				users: [], // all users of laxyo docs
				breadcrumbs: [],
				location: { // current location of user in folder tree
					stack_id: this.stack.id,
					folder: null // folder object where user is current present
				},
				folders: [], // list of folders currently loaded
				docs:[], // list of files currently loaded
				env: {
					folderFormOpened: false, 
					folderEditMode: false,
					fileEditMode: false,
					fileFormOpened: false,
					// uploadFolder: false,
					uploading: false // processing a file upload
				},
				folder: this.emptyFolderForm(),
				upload: this.emptyFileForm(), // file upload form
				focusedFolder: {}, // folder being manipulated
				focusedDoc: {}, // file being manipulated
				focusedMoveDoc: {}, // file being manipulated
				focusedMoveFolder: {}, // folder being manipulated
				moveAction:null,
			}
		},
		created() {
			this.folders = this.parent_folders;
		},
		mounted() {
			this.users = this.docs_users;
			this.filetypes = this.meta.filetypes;
			if(this.meta.active_file.file_id!=''){
				this.location.folder = this.meta.active_file.folder;
				this.breadcrumbs = this.meta.active_file.breadcrumbs;
				this.folders = this.meta.active_file.subfolders;
				this.docs = this.meta.active_file.folder.documents;
			}
		},
		methods: {
			cutMultiDoc(){
				let logged_user = this.logged_user;
				let performAction = false;
				let selectedDocs = this.selectedDoc;
				let docs = this.docs;
				let can_move = JSON.parse(this.stack.permissions)['file']['move'];
				if(can_move.indexOf(this.logged_user.id) > -1 ){
					performAction = true;
				}else{
					performAction = true;
					selectedDocs.forEach(function(e1){
						docs.forEach(function(e2){
							if(e1 == e2.id &&  e2.owner_id != logged_user.id){
								performAction = false;
							}
						});
					});
				}
				if(performAction){
					this.bulkAction='cut';
					this.openGroupDocMenu = !this.openGroupDocMenu;
				}else{
					alert('You do not have permission to perform this action!');
				}
			},
			pasteAllDoc(){
				let action = this.bulkAction;
				let data = {};
				 data.ids = this.selectedDoc;
				 console.log('ids',data.ids);
				 data.folder_id = this.location.folder.id;
				if(action=='cut'){
					axios.post(`/docs/documents/multi_cut_paste`,data).then(response => {
						console.log(response.data);
						if(response.status==201){
							let doc_arr = this.docs;
							response.data.forEach(function(e){
								doc_arr.unshift(e); 
							});
							this.docs = doc_arr;
							this.selectedDoc = [];
							this.bulkAction = '';
						}else{
							Vue.swal(
							  'Unable to paste',
							  'Can not paste in same folder!!',
							  'error'
							)
						}
		  			 // 	this.docs.unshift(response.data);
		  				// this.focusedMoveDoc = {}; // file being manipulated
						 	// this.moveAction = null;
		  			}).catch(error => console.log(error.response.data));
				}else{
					alert('Something went wrong!');
				}
			},
			downloadDoc(data){
				let downloaders = JSON.parse(this.stack.permissions)['file']['download'];
				if(downloaders.indexOf(this.logged_user.id) > -1 || data.owner_id==this.logged_user.id)
				{
					this.$refs[`link-${data.file[0].id}`][0].click();
				} else {
					alert('You do not have permission to download files.');
				}
			},
			selectAllDoc(){
				let canSelect = true;
				let logged_id =  this.logged_user.id;
				let arr = [];
				this.docs.forEach(function(e){
					if(e.owner_id != logged_id){
						canSelect = false;
					}
					arr.push(e.id)
				});
				// if(canSelect || logged_id==2){
					this.selectedDoc = arr;
				// }else{
				// 	alert('Only uploader can select files. All files are not uploded by you.');
				// }
				
			},
			selectDoc(id){
				let logged_id = this.logged_user.id;
				let canSelect = true;
				let docs = this.docs;
				docs.forEach(function(e){
					if(e.id == id && (e.owner_id != logged_id || logged_id == 2 )){
						canSelect = false;
					}
				});
				// if(canSelect){
					if(this.selectedDoc.indexOf(id) > -1){
						this.selectedDoc = this.selectedDoc.filter(u => u != id);
					}else{
						this.selectedDoc.push(id);
					}
				// }else{
				// 	Vue.toasted.error(`Only Creator can select file.`, {
		  //     	duration: 2000
		  //     });
				// }
			},
			infoFolder(folder){
				let date = moment(folder.created_at,"YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
				console.log(date);
				Vue.swal({
					  html:
					  'Folder Name : <b>' + folder.title+'</b><br>'+
					  'No of Files in this Folder : <b>' +folder.documents.length +'</b><br>'+
					  'Created At: <b>' + date+'</b>',
					  showCloseButton: true,
					  confirmButtonText:
					    '<i class="fa fa-thumbs-up"></i>',
					})
			},
			getSizeofFile(bytes) {
		    if(bytes < 1024) return bytes + " Bytes";
		    else if(bytes < 1048576) return(bytes / 1024).toFixed(3) + " KB";
		    else if(bytes < 1073741824) return(bytes / 1048576).toFixed(3) + " MB";
		    else return(bytes / 1073741824).toFixed(3) + " GB";
			},
			infoDoc(doc){
				let date = moment(doc.created_at,"YYYY-MM-DD HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
				console.log(date);
				let filesize = this.getSizeofFile(doc.file[0].size);
				Vue.swal({
					  html:
					  'File Name : <b>' + doc.title+'</b><br>'+
					  'File Uploader : <b>' +doc.owner.name +'</b><br>'+
					  'Size : <b>' +filesize +'</b><br>'+
					  'Created At: <b>' + date+'</b>',
					  showCloseButton: true,
					  confirmButtonText:
					    '<i class="fa fa-thumbs-up"></i>',
					})
			},
			scroll_1($event) {
        const elem = this.$refs.scroll_1;
        elem.click();
      },
			scroll_2($event) {
        const elem = this.$refs.scroll_2;
        elem.click();
      },
	  	isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},
			emptyFolderForm() { // folder form
				return {
					title: ''
				}
			},
			emptyFileForm() {
			  return {
			    title: [],
			    files: [],
			    progress: [],
			    shared_with_users: [],
			    type: 10,
			    note:''
			  }
			},
			getFileSize(byteSize) {
				return Math.round(byteSize/1024)+ ' KB'
			},
			getFileIcon(mimeType) {
				console.log(mimeType)
				let images = ['image/jpeg','image/jpg','image/png','image/gif'];
				let audio = ['audio/mpeg'];
				let video = ['video/mpeg', 'video/mp4', 'video/quicktime'];
				let application = ['application/vnd.ms-excel', 'application/msword', 'application/pdf'];
				if(images.indexOf(mimeType) > -1)
					return 'fa fa-file-photo-o';
				else if(audio.indexOf(mimeType) > -1)
					return 'fa fa-file-audio-o';
				else if(video.indexOf(mimeType) > -1)
					return 'fa fa-file-movie-o';
				else if(mimeType == 'application/vnd.ms-excel')
					return 'fa fa-file-excel-o';
				else if(mimeType == 'application/msword')
					return 'fa fa-file-word-o';
				else if(mimeType == 'application/pdf')
					return 'fa fa-file-pdf-o';
				else
					return 'fa fa-file';
			},
			createFolder() {
				
				this.env.folderFormOpened = true;

			},
			storeFolder() {
				let postData = this.folder;
				postData.stack_id = this.stack.id;
				postData.parent = this.location.folder;
				axios.post('/docs/folders', {folder: postData}).then(response => {
					this.env.folderFormOpened = false;
					this.folder = this.emptyFolderForm();
					console.log(response.data);
					this.folders.push(response.data);

				}).catch(error => console(error.response.data));
			},

			editFolder(data) {
				let can_edit = JSON.parse(this.stack.permissions)['folder']['edit'];
				if( (this.stack.id === this.logged_user.filestack_id) || data.owner_id==this.logged_user.id || can_edit.indexOf(this.logged_user.id) > -1 ){
					this.focusedFolder = data;
					this.env.folderFormOpened = this.env.folderEditMode = true;
					this.scroll_1(); // scroll to folder form 
				}else{
					alert("You don't have permission to edit the name.");
				}
			},

			deleteFolder(data) {
				let can_delete_folders = JSON.parse(this.stack.permissions)['folder']['delete'];
				if(  (this.stack.id === this.logged_user.filestack_id)  || can_delete_folders.indexOf(this.logged_user.id) > -1 || data.owner_id==this.logged_user.id)
				{
					Vue.swal({
					  title: 'Are you sure?',
					  text: "You won't be able to revert this!",
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
						if (result.value) {
							axios.delete(`/docs/folders/${data.id}`).then(response => {
								this.folders = this.folders.filter(f => (f.id !== data.id));
							}).catch(error => {
								if(error.response.status == 422){
									Vue.swal("This folder has subfolders or files in it. So it can't be deleted!");
								}
								console.log(error.response.data)
							});
						}
				}).catch(error => console.log(error));
				} else {
					alert('You do not have permission to delete folders');
				}
			},
			updateFolder() {
				let id = this.focusedFolder.id;
				axios.patch(`/docs/folders/${id}`, {title: this.focusedFolder.title}).then(response => {
					this.env.folderEditMode = this.env.folderFormOpened = false;
					console.log(response.data);
					this.folder = this.emptyFolderForm();
					this.folders.forEach((v, k) => {
						if (v.id == id)
						Vue.set(this.folders, k, response.data);
						Vue.swal('Folder Updated!');
					});
				}).catch(error => console(error.response.data));
			},

			backToHome() {
				axios.get(`/docs/back_to_home/${this.stack.id}`).then(response => {
					console.log(response.data);
					this.location.folder = {};
					this.breadcrumbs = [];
					this.docs = [];
					this.folders = response.data.parent_folders;
				}).catch(error => console(error.response.data));
			},

			levelDown(id) {
				console.log("level down",id);
				if(id!=this.focusedMoveFolder.id){
					axios.get(`/docs/folders/${id}`).then(response => {
						console.log(response.data);
						this.location.folder = response.data.folder;
						this.breadcrumbs.push(response.data.folder);
						this.folders = response.data.subfolders;
						this.docs = response.data.folder.documents;
					}).catch(error => console(error.response.data));
				}
			},

			refresh(id) {
				axios.get(`/docs/folders/${id}`).then(response => {
					console.log(response.data);
					this.folders = response.data.subfolders;
					this.docs = response.data.folder.documents;
				}).catch(error => console(error.response.data));
			},

			levelUp(id,level) {
				axios.get(`/docs/folders/${id}`).then(response => {
					console.log(response.data);
					this.location.folder = response.data.folder;
						// this.breadcrumbs.push(response.data.folder);
						let newbreadcrumbs = [];
						this.breadcrumbs.forEach(function(v, k){
							if(k<=level){
								newbreadcrumbs.push(v);
							}
						});
						this.breadcrumbs = newbreadcrumbs
					this.folders = response.data.subfolders;
					this.docs = response.data.folder.documents;
				}).catch(error => console(error.response.data));
			},

	    checkPermission(doc) {
    		let users = [];
    		doc.shared_with_users.forEach(function(e){
    		  users.push(e.id);
    		});
    		if((doc.type == 10) || // if public
    		  (doc.type == 11 && (users.indexOf(this.logged_user.id) > -1)) || // if shared with user
    		  (doc.owner_id == this.logged_user.id)) { // if doc owner
    		  return true;
    		}
    		return false;
	    },

	    checkDownloadAbility() {
	    	let stack_type = this.stack.type;
	    	let downloaders = [];
	    	// let downloaders = (JSON.parse(this.stack.permissions)).downloaders;
	    	if(stack_type === 12) {
	    		if(downloaders.indexOf(this.logged_user.id) > -1) {
	    			return true;
	    		} else {
	    			return false;
	    		}
	    	}
	    	return true;
	    },

	    search() {
	      if(this.searchKey == ''){
	         Vue.swal({
	          type: 'warning',
	          title: '',
	          text: 'Please Enter a keyword!',    
	        })
	      }else{
	         axios.post(`${BASE_URL}/search`,{
	          'keyword' :this.searchKey,
	          'stack_id' :this.filestack.id
	         }).then(response => {
	            this.documents = response.data;
	          }).catch(error => {
	            console.log(error.response.data)
	          });
	      }
	    },

	    // Methods for file upload
	    triggerCreateFile() {
      	this.$refs.fileInput.click();
      	this.scroll_2(); // scroll to file form 
    	},
    	// triggerBulkCreate() {
     //  	this.$refs.bulkFolder.click();
    	// },
    	createFile() {
    		if(this.$refs.fileInput.files.length>20){
    			Vue.toasted.error(`More than 20 files can not be uploaded (Max Limit,20)`, {
									action : {
					        text : 'Okay',
					        onClick : (e, toastObject) => {
				            toastObject.goAway(0);
					        }
			  		    },
							});
    		}else{
    			this.upload.files = this.$refs.fileInput.files;
	    	  let progress = [];
	    	  if(this.upload.files.length==1){
	    	  	this.singleFile=true;
	    	  }else{
	    	  	this.singleFile=false;
	    	  }
			    for (var i = 0; i < this.upload.files.length; i++) {
			      progress.push(0);
			    }
	    	 	this.upload.progress = progress;
	    	  this.env.fileFormOpened = true;
    		}
    	},
    	// bulkCreate() {
    	//   this.upload.file = this.$refs.bulkFolder.files;
    	// },
    	storeFile(){
  	  	this.env.uploading = true;
  	    let formData = new FormData();
  	    let scope = this;
  	     for( var i = 0; i < this.upload.files.length; i++ ){		
          let file = this.upload.files[i];
          formData.append('file', file);
          formData.append('title', file.name);
          formData.append('type', this.upload.type);
	  	    formData.append('note', this.upload.note);
	  	    formData.append('users', this.upload.shared_with_users);
	  	    formData.append('stack_id', this.stack.id);
	  	    formData.append('folder_id', this.location.folder.id);
	   
         axios.post('/docs/documents',
  	      formData,
  	      {
  	        headers: {
  	          'Content-Type': 'multipart/form-data'
  	        },
  	        // onUploadProgress: function( progressEvent ) {
  	        // }.bind(this)
  	      }
  	    ).then(response => {
  	    	Vue.toasted.success(`${file.name} has uploaded successfully.`, {
		      	duration: 2000
		      });
  	      // this.upload.progress[i] = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ));
  	      this.docs.unshift(response.data); 
  	    }).catch(error => {
  	    	Vue.toasted.error(`${file.name} uploading failed.`, {
									action : {
					        text : 'Okay',
					        onClick : (e, toastObject) => {
				            toastObject.goAway(0);
					        }
			  		    },
							});
  	      // console.log(error.response.data);
  	    });
  	    this.singleFile = false;
		 }
  	    // formData.append('file', this.upload.files);
  	    // formData.append('title', this.upload.files.name);
  	    this.env.uploading = false;
	      this.upload = this.emptyFileForm();
	      this.env.fileFormOpened = false;
    	},
    	editFile(id){
    	  axios.get(`/docs/documents/${id}`).then(response => {
    	  	this.scroll_2(); // scroll to file form 
    	    this.focusedDoc = response.data;
    	    this.upload.note = this.focusedDoc.note; 
    	    this.upload.type = this.focusedDoc.type;
    	    // this.upload.files = this.focusedDoc.type;
    	    this.upload.title = this.focusedDoc.title;
    	    this.env.fileEditMode = this.env.fileFormOpened = true;
    	    let users = [];
    	    this.focusedDoc.shared_with_users.forEach(function(e){
    	      users.push(e.id);
    	    });
    	    this.upload.shared_with_users = users;
    	  }).catch(error => console.log(error.response.data));
    	},
    	updateFile() {
	     	let formData = {
		      'type': this.upload.type,
		      'note': this.upload.note,
		      'users': this.upload.shared_with_users
	     	};
  	    console.log(formData);
	     	window.axios.patch(`/docs/documents/${this.focusedDoc.id}`, formData).then(response => {
	     	console.log(response.data);
		     	let id = this.focusedDoc.id;
		     	this.docs.forEach((v, k) => {
	         	if (v.id == id)
	         	Vue.set(this.docs,k,response.data);
	      	});
  	     	Vue.swal('File Updated!');
  	      this.upload = this.emptyFileForm();
  	      this.env.fileEditMode = this.env.fileFormOpened = false;
  	    }).catch(error => {
  	      console.log(error.response.data);
  	    });
    	},

    	/* --------------------- Bulk folder upload function
    	bulkFolderStore() {
    		let paths = [];
    		let folder = this.upload.file;
		  	for (let i = 0; i < folder.length; i++) {
			    let file = folder[i]
			    paths.push(file.webkitRelativePath);
			  }
			  console.log('paths', paths);
			  let formData = new FormData();
  	    formData.append('file', folder);

  	  	this.env.uploading = true;

  	    axios.post('/docs/documents/upload_folder',
  	    	formData,
  	      {
  	        headers: {
  	          'Content-Type': 'multipart/form-data'
  	        }
  	      }
  	    ).then(response => {
  	    	this.env.uploading = false;
  	      console.log(response.data);
  	      Vue.swal('File Uploaded!');
  	      this.upload = this.emptyFileForm();
  	      this.env.uploadFolder = false;
  	    }).catch(error => {
  	      console.log(error.response.data);
  	    });
    	}
    	---------------------------------------- */
    	pasteDoc(data,action){
    		console.log('pasteDoc',data);
    		let folder_id = data.folder_id; //current folder_id before updating
    		data.action = action;
    		data.folder_id = this.location.folder.id;
    		if(data.folder_id != folder_id){
    				axios.post(`/docs/documents/move_file`,data).then(response => {
		  				this.docs.unshift(response.data);
		  				this.focusedMoveDoc = {}; // file being manipulated
							this.moveAction = null;
		  			}).catch(error => console.log(error.response.data));
    		}else{
    			Vue.swal(
					  'Unable to paste',
					  'Can not paste in same folder!!',
					  'error'
					)
    		}
  			
    	},
    	moveDoc(data,action){
				let can_cut_docs = JSON.parse(this.stack.permissions)['file']['move'];
				let can_copy_docs = JSON.parse(this.stack.permissions)['file']['copy'];
    		// if(can_copy_docs.indexOf(this.logged_user.id) > -1) {
    		let cut = (action=='cut' && (data.owner_id == this.logged_user.id || can_cut_docs.indexOf(this.logged_user.id) > -1));
    		let copy =  (action=='copy' && (data.owner_id == this.logged_user.id || can_copy_docs.indexOf(this.logged_user.id) > -1));
    		if(cut || copy){
    			this.focusedMoveDoc = data; 
					this.moveAction= action;
    		}else{
    			alert("You do not have permission to "+action+" this file.");
    		}
    	},
    	deleteMultiDoc(){
    		let logged_user = this.logged_user;
    		let performAction = false;
    		let selectedDocs = this.selectedDoc;
				let docs = this.docs;
    		let can_delete_docs = JSON.parse(this.stack.permissions)['file']['delete'];

    		if(can_delete_docs.indexOf(this.logged_user.id) > -1 ){
					performAction = true;
				}else{
					performAction = true;
					selectedDocs.forEach(function(e1){
						docs.forEach(function(e2){
							if(e1 == e2.id &&  e2.owner_id != logged_user.id){
								performAction = false;
							}
						});
					});
				}

    		if(performAction) {
    			Vue.swal({
					  title: 'Are you sure?',
					  text: "You won't be able to revert this!",
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
					  if (result.value) {
					    axios.post(`/docs/documents/multi_delete`,{ids:this.selectedDoc}).then(response => {
					    	let docs = this.docs;
					    	this.selectedDoc.forEach(function(e){
					    		console.log('e',e);
					    		docs = docs.filter(f => (f.id != e));
					    	});
					    	this.docs = docs;
						    Vue.toasted.success('Deleted Successfully.', {
					      	duration: 2000
					      });
					      this.selectedDoc = [];
					    }).catch(error => console.log(error));
					  }
					})
    		} else {alert('You do not have permission to delete all files')}
    	},
    	deleteDoc(data) {
    		let can_delete_docs = JSON.parse(this.stack.permissions)['file']['delete'];
    			if(can_delete_docs.indexOf(this.logged_user.id) > -1 || data.owner_id == this.logged_user.id) {
	    			Vue.swal({
						  title: 'Are you sure?',
						  text: "You won't be able to revert this!",
						  showCancelButton: true,
						  confirmButtonColor: '#3085d6',
						  cancelButtonColor: '#d33',
						  confirmButtonText: 'Yes, delete it!'
						}).then((result) => {
						  if (result.value) {
						    axios.delete(`/docs/documents/${data.id}`).then(response => {
							    this.docs = this.docs.filter(f => (f.id !== data.id));
							    Vue.toasted.success('Deleted Successfully!!', {
						      	duration: 2000
						      });
						      $('.v-context').hide();
						    }).catch(error => console.log(error.response.data));
						  }
						})
	    		} else {alert('You do not have permission to delete files')}
    	},
    	cutFolder(data){
    		 let can_move = JSON.parse(this.stack.permissions)['folder']['move'];
    		if(this.stack.id === this.logged_user.filestack_id  || data.owner_id==this.logged_user.id || can_move.indexOf(this.logged_user.id) > -1 ){
    				this.focusedMoveFolder = data;
    			}else{
    				alert("You don't have permission to move the Folder.");
    			}
    	},
    	pasteFolder(data){
	  		data.updateParent = this.isEmpty(this.location.folder) ? null : this.location.folder.id;
	  		if(data.updateParent == data.parent){
	  			Vue.toasted.error('Can not move in same folder.', {
			      	duration: 2000
			      });
	  		}else{
	  			axios.post(`/docs/move_folder`,data).then(response => {
		  			Vue.toasted.success('Moved Succesfully.', {
			      	duration: 2000
			      });
						this.folders.push(response.data);
						this.focusedMoveFolder = {};
		  		}).catch(error => console.log(error.response.data));
	  		}
    	}
		}
	}
</script>
<style>	

</style>