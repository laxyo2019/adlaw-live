<template>
  <div class="card">
		<div class="row pl-5 pr-5 pt-5">
			<div class="form-group col-sm-6 col-xs-12">
				<!-- <label for=""><b>Post Title</b></label> -->
  			<input type="text" name="title" class="h-100 form-control" v-model="post.title"
  				placeholder="Give your post a title..." required autofocus>
  				<div class="validation-message" v-text="validation.getMessage('title')"></div>
  		</div>
			<div class="form-group col-sm-6  col-xs-12">
						  <multiselect v-model="post.category" 
						  :options="postCategories" 
						  :multiple="false" 
						  :close-on-select="true" 
						  :clear-on-select="true" 
						  :preserve-search="true" 
						  placeholder="Pick some category" 
						  label="name" 
						  track-by="name" 
						  :preselect-first="false">
					  </multiselect>
						</div>
			<div class="form-group col-12">
				<!-- <label for=""><b>Post Body</b></label> -->
				<vue-editor v-model="post.body" :editor-toolbar="customToolbar" />
  		</div>

			<p><i class="fe fe-link"></i><a href="#" 
				@click.prevent="showDropzone = !showDropzone"
				class="text-decoration-none">Add Attachments</a>
			</p>

			<div class="form-group col-12" v-show="showDropzone">
				<!-- <label for=""><b>Attachments</b></label> -->
  			<vue-dropzone ref="myVueDropzone" id="dropzone" 
  				:options="dropzoneOptions" :useCustomSlot=true 
  				v-on:vdropzone-sending="sendingEvent">

		     	<div class="dropzone-custom-content">
		       	<h3 class="dropzone-custom-title">Drag and drop to upload attachments!</h3>
		       	<div class="subtitle">...or click to select a file from your computer</div>
		     	</div>
		   	</vue-dropzone>
  		</div>
				
  		<div class="form-group col-12 text-center">
  			<button type="button" class="btn btn-pill btn-success" @click="storePost">
  				<i class="fe fe-send mr-2"></i>Post
				</button>
  			<button type="button" class="btn btn-pill btn-outline-dark ml-2" 
	  			@click="$emit('close-form'); post=emptyPostForm();">Cancel
	  		</button>
  		</div>
		</div>
  </div>
</template>

<script>
	import Validation from '../../../utils/Validation.js';
	import { VueEditor } from 'vue2-editor'
	import vue2Dropzone from 'vue2-dropzone'
	import 'vue2-dropzone/dist/vue2Dropzone.min.css'
	export default {
		props: ['board_id', 'creator_id','postCategories'],
		components: {
	    VueEditor,
	    vueDropzone: vue2Dropzone
	  },
	  data() {
	  	return {
	  		validation: new Validation(),
	  		stored_post: {},
	  		showDropzone: false,
	  		post: this.emptyPostForm(),
	  		dropzoneOptions: {
          url: '/pms/posts/append_images',
          thumbnailWidth: 200,
          addRemoveLinks: true,
          maxFilesize: 20, // MB
          autoProcessQueue: false,
	        headers: {
	          "Access-Control-Allow-Origin": "*",
	          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
	        },
	        parallelUploads: 1
	      },
	      customToolbar: [
	        ["bold", "italic", "underline"],
	        [{ list: "ordered" }, { list: "bullet" }],
	        ["image", "code-block"]
	      ],
	  	}
	  },
	  methods: {
	  	emptyPostForm() {
	  		return {
	  			board_id: this.board_id,
	  			creator_id: this.creator_id,
	  			title: '',
	  			body: '',
	  			category: []
	  		}
	  	},
	  	storePost() {
	  		let postData = this.post;
	      axios.post('/pms/posts', postData).then(response => {
	      	this.validation = new Validation();
	      	console.log('response', response.data);
	      	this.stored_post = response.data;
	        this.$refs.myVueDropzone.processQueue();
	        this.post = this.emptyPostForm();
	        this.$emit('post-stored', response.data);
	          this.$emit('close-form');
	        Vue.toasted.success(`Created Successfully.`, {
			      	duration: 2000
			      });
	      
	      }).catch(error => {
	      	if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	 console.log(error.response.data)
	        }
	      });
	  	},
	    sendingEvent(file, xhr, formData) {
	      formData.append("post_id", this.stored_post.id);
	    }
	  }
	}
</script>

<style scoped>
	.dropzone-custom-content {
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%, -50%);
	  text-align: center;
	}

	.dropzone-custom-title {
	  margin-top: 0;
	  color: #00b782;
	}

	.subtitle {
	  color: #314b5f;
	}
</style>