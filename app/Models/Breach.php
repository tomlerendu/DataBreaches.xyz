<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\BreachData as BreachData;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breach extends Model
{
    use SoftDeletes;
    use Reviewable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'date_occurred'];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */
    protected $appends = ['formatted_data_leaked'];

    /**
     * Calculates the severity score of a breach.
     *
     * @return float The score given
     */
    public function calculateScore(): float
    {
        $score = BreachData::$initialModifier;
        $scoreModifier = BreachData::$initialModifier;

        if ($this->previously_known) {
            $scoreModifier += BreachData::$previouslyKnownModifier;
        }

        $scoreModifier += BreachData::$responseStanceModifiers[$this->response_stance];
        $scoreModifier += BreachData::$responsePatchedModifiers[$this->response_patched];
        $scoreModifier += BreachData::$responseCustomersInformedModifiers[$this->response_customers_informed];
        
        return $score * $scoreModifier;
    }

    /**
     * Get the title of the method used in the breach.
     *
     * @return string
     */
    public function getMethodTitleAttribute(): string
    {
        return BreachData::$methods[$this->method]['title'] ?? 'Unknown';
    }

    /**
     * Get the description of the method used in the breach.
     *
     * @return string
     */
    public function getMethodDescriptionAttribute(): string
    {
        return BreachData::$methods[$this->method]['description'] ?? 'Unknown';
    }

    /**
     * Get the organisation response to the breach.
     *
     * @return string
     */
    public function getFormattedResponseStanceAttribute(): string
    {
        return BreachData::$responseStances[$this->response_stance] ?? 'Unknown';
    }

    /**
     * Get the time taken to patch the breach.
     *
     * @return string
     */
    public function getFormattedResponsePatchedAttribute(): string
    {
        return BreachData::$responsePatched[$this->response_patched] ?? 'Unknown';
    }

    /**
     * Get the time taken to inform customers about the breach.
     *
     * @return string
     */
    public function getFormattedResponseCustomersInformedAttribute(): string
    {
        return BreachData::$responseCustomersInformed[$this->response_customers_informed] ?? 'Unknown';
    }

    /**
     * Get a list of information types that were leaked.
     *
     * @param string $value
     * @return array
     */
    public function getDataLeakedAttribute(string $value): array
    {
        if ($value == '') {
            return [];
        }

        $types = explode(',', $value);

        return $types;
    }

    /**
     * Get a list of information that was leaked.
     *
     * @return array
     */
    public function getFormattedDataLeakedAttribute(): array
    {
        $types = $this->data_leaked;
        //Take each data leaked type and get the human readable name
        $types = array_map(function ($index) {
            return BreachData::$dataTypes[$index];
        }, $types);

        return $types;
    }

    /**
     * Set the types of information that were leaked.
     *
     * @param array $value
     */
    public function setDataLeakedAttribute(array $value)
    {
        $this->attributes['data_leaked'] = implode(',', $value);
    }

    /**
     * The organisation that the breach belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo('App\Models\Organisation');
    }

    /**
     * Scope to select recent breaches and order by most recent.
     *
     * @param mixed $query The laravel query object
     * @param int $numRecords Number of breaches to select
     * @return mixed
     */
    public function scopeRecent($query, int $numRecords = 10)
    {
        return $query
            ->where('status', 'Accepted')
            ->orderBy('date_occurred', 'desc')
            ->take($numRecords);
    }
}