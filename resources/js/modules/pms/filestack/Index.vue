<template>
  <div class="filestack__wrapper">
    <div class="row">
      <div class="form-group col-8">
        <button class="btn btn-pill btn-primary" @click="trigger">
          <i class="fe fe-plus"></i> Add New
        </button>
      </div>
      <div class="col-4">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search a doc..." v-model="searchKey" @keyup.enter="search">
          <span class="input-group-btn ml-2">
            <button class="btn btn-sm btn-default" @click="search">
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

    <div class="card">
      <div class="row pl-5 pr-5 pt-5" v-show="env.uploadFile">
        <div class="form-group col-4">
          <div class="form-label">File Type</div>
          <div class="custom-controls-stacked">
            <label class="custom-control custom-radio" v-for="filetype in meta.filetypes">
              <input type="radio" class="custom-control-input" name="example-radios" :value="filetype.id" v-model="upload.type">
              <div class="custom-control-label">{{filetype.name}}</div>
            </label>
          </div>
        </div>
        <div class="form-group col-6">
          <div class="form-label">File Name</div>
          {{ upload.file.name }}
          <input type="file" ref="fileInput" @change="handleFileUpload()" class="d-none">     
        </div>
        <div class="form-group col-2">
          <div class="form-label">Upload Progress</div>
          {{ upload.progress }} %
        </div>
         <div class="form-group col-10 offset-1 mb-5">
          <textarea v-model="upload.note" class="form-control" placeholder="Enter Note"></textarea>
        </div>
        <div class="form-group col-10 offset-1 mb-5">
          <!-- <p-check  v-if="upload.type == 11" 
            v-model="upload.shared_with_users"
            v-for="user in toShareWith"
            class="col-3" 
            color="success"
            :value="user.id"
            :key="user.id"
            >
              {{user.name}}
          </p-check> -->


          <user-selector 
            :users="toShareWith" v-if="upload.type == 11"
            :selectedUsersProp="upload.shared_with_users" 
            @input="agenda[selectedTab] = $event">
          </user-selector>
        </div>
        <div class="form-group col-12 text-center">
          <button class="btn btn-pill btn-success" :class="[env.uploading ? 'btn-loading' : '']"
            @click.prevent="uploadFile()"><i class="fe fe-upload mr-2"></i>Upload</button>

          <button class="btn btn-pill btn-outline-dark ml-2" v-show="!env.uploading"
            @click="env.uploadFile = false; upload = emptyUploadForm();">Cancel</button>
        </div>  
      </div>
    </div>              

    <div class="table-responsive">
      <table class="table table-hover">
        <thead> 
          <tr>
            <th>Title</th>
            <th class="text-center">Owner</th>
            <th class="text-center">Type</th>
            <th class="text-center">Size</th>
            <th class="text-center">Uploaded At</th>
            <th class="text-center">Note</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="doc in documents.data" :key="doc.id"  :class="[doc.id==meta.active_file ? 'active_file': '']">
            <!-- If document is public -->
            <td v-if="checkPermission(doc)">
              <a :href="`/pms/media/${doc.file[0].id}`">{{ doc.title }}</a>
            </td>
            <td v-else>
              {{ '*'.repeat(doc.file[0].name.length) }}
            </td>
            <td><div class="small text-muted text-center">{{ doc.owner.name }}</div></td>
            <td><div class="small text-muted text-center">{{ docType(doc.type) }}</div></td>
            <td>
              <div class="small text-muted text-center">
                {{ Math.round(doc.file[0].size / 1024) }} KB
              </div>
            </td>
            <td>
              <div class="small text-muted text-center">
                {{ doc.created_at | moment("DD/MM/YYYY  hh:mm A") }}
              </div>
            </td>
            <td><div class="small text-muted text-center">{{ doc.note }}</div></td>
            <td class="text-center">
              <!-- <a href=""><i class="dropdown-icon fe fe-download"></i></a> -->
              <a v-if="doc.owner_id == logged_user.id" @click="editFile(doc.id)" title="Edit"><i class="fe fe-edit text-primary"></i></a>
            </td>
          </tr>
        </tbody>
      </table> 
      <pagination :data="documents" @pagination-change-page="paginatedDocs" align="center">
        <span slot="prev-nav">Previous</span>
        <span slot="next-nav">Next</span>
      </pagination>
    </div>
  </div>
