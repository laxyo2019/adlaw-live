<template>
	<div class="card card-profile">
		
	  <div class="card-body text-center">

	    <span> 
				<croppa v-model="myCroppa"
				 :width="128"
				 :height="128"
				 :initial-image="user_img"></croppa>
			</span>

	    <h3 class="mt-3 mb-3">{{ user.name }}</h3>
	    <p class="mb-4">{{ user.email }}</p>

	    <button class="btn btn-outline-primary btn-sm" v-if="user.id === logged_user.id" @click="uploadCroppedImage">Upload</button> 
	  </div>
  </div>
</template>

<script>
	import Croppa from 'vue-croppa';
	import 'vue-croppa/dist/vue-croppa.css';
	Vue.use(Croppa);

	export default {
		props: ['logged_user', 'user', 'user_img'],
		data() {
			return {
				avatar: '',
				myCroppa: {}
			}
		},
		mounted() {
			this.avatar = 'https://img.icons8.com/ios/100/000000/contacts.png';
		},
		methods: {
			uploadCroppedImage() {
				var base64 = this.myCroppa.generateDataUrl();
				window.axios.post('/user/profile/set_avatar', {file: base64}).then(response => {
        	console.log(response.data);
        	this.avatar = response.data;
        	Vue.swal('Picture Updated!');
        }).catch(error => console.log(error.response));
	    }
		}
	}
</script>
