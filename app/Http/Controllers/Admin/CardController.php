<?php

namespace App\Http\Controllers\Admin;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('checkStatusAdmin');
        $this->middleware('CheckAdminAccess');
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $cards = Card::all();
        return view('admin.card.index',compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        return view('admin.card.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $this->validate($req, [
            'image_category_id'   => 'required|string|max:255',
            'status' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:10000',

        ]);
        $data = $req->all();
        $cardObj = new Card;
        $userObj = new User;
        if($req->hasFile('image')){
            $data['image'] = $userObj->uploadFile($req->image);
        }
        $card = $cardObj->createCard($data);
        return redirect()->route('admin.card.index')->with('toast-success', __('Successfully added').'!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        if ($card) {
            return view('admin.card.show',compact('card'));
        }
        return redirect()->back()->with('toast-error', 'Card not found!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\card  $card
     * @return \Illuminate\Http\Response
     */
    public function edit(card $card)
    {
        if ($card) {
            return view('admin.card.edit',compact('card'));
        }
        return redirect()->back()->with('toast-error', 'Card not found!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $this->validate($req, [
            'image_category_id'   => 'required|string|max:255',
            'status' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:10000',
        ]);
        $data = $req->all();
        $card = Card::find($id);
        if ($card) {
            $userObj = new User;
            if($req->hasFile('image')){
                $data['image'] = $userObj->uploadFile($req->image);
            }
            $card = $card->updatecard($data,$card);
            return redirect()->route('admin.card.index')->with('toast-success', __('Successfully updated').'!');
        }
        return redirect()->back()->with('toast-error', 'Card not found!');
    }

    /**
     * Change the status.
     *
     * @param  \App\Models\card  $card
     * @return \Illuminate\Http\Response
     */
    public function statusToggle(card $card)
    {
        $status = ($card->status)?'InActivated':'Activated';
        $card->status = !$card->status;
        $card->save();
        return redirect()->route('admin.card.index')->with('toast-success',__('Successfully '.$status).'!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(card $card)
    {
        $card->delete();
        return redirect()->route('admin.card.index')->with('toast-success',__('successfully deleted').' ! ');
    }
}
