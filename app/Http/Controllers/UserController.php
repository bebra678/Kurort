<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteRequest;
use App\Http\Requests\PhotoRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UserRequest;
use App\Models\Attraction;
use App\Models\Card;
use App\Models\Categorie;
use App\Models\Editemail;
use App\Models\Favorite;
use App\Models\Food;
use App\Models\Poster;
use App\Models\Reaction;
use App\Models\Review;
use App\Models\Reviewsimage;
use App\Models\Router;
use App\Models\Shoping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function user()
    {
        $user = Auth::user();
        $user['photo'] = 'https://kurort26-api.ru/api/user/photo';
        return response()->json($user);
    }

    public function photo()
    {
        $user = Auth::user();
        if(!$user['photo'])
        {
            return response()->file(Storage::path('public/user/empty_profile_avatar.png'));
        }
        return response()->file(Storage::path('public/' . $user['photo']));
    }

    public function uploadPhoto(PhotoRequest $request)
    {
        $request->validated();
        $user = Auth()->user();
        $filePath = base_path('public/images/users/' . $user['photo']);
        if (file_exists($filePath))
        {
            unlink($filePath);
        }
        $name = Storage::disk('user_photo')->put('', $request['photo']);
        $user['photo'] = $name;
        $user['path_photo'] = 'https://kurort26-api.ru/api/images/users/' . $name;
        $user->save();
        return response()->json(['success' => true, 'name' => $name, 'path' => 'https://kurort26-api.ru/api/images/users/' . $name]);
    }

    public function update(UserRequest $request)
    {
        $request->validated();
        $data = User::find(Auth::id());
        $data->fill($request->except(['id']));
        $data->save();
        return response()->json(['success' => true, 'message' => 'Вы успешно изменили данные']);
    }

    public function review(ReviewRequest $request) //надо переделать ReviewRequest
    {
        $data = $request->validated();
        $array = Review::where('user_id', Auth::id())->where('card_id', $data['card_id'])->where('category_id', $data['category_id'])->exists();
        if($array)
        {
            return response()->json(['success' => false, 'error' => 'Тут вы уже оставили отзыв']);
        }
        if($data['category_id'] == 1)
        {
            $card = Food::find($data['card_id']);
        }
        elseif($data['category_id'] == 2)
        {
            $card = Poster::find($data['card_id']);
        }
        elseif($data['category_id'] == 3)
        {
            $card = Attraction::find($data['card_id']);
        }
        elseif($data['category_id'] == 4)
        {
            $card = Shoping::find($data['card_id']);
        }
        elseif($data['category_id'] == 5)
        {
            $card = Router::find($data['card_id']);
        }
        if(!$card)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не сущевствует']);
        }
        $data = Review::create([
            'user_id' => $data['user_id'] = Auth::id(),
            'card_id' => $data['card_id'],
            'text' => $data['text'],
            'rating' => $data['rating'],
            'category_id' => $data['category_id'],
        ]);
        $images = [];
        $i = 1;
        if ($request->hasFile('images'))
        {
            foreach ($request->file('images') as $image)
            {
                $path = Storage::disk('reviews')->put('', $image);
                Reviewsimage::create([
                    'review_id' => $data->id,
                    'name' => $path,
                    'path' => 'https://kurort26-api.ru/images/reviews/' . $path,
                ]);
                $images[$i] = 'https://kurort26-api.ru/images/reviews/' . $path;
                $i++;
            }
        }
        return response()->json(['success' => true, 'data' => $data, 'images' => $images]);
    }

    public function favorite(FavoriteRequest $request)
    {
        $data = $request->validated();
        if($data['category_id'] == 1)
        {
            $card = Food::find($data['card_id']);
        }
        elseif($data['category_id'] == 2)
        {
            $card = Poster::find($data['card_id']);
        }
        elseif($data['category_id'] == 3)
        {
            $card = Attraction::find($data['card_id']);
        }
        elseif($data['category_id'] == 4)
        {
            $card = Shoping::find($data['card_id']);
        }
        elseif($data['category_id'] == 5)
        {
            $card = Router::find($data['card_id']);
        }
        else
        {
            return response()->json(['success' => false, 'error' => 'Такой категирии не существует']);
        }
        if(!$card)
        {
            return response()->json(['success' => false, 'error' => 'Такой карточки не сущевствует']);
        }
        $array = Favorite::where('user_id', Auth::id())->where('card_id', $data['card_id'])->where('category_id', $data['category_id'])->exists();
        if($array)
        {
            return response()->json(['success' => false, 'error' => 'Эту карточку вы уже добавили в избранное']);
        }
        $data['user_id'] = Auth::id();
        Favorite::firstOrCreate($data);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function indexFavorite()
    {
        $data = Favorite::where('user_id', Auth::id())->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Список пуст']);
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function indexReview()
    {
        $data = Review::where('user_id', Auth::id())->get();
        if(!$data)
        {
            return response()->json(['success' => false, 'error' => 'Список пуст']);
        }
        return response()->json(['success' => true, 'data' =>$data]);
    }

    public function updateEmail(UpdateEmailRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $updateEmail = Editemail::where('user_id', $user['id'])->exists();
        if($updateEmail)
        {
            return response()->json(['success' => false, 'error' => 'Вы уже подали заявку на смену почты']);
        }
        $updateEmail1 = Editemail::where('email', $data['email'])->exists();
        if($updateEmail1)
        {
            return response()->json(['success' => false, 'error' => 'Данная почта занята']);
        }
        $data['user_id'] = $user['id'];
        $verificationCode = mt_rand(100000, 999999);
        $data['verification_code'] = $verificationCode;
        Editemail::firstOrCreate($data);
        Mail::raw('Ваш код подтверждения для смены почты: ' . $verificationCode, function ($message) use ($user) {
            $message->to($user['email'])->subject('Код подтверждения');
        });
        return response()->json(['success' => true, 'message' => 'Код подтверждения отправлен на вашу почту']);
    }

    public function verifyCodeEmail(Request $request)
    {
        $updateEmail = Editemail::where('user_id', Auth::id())->first();
        if(!$updateEmail)
        {
            return response()->json(['success' => false, 'error' => 'Вы не оставляли заявку на смену почты']);
        }
        if($updateEmail['verification_code'] === $request->code)
        {
            $user = User::find(Auth::id());
            $user['email'] = $updateEmail['email'];
            $updateEmail->delete();
            $user->save();
            return response()->json(['success' => true, 'message' => 'Вы успешно подтвердили свою новую почту!']);
        }
        else
        {
            return response()->json(['success' => false, 'error' => 'Неверный код подтверждения']);
        }
    }
}
