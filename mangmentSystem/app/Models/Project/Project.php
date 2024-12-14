<?php

namespace App\Models\Project;

use App\Models\BaseEntity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends BaseEntity
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        // Merge the parent's fillable fields dynamically
        $this->fillable = array_merge(
            $this->getFillable(), // Get fillable fields from the parent model
            ['content'] // Add the fields specific to Project
        );

        parent::__construct($attributes); // Call the parent constructor
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
