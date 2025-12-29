<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'icon',
        'color',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'display_order',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relationships
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('display_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Accessors
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-category.png');
    }

    public function getFullPathAttribute(): string
    {
        $path = [];
        $category = $this;
        
        while ($category) {
            $path[] = $category->name;
            $category = $category->parent;
        }
        
        return implode(' > ', array_reverse($path));
    }

    public function getProductCountAttribute(): int
    {
        return $this->products()->where('status', 'active')->count();
    }

    public function getServiceCountAttribute(): int
    {
        return $this->services()->where('status', 'active')->count();
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->product_count + $this->service_count;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithChildren($query)
    {
        return $query->with('children');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumbAttribute(): array
    {
        $breadcrumbs = [];
        $category = $this;
        
        while ($category) {
            $breadcrumbs[] = [
                'name' => $category->name,
                'url' => route('categories.show', $category->slug)
            ];
            $category = $category->parent;
        }
        
        return array_reverse($breadcrumbs);
    }

    /**
     * Get all descendants (recursive)
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Get all descendant IDs including self
     */
    public function getDescendantIdsAttribute(): array
    {
        $ids = [$this->id];
        
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendant_ids);
        }
        
        return $ids;
    }

    /**
     * Check if category has children
     */
    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get category depth level
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $category = $this;
        
        while ($category->parent) {
            $depth++;
            $category = $category->parent;
        }
        
        return $depth;
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug(string $name): string
    {
        $slug = str()->slug($name);
        $count = 1;
        $originalSlug = $slug;
        
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }

    /**
     * Get tree structure for nested categories
     */
    public static function getTreeStructure(): array
    {
        $categories = self::with('children')->whereNull('parent_id')->ordered()->get();
        
        $tree = [];
        foreach ($categories as $category) {
            $tree[] = self::buildTreeItem($category);
        }
        
        return $tree;
    }

    private static function buildTreeItem(Category $category): array
    {
        $item = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'product_count' => $category->product_count,
            'service_count' => $category->service_count,
            'has_children' => $category->has_children,
            'depth' => $category->depth,
            'children' => []
        ];
        
        if ($category->has_children) {
            foreach ($category->children as $child) {
                $item['children'][] = self::buildTreeItem($child);
            }
        }
        
        return $item;
    }

    /**
     * Get category options for dropdown
     */
    public static function getCategoryOptions(?int $excludeId = null): array
    {
        $categories = self::active()->ordered()->get();
        $options = [];
        
        foreach ($categories as $category) {
            if ($excludeId && $category->id == $excludeId) {
                continue;
            }
            
            $prefix = str_repeat('-- ', $category->depth);
            $options[$category->id] = $prefix . $category->name;
        }
        
        return $options;
    }

    /**
     * Check if category can be deleted
     */
    public function canBeDeleted(): bool
    {
        // Check if category has products
        if ($this->products()->exists()) {
            return false;
        }
        
        // Check if category has services
        if ($this->services()->exists()) {
            return false;
        }
        
        // Check if category has children
        if ($this->children()->exists()) {
            return false;
        }
        
        return true;
    }
}