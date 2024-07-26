<?php

namespace App\Http\Resources;

use App\Models\Citie;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Routerpoint;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoutersAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = Type::find($this->type_id);
        $type = $type['name'];
        $city = Citie::find($this->city_id);
        $city = $city['name'];
        $reviews = Review::where('card_id', $this->id)->where('category_id', $this->category_id)->get();
        $rating = round((float) $reviews->avg('rating'), 1);
        $voted = Review::where('card_id', $this->id)->where('category_id', $this->category_id)->count();
        $isFavorite = Favorite::where('user_id', Auth::id())->where('card_id', $this->id)->where('category_id', $this->category_id)->first();
        if($isFavorite)
        {
            $isFavorite = true;
        }
        else
        {
            $isFavorite = false;
        }
        $routerpoints = Routerpoint::where('router_id', $this->id)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $type,
            'city' => $city,
            'titleDesc' => $this->titleDesc,
            'description' => $this->description,
            'previewDescription' => $this->previewDescription,
            'advice' => $this->advice,
            'site' => $this->site,
            'phone' => $this->phone,
            'social' => $this->social,
            'percent' => $this->percent,
            'preview' => $this->preview,
            'text' => $this->text,
            'reasonsVisit' => $this->reasonsVisit,
            'verified' => $this->verified,
            'isTop' => $this->isTop,
            'chooseCurort26' => $this->chooseCurort26,
            'features' => $this->features,
            'totalTime' => $this->totalTime,
            'totalDistance' => $this->totalDistance,
            'anyIndex' => $this->anyIndex,
            'isParking' => $this->isParking,
            'imageMap' => $this->imageMap,
            'rating' => $rating,
            'voted' => $voted,
            'isFavorite' => $isFavorite,
            'taxi' => 123,
            'routerpoints' => $routerpoints,
            'imageUrl' => 'https://kurort26-api.ru/api/cards/photo/' . $this->category_id . '/' . $this->id . '/' . 1,
        ];
    }
}
