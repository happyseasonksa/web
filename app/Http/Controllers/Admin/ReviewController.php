<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\RestaurantBranch;
use App\Models\Review;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\ReviewProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;

class ReviewController extends Controller
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
        $reviews = collect();
        $provider_id = auth()->user()->id;
        if ($provider_id) {
            if ($req->ajax()) {
                if ($this->authUser->type != 0) {
                    $items = Item::where('admin_id',$this->authUser->id)->pluck('id');
                    $data = Review::with('user')->whereIn('item_id', $items)->latest()->get();
                }else{
                    $data=Review::with('user')->get();
                }
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('item', function($row){
                                $btn = __('Deleted');
                                if (isset($row['item']['name'])) {
                                    $btn = '<a href="'.route('admin.item.show',['item'=>$row['item_id']]).'" class="text-primary">'.$row['item']['name'].'</a>';
                                }
                                return $btn;
                        })
                       ->addColumn('user', function($row){
                                $btn = __('Deleted');
                                if (isset($row['user']['name'])) {
                                    $btn = '<a href="'.route('admin.customer.show',['customer'=>$row['user']]).'" class="text-primary">'.$row['user']['name'].'</a>';
                                }
                                return $btn;
                        })
                    ->addColumn('action', function($row){
                        $statusToggleBtn = '';

                        $viewBtn = '';
                        $deleteBtn = '';
                        if (isset($row)) {
                            $viewBtn = '<a title="VIEW" href="'.route('admin.review.show',['review'=>$row->id]).'" class="btn btn-secondary btn-sm"><i class="fa fa-info-circle"></i> '.__('View').' </a> ';
                                $statusLabel = (isset($row->status) && $row->status == true)?__('Active'):__('InActive');
                                $statusTitle = (isset($row->status) && $row->status == true)?__('InActive'):__('Active');
                                $statusClass = (isset($row->status) && $row->status == true)?'btn-danger':'btn-success';
                                if ($this->authUser->type == 0) {
                                    $statusToggleBtn = '<a class="btn ' . $statusClass . ' btn-sm" title="' . $statusTitle . '" href="' . route('admin.review.toggle', ['review' => $row->id]) . '">' . $statusTitle . '</a> ';
                                }
                        }
                        return $statusToggleBtn.$viewBtn;
                    })
                    ->addColumn('status', function($row){
                            if ($row['status'] == 1){
                                return __('Active');
                            }
                            else{
                                return __("InActive");
                            }
                        })
                        ->rawColumns(['item','user','action'])
                        ->make(true);
            }
        }

        return view('admin.review.index',compact('reviews','provider_id'));
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        if ($review) {
            if (!(checkValidRestId($this->authUser,$review->item_id))) {
                return redirect('/admin')->with('toast-error', __('Not Authorised'));
            }
            return view('admin.review.show',compact('review'));
        }
        return redirect()->back()->with('toast-error', 'Review not found!');
    }

    /**
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function toggle(Review $review)
    {

        $reviews = collect();
        $provider_id = $this->authUser->id;
        if ($review) {
            if (!(checkValidRestId($this->authUser,$provider_id))) {
                return redirect('/admin')->with('toast-error', __('Not Authorised'));
            }
            if ($review->status == 1){
               $review->status=0;
               $review->save();
            }else{
                $review->status=1;
                $review->save();
            }
            return redirect()->route('admin.review.index',['provider_id' => $provider_id]);
        }
        return redirect()->back()->with('toast-error', 'Review not found!');
    }
}
