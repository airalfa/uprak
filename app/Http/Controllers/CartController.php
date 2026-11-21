<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'integer', 'exists:menus,id'],
            'qty'     => ['nullable', 'integer', 'min:1'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $tambahQty = $validated['qty'] ?? 1;

        $cart = session()->get('cart', []);

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['qty'] += $tambahQty;
        } else {
            $cart[$menu->id] = [
                'id'    => $menu->id,
                'name'  => $menu->name,
                'price' => $menu->price,
                'qty'   => $tambahQty,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', $menu->name . ' ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $menuId): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:increase,decrease'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$menuId])) {
            return redirect()->back();
        }

        if ($request->action === 'increase') {
            $cart[$menuId]['qty'] += 1;
        } elseif ($request->action === 'decrease') {
            $cart[$menuId]['qty'] -= 1;
            if ($cart[$menuId]['qty'] <= 0) {
                unset($cart[$menuId]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->back();
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
