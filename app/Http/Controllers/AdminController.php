<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_ebooks' => Ebook::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('statut', 'payee')->sum('montant_total'),
            'recent_orders' => Order::with('orderItems')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Ebooks
    public function ebooks()
    {
        $ebooks = Ebook::with('category')->latest()->paginate(20);
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function ebooksCreate()
    {
        $categories = Category::all();
        return view('admin.ebooks.create', compact('categories'));
    }

    public function ebooksStore(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'pages' => 'nullable|integer',
            'langue' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['titre']);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ebooks/images', 'public');
        }
        
        if ($request->hasFile('fichier_pdf')) {
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('ebooks/pdfs', 'public');
        }

        $validated['actif'] = $request->has('actif');
        $validated['bestseller'] = $request->has('bestseller');
        $validated['nouveau'] = $request->has('nouveau');

        Ebook::create($validated);

        return redirect()->route('admin.ebooks')->with('success', 'Ebook créé avec succès');
    }

    public function ebooksEdit($id)
    {
        $ebook = Ebook::findOrFail($id);
        $categories = Category::all();
        return view('admin.ebooks.edit', compact('ebook', 'categories'));
    }

    public function ebooksUpdate(Request $request, $id)
    {
        $ebook = Ebook::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'prix_promo' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'pages' => 'nullable|integer',
            'langue' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
            'fichier_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['titre']);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ebooks/images', 'public');
        }
        
        if ($request->hasFile('fichier_pdf')) {
            $validated['fichier_pdf'] = $request->file('fichier_pdf')->store('ebooks/pdfs', 'public');
        }

        $validated['actif'] = $request->has('actif');
        $validated['bestseller'] = $request->has('bestseller');
        $validated['nouveau'] = $request->has('nouveau');

        $ebook->update($validated);

        return redirect()->route('admin.ebooks')->with('success', 'Ebook modifié avec succès');
    }

    public function ebooksDestroy($id)
    {
        Ebook::findOrFail($id)->delete();
        return redirect()->route('admin.ebooks')->with('success', 'Ebook supprimé avec succès');
    }

    // Categories
    public function categories()
    {
        $categories = Category::withCount('ebooks')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        return view('admin.categories.create');
    }

    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);
        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Catégorie créée avec succès');
    }

    public function categoriesEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);
        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Catégorie modifiée avec succès');
    }

    public function categoriesDestroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories')->with('success', 'Catégorie supprimée avec succès');
    }

    // Orders
    public function orders()
    {
        $orders = Order::with('orderItems')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function ordersShow($id)
    {
        $order = Order::with('orderItems.ebook')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Payment Settings
    public function paymentSettings()
    {
        return view('admin.payment-settings');
    }

    public function paymentSettingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'payment_provider' => 'required|in:paydunya,fedapay,kkiapay',
            'paydunya_master_key' => 'nullable|string',
            'paydunya_private_key' => 'nullable|string',
            'paydunya_token' => 'nullable|string',
            'paydunya_mode' => 'required|in:test,live',
            'fedapay_public_key' => 'nullable|string',
            'fedapay_secret_key' => 'nullable|string',
            'fedapay_mode' => 'required|in:test,live',
            'kkiapay_public_key' => 'nullable|string',
            'kkiapay_private_key' => 'nullable|string',
            'kkiapay_secret' => 'nullable|string',
            'kkiapay_sandbox' => 'required|in:true,false',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\PaymentSetting::set($key, $value);
        }

        return redirect()->route('admin.payment-settings')->with('success', 'Configuration de paiement mise à jour');
    }
}
