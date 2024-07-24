<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionRequest;
use App\Http\Resources\AttractionsResource;
use App\Http\Resources\FoodsResource;
use App\Http\Resources\RoutersResource;
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
use App\Models\Shoping;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        //return response()->json($data);
        return AttractionsResource::collection($data);
    }

    public function indexAllAttractions()
    {
        return response()->json(Attraction::all());
    }

    public function showAttractions(string $id)
    {
        $data = Attraction::find($id);
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        $city = Citie::find($id)->first();
        $data['city'] = $city['name'];
        $type = Type::find($id)->first();
        $data['type'] = $type['name'];
        $imageUrl = $this->searchImages($id, $data['category_id']);
        $reviews = $this->searchReview($id, $data['category_id']);
        return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
    }

    public function indexFoods()
    {
        $data = Food::all();
        return FoodsResource::collection($data);
    }

    public function indexAllFoods()
    {
        return response()->json(Food::all());
    }

    public function showFoods(string $id)
    {
        $data = Food::find($id);
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        $imageUrl = $this->searchImages($id, $data['category_id']);
        $reviews = $this->searchReview($id, $data['category_id']);
        return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
    }

    public function indexRouters()
    {
        $data = Router::all();
        return RoutersResource::collection($data);
    }

    public function indexAllRouters()
    {
        return response()->json(Router::all());
    }

    public function showRouters(string $id)
    {
        $data = Router::find($id);
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        $imageUrl = $this->searchImages($id, $data['category_id']);
        $reviews = $this->searchReview($id, $data['category_id']);
        return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
    }

    public function indexShopings()
    {
        $data = Shoping::all();
        return AttractionsResource::collection($data);
    }

    public function indexAllShopings()
    {
        return response()->json(Shoping::all());
    }

    public function showShopings(string $id)
    {
        $data = Shoping::find($id);
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        $imageUrl = $this->searchImages($id, $data['category_id']);
        $reviews = $this->searchReview($id, $data['category_id']);
        return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
    }

    public function indexPosters()
    {
        $data = Poster::all();
        return AttractionsResource::collection($data);
    }

    public function indexAllPosters()
    {
        return response()->json(Poster::all());
    }

    public function showPosters(string $id)
    {
        $data = Poster::find($id);
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не существует']);
        }
        $imageUrl = $this->searchImages($id, $data['category_id']);
        $reviews = $this->searchReview($id, $data['category_id']);
        return response()->json(['success' => true, 'card' => $data, 'imageUrl' => $imageUrl, 'reviews' => $reviews]);
    }

    public function searchImages(string $id, string $category_id)
    {
        $count = Image::where('card_id', $id)->where('category_id', $category_id)->count();
        $imageUrl = [];
        for($i = 0; $i < $count; $i++)
        {
            $imageUrl[] = 'https://kurort26-api.ru/api/cards/photo/' . $category_id . '/' . $id . '/' . $i+1;
        }
        return $imageUrl;
    }

    public function searchReview(string $id, string $category_id)
    {
        $reviews = Review::where('card_id', $id)->where('category_id', $category_id)->get();
        foreach ($reviews as $review)
        {
            $review['likes'] = Reaction::where('review_id', $review->id)->where('type', 1)->count();
        }
        foreach ($reviews as $review)
        {
            $review['dislikes'] = Reaction::where('review_id', $review->id)->where('type', 2)->count();
        }
        return $reviews;
    }

    public function photoCards(string $cat_id,string $id, string $page)
    {
        $data = Image::where('card_id', $id)->where('page', $page)->where('category_id', $cat_id)->first();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Изображение не найдено']);
        }
        return response()->file(Storage::path('public/images/' . $data['name']));
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
        return response()->json($data);
    }
}
