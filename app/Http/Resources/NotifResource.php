<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class NotifResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timestamp = date_create($this['timestamp']);

        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'description' => $this['description'],
            'timestamp' => date_format($timestamp, "Y-m-d H:i:s"),


        ];

    }
}
