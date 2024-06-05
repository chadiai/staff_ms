<?php


namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

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
    protected $appends = ['start_date_format','end_date_format'];


    /**
     * Apply the scope to a given Eloquent query builder
     * prefix the method name with 'scope' e.g. 'scopeIsActive()'
     * Utilize the scope in the controller  Model::is_active()->get();
     */


    /**
     * Add attributes that should be hidden to the $hidden array
     */
    protected $hidden = [];

    public function event_creation_request()
    {
        return $this->belongsTo(EventCreationRequest::class)->withDefault();  // a record belongs to a genre
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();  // a record belongs to a genre
    }

    public function event_members()
    {
        return $this->hasMany(EventMember::class);   // a genre has many records
    }

    protected function startDateFormat(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $startDateTime = new DateTime($attributes['start_date_time']);
                return $startDateTime->format('H:i');
            },
        );
    }

    protected function endDateFormat(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $endDateTime = new DateTime($attributes['end_date_time']);
                return $endDateTime->format('H:i');
            },
        );
    }

    public function scopeSearchName($query, $search = '%')
    {
        return $query->where('name', 'like', "%{$search}%");
    }


}
