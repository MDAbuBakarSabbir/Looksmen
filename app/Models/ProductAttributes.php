<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $guarded = [''];

    protected $appends = ['resolved_value'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function attributeVal()
    {
        return $this->belongsTo(AttributeValues::class, 'attribute_value');
    }

    public function getResolvedValueAttribute()
    {
        if ($this->relationLoaded('attributeVal') && $this->attributeVal) {
            return $this->attributeVal->value;
        }

        if (is_numeric($this->attribute_value)) {
            $valObj = AttributeValues::find($this->attribute_value);
            if ($valObj) {
                return $valObj->value;
            }
        }

        return $this->attribute_value;
    }
}

