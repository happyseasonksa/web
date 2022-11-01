<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $path = $request->path();
            $arr = explode('/', $path);
            $entity = null;
            $entityArr = typeOfAdminsEntity();
            $type = null;
            $typeArr = ['create','store','show','edit','status','update','destroy'];
            if (count($arr) > 1) {
                $entity = $arr[1];
                switch ($entity) {
                    case 'sub-category':
                        $entity = "subcategory";
                        break;
                    case 'menu-item':
                        $entity = "item";
                        break;
                    case 'ingredient':
                        $entity = "inventory";
                        break;
                }
                if ($entity === 'table-category' || $entity === 'table-sub-category') {
                    if (!Auth::user()->checkAdminAccess('table',null,'add') && !Auth::user()->checkAdminAccess('table',null,'update')) {
                        return redirect('/admin')->with('toast-error', __('Not Authorised'));
                    }
                }
                elseif (count($arr) === 2 && array_search($entity,$entityArr) !== false) {
                    if (!Auth::user()->checkAdminAccess($entity,null,'view')) {
                        return redirect('/admin')->with('toast-error', __('Not Authorised'));
                    }
                }
                elseif (count($arr) > 2 && array_search($entity,$entityArr) !== false) {
                    $type = $arr[2];
                    if (array_search($type,$typeArr) !== false) {
                        if ($type === 'create' || $type === 'store') {
                            $type = 'add';
                        }
                        elseif ($type === 'edit' || $type === 'status' || $type === 'update') {
                            $type = 'update';
                        }
                        elseif ($type === 'show') {
                            $type = 'view';
                        }
                        elseif ($type === 'destroy') {
                            $type = 'delete';
                        }
                        if (!Auth::user()->checkAdminAccess($entity,null,$type)) {
                            return redirect('/admin')->with('toast-error', __('Not Authorised'));
                        }
                    }
                }
            }
            return $next($request);
        }
        return redirect('/admin')->with('error', 'Invalid Request');
    }
}
