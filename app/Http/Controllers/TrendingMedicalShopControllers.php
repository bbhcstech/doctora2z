<?php

namespace App\Http\Controllers;
use App\Models\TrendingMedicalShop;
use App\Models\Medicashop;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrendingMedicalShopControllers extends Controller
{
     public function index()
    {
        $trendingshop  = TrendingMedicalShop::all();

        return view('admin.trending-medi-shop.index', compact('trendingshop'));
    }
    public function create()
    {
        $shops = Medicashop::all();

        return view('admin.trending-medi-shop.create', compact('shops'));
    }


    public function store(Request $request)
    {
        $name = $request->input('name');

        $shopId = $request->input('shop_id');
  
        // Save or process the data
        TrendingMedicalShop::create([
            'name' => $name,
            'shop_id' => $shopId,
        ]);

       
         return redirect()->route('trending-shop.index')->with('success', 'Trending Medical Shop added successfully!');
    }
    
   
    
     // Remove the specified resource from storage
  public function destroy($id)
  {
 
    $trendingshop = TrendingMedicalShop::findOrFail($id);
    
    // Delete the doctor
    $trendingshop->delete();

    // Redirect back with a success message
    return redirect()->route('trending-shop.index')->with('success', 'Trending Medical Shop deleted successfully.');

  }
  
}
