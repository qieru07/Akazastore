<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('payment_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:payment_methods',
            'type' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/payments'), $imageName);
            $data['image'] = $imageName;
        }

        PaymentMethod::create($data);

        return redirect()->route('payment-methods.index')->with('success', 'Metode pembayaran berhasil ditambahkan!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:payment_methods,code,' . $paymentMethod->id,
            'type' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($paymentMethod->image && file_exists(public_path('images/payments/' . $paymentMethod->image))) {
                unlink(public_path('images/payments/' . $paymentMethod->image));
            }
            
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/payments'), $imageName);
            $data['image'] = $imageName;
        }

        $paymentMethod->update($data);

        return redirect()->route('payment-methods.index')->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->image && file_exists(public_path('images/payments/' . $paymentMethod->image))) {
            unlink(public_path('images/payments/' . $paymentMethod->image));
        }
        $paymentMethod->delete();
        return redirect()->route('payment-methods.index')->with('success', 'Metode pembayaran berhasil dihapus!');
    }
}
