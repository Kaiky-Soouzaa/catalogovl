<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class ProductStats extends Widget
{
    protected static string $view = 'filament.widgets.product-stats';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'totalProducts' => Product::count(),
            'onSaleProducts' => Product::where('on_sale', true)->count(),
            'inStockProducts' => Product::where('in_stock', true)->count(),
            'outOfStockProducts' => Product::where('in_stock', false)->count(),
            'featuredProducts' => Product::where('is_featured', true)->count(),
        ];
    }
}
