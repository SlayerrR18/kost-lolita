<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::latest();

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

        $expenses = $query->paginate(15);

        // Calculate totals
        $totalExpense = Expense::getTotalExpense(
            $request->filled('start_date') ? $request->start_date : null,
            $request->filled('end_date') ? $request->end_date : null
        );

        return view('admin.finance.expense.index', [
            'expenses' => $expenses,
            'totalExpense' => $totalExpense,
            'categories' => Expense::getCategoryOptions(),
            'filters' => $request->only(['category', 'start_date', 'end_date']),
        ]);
    }


    public function create()
    {
        return view('admin.finance.expense.create', [
            'categories' => Expense::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:' . implode(',', array_keys(Expense::getCategoryOptions())),
            'payment_method' => 'required|in:' . implode(',', array_keys($this->getPaymentMethods())),
            'reference' => 'nullable|string|max:100',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle uploaded bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('expenses/bukti_transfer', 'public');
            $validated['bukti_transfer'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('admin.finance.expense.index')
                        ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show(Expense $expense)
    {
        return view('admin.finance.expense.show', [
            'expense' => $expense,
            'categories' => Expense::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function edit(Expense $expense)
    {
        return view('admin.finance.expense.edit', [
            'expense' => $expense,
            'categories' => Expense::getCategoryOptions(),
            'paymentMethods' => $this->getPaymentMethods(),
        ]);
    }


    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category' => 'required|in:' . implode(',', array_keys(Expense::getCategoryOptions())),
            'payment_method' => 'required|in:' . implode(',', array_keys($this->getPaymentMethods())),
            'reference' => 'nullable|string|max:100',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Handle uploaded bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            if ($expense->bukti_transfer) {
                Storage::disk('public')->delete($expense->bukti_transfer);
            }
            $path = $request->file('bukti_transfer')->store('expenses/bukti_transfer', 'public');
            $validated['bukti_transfer'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('admin.finance.expense.show', $expense)
                        ->with('success', 'Pengeluaran berhasil diperbarui.');
    }


    public function destroy(Expense $expense)
    {

        if ($expense->bukti_transfer) {
            Storage::disk('public')->delete($expense->bukti_transfer);
        }

        $expense->delete();

        return redirect()->route('admin.finance.expense.index')
                        ->with('success', 'Pengeluaran berhasil dihapus.');
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
