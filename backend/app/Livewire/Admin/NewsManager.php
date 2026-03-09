<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class NewsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or;
    public $content_en, $content_am, $content_or;
    public $category = 'General';
    public $status = 'published';
    public $published_at;
    public $thumbnail;
    public $search = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string',
        'title_or' => 'nullable|string',
        'content_en' => 'required|string',
        'content_am' => 'nullable|string',
        'content_or' => 'nullable|string',
        'category' => 'required|string',
        'status' => 'required|in:published,draft',
        'thumbnail' => 'nullable|image|max:4096',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'content_en', 'content_am', 'content_or', 'category', 'status', 'thumbnail', 'published_at']);
        $this->category = 'General';
        $this->status = 'published';
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $post = Post::findOrFail($id);
        $this->editingId = $id;
        $this->title_en = $post->title_en;
        $this->title_am = $post->title_am;
        $this->title_or = $post->title_or;
        $this->content_en = $post->content_en;
        $this->content_am = $post->content_am;
        $this->content_or = $post->content_or;
        $this->category = $post->category;
        $this->status = $post->status;
        $this->published_at = $post->published_at?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title_en' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'content_en' => $this->content_en,
            'content_am' => $this->content_am,
            'content_or' => $this->content_or,
            'category' => $this->category,
            'status' => $this->status,
            'published_at' => $this->published_at ?: now(),
        ];
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('uploads', 'public_uploads');
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            Post::findOrFail($this->editingId)->update($data);
        } else {
            Post::create(array_merge($data, ['admin_id' => auth()->id()]));
        }
        $this->showModal = false;
        session()->flash('success', 'News article saved.');
    }

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
        session()->flash('success', 'Article deleted.');
    }

    public function render()
    {
        $news = Post::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.news-manager', ['news' => $news]);
    }
}