</template>
<style>
.active_file a, .active_file div{
	color:#fff !important;
}
.active_file {
	background:#6cb2eb !important;
}
</style>
<script>
const BASE_URL = '/pms/documents';
export default {
  props: ['team', 'filestack', 'logged_user', 'meta'],
  data() {
    return {
    	toShareWith : this.removeObject(this.logged_user.id, this.team.users_in_team),
      env: {
        uploadFile: false,
        uploading: false
      },
      searchKey:'',
      filetypes: [],
      documents: {},
      users: [],
      upload: this.emptyUploadForm(),
      focusedDoc: {},
      editMode: false
    };
  },
  mounted(){
    this.users = this.team.users_in_team;
    this.paginatedDocs();
    this.filetypes = this.meta.filetypes;
  },
  methods: {
  	removeObject(id,array){
  		return array.filter(u => (u.id !== id));
  	},
    checkPermission(doc) {
      let users = [];
      doc.shared_with_users.forEach(function(e){
        users.push(e.id);
      });

      console.log('users', users)
      console.log('indexOf', users.indexOf(this.logged_user.id));

      if((doc.type == 10) || // if public
        (doc.type == 11 && (users.indexOf(this.logged_user.id) > -1)) || // if shared with user
        (doc.owner_id == this.logged_user.id)) { // if doc owner
        return true;
      }
      return false;
    },
    paginatedDocs(page=1){
      axios.post(`${BASE_URL}/search?page=` + page,{
        'keyword':this.searchKey,
        'stack_id':this.filestack.id
      }).then(response => {
        this.documents = response.data;
      }).catch(error => console.log(error.response.data));
    },
    refresh(page=1){
      axios.post(`${BASE_URL}/search?page=` + page, {
        'stack_id':this.filestack.id
      }).then(response => {
          this.documents = response.data;
      }).catch(error => console.log(error.response.data));
    },
    search() {
      if(this.searchKey==''){
         Vue.swal({
          type: 'warning',
          title: '',
          text: 'Please Enter keywords for search.',    
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
    emptyUploadForm() {
      return {
        title: '',
        file: '',
        progress: 0,
        shared_with_users: [],
        type: 10,
        note:''
      }
    },
    // getFileSize(bytes) {
    //   let size = '';
    //   if(bytes < 1024)
    //     size = bytes + ' Bytes';
    //   else if (1048576 > bytes >= 1024)
    //     size = Math.round(bytes / 1024) + ' KB';
    //   else
    //     size = Math.round(bytes / (1024*1024)) + ' MB';

    //   return size;
    // },
    trigger() {
      this.$refs.fileInput.click();
    },
    editFile(id){
      window.axios.get(`${BASE_URL}/${id}`).then(response => {
        this.focusedDoc = response.data;
        this.upload.note = this.focusedDoc.note; 
        this.upload.type = this.focusedDoc.type;
        this.upload.title = this.focusedDoc.title;
        this.editMode = true;
        let users = [];
        this.focusedDoc.shared_with_users.forEach(function(e){
          users.push(e.id);
        });
        this.upload.shared_with_users = users;
        window.scrollTo(0,0);
        this.env.uploadFile = true; // to re-open form
      }).catch(error => console.log(error.response.data));
    },
    handleFileUpload() {
      this.upload.file = this.$refs.fileInput.files[0];
      this.env.uploadFile = true;
    },
    uploadFile(){
      if(this.editMode)
      {
         let formData = {
          'type': this.upload.type,
          'note': this.upload.note,
          'users': this.upload.shared_with_users
         };
        console.log(formData);
         window.axios.patch(`${BASE_URL}/${this.focusedDoc.id}`, formData).then(response => {
         console.log(response.data);
         let id = this.focusedDoc.id;
         this.documents.data.forEach((v, k) => {
             if (v.id == id)
             Vue.set(this.documents.data,k,response.data);
          });
         Vue.swal({
            type: 'success',
            title: '',
            text: 'Edited Successfully.',    
          })
          this.upload = this.emptyUploadForm();
          this.env.uploadFile = false;
        }).catch(error => {
          console.log(error.response.data);
        })
      }
      else
      {
      	console.log('upload file');
        this.env.uploading = true;
        let formData = new FormData();
        formData.append('file', this.upload.file);
        formData.append('title', this.upload.file.name);
        formData.append('type', this.upload.type);
        formData.append('note', this.upload.note);
        formData.append('users', this.upload.shared_with_users);
        formData.append('stack_id', this.filestack.id);
        formData.append('team_id', this.team.id);

        axios.post(`${BASE_URL}`,
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: function( progressEvent ) {
              this.upload.progress = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ));
            }.bind(this)
          }
        ).then(response => {
          this.env.uploading = false;
          // console.log(response.data);
          Vue.swal('File Uploaded!');
          this.upload = this.emptyUploadForm();
          this.env.uploadFile = false;
          this.documents.data.unshift(response.data);
        }).catch(error => {
          console.log(error.response.data);
        });
      }
    },
    docType(id) {
      let x;
      this.meta.filetypes.forEach((v, k) => {
        if (v.id == id) {
          x = v.name;
        }
      });

      return x;
    },
  }
}
</script>
