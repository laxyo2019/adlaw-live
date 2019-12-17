<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupMember extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            // 'chatroom_id'  =>$this->chatroom_id,
            'user_id'  =>$this->id,
            'user_name'=>$this->name,
            // 'avatar'=>($this->users->avatar) ? $this->users->avatar : 'default_avatar_male.jpg',
            // 'user_name'=>$this->users->fname,
        ];
    }
}
