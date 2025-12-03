<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Post;
use App\Models\Section;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $countries = Country::with('states.cities')->orderBy('order')->orderBy('name')->get();
        $sections = Section::with('categories')->orderBy('name')->get();

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

        // Create post (allow anonymous posting)
        $post = Post::create([
            'user_id' => Auth::id(), // Can be null for anonymous posts
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

        $message = 'Post submitted successfully! It will be reviewed by our team.';

        // If user is logged in, offer to go to dashboard
        if (Auth::check()) {
            $message .= ' <a href="'.route('dashboard.posts').'" class="text-indigo-600 hover:text-indigo-800">Manage your posts</a>';
        }

        return redirect()->route('posts.create')->with('message', $message);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $posts = $user ? Post::where('user_id', $user->id)->latest()->paginate(10) : collect();

        return view('dashboard.index', compact('posts'));
    }

    public function userPosts()
    {
        $user = Auth::user();
        $posts = $user ? Post::where('user_id', $user->id)->latest()->paginate(15) : collect();

        return view('dashboard.posts', compact('posts'));
    }

    public function profile()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $postsCount = $user->posts()->whereNotNull('user_id')->count();
        $pendingPosts = $user->posts()->where('status', 'pending')->count();
        $approvedPosts = $user->posts()->where('status', 'approved')->count();
        $rejectedPosts = $user->posts()->where('status', 'rejected')->count();

        return view('dashboard.profile', compact(
            'postsCount',
            'pendingPosts',
            'approvedPosts',
            'rejectedPosts'
        ));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'occupation' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
        ]);

        // Only update fields that exist in the database
        $updateData = [];
        $allowedFields = ['name', 'email', 'phone', 'date_of_birth', 'gender', 'occupation', 'bio', 'address', 'city', 'state', 'zip_code', 'country'];

        foreach ($allowedFields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        Auth::user()->update($updateData);

        return redirect()->route('dashboard.profile')->with('message', 'Profile updated successfully!');
    }

    public function verification()
    {
        $user = Auth::user();

        return view('dashboard.verification', compact('user'));
    }

    public function submitVerification(Request $request)
    {
        $request->validate([
            'id_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'selfie' => 'required|file|mimes:jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Handle ID document upload
        if ($request->hasFile('id_document')) {
            $idPath = $request->file('id_document')->store('verification/id_documents', 'public');
            $user->id_document_path = $idPath;
        }

        // Handle selfie upload
        if ($request->hasFile('selfie')) {
            $selfiePath = $request->file('selfie')->store('verification/selfies', 'public');
            $user->selfie_path = $selfiePath;
        }

        $user->verification_status = 'pending';
        $user->save();

        return redirect()->route('dashboard.verification')->with('message', 'Verification documents submitted successfully! Our team will review them within 24-48 hours.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only(['email', 'password']), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'))->with('message', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'verification_status' => 'unverified',
        ]);

        Auth::login($user);

        return redirect(route('dashboard.index'))->with('message', 'Account created successfully! Welcome to our platform.');
    }

    public function browsePosts(Request $request)
    {
        $query = Post::with(['user', 'country', 'state', 'city', 'section', 'category', 'media'])
            ->where('status', 'approved');

        // Apply filters
        if ($request->country) {
            $query->where('country_id', $request->country);
        }
        if ($request->state) {
            $query->where('state_id', $request->state);
        }
        if ($request->city) {
            $query->where('city_id', $request->city);
        }
        if ($request->section) {
            $query->where('section_id', $request->section);
        }
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $posts = $query->latest()->paginate(12);

        // Get filter options
        $countries = Country::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();

        return view('posts.index', compact('posts', 'countries', 'sections'));
    }

    public function showPost(Post $post)
    {
        // Only show approved posts to public
        if ($post->status !== 'approved' && ! Auth::check()) {
            abort(404);
        }

        $post->load(['user', 'country', 'state', 'city', 'section', 'category', 'media']);

        return view('posts.show', compact('post'));
    }

    public function showCountrySections(Country $country)
    {
        $sections = Section::with(['categories' => function ($query) use ($country) {
            $query->whereHas('posts', function ($postQuery) use ($country) {
                $postQuery->where('country_id', $country->id)
                    ->where('status', 'approved');
            });
        }])->get();

        return view('locations.country', compact('country', 'sections'));
    }

    public function showSectionCategories(Country $country, Section $section)
    {
        $categories = Category::whereHas('posts', function ($query) use ($country, $section) {
            $query->where('country_id', $country->id)
                ->where('section_id', $section->id)
                ->where('status', 'approved');
        })->withCount(['posts' => function ($query) use ($country, $section) {
            $query->where('country_id', $country->id)
                ->where('section_id', $section->id)
                ->where('status', 'approved');
        }])->get();

        return view('locations.section', compact('country', 'section', 'categories'));
    }

    public function showCategoryPosts(Country $country, Section $section, Category $category)
    {
        $posts = Post::with(['user', 'country', 'state', 'city', 'section', 'category', 'media'])
            ->where('country_id', $country->id)
            ->where('section_id', $section->id)
            ->where('category_id', $category->id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('locations.category-posts', compact('country', 'section', 'category', 'posts'));
    }
}
