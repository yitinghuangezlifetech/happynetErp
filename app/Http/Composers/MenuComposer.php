<?php
namespace App\Http\Composers;

use Auth;
use App\Models\Menu;
use App\Models\RolePermission;
use Illuminate\View\View;

class MenuComposer {
    public function compose(View $view) {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user->role) {
                if ($user->role->permissions->count() > 0) {
                    $arr = [];

                    foreach ($user->role->permissions as $permission) {
                        if (!in_array($permission->menu_id, $arr)) {
                            array_push($arr, $permission->menu_id);
                        }
                    }

                    $childs = app(Menu::class)
                        ->where('no_show', 2)
                        ->whereIn('id', $arr)
                        ->get();

                    if ($childs->count() > 0) {
                        $arr = [];
                        foreach ($childs as $child) {
                            if (!empty($child->parent_id)) {
                                if (!in_array($child->parent_id, $arr)) {
                                    array_push($arr, $child->parent_id);
                                }
                            } else {
                                if (!empty($child->slug)) {
                                    if (!in_array($child->id, $arr)) {
                                        array_push($arr, $child->id);
                                    }
                                }
                            }
                        }

                        $menus = app(Menu::class)
                            ->where('no_show', 2)
                            ->whereIn('id', $arr)
                            ->orderBy('sort', 'ASC')
                            ->get();

                        if ($menus->count() > 0) {
                            $view->with('menus', $menus);
                        } else {
                            $view->with('menus', []);
                        }
                    } else {
                        $view->with('menus', []);
                    }
                } else {
                    $view->with('menus', []);
                }
            } else {
                $view->with('menus', []);
            }
        } else {
            $view->with('menus', []);
        }
    }
}