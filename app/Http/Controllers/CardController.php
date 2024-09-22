<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsdRequest;
use App\Http\Requests\ParsRequest;
use App\Http\Requests\ReactionImageRequest;
use App\Http\Requests\ReactionRequest;
use App\Http\Resources\AttractionsAllResource;
use App\Http\Resources\AttractionsResource;
use App\Http\Resources\FoodsAllResource;
use App\Http\Resources\FoodsResource;
use App\Http\Resources\PostersAllResource;
use App\Http\Resources\PostersResource;
use App\Http\Resources\RoutersAllResource;
use App\Http\Resources\RoutersResource;
use App\Http\Resources\ShopingResource;
use App\Models\Allimage;
use App\Models\Attraction;
use App\Models\Card;
use App\Models\Citie;
use App\Models\Food;
use App\Models\Image;
use App\Models\Poster;
use App\Models\Reaction;
use App\Models\Review;
use App\Models\Router;
use App\Models\Routerpoint;
use App\Models\Shoping;
use App\Models\Test;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Reactionsimage;

class CardController extends Controller
{
    public function index()
    {
        $attractions = Attraction::all();
        $cities = Citie::all();
        $foods = Food::all();
        $routers = Router::all();
        $shopings = Shoping::all();
        return response()->json(['attractions' => $attractions, 'cities' => $cities, 'foods' => $foods, 'routers' => $routers, 'shopings' => $shopings]);
    }

    public function indexAttractions()
    {
        $data = Attraction::all();
        return AttractionsAllResource::collection($data);
    }

    public function showAttractions(string $id)
    {
        $data = Attraction::where('id', $id)->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
//        $city = Citie::find($id)->first();
//        $data['city'] = $city['name'];
//        $type = Type::find($id)->first();
//        $data['type'] = $type['name'];
//        $imageUrl = $this->searchImages($id, $data['category_id']);
//        $reviews = $this->searchReview($id, $data['category_id']);
        //return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
        return AttractionsResource::collection($data);
    }

    public function indexFoods()
    {
        $data = Food::all();
        return FoodsAllResource::collection($data);
    }

    public function showFoods(string $id)
    {
        $data = Food::where('id', $id)->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        return FoodsResource::collection($data);
    }

    public function indexRouters()
    {
        $data = Router::all();
        return RoutersAllResource::collection($data);
    }

    public function showRouters(string $id)
    {
        $data = Router::where('id', $id)->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        return RoutersResource::collection($data);
    }

    public function indexShopings()
    {
        $data = Shoping::all();
        return AttractionsAllResource::collection($data);
    }

    public function showShopings(string $id)
    {
        $data = Shoping::where('id', $id)->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        return ShopingResource::collection($data);
    }

    public function indexPosters()
    {
        $data = Poster::all();
        return PostersAllResource::collection($data);
    }

    public function showPosters(string $id)
    {
        $data = Poster::where('id', $id)->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        return PostersResource::collection($data);
    }


    public function imagesAll()
    {
        return response()->json(Allimage::all());
    }

    public function reaction(ReactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $reaction = Reaction::where('user_id', $data['user_id'])->where('review_id', $data['review_id'])->first();
        if($reaction)
        {
            $reaction->fill($data);
            $reaction->save();
        }
        Reaction::firstOrCreate($data);
        return response()->json(['success' => true]);
    }

    public function reactionImage(ReactionImageRequest $request)
    {
        $data = $request->validated();
        $img = Image::where('id', $data['img_id'])->exists();
        if(empty($img))
        {
            return response()->json(['success' => false, 'error' => 'Такого изображения не существует']);
        }
        $data['user_id'] = Auth::id();
        $reaction = Reactionsimage::where('user_id', $data['user_id'])->where('img_id', $data['img_id'])->first();
        if($reaction)
        {
            $reaction->fill($data);
            $reaction->save();
        }
        Reactionsimage::firstOrCreate($data);
        return response()->json(['success' => true]);
    }
}



























//    public function searchImages(string $id, string $category_id)
//    {
//        $count = Image::where('card_id', $id)->where('category_id', $category_id)->count();
//        $imageUrl = [];
//        for($i = 0; $i < $count; $i++)
//        {
//            $imageUrl[] = 'https://kurort26-api.ru/api/cards/photo/' . $category_id . '/' . $id . '/' . $i+1;
//        }
//        return $imageUrl;
//    }
//
//
//    public function searchReview(string $id, string $category_id)
//    {
//        $reviews = Review::where('card_id', $id)->where('category_id', $category_id)->get();
//        foreach ($reviews as $review)
//        {
//            $review['likes'] = Reaction::where('review_id', $review->id)->where('type', 1)->count();
//        }
//        foreach ($reviews as $review)
//        {
//            $review['dislikes'] = Reaction::where('review_id', $review->id)->where('type', 2)->count();
//        }
//        return $reviews;
//    }

//    public function photoCards(string $cat_id,string $id, string $page)
//    {
//        $data = Image::where('card_id', $id)->where('page', $page)->where('category_id', $cat_id)->first();
//        if(!$data)
//        {
//            return response()->json(['success' => false, 'error' => 'Изображение не найдено']);
//        }
//        return response()->file(Storage::path('public/images/' . $data['name']));
//    }

