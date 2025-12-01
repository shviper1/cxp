<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\Section;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    public function index()
    {
        // eager load states and cities
        $countries = Country::with('states.cities')->get();

        return view('welcome', compact('countries'));
    }

    public function createPost(Request $request)
    {
        $countries = Country::orderBy('order')->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        // Pre-populate form if city is specified in URL
        $selectedCity = null;
        $selectedCountry = null;
        $states = collect();
        $cities = collect();
        $categories = collect();

        // Handle dynamic loading based on request parameters
        if ($request->has('country_id')) {
            $states = State::where('country_id', $request->country_id)->orderBy('order')->orderBy('name')->get();
        }

        if ($request->has('state_id')) {
            $cities = City::where('state_id', $request->state_id)->orderBy('order')->orderBy('name')->get();
        }

        if ($request->has('section_id')) {
            $categories = Category::where('section_id', $request->section_id)->orderBy('name')->get();
        }

        // Pre-populate if city is specified directly (from welcome page)
        if ($request->has('city')) {
            $cityId = $request->get('city');
            $city = City::with('state.country')->find($cityId);

            if ($city) {
                $selectedCity = $city;
                $selectedCountry = $city->state->country;
                $states = State::where('country_id', $city->state->country_id)->orderBy('order')->orderBy('name')->get();
                $cities = City::where('state_id', $city->state_id)->orderBy('order')->orderBy('name')->get();
            }
        }

        return view('create-post', compact(
            'countries',
            'sections',
            'selectedCity',
            'selectedCountry',
            'states',
            'cities',
            'categories'
        ));
    }

    public function storePost(Request $request)
    {
        $request->validate([
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
        $city = City::find($request->city_id);
        $paymentStatus = $city->isPaid() ? 'pending' : 'free';

        // Create post
        $post = Post::create([
            'user_id' => Auth::id(),
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'section_id' => $request->section_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'age' => $request->age,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending',
            'payment_status' => $paymentStatus,
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $type = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';

                $post->media()->create([
                    'file_path' => $path,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('posts.create')->with('message', 'Post submitted successfully! It will be reviewed by our team.');
    }
}
