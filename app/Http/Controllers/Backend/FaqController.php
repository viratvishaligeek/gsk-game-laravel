<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::query()->with('game')->orderBy('sort_order')->orderBy('id')->get();
        $pageName = 'FAQs Management';
        return view('backend.faqs.index', compact('faqs', 'pageName'));
    }

    public function create(): View
    {
        $pageName = 'Create FAQ';
        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('name')->get(['id', 'name']);
        return view('backend.faqs.create', compact('pageName', 'games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'platform' => ['required', 'in:web,app,both'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
            'page_slug' => ['nullable', 'string', 'max:150', 'regex:/^[A-Za-z0-9-]+$/'],
            'game_id' => ['nullable', 'integer', Rule::exists('games', 'id')->where(fn ($query) => $query->where('status', 'active')->whereNull('deleted_at')->where('site_key', config('sites.current_key', config('sites.default', 'galisattakings'))))],
        ]);
        Faq::create($validated);
        $this->clearFaqCache();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        $pageName = 'Edit FAQ';
        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('name')->get(['id', 'name']);
        return view('backend.faqs.edit', compact('faq', 'pageName', 'games'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'platform' => ['required', 'in:web,app,both'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
            'page_slug' => ['nullable', 'string', 'max:150', 'regex:/^[A-Za-z0-9-]+$/'],
            'game_id' => ['nullable', 'integer', Rule::exists('games', 'id')->where(fn ($query) => $query->where('status', 'active')->whereNull('deleted_at')->where('site_key', config('sites.current_key', config('sites.default', 'galisattakings'))))],
        ]);
        $faq->update($validated);
        $this->clearFaqCache();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();
        $this->clearFaqCache();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    private function clearFaqCache(): void
    {
        $siteKey = (string) config('sites.current_key', config('sites.default', 'galisattakings'));
        Cache::forget("support-faqs:{$siteKey}:web");
        Cache::forget("support-faqs:{$siteKey}:app");
    }
}
