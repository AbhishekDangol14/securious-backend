<?php

namespace App\Http\Resources;

class RegistrationSuccessResponse extends LoginSuccessResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['is_newly_created'] = true;
        $data['role'] = $this->role;
        return $data;
    }
}
