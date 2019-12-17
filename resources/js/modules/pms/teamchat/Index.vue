<template>
  <div>
    <div class="mesgs">
      <div class="msg_history">
       <div v-for="(message,index) in messages" :key="index" class="messages">
         <div class="incoming_msg" v-if="message.sender_id != user.id">
          <div class="received_msg">
            <div class="received_withd_msg">
              <span class="time_date"> 
                {{ message.send_time }} 
              </span>
              <p class="text-muted" 
              v-if="message.message_deleted!='0'">
              {{ message.message_deleted }}
            </p>
            <p v-else-if="message.is_file=='0'" v-html="message.message"></p>
            <div v-else>
              <a v-if="checkURL(message.fileurl)" :href="message.fileurl" target="_blank" download>
                <img :src="message.fileurl" class="file_thumbnail"/>
              </a>
              <p v-else class="file_container_recieve card text-center"> 

                <i class="fa fa-paperclip"></i>
                <b>
                  <a :href="message.fileurl" target="_blank" download>
                    {{ message.file_name }}
                  </a><br>
                  {{ message.file_type }}<br>
                  {{ message.file_size }}
                </b>
              </p>
            </div>
            <span class="text-muted" style="font-size:10px;">( {{ message.sender_name }} )</span>
          </div>
        </div>
      </div>
      <div class="outgoing_msg" v-else>
        <div class="sent_msg">
          <span class="time_date text-right"> 
            {{ message.send_time }} 
          </span>
          <div v-if="message.message_deleted == '0'" class="dropdown float-right actionbuttons">
            <button class="btn" type="button" id="dropdownMenuButton" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <span v-if="message.is_file=='0'" class="dropdown-item" @click="openEditMessageEditor(message,index)"><i class="fa fa-edit" ></i> Edit</span>
            <span class="dropdown-item" @click="delteMessage(index,message.message_id)"><i class="fa fa-trash"></i> Delete</span>
          </div>
        </div>  
        <p class="text-muted" 
        v-if="message.message_deleted!='0'">
        {{ message.message_deleted }}
      </p>
      <p v-else-if="message.is_file=='0'" v-html="message.message"></p>
      <div v-else>
        <a v-if="checkURL(message.fileurl)" :href="message.fileurl" target="_blank" download>
          <img :src="message.fileurl" class="file_thumbnail"/>
        </a>
        <p v-else class="file_container_recieve card text-center"> 

          <i class="fa fa-paperclip"></i>
          <b>
            <a :href="message.fileurl" target="_blank" download>
              {{ message.file_name }}
            </a><br>
            {{ message.file_type }}<br>
            {{ message.file_size }}
          </b>
        </p>
      </div>
      
    </div>
  </div>
</div>
</div>
</div>
<WriteMessage @send="sendMessage" ref="messagecomposercomponent"
  :group="group" :group_users="group_users" :user="user"></WriteMessage>

</div>
</template>

