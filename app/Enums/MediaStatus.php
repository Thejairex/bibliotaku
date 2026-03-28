<?php
// app/Enums/MediaStatus.php
namespace App\Enums;

enum MediaStatus: string
{
    case Watching = 'watching';
    case Rewatching = 'rewatching';
    case Reading = 'reading';
    case Completed = 'completed';
    case OnHold = 'on_hold';
    case Dropped = 'dropped';
    case PlanToWatch = 'plan_to_watch';

    public function label(): string
    {
        return match ($this) {
            self::Watching => 'Viendo',
            self::Reading => 'Leyendo',
            self::Completed => 'Completado',
            self::OnHold => 'Pausado',
            self::Dropped => 'Abandonado',
            self::PlanToWatch => 'Pendiente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Watching => 'blue',
            self::Reading => 'blue',
            self::Completed => 'green',
            self::OnHold => 'yellow',
            self::Dropped => 'red',
            self::PlanToWatch => 'gray',
        };
    }
}