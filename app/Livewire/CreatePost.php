<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\Section;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    // Location fields
    public $country_id;
    public $state_id;
    public $city_id;

    // Category fields
    public $section_id;
    public $category_id;

    // Post fields
    public $title;
    public $description;
    public $age;
    public $email;
    public $phone;

    // Media
    public $media = [];

    // Dynamic data
    public $states = [];
    public $cities = [];
    public $categories = [];
    public $selectedCountry;
    public $selectedCity;

    public function mount()
    {
        $this->email = Auth::user()->email ?? '';
    }

    public function updatedCountryId($value)
    {
        $this->selectedCountry = Country::find($value);
        $this->states = State::where('country_id', $value)->orderBy('order')->orderBy('name')->get();
        $this->state_id = null;
        $this->cities = [];
        $this->city_id = null;
        $this->selectedCity = null;
    }

    public function updatedStateId($value)
    {
        $this->cities = City::where('state_id', $value)->orderBy('order')->orderBy('name')->get();
        $this->city_id = null;
        $this->selectedCity = null;
    }

    public function updatedCityId($value)
    {
        $this->selectedCity = City::with('state.country')->find($value);
    }

    public function updatedSectionId($value)
    {
        $this->categories = Category::where('section_id', $value)->orderBy('name')->get();
        $this->category_id = null;
    }

    public function submit()
    {
        $this->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'section_id' => 'required|exists:sections,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'age' => 'required|integer|min:1|max:150',
            'email' => 'required|email',
            'phone' => 'required|string',
            'media.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Determine payment status based on city's effective post type
        $city = City::find($this->city_id);
        $paymentStatus = $city->isPaid() ? 'pending' : 'free';

        // Create post
        $post = Post::create([
            'user_id' => Auth::id(),
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'section_id' => $this->section_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'age' => $this->age,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => 'pending',
            'payment_status' => $paymentStatus,
        ]);

        // Handle media uploads
        if ($this->media) {
            foreach ($this->media as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';

                $post->media()->create([
                    'file_path' => $path,
                    'type' => $type,
                ]);
            }
        }

        session()->flash('message', 'Post submitted successfully! It will be reviewed by our team.');

        return redirect()->route('posts.create');
    }

    public function render()
    {
        return view('livewire.create-post', [
            'countries' => Country::orderBy('order')->orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }
}
