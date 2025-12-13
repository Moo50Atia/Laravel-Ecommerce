<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = "pending";
    case PROCESSING = "processing";
    case SHIPPED = "shipped";
    case DELIVERED = "delivered";
    case CANCELED = "canceled";
    case REFUNDED = "refunded";

    // ممكن تضيف دوال مساعدة هنا
    public function label(): string
    {
        return match($this) {
            self::PENDING => "معلق",
            self::PROCESSING => "قيد المعالجة",
            self::SHIPPED => "تم الشحن",
            self::DELIVERED => "تم التوصيل",
            self::CANCELED => "ملغي",
            self::REFUNDED => "مسترد",
        };
    }
}
