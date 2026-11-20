<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'menu_id' => 'required|exists:menus,id',
        'qty'     => 'required|integer|min:1|max:100',
    ]);

    $menu = Menu::findOrFail($request->menu_id);
    $qty  = (int) $request->qty;

    $cart = session()->get('cart', []);

    if (isset($cart[$menu->id])) {
        $cart[$menu->id]['qty'] += $qty;
    } else {
        $cart[$menu->id] = [
            'id'       => $menu->id,
            'name'     => $menu->name,
            'category' => $menu->category, 
            'price'    => $menu->price,
            'image'    => $menu->image,
            'qty'      => $qty,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->back()->with('success', "{$qty}x {$menu->name} berhasil ditambahkan ke keranjang!");
 }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        if ($request->action === 'increase') {
            $cart[$id]['qty']++;
        } elseif ($request->action === 'decrease') {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);
    }

    return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }
}
