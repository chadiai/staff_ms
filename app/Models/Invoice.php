<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable
     * $fillable: array of attributes that are mass assignable
     * $guarded: array of attributes that are not mass assignable
     * REMARK: the save() methode does not pass the guarded attributes!
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationship between models
     * hasMany('model', 'foreign_key', 'primary_key'):  method name is lowercase and plural case
     * belongsTo('model', 'foreign_key', 'primary_key')->withDefaults():  method name is lowercase and singular case
     */

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitting_user_id', 'id')->withDefault();
    }


    /**
     * Accessors and mutators (method name is the attribute name)
     * get: transform the attribute after it has retrieved from database
     * set: transform the attribute before it is sent to database
     */


    /**
     * Add additional attributes that do not have a corresponding column in your database
     * REMARK: additional attributes are not automatically included to the model!
     *    - add the attributes to the $appends array to include them always to the model
     *    - or append the attributes in runtime with Model::get()->append([])
     */
    protected $appends = [];


    /**
     * Apply the scope to a given Eloquent query builder
     * prefix the method name with 'scope' e.g. 'scopeIsActive()'
     * Utilize the scope in the controller  Model::is_active()->get();
     */

//    public function scopeSearchTitleNumber($query, $search = '%')
//    {
//        return $query->where('title', 'like', "%{$search}%")
//            ->orWhere('number', 'like', "%{$search}%")
//            ->orWhere('submitting_user_id', 'like', User::where('first_name', $search)->id);
//    }


    public function scopeSearchTitleNumber($query, $search = '%')
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('number', 'like', "%{$search}%")
            ->orWhere(function ($query) use ($search) {
                $query->where('submitting_user_id', User::where('first_name', $search)->value('id'));
                })
            ->orWhere(function ($query) use ($search) {
                $query->where('submitting_user_id', User::where('last_name', $search)->value('id'));
            })
            ;
    }


    /**
     * Add attributes that should be hidden to the $hidden array
     */
    protected $hidden = [];
}
