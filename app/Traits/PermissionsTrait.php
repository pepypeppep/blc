<?php

namespace App\Traits;

use ReflectionClass;

trait PermissionsTrait
{
    public static array $dashboardPermissions = [
        'group_name' => 'dashboard',
        'permissions' => [
            'dashboard.view',
        ],
    ];

    public static array $adminProfilePermissions = [
        'group_name' => 'admin profile',
        'permissions' => [
            'admin.profile.view',
            'admin.profile.update',
        ],
    ];

    public static array $adminPermissions = [
        'group_name' => 'admin',
        'permissions' => [
            'admin.view',
            'admin.create',
            'admin.store',
            'admin.edit',
            'admin.update',
            'admin.delete',
        ],
    ];

    public static array $blogCatgoryPermissions = [
        'group_name' => 'blog category',
        'permissions' => [
            'blog.category.view',
            'blog.category.create',
            'blog.category.translate',
            'blog.category.store',
            'blog.category.edit',
            'blog.category.update',
            'blog.category.delete',
        ],
    ];

    public static array $blogPermissions = [
        'group_name' => 'blog',
        'permissions' => [
            'blog.view',
            'blog.create',
            'blog.translate',
            'blog.store',
            'blog.edit',
            'blog.update',
            'blog.delete',
        ],
    ];

    public static array $blogCommentPermissions = [
        'group_name' => 'blog comment',
        'permissions' => [
            'blog.comment.view',
            'blog.comment.update',
            'blog.comment.delete',
        ],
    ];

    public static array $rolePermissions = [
        'group_name' => 'role',
        'permissions' => [
            'role.view',
            'role.create',
            'role.store',
            'role.assign',
            'role.edit',
            'role.update',
            'role.delete',
        ],
    ];

    public static array $settingPermissions = [
        'group_name' => 'setting',
        'permissions' => [
            'setting.view',
            'setting.update',
        ],
    ];

    public static array $basicPaymentPermissions = [
        'group_name' => 'basic payment',
        'permissions' => [
            'basic.payment.view',
            'basic.payment.update',
        ],
    ];

    public static array $contectMessagePermissions = [
        'group_name' => 'contect message',
        'permissions' => [
            'contect.message.view',
            'contect.message.delete',
        ],
    ];

    public static array $currencyPermissions = [
        'group_name' => 'currency',
        'permissions' => [
            'currency.view',
            'currency.create',
            'currency.store',
            'currency.edit',
            'currency.update',
            'currency.delete',
        ],
    ];

    public static array $customerPermissions = [
        'group_name' => 'customer',
        'permissions' => [
            'customer.view',
            'customer.bulk.mail',
            'customer.create',
            'customer.store',
            'customer.edit',
            'customer.update',
            'customer.delete',
        ],
    ];

    public static array $languagePermissions = [
        'group_name' => 'language',
        'permissions' => [
            'language.view',
            'language.create',
            'language.store',
            'language.edit',
            'language.update',
            'language.delete',
            'language.translate',
            'language.single.translate',
        ],
    ];

    public static array $menuPermissions = [
        'group_name' => 'menu builder',
        'permissions' => [
            'menu.view',
            'menu.create',
            'menu.store',
            'menu.edit',
            'menu.update',
            'menu.delete',
        ],
    ];

    public static array $pagePermissions = [
        'group_name' => 'page builder',
        'permissions' => [
            'page.management'
        ],
    ];
    public static array $newsletterPermissions = [
        'group_name' => 'newsletter',
        'permissions' => [
            'newsletter.view',
            'newsletter.mail',
            'newsletter.delete',
        ],
    ];

    public static array $testimonialPermissions = [
        'group_name' => 'testimonial',
        'permissions' => [
            'testimonial.view',
            'testimonial.create',
            'testimonial.translate',
            'testimonial.store',
            'testimonial.edit',
            'testimonial.update',
            'testimonial.delete',
        ],
    ];

    public static array $faqPermissions = [
        'group_name' => 'faq',
        'permissions' => [
            'faq.view',
            'faq.create',
            'faq.translate',
            'faq.store',
            'faq.edit',
            'faq.update',
            'faq.delete',
        ],
    ];

    public static array $locationPermissions = [
        'group_name' => 'locations',
        'permissions' => [
            'location.view',
            'location.create',
            'location.store',
            'location.edit',
            'location.update',
            'location.delete',
        ],
    ];

    public static array $instructorRequestPermissions = [
        'group_name' => 'instructor request',
        'permissions' => [
            'instructor.request.list',
            'instructor.request.setting',
        ],
    ];

    public static array $coursePermissions = [
        'group_name' => 'courses management',
        'permissions' => [
            'course.create',
            'course.store',
            'course.edit',
            'course.update',
            'course.delete',
            'course.view',
            'course.verify',
            'course.status.update',
        ],
    ];

