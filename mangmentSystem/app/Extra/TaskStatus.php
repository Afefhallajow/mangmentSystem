<?php

namespace App\Extra;

enum TaskStatus:string
{
    case Pending = "pending";
    case Active = "active";
    case Done = "Done";
    case Freeze = "freeze";
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Done => 'Done',
            self::Freeze => 'Freeze',
        };
    }

    public static function getAll(): array
    {
        return array_map(function ($status) {
            return [
                'value' => $status->value,
                'label' => $status->label(),
            ];
        }, self::cases());
    }
}
