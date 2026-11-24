<?php

/*
| Menu QLĐT XE MÁY (mindnote «Chuẩn bị cho 1 khoá XE MÁY»).
| Slug → /qldt-moto/{slug}, route qldtmoto.{slug với / thành .}
| 'route' / 'active' khi đã có màn riêng; còn lại dùng 'trang'.
*/

return [
    'tabs' => [
        [
            'title' => 'QLĐT XE MÁY',
            'children' => [
                [
                    'title' => 'Công việc cần thiết',
                    'children' => [
                        ['title' => 'Làm tiến độ đào tạo năm', 'trang' => 'cong-viec-lam-tien-do-dao-tao-nam'],
                        ['title' => 'Cập nhật tiến độ đào tạo', 'trang' => 'cong-viec-cap-nhat-tien-do-dao-tao'],
                        ['title' => 'Huỷ hồ sơ', 'trang' => 'cong-viec-huy-ho-so'],
                    ],
                ],
                [
                    'title' => '1. Chuẩn bị khoá khai giảng',
                    'children' => [
                        [
                            'title' => 'Kế hoạch đào tạo khoá',
                            'children' => [
                                [
                                    'title' => 'Làm lịch dự kiến',
                                    'children' => [
                                        [
                                            'title' => 'Lý thuyết',
                                            'trang' => '1-lich-du-kien-ly-thuyet',
                                            'route' => 'qldtmoto.lich-du-kien.ly-thuyet.index',
                                            'active' => 'qldtmoto.lich-du-kien.ly-thuyet.*',
                                        ],
                                        [
                                            'title' => 'Thực hành',
                                            'trang' => '1-lich-du-kien-thuc-hanh',
                                            'route' => 'qldtmoto.lich-du-kien.thuc-hanh.index',
                                            'active' => 'qldtmoto.lich-du-kien.thuc-hanh.*',
                                        ],
                                    ],
                                ],
                                [
                                    'title' => 'Đăng ký lịch sát hạch với Phòng CSGT CA Tỉnh',
                                    'mo_ta' => 'Phòng CSGT',
                                    'trang' => '1-dang-ky-lich-sat-hach-csgt',
                                ],
                                [
                                    'title' => 'Làm lịch chính thức',
                                    'mo_ta' => 'Hướng dẫn: KG/BG khoá — không nhập PM',
                                    'trang' => '1-lam-lich-chinh-thuc',
                                    'route' => 'qldtmoto.lam-lich-chinh-thuc.index',
                                    'active' => 'qldtmoto.lam-lich-chinh-thuc.index',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Kênh liên lạc và tài liệu học tập',
                            'children' => [
                                [
                                    'title' => 'Tạo nhóm Zalo học viên',
                                    'mo_ta' => 'Tạo nhóm Zalo và mời HV vào lớp (thủ công)',
                                    'trang' => '1-tao-nhom-zalo-hoc-vien',
                                    'route' => 'qldtmoto.tao-nhom-zalo-hoc-vien.index',
                                    'active' => 'qldtmoto.tao-nhom-zalo-hoc-vien.index',
                                ],
                                [
                                    'title' => 'Thông báo lịch thi dự kiến',
                                    'mo_ta' => 'Xin phòng CSGT lịch thi dự kiến theo nhu cầu HV',
                                    'trang' => '1-thong-bao-lich-thi-du-kien',
                                    'route' => 'qldtmoto.thong-bao-lich-thi-du-kien.index',
                                    'active' => 'qldtmoto.thong-bao-lich-thi-du-kien.index',
                                ],
                                [
                                    'title' => 'Cung cấp tài liệu hướng dẫn học viên',
                                    'mo_ta' => 'Tài liệu LT + TH — từ cô Huyền',
                                    'trang' => '1-tai-lieu-huong-dan-hoc-vien',
                                    'route' => 'qldtmoto.tai-lieu-huong-dan-hoc-vien.index',
                                    'active' => 'qldtmoto.tai-lieu-huong-dan-hoc-vien.index',
                                ],
                                [
                                    'title' => 'Thông báo học viên trên nhóm (lịch thi chính thức)',
                                    'mo_ta' => 'Sau lịch chính thức — thông báo HV trên Zalo',
                                    'trang' => '1-thong-bao-lich-thi-chinh-thuc-zalo',
                                    'route' => 'qldtmoto.thong-bao-lich-thi-chinh-thuc-zalo.index',
                                    'active' => 'qldtmoto.thong-bao-lich-thi-chinh-thuc-zalo.index',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Đồng bộ lên phần mềm',
                            'children' => [
                                [
                                    'title' => 'Tạo khoá học trên PMGPLX-MOTO',
                                    'trang' => '1-tao-khoa-hoc-pmgplxmoto',
                                    'route' => 'pmgplxmoto.dm.khoa-hoc.index',
                                    'active' => 'pmgplxmoto.dm.khoa-hoc.*',
                                ],
                                [
                                    'title' => 'Nhập hồ sơ học viên lên PMGPLX',
                                    'mo_ta' => 'Danh sách đăng ký thi chính thức',
                                    'trang' => '1-nhap-ho-so-hoc-vien-pmgplx',
                                    'route' => 'pmgplxmoto.dm.hoc-vien.nhap-file.create',
                                    'active' => 'pmgplxmoto.dm.hoc-vien.nhap-file.*',
                                ],
                                ['title' => 'Kiểm tra, rà soát thông tin học viên + ảnh thẻ', 'trang' => '1-kiem-tra-hoc-vien-anh-the'],
                                [
                                    'title' => 'Kết xuất danh sách học viên từ phần mềm',
                                    'trang' => '1-xuat-danh-sach-hoc-vien-pmgplx',
                                    'route' => 'pmgplxmoto.dm.hoc-vien.index',
                                    'active' => 'pmgplxmoto.dm.hoc-vien.*',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Hồ sơ quản lý đào tạo',
                            'mo_ta' => 'Khi đến ngày khai giảng (đã đóng dấu báo cáo 1)',
                            'children' => [
                                [
                                    'title' => 'Báo cáo đăng ký khoá (Báo cáo 1)',
                                    'mo_ta' => 'Nộp Sở XD trong ngày khai giảng',
                                    'children' => [
                                        [
                                            'title' => 'Xuất file "Báo cáo đăng ký"',
                                            'trang' => '1-bao-cao-dang-ky',
                                            'route' => 'qldtmoto.bao-cao-dang-ky.index',
                                            'active' => 'qldtmoto.bao-cao-dang-ky.*',
                                        ],
                                        [
                                            'title' => 'Xuất file "Danh sách học viên lái xe"',
                                            'mo_ta' => 'Xuất từ phần mềm',
                                            'trang' => '1-danh-sach-hoc-vien-lai-xe',
                                            'route' => 'qldtmoto.danh-sach-hoc-vien-lai-xe.index',
                                            'active' => 'qldtmoto.danh-sach-hoc-vien-lai-xe.*',
                                        ],
                                        [
                                            'title' => 'Xuất file "Kế hoạch đào tạo"',
                                            'trang' => '1-ke-hoach-dao-tao',
                                            'route' => 'qldtmoto.ke-hoach-dao-tao.index',
                                            'active' => 'qldtmoto.ke-hoach-dao-tao.*',
                                        ],
                                    ],
                                ],
                                [
                                    'title' => 'Quyết định thành lập lớp học + danh sách học viên',
                                    'trang' => '1-quyet-dinh-thanh-lap-lop-hoc-danh-sach-hoc-vien',
                                    'route' => 'qldtmoto.quyet-dinh-thanh-lap-lop-hoc.index',
                                    'active' => 'qldtmoto.quyet-dinh-thanh-lap-lop-hoc.*',
                                ],
                                ['title' => 'Danh sách học viên học tập trung lớp lý thuyết', 'trang' => '1-danh-sach-hoc-vien-tap-trung-lop-ly-thuyet'],
                                ['title' => 'Danh sách học viên học tập trung lớp thực hành', 'trang' => '1-danh-sach-hoc-vien-tap-trung-lop-thuc-hanh'],
                                [
                                    'title' => 'Làm sổ tay giáo viên',
                                    'children' => [
                                        [
                                            'title' => 'Môn pháp luật giao thông đường bộ',
                                            'trang' => '1-so-tay-gv-phap-luat-giao-thong',
                                            'route' => 'qldtmoto.so-tay-giao-vien-ly-thuyet.index',
                                            'active' => 'qldtmoto.so-tay-giao-vien-ly-thuyet.*',
                                        ],
                                        [
                                            'title' => 'Môn kỹ thuật lái xe',
                                            'trang' => '1-so-tay-gv-ky-thuat-lai-xe',
                                            'route' => 'qldtmoto.so-tay-gv-ky-thuat-lai-xe.index',
                                            'active' => 'qldtmoto.so-tay-gv-ky-thuat-lai-xe.*',
                                        ],
                                        [
                                            'title' => 'Môn thực hành lái xe',
                                            'trang' => '1-so-tay-gv-thuc-hanh-lai-xe',
                                            'route' => 'qldtmoto.so-tay-giao-vien-thuc-hanh.index',
                                            'active' => 'qldtmoto.so-tay-gv-thuc-hanh-lai-xe.*',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'title' => '2. Khai giảng xong',
                    'children' => [
                        [
                            'title' => 'Xét công nhận hoàn thành trên phần mềm',
                            'children' => [
                                ['title' => 'Cập nhật kết quả đào tạo', 'trang' => '2-cap-nhat-ket-qua-dao-tao'],
                                ['title' => 'Kết xuất kết quả đào tạo', 'trang' => '2-ket-xuat-ket-qua-dao-tao'],
                                ['title' => 'Tiếp nhận kết quả cập nhật', 'trang' => '2-tiep-nhan-ket-qua-cap-nhat'],
                                ['title' => 'Xác nhận hoàn thành khoá học', 'trang' => '2-xac-nhan-hoan-thanh-khoa-hoc'],
                                ['title' => 'Truyển dữ liệu ra Sở Xây Dựng', 'trang' => '2-truyen-du-lieu-ra-so'],
                                [
                                    'title' => 'Báo cáo hoàn thành + danh sách gửi Sở (DSHTKĐT)',
                            'trang' => '2-bao-cao-hoan-thanh-gui-so',
                            'route' => 'qldtmoto.2-bao-cao-hoan-thanh-gui-so.index',
                            'active' => 'qldtmoto.2-bao-cao-hoan-thanh-gui-so.*',
                        ],
                            ],
                        ],
                        [
                            'title' => 'Quyết định thành lập hội đồng xét hoàn thành + Danh sách hội đồng',
                            'trang' => '2-quyet-dinh-thanh-lap-hoi-dong-xet-hoan-thanh',
                            'route' => 'qldtmoto.thanh-lap-hoi-dong-xet-ktm.index',
                            'active' => 'qldtmoto.thanh-lap-hoi-dong-xet-ktm.*',
                        ],
                        [
                            'title' => 'Biên bản họp công nhận xét tốt nghiệp',
                            'trang' => '2-bien-ban-hop-xet-tot-nghiep',
                            'route' => 'qldtmoto.quyet-dinh-to-chuc-ktm-bb.index',
                            'active' => 'qldtmoto.quyet-dinh-to-chuc-ktm-bb.*',
                        ],
                        [
                            'title' => 'Quyết định hoàn thành khoá + danh sách học viên',
                            'mo_ta' => 'Gửi Sở XD trong 2 ngày làm việc kể từ ngày cấp GCN hoàn thành',
                            'trang' => '2-quyet-dinh-hoan-thanh-khoa-danh-sach-hoc-vien',
                            'route' => 'qldtmoto.quyet-dinh-hoan-thanh-khoa-hoc.index',
                            'active' => 'qldtmoto.quyet-dinh-hoan-thanh-khoa-hoc.*',
                        ],
                    ],
                ],
                [
                    'title' => '3. Sau khi hoàn thành khoá học',
                    'children' => [
                        ['title' => 'Cấp giấy chứng nhận hoàn thành khoá đào tạo', 'trang' => '3-cap-giay-chung-nhan-hoan-thanh'],
                        [
                            'title' => 'Tổng hợp danh sách học viên đăng ký thi lại',
                            'children' => [
                                ['title' => 'Đưa vào phần mềm', 'trang' => '3-dua-vao-phan-mem-thi-lai'],
                                ['title' => 'Gửi Nhung kiểm tra tiền', 'trang' => '3-gui-nhung-kiem-tra-tien'],
                                ['title' => 'Cập nhật danh sách thi lại vào phần mềm', 'trang' => '3-cap-nhat-ds-thi-lai-pm'],
                                ['title' => 'Tổng hợp báo cáo 2', 'trang' => '3-tong-hop-bao-cao-2', 'route' => 'qldtmoto.3.lam-bao-cao-2-va-danh-sach.index', 'active' => 'qldtmoto.3.lam-bao-cao-2-va-danh-sach.*'],
                                [
                                    'title' => 'Kết xuất danh sách học viên báo cáo 2',
                                    'trang' => '3-ket-xuat-danh-sach-bc2',
                                    'route' => 'qldtmoto.3.ket-xuat-danh-sach-bc2.index',
                                    'active' => 'qldtmoto.3.ket-xuat-danh-sach-bc2.*',
                                ],
                                [
                                    'title' => 'Làm báo cáo 2 + danh sách học viên',
                                    'trang' => '3-lam-bao-cao-2-va-danh-sach',
                                    'route' => 'qldtmoto.3.lam-bao-cao-2-va-danh-sach.index',
                                    'active' => 'qldtmoto.3.lam-bao-cao-2-va-danh-sach.*',
                                ],
                                [
                                    'title' => 'Xuất file "Báo cáo đề nghị tổ chức sát hạch"',
                                    'trang' => '3-bao-cao-de-nghi-sat-hach',
                                    'route' => 'qldtmoto.bao-cao-de-nghi-sat-hach.index',
                                    'active' => 'qldtmoto.bao-cao-de-nghi-sat-hach.*',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Chuẩn bị hồ sơ sát hạch',
                            'mo_ta' => 'Xác nhận lại với Hải giấy tờ cần',
                            'trang' => '3-chuan-bi-ho-so-sat-hach',
                        ],
                    ],
                ],
                [
                    'title' => '4. Lập lưu hồ sơ',
                    'children' => [
                        [
                            'title' => 'Hồ sơ quản lý đào tạo',
                            'mo_ta' => 'Một file Word — QĐ lập, thống kê B2, QĐ lưu, B6, bìa B10',
                            'children' => [
                                [
                                    'title' => 'Quyết định về việc lập hồ sơ',
                                    'mo_ta' => 'Gồm QĐ lập + thống kê tài liệu (B2…)',
                                    'trang' => '4-ql-quyet-dinh-lap-ho-so',
                                    'route' => 'qldtmoto.4-ql-quyet-dinh-lap-ho-so.index',
                                    'active' => 'qldtmoto.4-ql-quyet-dinh-lap-ho-so.*',
                                ],
                                [
                                    'title' => 'Quyết định về việc lưu hồ sơ',
                                    'mo_ta' => 'Gồm QĐ lưu + bản định thời hạn bảo quản (B6…)',
                                    'trang' => '4-ql-quyet-dinh-luu-ho-so',
                                    'route' => 'qldtmoto.4-ql-quyet-dinh-luu-ho-so.index',
                                    'active' => 'qldtmoto.4-ql-quyet-dinh-luu-ho-so.*',
                                ],
                            ],
                        ],
                        [
                            'title' => 'Hồ sơ đào tạo',
                            'mo_ta' => 'Một file Word — sổ sách, giáo án (mẫu hồ sơ đào tạo)',
                            'children' => [
                                [
                                    'title' => 'Quyết định về việc lập hồ sơ',
                                    'mo_ta' => 'Gồm QĐ lập + thống kê tài liệu',
                                    'trang' => '4-dt-quyet-dinh-lap-ho-so',
                                    'route' => 'qldtmoto.4-dt-quyet-dinh-lap-ho-so.index',
                                    'active' => 'qldtmoto.4-dt-quyet-dinh-lap-ho-so.*',
                                ],
                                [
                                    'title' => 'Quyết định về việc lưu hồ sơ',
                                    'mo_ta' => 'Gồm QĐ lưu + bản định thời hạn bảo quản',
                                    'trang' => '4-dt-quyet-dinh-luu-ho-so',
                                    'route' => 'qldtmoto.4-dt-quyet-dinh-luu-ho-so.index',
                                    'active' => 'qldtmoto.4-dt-quyet-dinh-luu-ho-so.*',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];