<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the payment accounts.
     */
    public function index()
    {
        $user = auth()->user();
        $query = PaymentAccount::query();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('provider_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        $paymentAccounts = $query->orderBy('type')
            ->orderBy('provider_name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payment_accounts', compact('user', 'paymentAccounts'));
    }

    /**
     * Store a newly created payment account in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'           => 'required|in:bank,ewallet',
            'provider_name'  => 'required|string|max:255',
            'provider_code'  => 'required|string|max:50|unique:payment_accounts,provider_code',
            'account_number' => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'is_active'      => 'nullable',
        ], [
            'provider_code.unique'   => 'Kode provider sudah digunakan.',
            'provider_name.required' => 'Nama provider wajib diisi.',
            'account_number.required'=> 'Nomor rekening/HP wajib diisi.',
            'account_name.required'  => 'Nama pemilik akun wajib diisi.',
        ]);

        $data = $request->only('type', 'provider_name', 'provider_code', 'account_number', 'account_name');
        $data['provider_code'] = strtolower(trim($data['provider_code']));
        $data['is_active'] = $request->has('is_active') ? true : false;

        PaymentAccount::create($data);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', "Akun pembayaran '{$request->provider_name}' berhasil ditambahkan.");
    }

    /**
     * Update the specified payment account in storage.
     */
    public function update(Request $request, $id)
    {
        $account = PaymentAccount::findOrFail($id);

        $request->validate([
            'type'           => 'required|in:bank,ewallet',
            'provider_name'  => 'required|string|max:255',
            'provider_code'  => "required|string|max:50|unique:payment_accounts,provider_code,{$id}",
            'account_number' => 'required|string|max:255',
            'account_name'   => 'required|string|max:255',
            'is_active'      => 'nullable',
        ], [
            'provider_code.unique'   => 'Kode provider sudah digunakan.',
            'provider_name.required' => 'Nama provider wajib diisi.',
            'account_number.required'=> 'Nomor rekening/HP wajib diisi.',
            'account_name.required'  => 'Nama pemilik akun wajib diisi.',
        ]);

        $data = $request->only('type', 'provider_name', 'provider_code', 'account_number', 'account_name');
        $data['provider_code'] = strtolower(trim($data['provider_code']));
        $data['is_active'] = $request->has('is_active') ? true : false;

        $account->update($data);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', "Akun pembayaran '{$account->provider_name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified payment account from storage.
     */
    public function destroy($id)
    {
        $account = PaymentAccount::findOrFail($id);
        $name = $account->provider_name;
        $account->delete();

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', "Akun pembayaran '{$name}' berhasil dihapus.");
    }
}