    public static array $courseCategoryPermissions = [
        'group_name' => 'course category management',
        'permissions' => [
            'course.category.management',
        ],
    ];

    public static array $courseLanguagePermissions = [
        'group_name' => 'course language management',
        'permissions' => [
            'course.language.management',
        ],
    ];

    public static array $courseLevelPermissions = [
        'group_name' => 'course level management',
        'permissions' => [
            'course.level.management',
        ],
    ];

    public static array $courseReviewPermissions = [
        'group_name' => 'course review management',
        'permissions' => [
            'course.review.management',
        ],
    ];

    public static array $CertificatePermission = [
        'group_name' => 'course certificate management',
        'permissions' => [
            'course.certificate.management',
        ],
    ];

    public static array $badgePermission = [
        'group_name' => 'Badges',
        'permissions' => [
            'badge.management',
        ],
    ];

    public static array $OrderPermission = [
        'group_name' => 'order management',
        'permissions' => [
            'order.management',
        ],
    ];

    public static array $couponPermission = [
        'group_name' => 'coupon management',
        'permissions' => [
            'coupon.management',
        ],
    ];

    public static array $withdrawPermission = [
        'group_name' => 'withdraw management',
        'permissions' => [
            'withdraw.management',
        ],
    ];

    public static array $appearancePermission = [
        'group_name' => 'site appearance management',
        'permissions' => [
            'appearance.management',
        ],
    ];

    public static array $siteSectionPermission = [
        'group_name' => 'site appearance management',
        'permissions' => [
            'section.management',
        ],
    ];

    public static array $brandPermission = [
        'group_name' => 'brand management',
        'permissions' => [
            'brand.management',
        ],
    ];

    public static array $footerPermission = [
        'group_name' => 'footer management',
        'permissions' => [
            'footer.management',
        ],
    ];

    public static array $socialPermission = [
        'group_name' => 'social link management',
        'permissions' => [
            'social.link.management',
        ],
    ];
    public static array $addonsPermissions = [
        'group_name' => 'Addons',
        'permissions' => [
            'addon.view',
            'addon.install',
            'addon.update',
            'addon.status.change',
            'addon.remove',
        ],
    ];

    public static array $PendidikanLanjutanPermissions = [
        'group_name' => 'pendidikan lanjutan',
        'permissions' => [
            'pendidikanlanjutan.view',
            'pendidikanlanjutan.create',
            'pendidikanlanjutan.store',
            'pendidikanlanjutan.edit',
            'pendidikanlanjutan.update',
            'pendidikanlanjutan.delete',
        ],
    ];

    public static array $PendidikanLanjutanManagementPermissions = [
        'group_name' => 'pendidikan lanjutan management',
        'permissions' => [
            'pendidikanlanjutan.verifikasi',
            'pendidikanlanjutan.pendaftar',
        ],
    ];

    public static array $knowledgePermissions = [
        'group_name' => 'knowledges',
        'permissions' => [
            'knowledge.verification',
        ],
    ];

    private static function getSuperAdminPermissions(): array
    {
        $reflection = new ReflectionClass(__TRAIT__);
        $properties = $reflection->getStaticProperties();

        $permissions = [];
        foreach ($properties as $value) {
            if (is_array($value)) {
                $permissions[] = [
                    'group_name' => $value['group_name'],
                    'permissions' => (array) $value['permissions'],
                ];
            }
        }

        return $permissions;
    }


    private static function getAdminBKPSDMPermissions(): array
    {
        $properties = [
            self::$dashboardPermissions,
            self::$coursePermissions,
            self::$courseCategoryPermissions,
            self::$courseLanguagePermissions,
            self::$courseLevelPermissions,
            self::$courseReviewPermissions,
            self::$CertificatePermission,
            self::$badgePermission,
            self::$PendidikanLanjutanPermissions,
            self::$PendidikanLanjutanManagementPermissions,
        ];

        $permissions = [];
        foreach ($properties as $value) {
            if (is_array($value)) {
                $permissions[] = [
                    'group_name' => $value['group_name'],
                    'permissions' => (array) $value['permissions'],
                ];
            }
        }

        return $permissions;
    }

    private static function getAdminOPDPermissions(): array
    {
        $properties = [
            self::$dashboardPermissions,
            self::$customerPermissions,
            self::$PendidikanLanjutanPermissions,
            [
                'group_name' => 'pendidikan lanjutan management',
                'permissions' => [
                    'pendidikanlanjutan.pendaftar',
                ],
            ],
            [
                'group_name' => 'courses management',
                'permissions' => [
                    'course.create',
                    'course.store',
                    'course.edit',
                    'course.update',
                    'course.delete',
                    'course.view',
                ],
            ]
        ];

        $permissions = [];
        foreach ($properties as $value) {
            if (is_array($value)) {
                $permissions[] = [
                    'group_name' => $value['group_name'],
                    'permissions' => (array) $value['permissions'],
                ];
            }
        }

        return $permissions;
    }
}
