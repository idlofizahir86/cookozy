<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'email' => $this['email'],
            'imageUrl' => $this['imageUrl'],
            'nama' => $this['nama'],
            'role' => $this['role'],
        ];
    }
}
