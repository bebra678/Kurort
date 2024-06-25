<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteRequest;
use App\Http\Requests\PhotoRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\UserRequest;
use App\Models\Card;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function user()
    {
        $user = Auth::user();
        $user['photo'] = 'http/user/photo';
        return response()->json($user);
    }

    public function photo()
    {
        $user = Auth::user();
        return response()->file(Storage::path('public/' . $user['photo']));
    }

    public function uploadPhoto(PhotoRequest $request)
    {
        $request->validated();
        $user = Auth()->user();
        if($user['photo'])
        {
            Storage::delete('public/' . $user['photo']);
        }
        Storage::disk('public')->put('user/', $request['photo']);
        $user->save();
        return response()->json($user['photo']);
    }

    public function update(UserRequest $request)
    {
        $request->validated();
        $data = User::find(Auth::id());
        $data->fill($request->except(['id']));
        $data->save();
        return response()->json([$data]);
    }

    public function review(ReviewRequest $request)
    {
        $data = $request->validated();
        //$card = Card::find($data['card_id']);
        $card = Card::where('id', $data['card_id'])->exists();
        if(!$card)
        {
            return response()->json(['error' => 'Такой карточки не сущевствует'], 404);
        }
        $array = Review::where('user_id', Auth::id())->where('card_id', $data['card_id'])->exists();
        if($array)
        {
            return response()->json(['error' => 'Тут вы уже оставили отзыв'], 403);
        }
        $data['user_id'] = Auth::id();
        Review::firstOrCreate($data);
        return response()->json($data);
    }

    public function favorite(FavoriteRequest $request)
    {
        $data = $request->validated();
        //$card = Card::find($data['card_id']);
        $card = Card::where('id', $data['card_id'])->exists();
        if(!$card)
        {
            return response()->json(['error' => 'Такой карточки не сущевствует'], 404);
        }
        $array = Favorite::where('user_id', Auth::id())->where('card_id', $data['card_id'])->exists();
        if($array)
        {
            return response()->json(['error' => 'Эту карточку вы уже добавили в избранное'], 403);
        }
        $data['user_id'] = Auth::id();
        Favorite::firstOrCreate($data);
        return response()->json($data);
    }

    public function indexFavorite()
    {
        $data = Favorite::where('user_id', Auth::id())->get();
        if(!$data)
        {
            return response()->json(['error' => 'Список пуст'], 204);
        }
        return response()->json($data);
    }

    public function indexReview()
    {
        $data = Review::where('user_id', Auth::id())->get();
        if(!$data)
        {
            return response()->json(['error' => 'Список пуст'], 204);
        }
        return response()->json($data);
    }
}
