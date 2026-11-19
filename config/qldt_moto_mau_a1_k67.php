<?php

/*
| Mẫu thực tế khoá A1-K67 (Downloads/A1-K67) ↔ slug menu QLĐT XE MÁY.
| ma_trang = slug trong config/menu_qldt.php (trang).
| nguon: pmgplx = xuất/lấy từ PMGPLX-MOTO; thu_cong = ghép Word/Excel từ mẫu; chua = chưa có màn xuất.
*/

return [
    'cong-viec-lam-tien-do-dao-tao-nam' => [
        'mau' => [],
        'ghi_chu' => 'Không có file trong bộ A1-K67 — quy trình nội bộ.',
        'trang_thai' => 'chua',
    ],
    'cong-viec-cap-nhat-tien-do-dao-tao' => [
        'mau' => [],
        'ghi_chu' => 'Không có file trong bộ A1-K67.',
        'trang_thai' => 'chua',
    ],
    'cong-viec-huy-ho-so' => [
        'mau' => [],
        'ghi_chu' => 'Không có file trong bộ A1-K67.',
        'trang_thai' => 'chua',
    ],
    '1-lich-du-kien-ly-thuyet' => [
        'mau' => [
            '4. LICH HỌC LT A1K67 - LỚP 01+02.xlsx',
            '4. LICH HỌC LT A1K67- LỚP 03.xlsx',
        ],
        'ghi_chu' => 'Lịch LT theo lớp (01+02 / 03). Cần màn chọn khoá + lớp → xuất Excel.',
        'trang_thai' => 'chua',
    ],
    '1-lich-du-kien-thuc-hanh' => [
        'mau' => ['5. LỊCH HỌC THỰC HÀNH.xlsx'],
        'ghi_chu' => 'Lịch TH một file/khoá hoặc theo lớp — xác nhận quy ước khi code.',
        'trang_thai' => 'chua',
    ],
    '1-dang-ky-lich-sat-hach-csgt' => [
        'mau' => [],
        'ghi_chu' => 'Nộp CSGT — không có mẫu in trong bộ (thủ tục hành chính).',
        'trang_thai' => 'thu_cong',
    ],
    '1-lam-lich-chinh-thuc' => [
        'mau' => [
            '4. LICH HỌC LT A1K67 - LỚP 01+02.xlsx',
            '4. LICH HỌC LT A1K67- LỚP 03.xlsx',
            '5. LỊCH HỌC THỰC HÀNH.xlsx',
        ],
        'ghi_chu' => 'Sau lịch CA: chỉnh từ lịch dự kiến — có thể cùng template với mục 4–5.',
        'trang_thai' => 'chua',
    ],
    '1-bao-cao-dang-ky' => [
        'mau' => [
            '1. Báo cáo đăng ký khóa ĐTLX-SỞ XD.docx',
            'DS BC1- A1-K67 236.doc',
        ],
        'ghi_chu' => 'BC1 gửi Sở XD. Tham khảo QLĐT Ô TÔ `BaoCaoDangKyController` — chỉnh header «Sở Xây dựng»/hạng A1.',
        'trang_thai' => 'pmgplx',
    ],
    '1-danh-sach-hoc-vien-lai-xe' => [
        'mau' => ['2. DANH SÁCH HỌC VIÊN BC 1.docx'],
        'ghi_chu' => 'Danh sách kèm BC1 — nguồn PMGPLX-MOTO (xuất Excel/DOC).',
        'trang_thai' => 'pmgplx',
    ],
    '1-ke-hoach-dao-tao' => [
        'mau' => ['3. KẾ HOẠCH ĐÀO TẠO KHÓA.docx'],
        'ghi_chu' => 'Tương tự ô tô `KeHoachDaoTaoController`.',
        'trang_thai' => 'pmgplx',
    ],
    '1-quyet-dinh-thanh-lap-lop-hoc-danh-sach-hoc-vien' => [
        'mau' => [
            '6. QUYET DINH THANH LAP LOP HOC A1K67.doc',
            '7. danh sách hv thành lập lớp học A1K67 01+02.xls',
            '7. danh sách hv thành lập lớp học A1K67- lớp 03.xls',
        ],
        'ghi_chu' => 'QĐ + DS theo lớp. Ô tô: `QuyetDinhThanhLapLopHocController`.',
        'trang_thai' => 'pmgplx',
    ],
    '1-danh-sach-hoc-vien-tap-trung-lop-ly-thuyet' => [
        'mau' => [
            '13. DANH SÁCH HỌC VIÊN THAM DỰ ĐẦY ĐỦ THỜI GIAN HỌC LT.xls',
            '13. DANH SÁCH HỌC VIÊN THAM DỰ ĐẦY ĐỦ THỜI GIAN HỌC LT - LỚP 03.xls',
        ],
        'ghi_chu' => 'Xuất từ PMGPLX (điểm danh LT) hoặc lọc thủ công.',
        'trang_thai' => 'chua',
    ],
    '1-danh-sach-hoc-vien-tap-trung-lop-thuc-hanh' => [
        'mau' => ['14. DANH SÁCH HỌC VIÊN THAM DỰ ĐẦY ĐỦ THỜI GIAN HỌC TH.xls'],
        'ghi_chu' => 'Xuất từ kết quả TH trên PMGPLX.',
        'trang_thai' => 'chua',
    ],
    '1-so-tay-gv-phap-luat-giao-thong' => [
        'mau' => [
            'SỔ TAY GV MÔN PL - lớp 01.docx',
            'SỔ TAY GV MÔN PL - lớp 02.docx',
            'SỔ TAY GV MÔN PL - lớp 03.docx',
        ],
        'ghi_chu' => 'Môn PL — ô tô chỉ có LT/TH sổ tay; mô tô 3 môn, port hoặc mẫu riêng.',
        'trang_thai' => 'chua',
    ],
    '1-so-tay-gv-ky-thuat-lai-xe' => [
        'mau' => [
            'SỔ TAY GV MÔN KTLX - lớp 01.docx',
            'SỔ TAY GV MÔN ktlx - lớp 02.docx',
            'SỔ TAY GV MÔN KTLX - LỚP 03.docx',
        ],
        'trang_thai' => 'chua',
    ],
    '1-so-tay-gv-thuc-hanh-lai-xe' => [
        'mau' => ['SỔ TAY GV MÔN THLX.docx'],
        'trang_thai' => 'chua',
    ],
    '2-quyet-dinh-thanh-lap-hoi-dong-xet-hoan-thanh' => [
        'mau' => [
            '8.  Quyết định thành lập hội đồng xét hoàn thành khóa đào tạo.docx',
            '9. DANH SÁCH HỘI ĐỒNG XÉT CÔNG NHẬN HOÀN THÀNH.xlsx',
        ],
        'ghi_chu' => 'Ô tô: `ThanhLapHoiDongXetKtmController` (đổi tên hội đồng xét hoàn thành).',
        'trang_thai' => 'port_oto',
    ],
    '2-bien-ban-hop-xet-tot-nghiep' => [
        'mau' => ['10. biên bản họp xét công nhận tốt nghiệp.docx'],
        'trang_thai' => 'chua',
    ],
    '2-quyet-dinh-hoan-thanh-khoa-danh-sach-hoc-vien' => [
        'mau' => [
            '11. Quyết định hoàn thành.doc',
            '12. danh sách hv hoàn thành lớp học.xls',
        ],
        'ghi_chu' => 'Ô tô: `QuyetDinhHoanThanhKhoaHocController`.',
        'trang_thai' => 'port_oto',
    ],
    '3-lam-bao-cao-2-va-danh-sach' => [
        'mau' => ['DS BC2- A1K67-334 HV.doc'],
        'ghi_chu' => 'BC2 + danh sách. Ô tô: `DanhSachBc2DocxGenerator` / báo cáo đề nghị sát hạch.',
        'trang_thai' => 'port_oto',
    ],
    '3-ket-xuat-danh-sach-bc2' => [
        'mau' => ['DS BC2- A1K67-334 HV.doc', '16. A1-K67 - 236 HV THI MỚI.xls'],
        'ghi_chu' => '16 = thí sinh thi mới; BC2 = toàn bộ kỳ.',
        'trang_thai' => 'pmgplx',
    ],
    '3-bao-cao-de-nghi-sat-hach' => [
        'mau' => ['15.Báo cáo đề nghị tổ chức sát hạch lái xe.docx'],
        'ghi_chu' => 'Chưa có slug menu — nên thêm dưới «Tổng hợp…» hoặc hồ sơ sát hạch.',
        'trang_thai' => 'port_oto',
        'menu_de_xuat' => '3-bao-cao-de-nghi-sat-hach',
    ],
    '3-chuan-bi-ho-so-sat-hach' => [
        'mau' => [
            '17. DANH SÁCH HỌC VIÊN ĐÃ CÓ GPLX Ô TÔ.xlsx',
            '18. DS THI LẠI SH 22.08.xlsx',
            '19. BBKTHS-.docx',
            'DSHV DỰ SÁT HẠCH LÁI XE HẠNG A1 KỲ NGÀY 22.8.26- HUYỀN MẠNH LINH.xlsx',
            'DANH SÁCH SBD DỰ THI NGÀY 22.08.2026.xls',
        ],
        'ghi_chu' => 'Một phần từ PMGPLX/bản cũ; BB kiểm tra hồ sơ (19) chưa có menu riêng.',
        'trang_thai' => 'thu_cong',
    ],
    '2-bao-cao-hoan-thanh-gui-so' => [
        'mau' => [
            '20. Báo cáo  SỞ DSHTKĐT.doc',
            '21. danh sách hv hoàn thành lớp học - bc sở.xls',
        ],
        'ghi_chu' => 'Gửi Sở sau hoàn thành — chưa có mục menu; gần `2-truyen-du-lieu-ra-so`.',
        'trang_thai' => 'chua',
        'menu_de_xuat' => '2-bao-cao-hoan-thanh-gui-so',
    ],
    'ket-qua-ky-sat-hach' => [
        'mau' => [
            'SH A1-K67 TTML  BBTH, BC chi tiet, BC CHUNG 22.08.2026.xls',
            'DANH SÁCH THÍ SINH THI ĐẠT SÁT HẠCH NGÀY 22.08.2026.xlsx',
            'DANH SÁCH THI SINH VẮNG THI, THI TRƯỢT NGÀY 22.08.2026.xlsx',
        ],
        'ghi_chu' => 'Sau kỳ thi — ngoài mindnote chính; có thể tab «Sau sát hạch».',
        'trang_thai' => 'chua',
    ],
];