<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\product_warehouse;
use App\Models\ProductLocation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    //----------- GET ALL  Locations --------------\\

    public function index(Request $request)
    {
        // $this->authorizeForUser($request->user('api'), 'view', Location::class);

        // How many items do you want to display.
        $perPage = $request->limit;
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;
        $order = $request->SortField;
        $dir = $request->SortType;

        $warehouses = Location::where('deleted_at', '=', null)

            // Search With Multiple Param
            ->where(function ($query) use ($request) {
                return $query->when($request->filled('search'), function ($query) use ($request) {
                    return $query->where('name', 'LIKE', "%{$request->search}%")
                        ->orWhere('mobile', 'LIKE', "%{$request->search}%")
                        ->orWhere('country', 'LIKE', "%{$request->search}%")
                        ->orWhere('city', 'LIKE', "%{$request->search}%")
                        ->orWhere('zip', 'LIKE', "%{$request->search}%")
                        ->orWhere('email', 'LIKE', "%{$request->search}%");
                });
            });
        $totalRows = $warehouses->count();
        $warehouses = $warehouses->offset($offSet)
            ->limit($perPage)
            ->orderBy($order, $dir)
            ->get();

        return response()->json([
            'warehouses' => $warehouses,
            'totalRows' => $totalRows,
        ]);
    }

    //----------- Store new location --------------\\

    public function store(Request $request)
    {
        // $this->authorizeForUser($request->user('api'), 'create', Location::class);

        request()->validate([
            'name' => 'required',
        ]);

        \DB::transaction(function () use ($request) {

            $Warehouse = new Location();
            $Warehouse->name = $request['name'];
            $Warehouse->mobile = $request['mobile'];
            $Warehouse->country = $request['country'];
            $Warehouse->city = $request['city'];
            $Warehouse->zip = $request['zip'];
            $Warehouse->email = $request['email'];
            $Warehouse->save();

            $products = Product::where('deleted_at', '=', null)
                ->pluck('id')
                ->toArray();

            if ($products) {
                foreach ($products as $product) {
                    $product_warehouse = [];
                    $Product_Variants = ProductVariant::where('product_id', $product)
                        ->where('deleted_at', null)
                        ->get();

                    if ($Product_Variants->isNotEmpty()) {
                        foreach ($Product_Variants as $product_variant) {

                            $product_warehouse[] = [
                                'product_id' => $product,
                                'location_id' => $Warehouse->id,
                                'product_variant_id' => $product_variant->id,
                            ];
                        }
                    } else {
                        $product_warehouse[] = [
                            'product_id' => $product,
                            'location_id' => $Warehouse->id,
                            'product_variant_id' => null,
                        ];
                    }

                    ProductLocation::insert($product_warehouse);
                }
            }
        }, 10);

        return response()->json(['success' => true]);
    }

    //------------ function show -----------\\

    public function show($id)
    {
        //

    }

    //-----------Update location --------------\\

    public function update(Request $request, $id)
    {
        // $this->authorizeForUser($request->user('api'), 'update', Location::class);

        request()->validate([
            'name' => 'required',
        ]);

        Location::whereId($id)->update([
            'name' => $request['name'],
            'mobile' => $request['mobile'],
            'country' => $request['country'],
            'city' => $request['city'],
            'zip' => $request['zip'],
            'email' => $request['email'],
        ]);
        return response()->json(['success' => true]);
    }

    //----------- Delete  location --------------\\

    public function destroy(Request $request, $id)
    {
        // $this->authorizeForUser($request->user('api'), 'delete', Location::class);

        \DB::transaction(function () use ($id) {

            Location::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            // product_warehouse::where('warehouse_id', $id)->update([
            //     'deleted_at' => Carbon::now(),
            // ]);
        }, 10);

        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {

        // $this->authorizeForUser($request->user('api'), 'delete', Location::class);

        \DB::transaction(function () use ($request) {
            $selectedIds = $request->selectedIds;
            foreach ($selectedIds as $warehouse_id) {
                Location::whereId($warehouse_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);

                // product_warehouse::where('warehouse_id', $warehouse_id)->update([
                //     'deleted_at' => Carbon::now(),
                // ]);
            }
        }, 10);

        return response()->json(['success' => true]);
    }

    //----------- GET ALL  location --------------\\

    public function Get_Locations()
    {
        $Warehouses = Location::where('deleted_at', '=', null)->get();
        return response()->json($Warehouses);
    }
}
