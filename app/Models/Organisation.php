<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use SoftDeletes;
    use Reviewable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'incorporated_on'];

    /**
     * Breaches that belong to the organisation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function breaches()
    {
        return $this->hasMany('App\Models\Breach');
    }

    /**
     * Tags that the organisation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'organisation_tags');
    }

    /**
     * Generates a list of tags that the organisation belongs to.
     *
     * @return array
     */
    public function getTagIds(): array
    {
        $tags = [];

        foreach ($this->tags as $tag) {
            $tags[] = $tag->id;
        }

        return $tags;
    }

    /**
     * Calculates and updates how well an organisation handles customer data (between 0 and 10).
     * The score is calculated as 10 - each breach score that has occurred.
     *
     * @return void
     */
    public function calculateScore()
    {
        $score = 10.0;
        $breaches = $this->breaches()
            ->status(['Accepted'])
            ->get();

        foreach ($breaches as $breach) {
            $score -= $breach->calculateScore();
        }

        $this->score = max(0, $score);
    }

    /**
     * Determines and updates how many data breaches an organisation has had.
     *
     * @return void
     */
    public function calculateBreaches()
    {
        $count = $this->breaches()
            ->status(['Accepted'])
            ->count();

        $this->breach_count = $count;
    }

    /**
     * Gets a name for a numeric score (Excellent, Good, Variable or Poor).
     *
     * @return string
     */
    public function getFormattedScoreGradeAttribute(): string
    {
        if ($this->score <= 5) {
            return 'Poor';
        }

        if ($this->score <= 7) {
            return 'Variable';
        }

        if ($this->score <= 9) {
            return 'Good';
        }

        return 'Excellent';
    }

    /**
     * Gets a grade for an organisation score which can be used as an ID or CSS class.
     *
     * @return string
     */
    public function getScoreGradeAttribute(): string
    {
        return strtolower($this->formatted_score_grade);
    }

    /**
     * Scope to query organisations by a search term.
     *
     * @param $query
     * @param string $searchTerm The word to search on
     * @param bool $showSubmitted Should organisations that have not yet been approved be shown
     * @return mixed
     */
    public function scopeSearch($query, string $searchTerm, bool $showSubmitted = false)
    {
        if ($showSubmitted) {
            $statuses = ['Accepted', 'Submitted'];
        } else {
            $statuses = ['Accepted'];
        }

        $searchTerm .= '%';

        return $query
            ->status($statuses)
            ->where(function ($query) use ($searchTerm) {
                $query
                    ->where('slug', 'LIKE', $searchTerm)
                    ->orWhere('name', 'LIKE', $searchTerm)
                    ->orWhere('styled_name', 'LIKE', $searchTerm);
            });
    }

    /**
     * Scope to select an organisation by its slug.
     *
     * @param $query
     * @param string $slug The organisations slug
     * @param array $statuses An array of review states the organisation can have
     * @return mixed
     */
    public function scopeSlug($query, string $slug, array $statuses = ['Accepted'])
    {
        return $query->where('slug', $slug)->status($statuses);
    }
}