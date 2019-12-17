<template>
  <div>
    <table class="table">
      <tr>
        <!-- <td style="width:5%">
          <i class
             v-bind:class="(!openfileuploadingbox) ? 'fa fa-paperclip attachment' :'fa fa-arrow-down'"
             aria-hidden="true"
             @click="uploadfileboxopen">
          </i>
        </td> -->
        <td style="width:90%">
          <at-ta :members="members" name-key="user_name" tabSelect="true" :allowSpaces="false">
            <textarea
              id="message_composer"
              v-model="message"
              @keydown.enter.exact.prevent="sendMessage"
              @keydown="sendTypingEvent"
              placeholder="Type your message here..."
              rows="1"
              autofocus
            ></textarea>
          </at-ta>
        </td>
        <td style="width:5%;background:#32465a;" @click="sendMessage">
          <button class="submit sendbutton">
            <i class="fa fa-paper-plane" aria-hidden="true"></i>
          </button>
        </td>
      </tr>
    </table>

<!--     <vue2Dropzone
      style="position: absolute;
      width: 100%;
      bottom: 50px;"
      v-show="openfileuploadingbox"
      ref="myVueDropzone"
      id="dropzone"
      :options="dropzoneOptions"
      v-on:vdropzone-sending="sendingEvent"
      @vdropzone-queue-complete="vqueueComplete">
    </vue2Dropzone> -->
  </div>
</template>

<script>
import AtTa from "vue-at/dist/vue-at-textarea";
import At from "vue-at";

// import vue2Dropzone from "vue2-dropzone";
// import "vue2-dropzone/dist/vue2Dropzone.min.css";
export default {
  props:{
    user: {
      type: Object,
      required: true
    },
    group:{
      type: Object,
      default:null
    },
    group_users:{
      type: Array,
      default: []
    }
  },
  // components: { AtTa, At, vue2Dropzone },
  components: { AtTa, At },
  created(){
    this.getgroupmembers();
  },
  data() {
    return {
      // attachmentIcon: "fa fa-paperclip attachment",
      // openfileuploadingbox: false,
      message: "",
      members: [],
      typing: {
        user: null,
        chatroom_id: null
      },
      // validlength: true,
      // queueComplete: false,
      // dropzoneOptions: {
      //   url: "/uploadfile",
      //   thumbnailWidth: 150,
      //   maxFilesize: 10,
      //   addRemoveLinks: true,
      //   autoProcessQueue: false,
      //   headers: {
      //     "Access-Control-Allow-Origin": "*",
      //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      //   },
      //   parallelUploads: 1
      // }
    };
  },
  methods: {
    sendMessage() {
      this.message = $.trim(this.message);
      if (this.message === "") {
        this.$refs.myVueDropzone.processQueue();
        return;
      }
      // if (this.message.length > 2000) {
      //   this.validlength = false;
      //   return;
      // }
      this.$emit("send", this.message);
      this.message = "";
      // var audio = new Audio('audio/sendmessage.mp3');
      // audio.play();
    },
    // uploadfileboxopen() {
    //   this.openfileuploadingbox = !this.openfileuploadingbox;
    // },
    sendingEvent(file, xhr, formData) {
      formData.append("chatroom_id", this.group.id);
    },
    // vqueueComplete(file, xhr, formData) {
    //   this.openfileuploadingbox = !this.openfileuploadingbox;
    //   this.queueComplete = true;
    //   setTimeout(function() {
    //     $(".dz-preview").remove();
    //     $(".dz-message").show();
    //   }, 1000);
    // },

    sendTypingEvent() {
      // if (this.message.length > 2000) {
      //   this.validlength = false;
      // } else {
      //   this.validlength = true;
      // }
      this.typing.user = this.user;
      this.typing.chatroom_id = this.group.chatroom_id;
      Echo.private(`teamchat.${this.group.chatroom_id}`).whisper(
        "typing",
        this.typing
      );
    },

    getgroupmembers(){
      axios.get("/getGroupMembers/" + this.group.id).then(response => {
          this.members = response.data;
          console.log(response.data)
          let i = this.members.map(item => item.user_id).indexOf(this.user.id) // find index of your object
          this.members.splice(i, 1)
        });
    }
  }
};
</script>

<style scoped>
table{
  margin-bottom: unset;
  background: white;
}
.table th, .table td {
    border: 1px solid #eeeeee;
    vertical-align: middle;
    text-align: center;
    padding: 0.2rem;
}
.fa-arrow-down {
  z-index: 4;
  font-size: 1.1em;
  color: #73777c;
  opacity: 0.5;
  cursor: pointer;
}
textarea {
  font-family: "proxima-nova", "Source Sans Pro", sans-serif;
  float: left;
  width: 100%;
  border: none;
  padding: 11px 32px 10px 8px;
  font-size: 1em;
  color: #32465a;
  resize: none;
  white-space: pre-wrap;
  height: 40px;
}
.fa-paperclip{
  color:#73777c;
}
textarea:hover, 
input:hover, 
textarea:active, 
input:active, 
textarea:focus, 
input:focus,
button:focus,
button:active,
button:hover,
label:focus,
.btn:active,
.btn.active
{
    outline:0px !important;
    -webkit-appearance:none;
}

.sendbutton{
    border: none;
    width: 100%;
    cursor: pointer;
    background: #32465a;
    color: #f5f5f5;
}

.vue-dropzone .dropzone .dz-clickable{
    
}
</style>