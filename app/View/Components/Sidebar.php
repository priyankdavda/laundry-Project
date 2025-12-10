<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Models\OrderStatus;


class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $userCount = User::count();
        view()->share('userCount',$userCount);

        $RoleCount = Role::count();
        view()->share('RoleCount',$RoleCount);

        $PermissionCount = Permission::count();
        view()->share('PermissionCount',$PermissionCount);

        $CategoryCount = Category::count();
        view()->share('CategoryCount',$CategoryCount);

        $SubCategoryCount = SubCategory::count();
        view()->share('SubCategoryCount',$SubCategoryCount);

        $CollectionCount = Collection::count();
        view()->share('CollectionCount',$CollectionCount);

        $ProductCount = Product::count();
        view()->share('ProductCount',$ProductCount);

        $OrderstatusCount = OrderStatus::count();
        view()->share('OrderstatusCount',$OrderstatusCount);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