<script>
  let FaviconNotification = require('favicon-notification');
  import WriteMessage from './WriteMessage';
  FaviconNotification.init({
    color: '#000000',
    lineColor: '#FFFFFF'
  });

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
  components: {WriteMessage},
  data() {
    return {
      messages: [],
      users: [],
      activeUser: false,
      typingStatus: false,
      editflag:false,
      editing_index:null,
      editing_message:null,
      totalusers:0,
      totalonline:0,
    }
  },
  created() {
    this.fetchMessages();

    Echo.private(`EditMessage.${this.group.chatroom_id}`).listen('EditMessage', (e) => {
      this.handleEditedMessage(e.editedmessage)
    });

    Echo.private(`DeleteMessage.${this.group.chatroom_id}`).listen('DeleteMessage', (e) => {
      this.handleDeletedMessage(e.deletedmessage)
    });

    Echo.private(`teamchat.${this.group.chatroom_id}`).listen('TeamNewMessage', (e) => {
      this.handleIncoming(e.message);
      }).listenForWhisper('typing', typingdata => {
      this.activeUser = typingdata.user;
      if(this.typingStatus) {
        // if(this.selectedContact.room_id == typingdata.chatroom_id){
          this.typing_indicator = typingdata.user.name+' is typing... ';
          $(".typing_indicator").text(this.typing_indicator)
        // }
                                
        // console.log(typingdata.user.name+' is typing in '+typingdata.chatroom_id)
        clearTimeout(this.typingStatus);
      }

      this.typingStatus = setTimeout(() => {
        this.activeUser = false;
        this.typing_indicator = ''
        $(".typing_indicator").text(this.typing_indicator)
      }, 2000);
    }); 
  },
  mounted(){
    document.addEventListener("visibilitychange", function() {
      if (document.visibilityState === 'visible') {
        FaviconNotification.remove();
      }
    }, false);

    Echo.join(`teamchatonline`).here((users) => {
      users.forEach((user)=>{
        if(this.group_users.filter(x => x.id === user.id)){
          this.totalonline++;
          $('.onlineuserscount').text(this.totalonline);
          console.log(this.totalonline)
        }
      });
    }).joining((user) => {
      if(this.group_users.filter(x => x.id === user.id)){
        this.totalonline++;
        $('.onlineuserscount').text(this.totalonline);
        console.log(this.totalonline)
      }
    }).leaving((user) => {
      if(this.group_users.filter(x => x.id === user.id)){
        this.totalonline--;
        $('.onlineuserscount').text(this.totalonline);
        console.log(this.totalonline)
      }
    }); 

    $('.totaluserscount').text(this.group_users.length)       
  },
  methods: {
    checkURL(url) {
      return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
    },

    fetchMessages() {
      axios.get('/getconversations/' + this.group.chatroom_id)
      .then((response) => {
        this.messages = response.data;
      });
      this.scroll();
    },

    sendMessage(message) {
      if(this.editflag){
        if(this.editmessage==this.$refs.messagecomposercomponent.message){
          return;
        }
        axios.post('/editMessage', {
          newmessage: this.$refs.messagecomposercomponent.message,
          message_id: this.editing_message.message_id,
          index :this.editing_index
        }).then((response) => {
          this.editmessage='';
          this.$refs.messagecomposercomponent.message = '';
          this.editflag = false;
          this.editing_index = null;
          this.editing_message = null;
        });
        return;
      }

      axios.post('/sendteamMessage', {
        message: message,
        chatroom_id:this.group.chatroom_id
      }).then(response => console.log(response.data));
      
    },

    saveNewMessage(message) {
      this.messages.push(message);
      if(message.sender_id != this.user.id){
        if (document.visibilityState === 'hidden') {
          FaviconNotification.add(); 
          this.showMessageNotificaiton(message);
        }else{
          // console.log('visible no notification');
        } 
      }
    },

    handleIncoming(message) {
      if (this.group.chatroom_id === message.room_id) {
        this.saveNewMessage(message);
        this.scroll();
        return;
      }
    },

    scroll(){
      $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
    },

    openEditMessageEditor(message,index){
      // console.log(message)
      // $('#edit_message_'+message.message_id).show();
      var editmessage = '';
      editmessage = message.message.replace(/<\/?[^>]+(>|$)/g, "")
      editmessage = editmessage.replace("( Edited )", "");
      this.editmessage = editmessage;
      this.$refs.messagecomposercomponent.message = this.editmessage;
      this.editflag = true;
      this.editing_index = index;
      this.editing_message = message;
    },

    delteMessage(index,message_id){
      axios.post('/deleteMessage', {
        message_id: message_id,
        index :index
      }).then((response) => {
        this.messages.splice(index, 1);
          // this.$emit('newMessage', response.data);
        });
    },

    handleEditedMessage(message){
      var index = 0;
      if(message.message_index || message.message_index!=''){
        index = message.message_index;
      }
      this.messages[index].message = message.message;
    },

    handleDeletedMessage(message){
      this.messages.splice(message.message_index, 1);
      this.saveNewMessage(message);
      return;
    },

    showMessageNotificaiton(message) {
      var iconURL = "/favicon.ico";
      var title='';
      var custommessage='';
      if(message.message.search('@'+this.user.name.split(" ")[0])>0){
        title = message.sender_name + ' has mentioned you in ' + message.chatroom_title;
        custommessage = message.message;
        custommessage = custommessage.replace(/<\/?[^>]+(>|$)/g, "");
        var audio = new Audio('/audio/mention.mp3');
        audio.play();
      }else{
        title = 'New Message From '+message.sender_name;
        custommessage = message.message;
        custommessage = custommessage.replace(/<\/?[^>]+(>|$)/g, "");
        var audio = new Audio('/audio/notification.mp3');
        audio.play();
      }

      Notification.requestPermission(permission => {
        let notificationabc = new Notification(title, {
            body: custommessage, // content for the alert
            icon: iconURL // optional image url
          });
        // link to page on clicking the notification
        notificationabc.onclick = () => {
          if (window.location.pathname == "/chat") {
            location.reload();
          } else {
            window.open("/chat");
          }
        };
      });
    },

  }
}
</script>

<style scoped>
  .file_thumbnail{
    width: 30% !important;
    border-radius: unset !important;
  }
</style>
