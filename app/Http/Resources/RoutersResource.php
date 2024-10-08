<?php

namespace App\Http\Resources;

use App\Models\Citie;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Reaction;
use App\Models\Reactionsimage;
use App\Models\Review;
use App\Models\Reviewsimage;
use App\Models\Routerpoint;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoutersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(isset($this->type_id))
        {
            $type = Type::find($this->type_id);
            $type = $type['name'];
        }
        if(isset($this->city_id))
        {
            $city = Citie::find($this->city_id);
            $city = $city['name'];
        }
        $reviews = Review::where('card_id', $this->id)->where('category_id', $this->category_id)->get();
        if($reviews)
        {
            foreach ($reviews as $review) {
                $review['likes'] = Reaction::where('review_id', $review->id)->where('type', 1)->count();
                $review['dislikes'] = Reaction::where('review_id', $review->id)->where('type', 2)->count();
                $user = User::find($review->user_id);
                $review['name'] = $user['name'];
                $review['images_reviews'] = Reviewsimage::where('review_id', $review->id)->get();
            }
        }
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
        $images = Image::where('card_id', $this->id)->where('category_id', $this->category_id)->get();
        foreach ($images as $img)
        {
            $img['path'] = 'https://kurort26-api.ru/images/' . $img['name'];
            $img['likes'] = Reactionsimage::where('img_id', $img['id'])->where('type', 1)->count();
            $img['dislikes'] = Reactionsimage::where('img_id', $img['id'])->where('type', 2)->count();
        }
        $routerpoints = Routerpoint::where('router_id', $this->id)->get();
        if($this->isTop == 1)
        {
            $isTop = true;
        }
        else
        {
            $isTop = false;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $type ?? null,
            'city' => $city ?? null,
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
            'verified' => $this->removeBrackets($this->verified),
            'isTop' => $isTop,
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
            'category_id' => $this->category_id,
            'routerpoints' => $routerpoints,
            //'imageUrl' => 'https://kurort26-api.ru/api/cards/photo/' . $this->category_id . '/' . $this->id . '/' . 1,
            'reviews' => $reviews,
            'images' => $images,
        ];
    }

    private function removeBrackets($string)
    {
        if(isset($string))
        {
            return $string[0];
        }
        return null;
    }
}
