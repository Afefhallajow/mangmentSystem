<?php

namespace App\Models\Project;

use App\Extra\Priority;
use App\Models\BaseEntity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Extra\TaskStatus;
use InvalidArgumentException;

class Task extends BaseEntity
{

    public function __construct(array $attributes = [])
    {
        // Merge the parent's fillable fields dynamically
        $this->fillable = array_merge(
            $this->getFillable(), // Get fillable fields from the parent model
            ['content',"project_id","priority","status","lastFinishDate"] // Add the fields specific to Project
        );
        parent::__construct($attributes); // Call the parent constructor
    }

    protected $casts = [
        'status' => TaskStatus::class
    ];

    public function setStatusAttribute($value): void{
        // Check if the value is a valid TaskStatus enum value
        if (!TaskStatus::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid status: $value");
        }

        $this->attributes['status'] = $value;
    }

    public function setPriorityAttribute($value): void
    {
        // Check if the value is a valid TaskStatus enum value
        if (!Priority::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid Priority: $value");
        }

        $this->attributes['priority'] = $value;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }
}
