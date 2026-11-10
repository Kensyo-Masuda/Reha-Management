<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentItem;

class AssessmentItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            '肩関節' => [
                '屈曲', '伸展', '外転', '内転',
                '外旋', '内旋', '水平外転', '水平内転',
            ],
            '肘関節' => [
                '屈曲', '伸展',
            ],
            '前腕' => [
                '回内', '回外',
            ],
            '手関節' => [
                '掌屈', '背屈', '橈屈', '尺屈',
            ],
            '手指' => [
                '屈曲', '伸展',
            ],
            '股関節' => [
                '屈曲', '伸展', '外転', '内転', '外旋', '内旋',
            ],
            '膝関節' => [
                '屈曲', '伸展',
            ],
            '足関節' => [
                '背屈', '底屈',
            ],
            '足部' => [
                '内がえし', '外がえし',
            ],
        ];

        $sortOrder = 1;

        foreach ($items as $bodyRegion => $movements) {
            foreach ($movements as $movement) {
                AssessmentItem::create([
                    'body_region' => $bodyRegion,
                    'movement' => $movement,
                    'sort_order' => $sortOrder,
                ]);

                $sortOrder++;
            }
        }
    }
}