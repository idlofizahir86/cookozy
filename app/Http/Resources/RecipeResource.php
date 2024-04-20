<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Recipe;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $recipe = new Recipe();
        $user = $recipe->getUserById($this['user_id']);
        $timestamp = date_create($this['timestamp']);

        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'user_id' => $this['user_id'],
            'user_name' => $user['nama'] ?? null,
            'description' => $this['description'],
            'image_url' => $this['image_url'],
            'level' => $this['level'],
            'type' => $this['type'],
            'verified' => $this['verified'],
        ];

    }
}
