<?php
namespace App\Http\Resources;

//Name: HO YI VON
//Student ID : 23WMR14542

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'genre'     => $this->genre,
            'language'  => $this->language,
            'duration'  => $this->duration,
            'release_date' => $this->release_date,
            'status'    => $this->status,      
            'is_popular'=> $this->is_popular,  
            'reviews_count' => $this->reviews_count ?? 0, 
            'reviews_avg_rating' => $this->reviews_avg_rating ?? 0,
            'image_url' => asset('storage/movies/' . $this->image_path),

            'cast' => $this->cast,

            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'related_movies' => MovieResource::collection($this->whenLoaded('related')),
        ];
    }
}

  