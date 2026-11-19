<?php

/**
 * Trang QLĐT Ô TÔ (tên route) → các route xuất file dùng chung khối lịch sử accordion.
 */
return [
    'qldtmoto.bao-cao-dang-ky.index' => ['qldtmoto.bao-cao-dang-ky.export'],
    'qldtmoto.hop-dong-dao-tao.index' => ['qldtmoto.hop-dong-dao-tao.export'],
    'qldtmoto.danh-sach-hoc-sinh-lai-xe.index' => ['qldtmoto.danh-sach-hoc-sinh-lai-xe.export'],
    'qldtmoto.lich-du-kien.ly-thuyet.index' => ['qldtmoto.lich-du-kien.ly-thuyet.export'],
    'qldtmoto.quyet-dinh-dieu-dong-xe.index' => ['qldtmoto.quyet-dinh-dieu-dong-xe.export'],
    'qldtmoto.quyet-dinh-dieu-dong-xe.preview' => ['qldtmoto.quyet-dinh-dieu-dong-xe.export'],
    'qldtmoto.bao-cao-de-nghi-sat-hach.index' => ['qldtmoto.bao-cao-de-nghi-sat-hach.export'],
    'qldtmoto.thong-bao-tap-trung-thuc-hanh.index' => ['qldtmoto.thong-bao-tap-trung-thuc-hanh.export'],
    'qldtmoto.quyet-dinh-hoan-thanh-khoa-hoc.index' => ['qldtmoto.quyet-dinh-hoan-thanh-khoa-hoc.export'],
    'qldtmoto.thong-bao-kiem-tra-ket-thuc-mon.index' => ['qldtmoto.thong-bao-kiem-tra-ket-thuc-mon.export'],
    'qldtmoto.thanh-lap-hoi-dong-xet-ktm.index' => [
        'qldtmoto.thanh-lap-hoi-dong-xet-ktm.export-quyet-dinh',
        'qldtmoto.thanh-lap-hoi-dong-xet-ktm.export-danh-sach',
        'qldtmoto.thanh-lap-hoi-dong-xet-ktm.export-ca-hai',
    ],
    'qldtmoto.quyet-dinh-to-chuc-ktm-bb.index' => [
        'qldtmoto.quyet-dinh-to-chuc-ktm-bb.export-quyet-dinh',
        'qldtmoto.quyet-dinh-to-chuc-ktm-bb.export-bien-ban',
        'qldtmoto.quyet-dinh-to-chuc-ktm-bb.export-ca-hai',
    ],
    'qldtmoto.bien-ban-phan-cong-to-cham.index' => ['qldtmoto.bien-ban-phan-cong-to-cham.export'],
    'qldtmoto.quyet-dinh-bao-luu.index' => ['qldtmoto.quyet-dinh-bao-luu.export'],
    'qldtmoto.quyet-dinh-cho-thoi-hoc.index' => ['qldtmoto.quyet-dinh-cho-thoi-hoc.export'],
    'qldtmoto.bao-cao-phien-hoc-loi.index' => [
        'qldtmoto.bao-cao-phien-hoc-loi.export-word',
        'qldtmoto.bao-cao-phien-hoc-loi.export-danh-sach',
        'qldtmoto.bao-cao-phien-hoc-loi.export-ca-hai',
    ],
    'qldtmoto.quyet-dinh-huy-phien.index' => [
        'qldtmoto.quyet-dinh-huy-phien.export-word',
        'qldtmoto.quyet-dinh-huy-phien.export-danh-sach',
        'qldtmoto.quyet-dinh-huy-phien.export-ca-hai',
    ],
    'qldtmoto.quyet-dinh-doi-xe.index' => ['qldtmoto.quyet-dinh-doi-xe.export'],
    'qldtmoto.quyet-dinh-doi-xe.preview' => ['qldtmoto.quyet-dinh-doi-xe.export'],
    'qldtmoto.quyet-dinh-doi-giao-vien.index' => ['qldtmoto.quyet-dinh-doi-giao-vien.export'],
    'qldtmoto.quyet-dinh-doi-giao-vien.preview' => ['qldtmoto.quyet-dinh-doi-giao-vien.export'],
    'qldtmoto.so-theo-doi-ket-qua-hoc-tap.index' => ['qldtmoto.so-theo-doi-ket-qua-hoc-tap.export'],
    'qldtmoto.so-tay-giao-vien-ly-thuyet.index' => ['qldtmoto.so-tay-giao-vien-ly-thuyet.export'],
    'qldtmoto.so-tay-giao-vien-thuc-hanh.index' => ['qldtmoto.so-tay-giao-vien-thuc-hanh.export'],
    'qldtmoto.so-phan-cong-gv-xe-tap-lai.index' => ['qldtmoto.so-phan-cong-gv-xe-tap-lai.export'],
    'qldtmoto.so-phan-cong-gv-xe-tap-lai.preview' => ['qldtmoto.so-phan-cong-gv-xe-tap-lai.export'],
    'qldtmoto.bien-ban-pho-bien-noi-quy.index' => ['qldtmoto.bien-ban-pho-bien-noi-quy.export'],
    'qldtmoto.quyet-dinh-thanh-lap-lop-hoc.index' => ['qldtmoto.quyet-dinh-thanh-lap-lop-hoc.export'],
    'qldtmoto.lich-du-kien.thuc-hanh.index' => ['qldtmoto.lich-du-kien.thuc-hanh.export-excel'],
    'qldtmoto.lich-du-kien.thuc-hanh.edit' => ['qldtmoto.lich-du-kien.thuc-hanh.export-excel'],
    'qldtmoto.lich-du-kien.thuc-hanh.create' => ['qldtmoto.lich-du-kien.thuc-hanh.export-excel'],
    'qldtmoto.lam-lich-chinh-thuc.index' => ['qldtmoto.lam-lich-chinh-thuc.export-excel'],
    'qldtmoto.lam-lich-chinh-thuc.show' => ['qldtmoto.lam-lich-chinh-thuc.export-excel'],

    'qldtmoto.1-lich-du-kien-ly-thuyet.index' => ['qldtmoto.lich-du-kien.ly-thuyet.export'],
    'qldtmoto.1-lich-du-kien-thuc-hanh.index' => ['qldtmoto.lich-du-kien.thuc-hanh.export-excel'],
    'qldtmoto.3.lam-bao-cao-2-va-danh-sach.index' => ['qldtmoto.3.lam-bao-cao-2-va-danh-sach.export'],
    'qldtmoto.3.ket-xuat-danh-sach-bc2.index' => ['qldtmoto.3.ket-xuat-danh-sach-bc2.export'],

];