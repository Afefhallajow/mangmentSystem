<?php
namespace App\Extra;

enum Priority: string
{
    case Height = "height";
    case Low = "low";
    case Normal = "normal";

    public function label(): string
    {
        return match($this) {
            self::Height => 'Height',
            self::Low => 'Low',
            self::Normal => 'Normal',
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
