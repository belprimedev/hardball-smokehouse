use App\Models\MenuItem;

Route::get('/dessert-items', function () {
    return MenuItem::whereHas('category', function ($q) {
        $q->where('name', 'Dessert');
    })->get();
}); 