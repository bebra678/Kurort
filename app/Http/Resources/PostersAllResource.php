<?php

namespace App\Http\Resources;

use App\Models\Citie;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Reactionsimage;
use App\Models\Review;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostersAllResource extends JsonResource
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
        $images = Image::where('card_id', $this->id)->where('category_id', $this->category_id)->where('page', 1)->get();
//        $images['path'] = 'https://kurort26-api.ru/images/' . $images->name;
//        $images['likes'] = Reactionsimage::where('img_id', $images['id'])->where('type', 1)->count();
//        $images['dislikes'] = Reactionsimage::where('img_id', $images['id'])->where('type', 2)->count();
        foreach ($images as $img)
        {
            $img['path'] = 'https://kurort26-api.ru/images/' . $img['name'];
            $img['likes'] = Reactionsimage::where('img_id', $img['id'])->where('type', 1)->count();
            $img['dislikes'] = Reactionsimage::where('img_id', $img['id'])->where('type', 2)->count();
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'begin' => $this->begin,
            'type' => $type,
            'city' => $city,
            'restrictions' => $this->restrictions,
            'nameInstitution' => $this->nameInstitution,
            'titleDesc' => $this->titleDesc,
            'description' => $this->description,
            'previewDescription' => $this->previewDescription,
            'addressesTicet' => $this->addressesTicet,
            'address' => $this->address,
            'site' => $this->site,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'social' => $this->social,
            'taxi' => 123,
            'percent' => $this->percent,
            'preview' => $this->preview,
            'text' => $this->text,
            'reasonsVisit' => $this->reasonsVisit,
            'verified' => $this->verified,
            'isTop' => $this->isTop,
            'chooseCurort26' => $this->chooseCurort26,
            'features' => $this->features,
            'rating' => $rating,
            'voted' => $voted,
            'weekWork' => $this->weekWork,
            'isFavorite' => $isFavorite,
            'category_id' => $this->category_id,
            'images' => $images,
        ];
    }
}
