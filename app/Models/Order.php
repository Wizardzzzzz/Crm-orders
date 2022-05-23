<?php

namespace App\Models;

use App\Orchid\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Access\UserAccess;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use Chartable;
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'user_id',
        'product_id',
        'receive_date',
        'price'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'receive_date',
        'price'
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'receive_date',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
