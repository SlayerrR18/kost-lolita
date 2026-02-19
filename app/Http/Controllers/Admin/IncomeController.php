<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            $query->byDateRange($startDate, $endDate);
        }

        $incomes = $query->paginate(15);

        // Calculate totals
        $totalIncome = Income::getTotalIncome(
            $request->filled('start_date') ? $request->start_date : null,
            $request->filled('end_date') ? $request->end_date : null
        );

        return view('admin.finance.income.index', [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
            'categories' => Income::getCategoryOptions(),
            'filters' => $request->only(['category', 'start_date', 'end_date']),
        ]);
    }


    public function create()
    {
        return view('admin.finance.income.create', [
            'categories' => Income::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:' . implode(',', array_keys(Income::getCategoryOptions())),
            'payment_method' => 'required|in:' . implode(',', array_keys($this->getPaymentMethods())),
            'reference' => 'nullable|string|max:100',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle uploaded bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('incomes/bukti_transfer', 'public');
            $validated['bukti_transfer'] = $path;
        }

        Income::create($validated);

        return redirect()->route('admin.finance.income.index')
                        ->with('success', 'Pendapatan berhasil ditambahkan.');
    }


    public function show(Income $income)
    {
        return view('admin.finance.income.show', [
            'income' => $income,
            'categories' => Income::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function edit(Income $income)
    {
        return view('admin.finance.income.edit', [
            'income' => $income,
            'categories' => Income::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:' . implode(',', array_keys(Income::getCategoryOptions())),
            'payment_method' => 'required|in:' . implode(',', array_keys($this->getPaymentMethods())),
            'reference' => 'nullable|string|max:100',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle uploaded bukti transfer (replace old file if present)
        if ($request->hasFile('bukti_transfer')) {
            // delete old file
            if ($income->bukti_transfer) {
                Storage::disk('public')->delete($income->bukti_transfer);
            }
            $path = $request->file('bukti_transfer')->store('incomes/bukti_transfer', 'public');
            $validated['bukti_transfer'] = $path;
        }

        $income->update($validated);

        return redirect()->route('admin.finance.income.show', $income)
                        ->with('success', 'Pendapatan berhasil diperbarui.');
    }


    public function destroy(Income $income)
    {
        // delete associated file
        if ($income->bukti_transfer) {
            Storage::disk('public')->delete($income->bukti_transfer);
        }

        $income->delete();

        return redirect()->route('admin.finance.income.index')
                        ->with('success', 'Pendapatan berhasil dihapus.');
    }

    private function getPaymentMethods()
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'check' => 'Cek',
            'other' => 'Lain-lain',
        ];
    }
}
