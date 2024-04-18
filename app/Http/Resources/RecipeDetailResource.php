<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Recipe;

class RecipeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timestamp = date_create($this['timestamp']);
        $recipe = new Recipe();
        $user = $recipe->getUserById($this['user_id']);

        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'user_id' => $this['user_id'],
            'user_name' => $user['nama'] ?? null,
            'user_image' => $user['imageUrl'] ?? null,
            'timestamp' => date_format($timestamp, "Y-m-d H:i:s"),
            'description' => $this['description'],
            'image_url' => $this['image_url'],
            'ingredients' => $this['ingredients'],
            'steps' => $this['steps'],
            'verified' => $this['verified'],
        ];
    }
}
