<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function index()
    {
        $data = Card::all();
        return response()->json($data);
    }

    public function show(string $id)
    {
        $data = Card::find($id);
        if(!$data)
        {
            return response()->json(['error' => 'Такой карточки не существует'], 404);
        }
        $count = Image::where('card_id', $id)->count();
        $imageUrl = [];
        for($i = 0; $i < $count; $i++)
        {
            //$imageUrl[] = 'http://kurort26-api.ru/api/cards/photo/' . $id . '/' . $i;
            $imageUrl[] = 'http://127.0.0.1:8000/api/cards/photo/' . $id . '/' . $i+1;
        }
        return response()->json([$data, $imageUrl]);
    }

    public function showIndex(string $id)
    {
        $data = Card::where('category_id', $id)->get();
        if(!$data)
        {
            return response()->json(['error' => 'Такой категории нет или список пуст'], 404);
        }
        return response()->json($data);
    }

    public function photo(string $id, string $page)
    {
        $data = Image::where('card_id', $id)->where('page', $page)->first();
        return response()->file(Storage::path('public/images/' . $data['name']));
    }
}
