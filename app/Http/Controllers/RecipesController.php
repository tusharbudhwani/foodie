<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Healthlabel;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    public function index()
    {
        return view('recipes.index');
    }

    public function create()
    {
        $healthlabels = Healthlabel::all();
        return view('recipes.create', compact(['healthlabels']));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'cuisineType' => 'required|max:100',
            'mealType' => 'required|max:100',
            'dishType' => 'required|max:100',
            'calories' => 'required|numeric',
            'recipe' => 'required',
            'healthlabel_id' => 'required|exists:healthlabels,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
        ];

        $this->validate($request, $rules);

        $image = $request->file('image')->store('images/recipes');

        $recipe = Recipe::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'cuisineType' => $request->cuisineType,
            'mealType' => $request->mealType,
            'dishType' => $request->dishType,
            'calories' => $request->calories,
            'recipe' => $request->recipe,
            'published_at' => $request->published_at,
            'image' => $image
        ]);

        $recipe->healthlabels()->attach($request->healthlabel_id);

        session()->flash('success', 'Recipe Created Successfully View It In Blog Section!');

        return redirect()->back();
    }

    public function show($id)
    {
        $recipe = $id;
        return view('recipes.show', compact(['recipe']));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function suggestRecipes(Request $request)
    {
        $rules = [
            'recipe_id' => 'required|exists:products,id'
        ];

        $this->validate($request, $rules);


    }

}
