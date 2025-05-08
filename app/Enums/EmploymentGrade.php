<?php

namespace App\Enums;

enum EmploymentGrade: string
{
    case JURU_IA = 'juru_ia';
    case JURU_MUDA_TINGKAT_I_IB = 'juru_muda_tingkat_i_ib';
    case JURU_IC = 'juru_ic';
    case JURU_TINGKAT_I_ID = 'juru_tingkat_i_id';
    case PENGATUR_IIA = 'pengatur_iia';
    case PENGATUR_MUDA_TINGKAT_I_IIB = 'pengatur_muda_tingkat_i_iib';
    case PENGATUR_IIC = 'pengatur_iic';
    case PENGATUR_TINGKAT_I_IID = 'pengatur_tingkat_i_iid';
    case PENATA_IIIA = 'penata_iiia';
    case PENATA_MUDA_TINGKAT_I_IIIB = 'penata_muda_tingkat_i_iiib';
    case PENATA_IIIC = 'penata_iiic';
    case PENATA_TINGKAT_I_IIID = 'penata_tingkat_i_iiid';
    case PEMBINA_IVA = 'pembina_iva';
    case PEMBINA_TINGKAT_I_IVB = 'pembina_tingkat_i_ivb';
    case PEMBINA_UTAMA_MUDA_IVC = 'pembina_utama_muda_ivc';
    case PEMBINA_UTAMA_MADYA_IVD = 'pembina_utama_madya_ivd';
    case PEMBINA_UTAMA_IVE = 'pembina_utama_ive';

    public function label(): string
    {
        return __('PendidikanLanjutan::employment_grade.' . $this->value);
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }

}
