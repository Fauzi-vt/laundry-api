<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    /** Simpan pelanggan baru */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Pelanggan '{$request->name}' berhasil ditambahkan.");
    }

    /** Update data pelanggan */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => "required|email|unique:users,email,{$id}",
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Data pelanggan '{$user->name}' berhasil diperbarui.");
    }

    /** Hapus pelanggan */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Pelanggan '{$name}' berhasil dihapus.");
    }

    /** Riwayat transaksi pelanggan (JSON untuk modal) */
    public function transactions($id)
    {
        $user = User::with(['transactions.details.service'])->findOrFail($id);

        return response()->json([
            'customer'     => ['name' => $user->name, 'email' => $user->email, 'phone' => $user->phone],
            'transactions' => $user->transactions->map(function ($t) {
                return [
                    'id'           => $t->id,
                    'invoice_code' => $t->invoice_code,
                    'status'       => $t->status,
                    'total_price'  => $t->total_price,
                    'created_at'   => $t->created_at->format('d M Y, H:i'),
                    'details'      => $t->details->map(fn($d) => [
                        'service' => $d->service->name ?? '-',
                        'unit'    => $d->service->unit ?? '',
                        'qty'     => $d->quantity,
                        'subtotal'=> $d->subtotal,
                    ]),
                ];
            }),
        ]);
    }
}
