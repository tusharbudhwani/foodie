<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Healthlabel;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'name' => "Sandeep Ahuja",
            'email' => "sandeepahuja@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'), //password is password
            'remember_token' => Str::random(10),
        ]);

        $healthlabels = [ "Low-Carb", "Low-Fat", "Low-Sodium","Sugar-Conscious","Low Sugar", "Low Potassium", "Kidney-Friendly", "Vegan", "Vegetarian", "Pescatarian", "Mediterranean", "Dairy-Free", "Sulfite-Free", "Alcohol-Free", "Kidney-Friendly", "Mediterranean", "Dairy-Free", "Gluten-Free", "Wheat-Free", "Egg-Free", "Peanut-Free", "Tree-Nut-Free", "Soy-Free", "Fish-Free", "Shellfish-Free", "Pork-Free", "Red-Meat-Free", "Crustacean-Free", "Celery-Free", "Mustard-Free", "Sesame-Free", "Lupine-Free", "Mollusk-Free", "Kosher"];

        foreach($healthlabels as $label) {
            Healthlabel::create([
                'name' => $label
            ]);
        }

        $categories = [
            "Fruits",
            "Vegetables",
            "Dairy",
            "Oils",
            "Breads",
            "Meat",
            "Seafood",
            "DryFruits",
            "Pulses",
            "Spices",
            "Others"
        ];
        $products = [
            // Fruits
            ["Mango", "Apple", "Banana", "Blueberries", "Casaba melon", "Dates", "Figs", "Grapes", "Guava",
            "Kiwi", "Orange", "Papaya", "Peach", "Pear", "Pineapple", "Plum",
            "Strawberries", "Watermelon"],

            // Vegetables
            ["Tomato", "Potato", "Broccoli", "Spinach", "Basil", "Lettuce", "Carrots","Lemon", "Pumpkin",
                "Red Bell Peppers", "Baby Corn", "Avocado", "Cabbage", "Cauliflower",
                "Celery", "Cucumbers", "Mushrooms", "Okra", "Onions", "Zucchini"],
            // Dairy
            ["Milk", "Yogurt", "Cheese", "Buttermilk", "Cottage cheese", "eggs","Ghee",
                "Whipped cream"],
            // Oils
            ["Canola oil", "Corn oil", "Sesame oil", "Soybean oil", "Olive oil",
                "Peanut oil", "Walnut oil", "Sunflower oil"],
            //Breads
            ["Bagel", "Brown bread", "Loaf", "Bun",
            "Tea Cake", "White bread", "Whole Wheat Bread"],
            //Meat
            ["Beef", "Chicken", "Mutton", "Pork"],
            //Seafood
            ["Fish", "Lobster", "Prawns", "Oyster", "Shark Meat"],
            //Dry Fruits
            ["Almond", "Apricot", "Cashewnuts", "Foxnuts",
                "Walnuts", "Peanuts", "Pistachios", "Raisins", "Saffron", "Chironji", "Watermelon Seeds"],
            //Pulses
            ["Besan", "Chana dal", "Kala chana", "Kabuli channa", "Masoor dal",
                "Rajma", "Soybean"],
            //Spices
            ["Basil seeds", "Black cardamom", "Black cumin seeds", "Black pepper", "Black salt",
                "Cinnamon", "Cloves", "Coriander powder", "Curry leaves", "Dry ginger",
                "Dry mango powder", "Garlic", "Jaggery", "Cocum,", "Mint", "Salt", "Rock Salt", "Tamarind",
                "Turmeric"],
            //Cereals and Others
            ["Oats", "Chickpeas", "Cocoa", "Coffee Bean", "Durian", "Tea", "Coffee"]


        ];
        foreach($categories as $key => $category){
            $categoryObject = Category::create([
                'name' => $category
            ]);

            foreach($products[$key] as $i => $product){
                Product::create([
                    'category_id' => $categoryObject->id,
                    'name' => $product,
                    'calories' => rand(10, 500),
                    'image' => 'images/products/'. strtolower($categoryObject->name) . '/' . str_replace(' ', '', strtolower($product)) . ".jpg"
                ]);
            }
        }

        Unit::create([
            'name' => 'killograms'
        ]);

        Unit::create([
            'name' => 'grams'
        ]);

        Unit::create([
            'name' => 'millilitres'
        ]);

        Unit::create([
            'name' => 'litres'
        ]);

        Unit::create([
            'name' => 'units'
        ]);
    }
}
