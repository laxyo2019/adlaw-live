<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\Models\Media;
use App\Models\ChatroomMessage;
use Storage;
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public $index;

    public function messageIndex($value){
        $this->index = $value;
        return $this;
    }
    public function toArray($request,$index=null)
    {
        $fileurl = '';
        $file_name = '';
        $file_type = '';
        $file_size=0;
        if($this->is_file==1){
            $files = ChatroomMessage::find($this->id);
            if(!empty($files)){
                $fileurl = $files->getMedia()[0]->getUrl();
                // dd($fileurl);
                $file_name = $files->getMedia()[0]->name;
                $file_type = $files->getMedia()[0]->mime_type;
                $file_size = $files->getMedia()[0]->size;
            }
        }
        return [
            'message_id' =>$this->id,
            'room_id'    =>$this->chatroom_id,
            'roomtype'   =>$this->chatroom->type,
            'sender_id'  =>$this->sender_id,
            'sender_name' =>$this->user->name,
            'message_index' => ($this->index) ? $this->index : '',
            'chatroom_title' =>$this->chatroom->title,
            'message'    => $this->message,
            'is_file'    => ($this->is_file) ? $this->is_file : '0',
            'fileurl'    => $fileurl,
            'file_name'    => $file_name,
            'file_type'    => $file_type,
            'file_size'    => round($file_size/1000) . ' KB' ,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'send_time' => $this->created_at->format('d-m-y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            'message_deleted' => ($this->deleted_at) ? "Message was deleted" :"0",

        ];
    }
}
