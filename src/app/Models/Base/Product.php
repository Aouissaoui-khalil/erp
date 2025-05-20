<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit_id',
        'purchase_price',
        'selling_price',
        'tax_rate',
        'track_inventory',
        'track_serial_number',
        'track_batch',
        'track_expiry',
        'min_stock_level',
        'barcode',
        'image_path',
        'active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:3',
        'selling_price' => 'decimal:3',
        'tax_rate' => 'decimal:2',
        'track_inventory' => 'boolean',
        'track_serial_number' => 'boolean',
        'track_batch' => 'boolean',
        'track_expiry' => 'boolean',
        'active' => 'boolean',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(\App\Models\Inventory\StockMovement::class);
    }

    public function serialNumbers()
    {
        return $this->hasMany(\App\Models\Inventory\SerialNumber::class);
    }

    public function batches()
    {
        return $this->hasMany(\App\Models\Inventory\Batch::class);
    }

    // Méthodes
    public function getCurrentStock()
    {
        // Logique pour calculer le stock actuel
        return $this->stockMovements()->sum('quantity');
    }

    public function getStockByWarehouse($warehouseId)
    {
        // Logique pour calculer le stock par entrepôt
        return $this->stockMovements()->where('warehouse_id', $warehouseId)->sum('quantity');
    }
}
