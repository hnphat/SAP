-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2021 at 05:31 PM
-- Server version: 5.6.51
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hyundaia_sap`
--

-- --------------------------------------------------------

--
-- Table structure for table `bh_pk_package`
--

CREATE TABLE `bh_pk_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `type` enum('free','pay','cost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancel_hd`
--

CREATE TABLE `cancel_hd` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `lyDoCancel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancel` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_sale`
--

CREATE TABLE `car_sale` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_type_car_detail` int(10) UNSIGNED NOT NULL,
  `year` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frame` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gear` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exist` tinyint(1) NOT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `order` tinyint(1) NOT NULL,
  `profit` int(11) NOT NULL DEFAULT '0',
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cong`
--

CREATE TABLE `cong` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_dv` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `thue` int(11) NOT NULL DEFAULT '10',
  `id_loai_cong` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky_su_dung`
--

CREATE TABLE `dang_ky_su_dung` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user_reg` int(10) UNSIGNED NOT NULL,
  `id_xe_lai_thu` int(10) UNSIGNED NOT NULL,
  `lyDo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `km_current` int(11) DEFAULT NULL,
  `fuel_current` int(11) DEFAULT NULL,
  `car_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_go` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0',
  `date_return` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tra_km_current` int(11) DEFAULT NULL,
  `tra_fuel_current` int(11) DEFAULT NULL,
  `tra_car_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tra_allow` tinyint(1) NOT NULL DEFAULT '0',
  `request_tra` tinyint(1) NOT NULL DEFAULT '0',
  `fuel_request` tinyint(1) NOT NULL DEFAULT '0',
  `fuel_allow` tinyint(1) NOT NULL DEFAULT '0',
  `fuel_type` enum('X','D') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel_num` int(11) NOT NULL DEFAULT '0',
  `fuel_lyDo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_check` tinyint(1) NOT NULL DEFAULT '0',
  `id_user_check` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dang_ky_su_dung`
--

INSERT INTO `dang_ky_su_dung` (`id`, `id_user_reg`, `id_xe_lai_thu`, `lyDo`, `km_current`, `fuel_current`, `car_status`, `date_go`, `allow`, `date_return`, `tra_km_current`, `tra_fuel_current`, `tra_car_status`, `tra_allow`, `request_tra`, `fuel_request`, `fuel_allow`, `fuel_type`, `fuel_num`, `fuel_lyDo`, `lead_check`, `id_user_check`, `created_at`, `updated_at`) VALUES
(2, 4, 2, 'Mua cồn ủng hộ UBND phường Mỹ Hoà', 63332, 186, 'Tốt', '2021-10-26', 1, '15:50 26-10-2021', 63339, 177, 'Tốt', 1, 1, 0, 0, NULL, 0, NULL, 0, NULL, '2021-10-26 08:19:19', '2021-10-27 08:06:14'),
(3, 4, 1, 'Tặng cồn UBND MỸ Hoà', 13732, 92, 'Xe dơ, chóc đuôi decal vị trí cửa tài xế', '2021-10-28', 1, '14:33 28-10-2021', 13743, 80, 'Như lúc tiếp nhận', 1, 1, 0, 0, NULL, 0, NULL, 0, NULL, '2021-10-28 00:47:32', '2021-10-28 07:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `dv`
--

CREATE TABLE `dv` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_guest_dv` int(10) UNSIGNED NOT NULL,
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `date_create` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soBaoGia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noiDungSuaChua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  `ghiChu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_type_guest` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daiDien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chucVu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mst` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cmnd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngayCap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noiCap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngaySinh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest_dv`
--

CREATE TABLE `guest_dv` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `date_create` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_car` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frame` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `km` int(11) NOT NULL DEFAULT '0',
  `user_update` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_cong`
--

CREATE TABLE `loai_cong` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_phu_tung`
--

CREATE TABLE `loai_phu_tung` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(283, '2014_10_12_000000_create_users_table', 1),
(284, '2014_10_12_100000_create_password_resets_table', 1),
(285, '2019_08_19_000000_create_failed_jobs_table', 1),
(286, '2021_08_08_070418_create_users_details_table', 1),
(287, '2021_08_08_071928_create_type_guests_table', 1),
(288, '2021_08_08_071942_create_guests_table', 1),
(289, '2021_08_08_072717_create_type_cars_table', 1),
(290, '2021_08_08_072728_create_type_car_details_table', 1),
(291, '2021_08_08_072802_create_car_sales_table', 1),
(292, '2021_08_08_074235_create_sales_table', 1),
(293, '2021_08_08_075302_create_bh_pk_packages_table', 1),
(294, '2021_08_08_075333_create_sale_offs_table', 1),
(295, '2021_08_08_080051_create_guest_dvs_table', 1),
(296, '2021_08_08_080601_create_d_v_s_table', 1),
(297, '2021_08_08_081101_create_loai_congs_table', 1),
(298, '2021_08_08_081142_create_loai_phu_tungs_table', 1),
(299, '2021_08_08_081154_create_congs_table', 1),
(300, '2021_08_08_081204_create_phu_tungs_table', 1),
(301, '2021_08_08_082016_create_xe_lai_thus_table', 1),
(302, '2021_08_08_082226_create_dang_ky_su_dungs_table', 1),
(303, '2021_08_08_102946_create_tai_lieus_table', 1),
(304, '2021_08_08_103138_create_nhoms_table', 1),
(305, '2021_08_08_103155_create_nhom_users_table', 1),
(306, '2021_08_08_103208_create_quyen_xems_table', 1),
(307, '2021_08_17_141055_create_roles_table', 1),
(308, '2021_08_17_141757_create_role_user_table', 1),
(309, '2021_08_31_133208_request_hd', 1),
(310, '2021_09_11_174300_create_cancel_h_d_s_table', 1),
(311, '2021_10_13_195319_report', 1),
(312, '2021_10_13_202424_report_car', 1),
(313, '2021_10_13_202628_report_work', 1),
(314, '2021_10_13_203711_report_nhap', 1),
(315, '2021_10_13_203724_report_xuat', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nhom`
--

CREATE TABLE `nhom` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhom_user`
--

CREATE TABLE `nhom_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_nhom` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phu_tung`
--

CREATE TABLE `phu_tung` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_dv` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `thue` int(11) NOT NULL DEFAULT '0',
  `id_loai_phu_tung` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quyen_xem`
--

CREATE TABLE `quyen_xem` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_tai_lieu` int(10) UNSIGNED NOT NULL,
  `id_nhom` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('pkd','pdv','mkt','xuong','cskh','hcns','it','ptdl','ketoan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngayReport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timeReport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_report` int(10) UNSIGNED NOT NULL,
  `doanhSoThang` int(11) DEFAULT NULL,
  `thiPhanThang` double(8,2) DEFAULT NULL,
  `xuatHoaDon` int(11) DEFAULT NULL,
  `xuatNgoaiTinh` int(11) DEFAULT NULL,
  `xuatTrongTinh` int(11) DEFAULT NULL,
  `hdHuy` int(11) DEFAULT NULL,
  `ctInternet` int(11) DEFAULT NULL,
  `ctShowroom` int(11) DEFAULT NULL,
  `ctHotline` int(11) DEFAULT NULL,
  `ctSuKien` int(11) DEFAULT NULL,
  `ctBLD` int(11) DEFAULT NULL,
  `saleInternet` int(11) DEFAULT NULL,
  `saleMoiGioi` int(11) DEFAULT NULL,
  `saleThiTruong` int(11) DEFAULT NULL,
  `khShowRoom` int(11) DEFAULT NULL,
  `baoDuong` int(11) DEFAULT NULL,
  `suaChua` int(11) DEFAULT NULL,
  `Dong` int(11) DEFAULT NULL,
  `Son` int(11) DEFAULT NULL,
  `congBaoDuong` int(11) DEFAULT NULL,
  `congSuaChuaChung` int(11) DEFAULT NULL,
  `congDong` int(11) DEFAULT NULL,
  `congSon` int(11) DEFAULT NULL,
  `dtPhuTung` int(11) DEFAULT NULL,
  `dtDauNhot` int(11) DEFAULT NULL,
  `dtPhuTungBan` int(11) DEFAULT NULL,
  `dtDauNhotBan` int(11) DEFAULT NULL,
  `phuTungMua` int(11) DEFAULT NULL,
  `dauNhotMua` int(11) DEFAULT NULL,
  `tonBaoDuong` int(11) DEFAULT NULL,
  `tonSuaChuaChung` int(11) DEFAULT NULL,
  `tonDong` int(11) DEFAULT NULL,
  `tonSon` int(11) DEFAULT NULL,
  `tiepNhanBaoDuong` int(11) DEFAULT NULL,
  `tiepNhanSuaChuaChung` int(11) DEFAULT NULL,
  `tiepNhanDong` int(11) DEFAULT NULL,
  `tiepNhanSon` int(11) DEFAULT NULL,
  `hoanThanhBaoDuong` int(11) DEFAULT NULL,
  `hoanThanhSuaChuaChung` int(11) DEFAULT NULL,
  `hoanThanhDong` int(11) DEFAULT NULL,
  `hoanThanhSon` int(11) DEFAULT NULL,
  `callDatHenSuccess` int(11) DEFAULT NULL,
  `callDatHenFail` int(11) DEFAULT NULL,
  `datHen` int(11) DEFAULT NULL,
  `dvHaiLong` int(11) DEFAULT NULL,
  `dvKhongHaiLong` int(11) DEFAULT NULL,
  `dvKhongThanhCong` int(11) DEFAULT NULL,
  `muaXeSuccess` int(11) DEFAULT NULL,
  `muaXeFail` int(11) DEFAULT NULL,
  `duyetBanLe` int(11) DEFAULT NULL,
  `knThaiDo` int(11) DEFAULT NULL,
  `knChatLuong` int(11) DEFAULT NULL,
  `knThoiGian` int(11) DEFAULT NULL,
  `knVeSinh` int(11) DEFAULT NULL,
  `knGiaCa` int(11) DEFAULT NULL,
  `knKhuyenMai` int(11) DEFAULT NULL,
  `knDatHen` int(11) DEFAULT NULL,
  `knTraiNghiem` int(11) DEFAULT NULL,
  `khBanGiao` int(11) DEFAULT NULL,
  `khSuKien` int(11) DEFAULT NULL,
  `clock` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `type`, `ngayReport`, `timeReport`, `user_report`, `doanhSoThang`, `thiPhanThang`, `xuatHoaDon`, `xuatNgoaiTinh`, `xuatTrongTinh`, `hdHuy`, `ctInternet`, `ctShowroom`, `ctHotline`, `ctSuKien`, `ctBLD`, `saleInternet`, `saleMoiGioi`, `saleThiTruong`, `khShowRoom`, `baoDuong`, `suaChua`, `Dong`, `Son`, `congBaoDuong`, `congSuaChuaChung`, `congDong`, `congSon`, `dtPhuTung`, `dtDauNhot`, `dtPhuTungBan`, `dtDauNhotBan`, `phuTungMua`, `dauNhotMua`, `tonBaoDuong`, `tonSuaChuaChung`, `tonDong`, `tonSon`, `tiepNhanBaoDuong`, `tiepNhanSuaChuaChung`, `tiepNhanDong`, `tiepNhanSon`, `hoanThanhBaoDuong`, `hoanThanhSuaChuaChung`, `hoanThanhDong`, `hoanThanhSon`, `callDatHenSuccess`, `callDatHenFail`, `datHen`, `dvHaiLong`, `dvKhongHaiLong`, `dvKhongThanhCong`, `muaXeSuccess`, `muaXeFail`, `duyetBanLe`, `knThaiDo`, `knChatLuong`, `knThoiGian`, `knVeSinh`, `knGiaCa`, `knKhuyenMai`, `knDatHen`, `knTraiNghiem`, `khBanGiao`, `khSuKien`, `clock`, `created_at`, `updated_at`) VALUES
(8, 'cskh', '18-10-2021', '17:55', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 3, 7, 4, 0, 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-18 09:52:53', '2021-10-18 10:01:55'),
(9, 'it', '18-10-2021', '19:48', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-18 12:05:11', '2021-10-18 12:10:48'),
(10, 'mkt', '18-10-2021', '20:16', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '2021-10-18 13:51:03', '2021-10-18 13:56:16'),
(11, 'hcns', '18-10-2021', '21:04', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-18 14:40:59', '2021-10-18 14:42:04'),
(12, 'it', '19-10-2021', '17:54', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-19 07:57:49', '2021-10-19 10:32:54'),
(14, 'cskh', '19-10-2021', '17:36', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 6, 2, 9, 1, 3, 1, 0, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-19 10:13:23', '2021-10-19 10:20:36'),
(15, 'mkt', '19-10-2021', '21:23', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, '2021-10-19 14:00:36', '2021-10-19 14:03:23'),
(16, 'hcns', '19-10-2021', '21:01', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-19 14:11:16', '2021-10-19 14:13:01'),
(17, 'it', '20-10-2021', '20:23', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 08:23:35', '2021-10-20 13:17:23'),
(18, 'xuong', '20-10-2021', '16:55', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 1, 2, 2, 10, 1, 2, 2, 10, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 09:21:16', '2021-10-20 09:21:55'),
(19, 'cskh', '20-10-2021', '17:42', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 18, 3, 15, 5, 9, 0, 0, 0, NULL, 2, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 10:17:05', '2021-10-20 10:24:42'),
(20, 'ptdl', '20-10-2021', '20:32', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 13:12:11', '2021-10-20 13:14:32'),
(21, 'mkt', '20-10-2021', '20:07', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 13:25:35', '2021-10-20 13:29:07'),
(22, 'hcns', '20-10-2021', '20:07', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-20 13:29:21', '2021-10-20 13:31:07'),
(23, 'pdv', '21-10-2021', '08:47', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 58, 89, 15, 15, 22465740, 37076548, 15652561, 40440302, 238626153, 26237670, 7843744, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-21 01:07:28', '2021-10-21 01:09:47'),
(24, 'it', '21-10-2021', '21:42', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-21 07:44:52', '2021-10-21 14:39:42'),
(25, 'cskh', '21-10-2021', '17:29', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 5, 1, 12, 1, 3, 2, 0, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2021-10-21 09:59:58', '2021-10-21 10:08:29'),
(26, 'ketoan', '21-10-2021', NULL, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2021-10-21 10:05:30', '2021-10-21 10:05:30'),
(27, 'xuong', '21-10-2021', '17:09', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-21 10:17:42', '2021-10-21 10:19:09'),
(28, 'mkt', '21-10-2021', '21:38', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '2021-10-21 14:38:29', '2021-10-21 14:44:38'),
(29, 'ptdl', '21-10-2021', '22:46', 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-21 15:56:07', '2021-10-21 15:58:46'),
(30, 'ketoan', '22-10-2021', '15:37', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 08:04:30', '2021-10-22 08:15:37'),
(31, 'pdv', '22-10-2021', '16:16', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60, 92, 19, 19, 23733040, 37343848, 18714861, 54233202, 244487413, 29846510, 11557234, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 09:30:55', '2021-10-22 09:34:16'),
(32, 'pkd', '22-10-2021', '20:44', 13, 19, 18.50, NULL, NULL, NULL, NULL, 3, 0, 0, 0, 0, 2, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 09:58:00', '2021-10-22 13:30:44'),
(33, 'it', '22-10-2021', '17:43', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 09:59:03', '2021-10-22 10:02:43'),
(34, 'xuong', '22-10-2021', '18:42', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 11:58:56', '2021-10-22 11:59:42'),
(35, 'cskh', '22-10-2021', '19:39', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 3, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 1, '2021-10-22 12:49:37', '2021-10-22 12:51:39'),
(36, 'ptdl', '22-10-2021', '20:06', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 13:04:14', '2021-10-22 13:06:06'),
(37, 'mkt', '22-10-2021', '20:55', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, 1, '2021-10-22 13:05:39', '2021-10-22 13:08:55'),
(38, 'hcns', '22-10-2021', '20:01', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-22 13:22:58', '2021-10-22 13:24:01'),
(39, 'it', '23-10-2021', '11:31', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-23 04:16:15', '2021-10-23 04:17:31'),
(40, 'xuong', '23-10-2021', '16:56', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-23 09:08:15', '2021-10-23 09:08:56'),
(41, 'pkd', '23-10-2021', '19:24', 13, 19, 18.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-23 12:16:44', '2021-10-23 12:20:24'),
(42, 'hcns', '23-10-2021', '20:30', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-23 13:29:27', '2021-10-23 13:29:30'),
(43, 'cskh', '24-10-2021', '19:29', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-24 12:57:27', '2021-10-24 12:59:29'),
(44, 'xuong', '25-10-2021', '16:06', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 6, 0, 1, 6, 6, 0, 1, 6, 5, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-25 09:50:04', '2021-10-25 09:53:06'),
(45, 'cskh', '25-10-2021', '17:14', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, 6, 0, NULL, NULL, NULL, 2, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-25 10:13:08', '2021-10-25 10:16:14'),
(46, 'pkd', '25-10-2021', '17:19', 13, 19, 18.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-25 10:17:49', '2021-10-25 10:25:19'),
(47, 'mkt', '25-10-2021', '21:15', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, NULL, 1, '2021-10-25 14:13:02', '2021-10-25 14:17:15'),
(48, 'xuong', '26-10-2021', '16:43', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 2, 2, 2, 7, 2, 2, 2, 7, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-26 09:43:49', '2021-10-26 09:47:43'),
(49, 'cskh', '26-10-2021', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 4, 7, 8, 0, 8, 3, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '2021-10-26 09:50:37', '2021-10-26 10:02:08'),
(50, 'ptdl', '26-10-2021', '17:24', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-26 09:58:17', '2021-10-26 10:05:24'),
(51, 'pkd', '26-10-2021', '17:13', 13, 19, 18.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-26 10:17:28', '2021-10-26 10:32:13'),
(52, 'ketoan', '27-10-2021', '15:45', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 08:06:14', '2021-10-27 08:12:45'),
(53, 'cskh', '27-10-2021', '17:59', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, 13, 5, 15, 0, 9, 6, 0, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 10:00:40', '2021-10-27 10:10:59'),
(54, 'xuong', '27-10-2021', '17:01', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 5, 4, 4, 5, 5, 4, 4, 5, 3, 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 10:04:30', '2021-10-27 10:07:01'),
(55, 'pkd', '27-10-2021', '17:03', 13, 19, 18.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 10:38:29', '2021-10-27 10:45:03'),
(56, 'it', '27-10-2021', '18:33', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 11:45:34', '2021-10-27 11:46:33'),
(57, 'mkt', '27-10-2021', '22:45', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '2021-10-27 14:58:37', '2021-10-27 15:28:45'),
(58, 'ptdl', '27-10-2021', '22:35', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-27 15:04:43', '2021-10-27 15:07:35'),
(59, 'mkt', '28-10-2021', '17:15', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, NULL, 1, '2021-10-28 10:01:00', '2021-10-28 10:05:15'),
(60, 'pkd', '28-10-2021', '17:55', 13, 19, 18.50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-28 10:22:29', '2021-10-28 10:26:55'),
(61, 'xuong', '28-10-2021', '18:59', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 2, 0, 0, 6, 2, 0, 0, 6, 3, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-28 11:08:21', '2021-10-28 11:09:59'),
(62, 'xuong', '29-10-2021', '17:39', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 2, 2, 4, 5, 2, 2, 4, 5, 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-10-29 10:05:21', '2021-10-29 10:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `report_car`
--

CREATE TABLE `report_car` (
  `id` int(10) UNSIGNED NOT NULL,
  `ngayTao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_report` int(10) UNSIGNED NOT NULL,
  `soLuong` int(11) NOT NULL,
  `dongXe` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_car`
--

INSERT INTO `report_car` (`id`, `ngayTao`, `id_report`, `soLuong`, `dongXe`, `created_at`, `updated_at`) VALUES
(3, '25-10-2021', 46, 1, 9, '2021-10-25 10:19:05', '2021-10-25 10:19:05'),
(4, '27-10-2021', 55, 1, 2, '2021-10-27 10:43:57', '2021-10-27 10:43:57'),
(5, '27-10-2021', 55, 1, 4, '2021-10-27 10:44:15', '2021-10-27 10:44:15'),
(6, '27-10-2021', 55, 1, 8, '2021-10-27 10:44:20', '2021-10-27 10:44:20'),
(7, '28-10-2021', 60, 1, 4, '2021-10-28 10:25:57', '2021-10-28 10:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `report_nhap`
--

CREATE TABLE `report_nhap` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_report` int(10) UNSIGNED NOT NULL,
  `nhaCungCap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hanMuc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soLuong` int(11) DEFAULT NULL,
  `tongTon` int(11) DEFAULT NULL,
  `ghiChu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_nhap`
--

INSERT INTO `report_nhap` (`id`, `id_report`, `nhaCungCap`, `hanMuc`, `soLuong`, `tongTon`, `ghiChu`, `created_at`, `updated_at`) VALUES
(1, 11, 'Viettel', 'VPP', NULL, 0, 'Giấy A4', '2021-10-18 14:41:14', '2021-10-18 14:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `report_work`
--

CREATE TABLE `report_work` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_report` int(10) UNSIGNED NOT NULL,
  `tenCongViec` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tienDo` int(11) DEFAULT NULL,
  `type` enum('cv','drp','ksnb','dt') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadLine` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketQua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghiChu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_work`
--

INSERT INTO `report_work` (`id`, `id_report`, `tenCongViec`, `tienDo`, `type`, `deadLine`, `ketQua`, `ghiChu`, `created_at`, `updated_at`) VALUES
(3, 8, 'Theo dõi và tạo lich hẹn', 100, 'cv', NULL, 'Hoàn thành', 'theo dõi nhắc hẹn KH', '2021-10-18 09:54:19', '2021-10-18 09:54:19'),
(4, 8, 'Họp tuần', 100, 'cv', NULL, 'Hoàn thành', 'Theo dõi các khiếu nại, góp ý PDV chưa hoàn thiện', '2021-10-18 09:55:01', '2021-10-18 09:55:01'),
(5, 8, 'Hỗ trợ quy trình tích điểm, thanh toán thẻ', 100, 'cv', NULL, '- Đối chiếu thanh toán thẻ (Hoàn thành). Mail nhờ idocnet hỗ trợ tích điểm thẻ', 'theo dõi mail phản hồi', '2021-10-18 09:57:17', '2021-10-18 09:57:17'),
(6, 8, 'Chuyển thông tin yêu cầu hỗ trợ của KH đến các phòng ban', 100, 'cv', NULL, 'Đã hoàn thành.', NULL, '2021-10-18 09:58:22', '2021-10-18 09:58:22'),
(7, 8, 'Chỉnh sửa KH LXAT', 80, 'cv', '2021-10-20', NULL, NULL, '2021-10-18 10:01:06', '2021-10-18 10:01:06'),
(8, 9, 'Đấu nối và đi dây mạng', 50, 'cv', '2021-10-23', 'Đã đi dây mạng cho MKT và TPKD', NULL, '2021-10-18 12:06:18', '2021-10-18 12:06:18'),
(9, 9, 'Đấu nối và mở máy chủ tạm thời cho CS làm việc', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 12:07:02', '2021-10-18 12:07:02'),
(10, 9, 'Gắn tạm máy chủ Carsoft sử dụng', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 12:07:28', '2021-10-18 12:07:28'),
(11, 9, 'Viết phần mềm hỗ trợ báo cáo và lái thử', 60, 'cv', '2021-10-31', 'Đang triển khai và thực hiên từng phần', NULL, '2021-10-18 12:08:12', '2021-10-18 12:08:12'),
(12, 9, 'Gắn máy, dây mạng và điện thoại cho Admin', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 12:09:16', '2021-10-18 12:09:16'),
(13, 9, 'Đấu nối tạm các đầu ghi camera', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 12:10:06', '2021-10-18 12:10:06'),
(14, 10, 'Chụp và post hình ảnh truyền thông chương trình KMDV', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 13:51:24', '2021-10-18 13:51:24'),
(15, 10, 'Gửi HTV DK KMDV T10-11: Comeback Hyundai - Thổi bay Covid (15/10 - 15/11)', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 13:51:59', '2021-10-18 13:51:59'),
(16, 10, 'Thiết kế backdrop, photoframe, hashtag và xin báo giá thi công ấn phẩm trưng bày \"Tuần lễ tôn vinh phụ nữ\"', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 13:52:45', '2021-10-18 13:52:45'),
(17, 10, 'Trao đổi với sếp và liên hệ đặt gạo - hỗ trợ người dân Tịnh Biên (500 kg)', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 13:54:25', '2021-10-18 13:54:25'),
(18, 10, 'Hoàn thành 71 túi \"Gói hỗ trợ Công Đoàn\"', 100, 'cv', '2021-10-18', 'Hoàn thành', NULL, '2021-10-18 13:55:06', '2021-10-18 13:55:06'),
(19, 10, 'Chuẩn bị kế hoạch Công đoàn 20/10', 70, 'cv', '2021-10-19', 'Đang thực hiện', NULL, '2021-10-18 13:55:57', '2021-10-18 13:55:57'),
(20, 11, 'Xử lý các số liệu năm 2017 2018 2019 để gửi Thuế', 10, 'cv', '2021-10-24', 'Chưa hoàn thành', NULL, '2021-10-18 14:41:58', '2021-10-18 14:41:58'),
(21, 12, 'Kiểm tra kiosk', 100, 'cv', '2021-10-19', 'Phát hiện hư hỏng', 'Đề nghị sửa chửa', '2021-10-19 07:58:45', '2021-10-19 07:58:45'),
(22, 12, 'Cài đặt phần mềm kiosk tạm vô máy mới', 100, 'cv', '2021-10-19', 'Đã cài đặt', NULL, '2021-10-19 07:59:38', '2021-10-19 07:59:38'),
(23, 12, 'Kiểm tra Máy tính trưởng phòng kinh doanh', 100, 'cv', '2021-10-19', 'Hư bộ nguồn', 'Đề nghị mua mới', '2021-10-19 08:00:08', '2021-10-19 08:00:08'),
(24, 12, 'Kiểm tra hộp mực showroom', 100, 'cv', '2021-10-19', 'Bị hỏng', 'Đề nghị mua mới', '2021-10-19 08:02:18', '2021-10-19 08:02:18'),
(25, 12, 'Kiểm tra hộp mực phòng dịch vụ', 100, 'cv', '2021-10-19', 'Bị hỏng', 'Đề nghị mua mới', '2021-10-19 08:02:38', '2021-10-19 08:02:38'),
(26, 14, 'Khảo sát HTV về chương trình tri ân', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-19 10:14:18', '2021-10-19 10:14:18'),
(27, 14, 'Chỉnh sửa chương trình LXAT', 88, 'cv', '2021-10-20', NULL, NULL, '2021-10-19 10:15:23', '2021-10-19 10:15:23'),
(28, 14, 'Check các khâu chương trình LXAT', 71, 'cv', '2021-10-21', NULL, NULL, '2021-10-19 10:16:25', '2021-10-19 10:16:25'),
(29, 14, 'Liên hệ KH tham gia LXAT', 50, 'cv', '2021-10-20', NULL, NULL, '2021-10-19 10:17:57', '2021-10-19 10:17:57'),
(30, 12, 'Đề nghị bảo trì sửa chữa', 100, 'cv', '2021-10-19', 'Đã lập đề nghị chờ ký', NULL, '2021-10-19 10:19:08', '2021-10-19 10:19:08'),
(31, 15, 'Set up khu vực trưng bày theo chủ đề 20/10', 100, 'cv', '2021-10-19', 'Hoàn thành', NULL, '2021-10-19 14:00:53', '2021-10-19 14:00:53'),
(32, 15, 'Post thông tin truyền thông chương trình \"Tuần lễ tôn vinh phụ nữ Việt Nam\"trên Fanpage', 100, 'cv', '2021-10-19', 'Hoàn thành', NULL, '2021-10-19 14:01:26', '2021-10-19 14:01:26'),
(33, 15, 'Lên kế hoạch chuẩn bị quà tặng 20/10 cho nhân viên nữ công ty', 90, 'cv', '2021-10-19', 'Đang thực hiện', NULL, '2021-10-19 14:01:54', '2021-10-19 14:01:54'),
(34, 15, 'Lấy thêm gạo hỗ trợ cấp quản lý', 100, 'cv', '2021-10-19', 'Hoàn thành', NULL, '2021-10-19 14:02:16', '2021-10-19 14:02:16'),
(35, 15, 'Trình bày với BLĐ về chương trình Tuần lễ tôn vinh phụ nữ Việt Nam', 100, 'cv', '2021-10-19', 'Hoàn thành', NULL, '2021-10-19 14:02:56', '2021-10-19 14:02:56'),
(36, 16, 'Xử lý số liệu thuế năm 2017 2018 2019', 20, 'cv', '2021-10-22', 'Chưa xong', NULL, '2021-10-19 14:12:55', '2021-10-19 14:12:55'),
(37, 17, 'Đấu nối và đi dây mạng', 60, 'cv', '2021-10-23', 'Đã đi dây mạng cho icic', NULL, '2021-10-20 08:24:05', '2021-10-20 08:24:05'),
(38, 17, 'Viết phần mềm hỗ trợ báo cáo và lái thử', 65, 'cv', '2021-10-31', 'Đang trong quá trình tối ưu và test', NULL, '2021-10-20 08:24:40', '2021-10-20 08:24:40'),
(39, 17, 'Hỗ trợ chở kế toán đi Ngân hàng', 100, 'cv', '2021-10-20', 'Đã hỗ trợ', NULL, '2021-10-20 08:25:06', '2021-10-20 08:25:06'),
(40, 17, 'Hỗ trợ scan tài liệu cho PKT và viết hướng dẫn scan', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 08:25:30', '2021-10-20 08:25:30'),
(41, 17, 'Tổ chức họp online cho PKD', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 08:26:11', '2021-10-20 08:26:11'),
(42, 19, 'Trình ký và theo dõi các công việc của LXAT', 95, 'cv', '2021-10-21', NULL, NULL, '2021-10-20 10:17:58', '2021-10-20 10:17:58'),
(43, 19, 'Tạo và theo dõi hẹn', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-20 10:18:21', '2021-10-20 10:18:21'),
(44, 19, 'Gọi KH LXAT', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-20 10:19:20', '2021-10-20 10:19:20'),
(45, 19, 'Cập nhật và theo dõi khiếu nại', 75, 'cv', NULL, NULL, NULL, '2021-10-20 10:19:46', '2021-10-20 10:19:46'),
(46, 20, 'Làm danh sách nhân viên đậu xe', 100, 'cv', '2021-10-20', 'Đã thông báo toàn công ty', NULL, '2021-10-20 13:12:50', '2021-10-20 13:12:50'),
(47, 20, 'Tổng hợp báo cáo các năm', 60, 'cv', '2021-10-20', 'Đang hoàn thiện', NULL, '2021-10-20 13:13:21', '2021-10-20 13:13:21'),
(48, 20, 'Liên hệ bên thi công may bạt dù', 70, 'cv', '2021-10-20', 'Đã liên hệ báo giá, đang đợi trích lục và doạ giá thêm', NULL, '2021-10-20 13:14:13', '2021-10-20 13:14:13'),
(49, 21, 'Setup chương trình \"Tuần lễ tôn vinh Phụ nữ\" và chạy chương trình', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 13:26:54', '2021-10-20 13:26:54'),
(50, 21, 'Mua sen đá tặng quà Công Đoàn 20/10', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 13:27:21', '2021-10-20 13:27:21'),
(51, 21, 'Làm và gửi báo cáo đối thủ', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 13:27:46', '2021-10-20 13:27:46'),
(52, 21, 'Tổ chức tặng gói quà công đoàn hỗ trợ nhân viên', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 13:28:18', '2021-10-20 13:28:18'),
(53, 21, 'Họp với HTV về kế hoạch Quý 4', 100, 'cv', '2021-10-20', 'Hoàn thành', NULL, '2021-10-20 13:28:55', '2021-10-20 13:28:55'),
(54, 22, 'Xử lý số liệu năm 2017, 2018, 2019 gửi thuế', 30, 'cv', '2021-10-22', 'Hoàn thành các sổ năm 2018', NULL, '2021-10-20 13:30:19', '2021-10-20 13:30:19'),
(55, 22, 'Chuẩn bị quà công đoàn ngày 20/10', 100, 'cv', '2021-10-20', 'Đã hoàn thành', NULL, '2021-10-20 13:30:47', '2021-10-20 13:30:47'),
(56, 24, 'Viết phần mềm hỗ trợ báo cáo và lái thử', 70, 'cv', '2021-10-23', 'Đang thực hiện', NULL, '2021-10-21 07:45:56', '2021-10-21 07:45:56'),
(57, 24, 'Sửa máy in 02 mặt phòng dịch vụ', 100, 'cv', '2021-10-21', 'Xong', NULL, '2021-10-21 07:46:20', '2021-10-21 07:46:20'),
(58, 25, 'Tạo thẻ hội viên', 100, 'cv', NULL, '5 thẻ (Hoàn thành)', NULL, '2021-10-21 10:00:09', '2021-10-21 10:00:09'),
(59, 25, 'Nhắc BD 1000 km đầu tiên', 100, 'cv', NULL, '2 lượt (Hoàn thành)', NULL, '2021-10-21 10:01:04', '2021-10-21 10:01:04'),
(60, 25, 'set up lại phòng làm việc', 100, 'cv', NULL, 'hoàn thành', NULL, '2021-10-21 10:02:22', '2021-10-21 10:02:22'),
(61, 25, 'Trình ký LXAT, gửi  lại bảng chốt với HTV', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-21 10:03:39', '2021-10-21 10:03:39'),
(62, 25, 'Xuất kho quà tặng', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-21 10:03:58', '2021-10-21 10:03:58'),
(63, 28, 'Gửi HTV báo cáo MKT đối thủ hoàn chỉnh', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:39:37', '2021-10-21 14:39:37'),
(64, 24, 'Tra cứu camera hỗ trợ các bộ phận', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:39:38', '2021-10-21 14:39:38'),
(65, 28, 'Chỉnh sửa và gửi in thiết kế standee LXAT theo mẫu CI mới', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:40:15', '2021-10-21 14:40:15'),
(66, 28, 'Chuẩn bị file trình chiếu background LXAT', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:40:39', '2021-10-21 14:40:39'),
(67, 28, 'Thống kê data khách hàng theo tuần gửi HTV', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:42:31', '2021-10-21 14:42:31'),
(68, 28, 'Thống kê & Chỉnh sửa ấn phẩm catalog chuẩn bị thay đổi mẫu CI mới', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:43:40', '2021-10-21 14:43:40'),
(69, 28, 'Tạo \"sự kiện\" online CT \"Tuần lễ tôn vinh phụ nữ VN\" theo kế hoạch', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:44:12', '2021-10-21 14:44:12'),
(70, 28, 'Đo kích thước thay đổi decan xe lái thử 3 xe demo theo mẫu mới', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 14:44:25', '2021-10-21 14:44:25'),
(71, 29, 'Giám sát hoàn thành đường điện P CSKH', 100, 'cv', '2021-10-21', 'Đã hoàn thành PCSKH', NULL, '2021-10-21 15:56:37', '2021-10-21 15:56:37'),
(72, 29, 'Tạo File đề nghị xử lý hàng hoá', 100, 'cv', '2021-10-21', 'Hoàn thành', NULL, '2021-10-21 15:57:09', '2021-10-21 15:57:09'),
(73, 29, 'Kiểm tra DRP các phòng ban', 100, 'cv', '2021-10-21', 'Giám sát thường xuyên', NULL, '2021-10-21 15:58:28', '2021-10-21 15:58:28'),
(74, 30, 'Cung cấp file số liệu năm 2020 cho thuế', 100, 'cv', '2021-10-22', 'Đã hoàn thành', NULL, '2021-10-22 08:05:45', '2021-10-22 08:05:45'),
(75, 30, 'Cập nhật số dư nội bộ vào PM Amis 2021', 60, 'cv', '2021-10-27', NULL, NULL, '2021-10-22 08:14:22', '2021-10-22 08:14:22'),
(76, 33, 'Đấu nối và đi dây mạng', 100, 'cv', '2021-10-22', 'Hoàn thành', 'Phòng kinh doanh', '2021-10-22 09:59:51', '2021-10-22 09:59:51'),
(77, 33, 'Thiết lập và đấu nối thiết bị chuẩn bị cho KTV thi online', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 10:00:26', '2021-10-22 10:00:26'),
(78, 33, 'Mua thiết bị sữa chữa kiosk, cây mực, ổ cứng', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 10:00:54', '2021-10-22 10:00:54'),
(79, 33, 'Sữa chữa Kiosk', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 10:01:07', '2021-10-22 10:01:07'),
(80, 33, 'Thay thế cây mực', 100, 'cv', '2021-10-22', 'Hoàn thành', 'Phòng dịch vụ, Showroom', '2021-10-22 10:01:29', '2021-10-22 10:01:29'),
(81, 33, 'Viết phần mềm hỗ trợ báo cáo và lái thử', 75, 'cv', '2021-10-31', 'Đang thực hiện', 'Tính năng báo nhanh bộ phận chưa báo cáo', '2021-10-22 10:02:18', '2021-10-22 10:02:18'),
(82, 35, 'Chạy thử chương trình LXAT', 100, 'cv', NULL, 'Hoàn thành chạy thử', NULL, '2021-10-22 12:50:52', '2021-10-22 12:50:52'),
(83, 36, 'Mua và tặng 60 lít cồn + bình xịt cho UBND P.Mỹ Thới', 100, 'cv', '2021-10-22', 'Hoàn thành', 'Chưa làm thanh toán', '2021-10-22 13:04:21', '2021-10-22 13:04:21'),
(84, 36, 'Hoàn bài nội dung chương trình LXAT và chạy thử', 100, 'cv', '2021-10-22', 'Hoàn thành nội dung', NULL, '2021-10-22 13:04:54', '2021-10-22 13:04:54'),
(85, 36, 'Theo dõi thay bóng đèn khu vực xưởng (Khoang QS, Phòng ráp máy)', 100, 'cv', '2021-10-22', 'Hoàn thành', 'Chưa làm thanh toán', '2021-10-22 13:05:45', '2021-10-22 13:05:45'),
(86, 37, 'Gửi duyệt mẫu và giá:  catalog, thông số kỹ thuật, tem dán đuôi xe, decan website , decan xe lái thử - CI mới', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:05:57', '2021-10-22 13:05:57'),
(87, 37, 'Hỗ trợ dán tem bình xịt khuẩn và trao tặng phường Mỹ Th', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:06:16', '2021-10-22 13:06:16'),
(88, 37, 'Hỗ trợ set up & chạy thử chương trình LXAT online', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:06:29', '2021-10-22 13:06:29'),
(89, 37, 'Thiết kế và đăng bài Facebook thông tin tuyển dụng, hỗ trợ cồn xịt khuẩn Phường Mỹ Thới', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:07:24', '2021-10-22 13:07:24'),
(90, 37, 'Chuẩn bị hình ảnh báo cáo CSR - Hỗ trợ Công An tỉnh An Giang 100tr', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:07:55', '2021-10-22 13:07:55'),
(91, 37, 'Trao đổi với anh Tuấn - GoGo Carspa về vị trí đặt pano và hẹn ngày mai thi công', 100, 'cv', '2021-10-22', 'Hoàn thành', NULL, '2021-10-22 13:08:34', '2021-10-22 13:08:34'),
(92, 38, 'Xử lý số liệu các năm gửi Thuế', 36, 'cv', '2021-10-27', 'Hoàn thành các sổ năm 2020', NULL, '2021-10-22 13:23:55', '2021-10-22 13:23:55'),
(93, 39, 'Cài đặt và giám sát thi online phòng dịch vụ', 100, 'cv', '2021-10-23', 'Hoàn thành', NULL, '2021-10-23 04:16:45', '2021-10-23 04:16:45'),
(94, 43, 'Thực hiện chương trình LXAT', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-24 12:59:05', '2021-10-24 12:59:05'),
(95, 45, 'Họp tuần', 0, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-25 10:13:54', '2021-10-25 10:13:54'),
(96, 45, 'Báo cáo LXAT', 60, 'cv', NULL, 'Bổ sung địa chỉ KH, số lượng và chi phí quà tặng', NULL, '2021-10-25 10:14:41', '2021-10-25 10:14:41'),
(97, 46, 'KIỂM TRA KHÁCH HÀNG MKT T10 TỪ SALE', 70, 'cv', '2021-10-25', NULL, 'CÒN 30% CẬP NHẬT VÀ XỬ LÝ VẤN ĐỀ', '2021-10-25 10:20:16', '2021-10-25 10:20:16'),
(98, 46, 'VỆ SINH', 100, 'cv', '2021-10-25', 'VỆ SINH SẠCH XE SHOWROOM BẰNG DUNG DỊCH CỒN KÈM VỆ SINH BỤI RẠCH - VỆ SINH KHỬ TRÙNG PKD VÀ XỬ LÝ KEO NỀN RẠCH.', NULL, '2021-10-25 10:22:03', '2021-10-25 10:22:03'),
(99, 46, 'HỌP PKD', 100, 'cv', NULL, '9:30 ĐẾN 112H VÀ TỪ 15H ĐẾN 16H30', NULL, '2021-10-25 10:25:13', '2021-10-25 10:25:13'),
(100, 47, 'Tìm hiểu và lập kế hoạch chạy quảng cáo GDN', 40, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-25 14:13:54', '2021-10-25 14:13:54'),
(101, 47, 'Post FB hình ảnh truyền thông sau chương trình LXAT', 100, 'cv', '2021-10-25', 'Hoàn thành', NULL, '2021-10-25 14:14:26', '2021-10-25 14:14:26'),
(102, 47, 'Theo dõi và hoàn thành dán decan xe lái thử Accent - CI mới HTV', 80, 'cv', '2021-10-26', 'Đang thực hiện', 'Còn phần cửa kính phía sau xe', '2021-10-25 14:15:02', '2021-10-25 14:15:02'),
(103, 47, 'Lên kế hoạch xây dựng cộng đồng những người yêu ô tô Hyundai tại An Giang', 20, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-25 14:15:49', '2021-10-25 14:15:49'),
(104, 47, 'Lọc danh sách KHTN từ tháng 3 đến hiện có quan tâm i10 để chăm sóc lại nhằm thúc đẩy hàng tồn', 100, 'cv', '2021-10-25', 'Hoàn thành', 'Đã bàn giao cho Linh để phân công chăm sóc lại', '2021-10-25 14:17:07', '2021-10-25 14:17:07'),
(105, 49, 'Theo dõi KCBL', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-26 09:54:18', '2021-10-26 09:54:18'),
(106, 49, 'Liên hệ xác nhận lịch hẹn DV', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-26 09:54:48', '2021-10-26 09:54:48'),
(107, 49, 'báo cáo LXAT', 100, 'cv', NULL, 'hoàn thành', NULL, '2021-10-26 09:55:05', '2021-10-26 09:55:05'),
(108, 49, 'Nhập kho quà tặng LXAT', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-26 09:56:37', '2021-10-26 09:56:37'),
(109, 50, 'Đánh giá DRP từ xa tháng 10/2021', 100, 'cv', '2021-10-26', 'Hoàn thành, đã gửi mail', NULL, '2021-10-26 09:58:44', '2021-10-26 09:58:44'),
(110, 50, 'Trình đề nghị thanh toán kính phòng CSKH - PGĐ', 100, 'cv', '2021-10-26', 'Chờ chuyển khoản', NULL, '2021-10-26 09:59:19', '2021-10-26 09:59:19'),
(111, 50, 'Tiếp nhận lại bình chữa cháy gửi bơm', 90, 'cv', '2021-10-26', 'Nhà cung cấp nợ 01 bình', 'Chưa thanh toán', '2021-10-26 10:00:15', '2021-10-26 10:00:15'),
(112, 50, 'Cung cấp dữ liệu và trao đổi nội dung bài phỏng vấn GĐ ĐL với anh Khiêm', 90, 'cv', '2021-10-26', 'Chờ anh Khiêm chỉnh sửa', NULL, '2021-10-26 10:00:52', '2021-10-26 10:00:52'),
(113, 50, 'Mua cồn ủng hộ UBND phường Mỹ Hoà', 50, 'cv', '2021-10-26', 'Chờ MKT in bảng dán và trao tặng', NULL, '2021-10-26 10:02:06', '2021-10-26 10:02:06'),
(114, 50, 'Chỉnh sửa KPI MKT', 60, 'cv', '2021-10-27', 'Đang thực hiện', NULL, '2021-10-26 10:02:34', '2021-10-26 10:02:34'),
(115, 50, 'Tổ chức lấy mẫu xét nghiệm', 100, 'cv', '2021-10-26', 'Chờ hoá đơn thanh toán', NULL, '2021-10-26 10:03:53', '2021-10-26 10:03:53'),
(116, 51, 'cập nhật dât khách hàng MKT', 100, 'cv', '2021-10-26', 'HOÀN THÀNH', NULL, '2021-10-26 10:31:44', '2021-10-26 10:31:44'),
(117, 51, 'CẬP NHẬT NỘI QUY', 60, 'cv', '2021-10-26', NULL, NULL, '2021-10-26 10:32:09', '2021-10-26 10:32:09'),
(118, 52, 'Xóa các số dư các TK trong bảng cân đối kế toán', 50, 'cv', '2021-10-28', NULL, NULL, '2021-10-27 08:10:12', '2021-10-27 08:10:12'),
(119, 52, 'Xác định lại các số dư của các tài khoản để nhập lên data 2021', 90, 'cv', '2021-10-28', NULL, NULL, '2021-10-27 08:11:51', '2021-10-27 08:11:51'),
(120, 53, 'Báo cáo chương trình Lái xe an toàn', 94, 'cv', NULL, 'Đợi trình ký và gửi file báo cáo HTV', NULL, '2021-10-27 10:01:37', '2021-10-27 10:01:37'),
(121, 53, 'Theo dõi và rà soát các thông tin KH kiểm chứng bán lẻ', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-27 10:02:37', '2021-10-27 10:02:37'),
(122, 53, 'Gửi tin nhắn chúc mừng sinh nhật KH tháng 11', 100, 'cv', NULL, 'Hoàn thành 119 tin nhắn', NULL, '2021-10-27 10:03:28', '2021-10-27 10:03:28'),
(123, 53, 'Tạo thẻ hội viên', 100, 'cv', NULL, '4 thẻ', NULL, '2021-10-27 10:03:46', '2021-10-27 10:03:46'),
(124, 53, 'Theo dõi  và tạo hẹn, chuyển thông tin đến các phòng ban', 100, 'cv', NULL, 'Hoàn thành', NULL, '2021-10-27 10:06:16', '2021-10-27 10:06:16'),
(125, 55, 'kiểm tra khách hàng quan tâm i10 sau tháng 10', 100, 'cv', '2021-10-27', NULL, NULL, '2021-10-27 10:44:53', '2021-10-27 10:44:53'),
(126, 56, 'Cài đặt nâng cấp ổ cứng cố vấn', 100, 'cv', '2021-10-27', 'Hoàn thành', NULL, '2021-10-27 11:45:56', '2021-10-27 11:45:56'),
(127, 56, 'Viết phần mềm module báo cáo, giao việc', 40, 'cv', '2021-10-31', 'Đang thực hiện', NULL, '2021-10-27 11:46:29', '2021-10-27 11:46:29'),
(128, 58, 'Xây dựng kịch bản covid 19', 90, 'cv', '2021-10-28', 'Bổ sung bảng hỗ trợ nhân viên dịch', NULL, '2021-10-27 15:05:34', '2021-10-27 15:05:34'),
(129, 58, 'Tham dự phỏng vấn ứng viên Rửa xe, Phụ Tùng, CVDV', 100, 'cv', '2021-10-27', 'Đã tuyển đủ', NULL, '2021-10-27 15:06:15', '2021-10-27 15:06:15'),
(130, 58, 'Hỗ trợ việc cúng', 100, 'cv', '2021-10-27', 'Hoàn thành', NULL, '2021-10-27 15:06:34', '2021-10-27 15:06:34'),
(131, 58, 'Làm phiếu theo dõi bình chữa cháy', 50, 'cv', '2021-10-28', 'Đang thực hiện', NULL, '2021-10-27 15:07:27', '2021-10-27 15:07:27'),
(132, 57, 'Quay clip: An toàn mùa dịch', 100, 'cv', '2021-10-27', 'Hoàn thành', NULL, '2021-10-27 15:25:53', '2021-10-27 15:25:53'),
(133, 57, 'Lập kế hoạch chạy QC google ads tháng 11', 70, 'cv', '2021-10-28', 'Đang thực hiện', NULL, '2021-10-27 15:26:35', '2021-10-27 15:26:35'),
(134, 57, 'Chụp ảnh và tặng quà cho khách hàng nữ sự kiện 20/10 (2 KH)', 100, 'cv', '2021-10-27', 'Hoàn thành', NULL, '2021-10-27 15:27:03', '2021-10-27 15:27:03'),
(135, 57, 'Gửi HTV ĐK MKT Trải nghiệm tại nhà - An toàn mùa dịch', 100, 'cv', '2021-10-27', 'Hoàn thành', NULL, '2021-10-27 15:27:22', '2021-10-27 15:27:22'),
(136, 57, 'Lên kế hoạch Car club', 45, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-27 15:27:57', '2021-10-27 15:27:57'),
(137, 57, 'Dựng clip Lái thử tại nhà - An toàn mùa dịch', 20, 'cv', '2021-10-30', 'Đang thực hiện', NULL, '2021-10-27 15:28:34', '2021-10-27 15:28:34'),
(138, 59, 'Dựng video lái thử tại nhà', 35, 'cv', '2021-10-30', 'Đang thực hiện', NULL, '2021-10-28 10:01:24', '2021-10-28 10:01:24'),
(139, 59, 'Thiết kế Landing page và kế hoạch chạy Google Ads Tháng 11', 80, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-28 10:01:59', '2021-10-28 10:01:59'),
(140, 59, 'Lên kế hoạch Car club', 80, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-28 10:02:22', '2021-10-28 10:02:22'),
(141, 59, 'Theo dõi và quản lý xe lái thử', 100, 'cv', '2021-10-28', 'Hoàn thành', NULL, '2021-10-28 10:02:56', '2021-10-28 10:02:56'),
(142, 59, 'Lên kế hoạch chạy GDN tháng 11', 70, 'cv', '2021-10-29', 'Đang thực hiện', NULL, '2021-10-28 10:05:02', '2021-10-28 10:05:02'),
(143, 60, 'đề xuất lương cho CTV', 80, 'cv', '2021-10-28', NULL, NULL, '2021-10-28 10:26:28', '2021-10-28 10:26:28'),
(144, 60, 'HOÀN THÀNH KẾ HAOCHJ BÁN HÀNG THÁNG 11', 100, 'cv', '2021-10-28', NULL, NULL, '2021-10-28 10:26:51', '2021-10-28 10:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `report_xuat`
--

CREATE TABLE `report_xuat` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_report` int(10) UNSIGNED NOT NULL,
  `tenNhanVien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hanMuc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soLuong` int(11) DEFAULT NULL,
  `tongTon` int(11) DEFAULT NULL,
  `ghiChu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_xuat`
--

INSERT INTO `report_xuat` (`id`, `id_report`, `tenNhanVien`, `hanMuc`, `soLuong`, `tongTon`, `ghiChu`, `created_at`, `updated_at`) VALUES
(1, 11, 'Phòng dịch vụ', 'VPP', NULL, NULL, 'Xuất giấy A4', '2021-10-18 14:41:37', '2021-10-18 14:41:37'),
(2, 16, 'Trương Thanh Huy', 'VPP', 1, 0, 'Ổ khoá', '2021-10-19 14:11:54', '2021-10-19 14:11:54'),
(3, 16, 'Nguyễn Thanh Thuý', 'VPP', NULL, 4, 'Giấy A4', '2021-10-19 14:12:17', '2021-10-19 14:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `request_hd`
--

CREATE TABLE `request_hd` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `car_detail_id` int(10) UNSIGNED NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tamUng` int(11) DEFAULT NULL,
  `giaXe` int(11) DEFAULT NULL,
  `admin_check` tinyint(1) NOT NULL DEFAULT '0',
  `sale_id` int(10) UNSIGNED DEFAULT NULL,
  `guest_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'system', NULL, NULL),
(2, 'adminsale', NULL, NULL),
(3, 'admindv', NULL, NULL),
(4, 'tpkd', NULL, NULL),
(5, 'tpdv', NULL, NULL),
(6, 'xuong', NULL, NULL),
(7, 'sale', NULL, NULL),
(8, 'boss', NULL, NULL),
(9, 'mkt', NULL, NULL),
(10, 'ketoan', NULL, NULL),
(11, 'cskh', NULL, NULL),
(12, 'drp', NULL, NULL),
(13, 'hcns', NULL, NULL),
(14, 'it', NULL, NULL),
(15, 'normal', NULL, NULL),
(16, 'sub1', NULL, NULL),
(17, 'sub2', NULL, NULL),
(18, 'lead_sub1', NULL, NULL),
(19, 'lead_sub2', NULL, NULL),
(20, 'lead', NULL, NULL),
(21, 'report', NULL, NULL),
(22, 'watch', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(1, 1),
(15, 2),
(21, 2),
(4, 2),
(15, 3),
(21, 3),
(6, 3),
(15, 4),
(21, 4),
(12, 4),
(22, 4),
(20, 2),
(15, 5),
(21, 5),
(11, 5),
(15, 6),
(15, 7),
(15, 8),
(21, 6),
(9, 6),
(21, 8),
(14, 8),
(5, 7),
(21, 7),
(20, 7),
(15, 9),
(21, 9),
(13, 9),
(15, 11),
(21, 11),
(8, 11),
(22, 11),
(15, 12),
(10, 12),
(21, 12),
(21, 13),
(20, 13),
(15, 13),
(4, 13),
(15, 14),
(21, 14),
(22, 14),
(12, 14),
(15, 32),
(15, 33),
(15, 35),
(15, 36),
(15, 37),
(15, 38),
(15, 39),
(15, 40),
(15, 41),
(15, 42),
(15, 43),
(15, 44),
(15, 45),
(15, 46),
(15, 47),
(15, 48),
(15, 49),
(15, 50),
(15, 51),
(15, 65),
(15, 66),
(15, 67),
(15, 68),
(15, 69),
(15, 70),
(15, 71),
(15, 72),
(15, 73),
(15, 74),
(15, 75),
(15, 76),
(15, 77),
(15, 78),
(15, 79),
(15, 80),
(15, 81),
(15, 82),
(15, 83),
(15, 84),
(15, 85),
(15, 86),
(15, 87),
(15, 88),
(15, 89),
(15, 90),
(15, 91),
(15, 92),
(15, 93),
(15, 94),
(15, 95),
(15, 96),
(15, 97),
(15, 98),
(15, 99),
(15, 100),
(15, 101),
(15, 102),
(15, 103),
(15, 104),
(15, 105),
(15, 106),
(15, 107),
(15, 108),
(15, 109),
(15, 110),
(15, 111),
(15, 112),
(15, 113),
(15, 114);

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_guest` int(10) UNSIGNED NOT NULL,
  `id_car_sale` int(10) UNSIGNED DEFAULT NULL,
  `id_user_create` int(10) UNSIGNED NOT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  `admin_check` tinyint(1) NOT NULL DEFAULT '0',
  `lead_sale_check` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_off`
--

CREATE TABLE `sale_off` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_sale` int(10) UNSIGNED NOT NULL,
  `id_bh_pk_package` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tai_lieu`
--

CREATE TABLE `tai_lieu` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `tieuDe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moTa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noiDung` text COLLATE utf8mb4_unicode_ci,
  `duongDan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngayTao` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_car`
--

CREATE TABLE `type_car` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_car`
--

INSERT INTO `type_car` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Grand i10 2018', '2021-10-18 02:55:32', '2021-10-18 02:55:32'),
(3, 'Grand i10 2021', '2021-10-18 02:55:37', '2021-10-18 02:55:37'),
(4, 'Accent', '2021-10-18 02:55:40', '2021-10-18 02:55:40'),
(5, 'Elantra', '2021-10-18 02:55:43', '2021-10-18 02:55:43'),
(6, 'Kona', '2021-10-18 02:55:45', '2021-10-18 02:55:45'),
(7, 'Tucson', '2021-10-18 02:55:49', '2021-10-18 02:55:49'),
(8, 'Santafe', '2021-10-18 02:55:52', '2021-10-18 02:55:52'),
(9, 'Solati', '2021-10-18 02:55:55', '2021-10-18 02:55:55'),
(10, 'H150', '2021-10-18 02:55:57', '2021-10-18 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `type_car_detail`
--

CREATE TABLE `type_car_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_type_car` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_guest`
--

CREATE TABLE `type_guest` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_guest`
--

INSERT INTO `type_guest` (`id`, `name`) VALUES
(1, 'Cá nhân'),
(2, 'Doanh nghiệp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `active`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, '$2y$10$/EJ7BVk9RNW9A.naGPLPB.DDPulLBkjNXIpzFRii6TMYEj9G9NcRq', 1, NULL, '2021-10-21 09:51:58'),
(2, 'demo', 'tpkd@zing.vn', '$2y$10$wjRG3o5P/uVyYkMuDBj.4eVgvbRgcwKm6cKb./jNErNhO2F/9p1Vi', 0, '2021-10-17 13:42:42', '2021-10-21 10:01:56'),
(3, 'xuong', 'xuong', '$2y$10$Cji3F94f2nJF4Egp9TtpK.gPxIaiW9lZ7DA1E9UrS40Vg0uUKj9.2', 1, '2021-10-17 14:03:45', '2021-10-17 14:03:45'),
(4, 'ptdl', '1', '$2y$10$DgSmIxMYIcX00YxKqzDlVO5sFZcfS6DxsJeJIaxhUcdNC0wF099Uu', 1, '2021-10-17 14:25:54', '2021-10-22 11:37:11'),
(5, 'cskh', 'z', '$2y$10$WgzSVIGcn3/V7w82R5iLhu0Cfw9ufj8K1/KfOuXHuV0gAAGEd6sBC', 1, '2021-10-17 14:28:54', '2021-10-17 14:28:54'),
(6, 'mkt', 'mkt', '$2y$10$xu684QtaCW31Fhz/MGu/j./7t.vpfoeAymAmPKH8WU.gi/QPm9m1S', 1, '2021-10-18 02:25:18', '2021-10-18 02:25:18'),
(7, 'tpdv', '1', '$2y$10$yARaANYfuhe7JviAOCcG8OBrpZFbaMZT15cgJMxKno8QcVdu52Bhi', 1, '2021-10-18 02:26:12', '2021-10-18 02:26:12'),
(8, 'it', 'it@hyundaiangiang.com', '$2y$10$3tHjW0dYa9nESlXMdCtrQeI/ekkt.fWHfmnK0zZE2W3t.Am6NynMu', 1, '2021-10-18 02:26:56', '2021-10-18 12:04:47'),
(9, 'hcns', '1', '$2y$10$7O2xJBJyky57JLdfVdb9Tu505pjHRhoXV.qfAnQAYSN4yb27BavQy', 1, '2021-10-18 02:28:57', '2021-10-18 02:28:57'),
(11, 'boss', '1', '$2y$10$Egcknpxqxfw0Ybje6eonc.wzyN5rZWbhKB5tnOLb5d3YR1h.3GG/.', 1, '2021-10-18 04:03:14', '2021-10-18 04:03:14'),
(12, 'ketoan', '1', '$2y$10$BH4bCh6i7qGNtj.lOusIzOPTo/DFRopgi41u9AZsUxmp4nLvD70Ti', 1, '2021-10-18 04:05:37', '2021-10-18 04:05:37'),
(13, 'tpkd', 'phongkinhdoanh@hyundaiangiang.com', '$2y$10$F6VienkmGClYlcD9dUyjmeZmtQc9C7LqY9SqZb8DAibbSjWMkKeIe', 1, '2021-10-18 09:44:12', '2021-10-21 10:20:11'),
(14, 'Huytt', 'truonghuy0605@gmail.com', '$2y$10$edV4r/AyCQYfn8wk3k7RkezgcfvPXoowdThQA5kXMPUZ.Jkhaulqu', 1, '2021-10-21 14:47:44', '2021-10-21 14:47:44'),
(32, 'nv003', NULL, '$2y$10$9sAaXB4BNq9jZutODFAxaO7Tqh/kmA9gBVAVwg39DdqZZbrqo/iS2', 1, '2021-10-24 14:32:14', '2021-10-24 14:33:43'),
(33, 'nv010', NULL, '$2y$10$uemYhtcC6Q14EKXBiB8nQuE7VBgmuKCfpsU.v53v9DUB2pm7t.MtW', 1, '2021-10-24 14:32:30', '2021-10-24 14:33:33'),
(35, 'nv015', NULL, '$2y$10$oTtP1ehzUyXwdsjXoCRbbuctOuLuy/iLs4aYzb12HJ8YpNbUR6P.u', 1, '2021-10-24 14:32:47', '2021-10-24 14:33:23'),
(36, 'nv065', NULL, '$2y$10$ko6hpY9e9hn7rlOBAtQK6.g9LCV6gJvDVjywBDzPzt7yj74WBnQtW', 1, '2021-10-24 14:33:02', '2021-10-24 14:33:02'),
(37, 'nv068', NULL, '$2y$10$qQluAOGYHYIWtEfOFgsex.TjDgzYbSqYbYlWqlsnKPusBDVD.txVC', 1, '2021-10-24 14:49:16', '2021-10-24 14:49:16'),
(38, 'nv142', NULL, '$2y$10$AnCZqmf5mnDj5NLhXZGNJ.yCZVwIs/PD5f07PGYm8qYpwBo9qi3wa', 1, '2021-10-24 14:49:47', '2021-10-24 14:49:47'),
(39, 'nv086', NULL, '$2y$10$IR5ZyCQHTLjFl98Tt2fWPO7da113Nd6NMbOXxpEt/UYeI7HVeagQe', 1, '2021-10-24 14:50:09', '2021-10-24 14:50:09'),
(40, 'nv091', NULL, '$2y$10$RHaLQ2ufAXfjnHWymecKqOr5z8A2gEOwk0898y6WGYqGX2qiALIf2', 1, '2021-10-24 14:50:19', '2021-10-24 14:50:19'),
(41, 'nv141', NULL, '$2y$10$EtnAr9LDHs//wvTE8kOkSejtyECPzLPT97KlJZiLFxq0j5yUgiohG', 1, '2021-10-24 14:50:50', '2021-10-24 14:50:50'),
(42, 'nv144', NULL, '$2y$10$AesayVIYG7c6P91ArU4LfexFW.tMD484y9XOqqksgpeX3qdzig2nq', 1, '2021-10-24 15:04:48', '2021-10-24 15:04:48'),
(43, 'nv143', NULL, '$2y$10$YvohilGgB2vYnbkAti9/1.pUDgydq9pGjaD.wY3Mu7ZvPdk9l1l9a', 1, '2021-10-24 15:05:02', '2021-10-24 15:05:02'),
(44, 'nv156', NULL, '$2y$10$NKxWt8RFwrA8yyjsPydOrOs3OC2vL7yf3W0YHJpIuIdjzrF4OW4BG', 1, '2021-10-24 15:05:43', '2021-10-24 15:05:43'),
(45, 'nv166', NULL, '$2y$10$PXkT1qPN6McT8marGVe9R.I9OLDj55HGlJtNKBz5y3iKFD/KeFMq.', 1, '2021-10-24 15:06:07', '2021-10-24 15:06:07'),
(46, 'nv168', NULL, '$2y$10$bBk1EImyuGFm1Z.co1oJPuk5tn12gFdde1QM4QQmBswF35huQdfGm', 1, '2021-10-24 15:06:21', '2021-10-24 15:06:21'),
(47, 'nv175', NULL, '$2y$10$qfjilWXn1sy84RdTMPT7Necav6v7EYkCNWWLoWQFjf4RTjEeXMNoe', 1, '2021-10-24 15:06:36', '2021-10-24 15:06:36'),
(48, 'nv185', NULL, '$2y$10$ZTe/1DaYe/i6C1p/oIvV9u04UwL55Fht4e3mXzdQRKVSw7KKojg8K', 1, '2021-10-24 15:07:13', '2021-10-24 15:07:13'),
(49, 'nv188', NULL, '$2y$10$tiuomad8ZcRp41nrmJ.2hOnRd2G0I7CCc50vA0hEcIBEYMgiiTs2O', 1, '2021-10-24 15:07:24', '2021-10-24 15:07:24'),
(50, 'nv189', NULL, '$2y$10$Cg8ijOk5u2ETe1gOZBEqhe4G8yCBxL9/hGp6T6maSKGX3Dzjf6gbC', 1, '2021-10-24 15:07:34', '2021-10-24 15:07:34'),
(51, 'nv190', NULL, '$2y$10$ueOaO6x2LVcUYcr74Ddr4Onmx7CwiKkaq.jHBdG6DkH//0jMw6V/6', 1, '2021-10-24 15:07:47', '2021-10-24 15:07:47'),
(65, 'nv198', NULL, '$2y$10$vN1/IPJXJZYhMgiwTRwfgOixkisRfo/oP8v7ojGip7s3CyVuIQqGm', 1, '2021-10-24 15:08:27', '2021-10-24 15:08:27'),
(66, 'nv005', NULL, '$2y$10$b33G.pdwOuUdltRDx7AoJuTl0lcR/VTPC2O1Ri8n4bgIvoZA9KlqC', 1, '2021-10-24 15:09:45', '2021-10-24 15:09:45'),
(67, 'nv012', NULL, '$2y$10$NMbXVwjahAjTwoqUg.gcbeD99YwOCLhWtoTArCYpHYJYZ/C1prxkK', 1, '2021-10-24 15:10:09', '2021-10-24 15:10:09'),
(68, 'nv160', NULL, '$2y$10$/haOnNpRXWAP80k6pI.e3eLKsmFsaKLTOMajZxxFTBHe6tbEypDq6', 1, '2021-10-24 15:10:42', '2021-10-24 15:10:42'),
(69, 'nv001', NULL, '$2y$10$C22aummPD0QrXws6n6hH8elACA/ZWCGfTjpi3d8wl9714IIXzwJI.', 1, '2021-10-24 15:10:58', '2021-10-24 15:10:58'),
(70, 'nv019', NULL, '$2y$10$An84SPXZz.fAqBDp6kLtjucmXVpWzx72Wfu6zI9cEJUZd0vfZUMzC', 1, '2021-10-24 15:11:12', '2021-10-24 15:11:12'),
(71, 'nv020', NULL, '$2y$10$Krn/Y2y4rtBICzNzRLJLT.DqocGSlvAk4XTBVCc4PTTKzITp7f/aW', 1, '2021-10-24 15:11:23', '2021-10-24 15:11:23'),
(72, 'nv021', NULL, '$2y$10$h4q1fJoxfQvyDQPUrY49m.i1nNeCdc0WVGYV0S5AV4SD1/LY0bCpO', 1, '2021-10-24 15:11:52', '2021-10-24 15:11:52'),
(73, 'nv022', NULL, '$2y$10$S8pB8nvvCDg3XprIFDhWCOrMACIrq.cjMPZU10xsUkEB0gMbCf8hG', 1, '2021-10-24 15:12:06', '2021-10-24 15:12:06'),
(74, 'nv023', NULL, '$2y$10$IXsgIQFEf1aJvDoH3Ubx5Om9X36Y8oiZcXzdEpI7qAULtue9f8QgW', 1, '2021-10-24 15:29:05', '2021-10-24 15:29:05'),
(75, 'nv027', NULL, '$2y$10$Sf1eM/ZB0QGsOkWRfMIlluI65zixY4kq.W4tUVC3YpAI6Tlq1tH8i', 1, '2021-10-24 15:29:22', '2021-10-24 15:29:22'),
(76, 'nv081', NULL, '$2y$10$5juV/ZWQMvlUPXGY8LOSxeS7kUoPVqf4sbA9WjTkLpwBYaWQrxIsK', 1, '2021-10-24 15:29:32', '2021-10-24 15:29:32'),
(77, 'nv090', NULL, '$2y$10$di0IH4lJ8F5uCe7ovcL9ceJprrCBn6Qb8y8ygoY2GnyPQhl/4QAbq', 1, '2021-10-24 15:29:45', '2021-10-24 15:29:45'),
(78, 'nv092', NULL, '$2y$10$e5LkwWnOVMgEL7umS3TW9OzcfMa4KsUgl2Ld4xWmsoV6FZhDAPBoG', 1, '2021-10-24 15:29:56', '2021-10-24 15:29:56'),
(79, 'nv174', NULL, '$2y$10$5Q2vNStaL3X2yb5670rQzO3i96boSnN3SusG2EbHUk8MsAq6Ve6ku', 1, '2021-10-24 15:30:09', '2021-10-24 15:30:09'),
(80, 'nv031', NULL, '$2y$10$H9qQUvyan361myZevfhsTuZErsGY8t/8UIzZUlQth33/yg6mgCAUS', 1, '2021-10-24 15:30:29', '2021-10-24 15:30:29'),
(81, 'nv083', NULL, '$2y$10$r/WnBi2WhTzQzhgQ/eZJRuKJgkoVFONiGucAYBF/6ZDzpn61kMjCW', 1, '2021-10-24 15:31:02', '2021-10-24 15:31:02'),
(82, 'nv094', NULL, '$2y$10$QOoQTBH6oWVSCkx0.mP95excvxy4Z6YQ1AFW37e/FjmeZS3NoXo02', 1, '2021-10-24 15:31:21', '2021-10-24 15:31:34'),
(83, 'nv171', NULL, '$2y$10$W7VZu30CjVj9ChyUddsyNeUOuOOAgOmy.wPYSjvV..eJVQjmvaHCG', 1, '2021-10-24 15:31:53', '2021-10-24 15:31:53'),
(84, 'nv195', NULL, '$2y$10$7B6tCBbExiCJOfSuvXWO.u85xFyJvkmNjZDANpY5owqFJpwcAS3CO', 1, '2021-10-24 15:32:16', '2021-10-24 15:32:16'),
(85, 'nv197', NULL, '$2y$10$JsS9NCLTEVUk3BGmN2G4j.rhvG6J6Qb7H3q3vKTBB428RRVbl0D9G', 1, '2021-10-24 15:32:27', '2021-10-24 15:32:27'),
(86, 'nv043', NULL, '$2y$10$/anBr7yM/4MBOc/PO/NayeXhdaOyGDYzR0mYzjpTuaeTyWcMKjz/2', 1, '2021-10-24 15:32:46', '2021-10-24 15:32:46'),
(87, 'nv169', NULL, '$2y$10$IxSnh1FVgZbIUHiekxvNzeCsYu6swy3rkHptS5dIJR5zmsW3sxObC', 1, '2021-10-24 15:33:02', '2021-10-24 15:33:02'),
(88, 'nv176', NULL, '$2y$10$UBCUpjesLo8uNqTqVvhWJenRvCz6Fz510K1egHEpTni.D9yeAd9Ye', 1, '2021-10-24 15:33:18', '2021-10-24 15:33:18'),
(89, 'nv178', NULL, '$2y$10$CfKLVSpKA.TpJEaRQWfRe.2BG7AgBE7anrWCRYPijulVj0zYvsake', 1, '2021-10-24 15:33:33', '2021-10-24 15:33:33'),
(90, 'nv199', NULL, '$2y$10$/jaB0YW8cjI4WN4v3Rl2xuk/LA/Lypa8t75tPWMdpQkZWzMtMHhwC', 1, '2021-10-24 15:33:42', '2021-10-24 15:33:42'),
(91, 'nv045', NULL, '$2y$10$piSWZLOudWXaStyfH/G2dOZq5uhSs9199JucaQ6Leto07alAwiol6', 1, '2021-10-24 15:33:53', '2021-10-24 15:33:53'),
(92, 'nv046', NULL, '$2y$10$1svHTqHZ.svQ1zqmWn1v2uVF5MPxbRFQrBIT3UjwiqFttx3LyLVEu', 1, '2021-10-24 15:34:06', '2021-10-24 15:34:06'),
(93, 'nv070', NULL, '$2y$10$BVwKPxdIgW0X3a5pOETWNOSK/W8bB9P.2WqPSritJ8Mhs8vaC8RwS', 1, '2021-10-24 15:34:19', '2021-10-24 15:34:19'),
(94, 'nv079', NULL, '$2y$10$AEOfUeU8Le9WFHxP0ahaOesc/sglAOYsP0HhxQokXqXDliKbBo9t.', 1, '2021-10-24 15:34:35', '2021-10-24 15:34:35'),
(95, 'nv053', NULL, '$2y$10$zhjtge/.ZUHOZYoqdT8LSu6hbIZnRjsPvukrDLLjsxeXHTsMtLw1O', 1, '2021-10-24 15:35:02', '2021-10-24 15:35:02'),
(96, 'nv064', NULL, '$2y$10$K5H8.22bU3.p858H/9roHOPvMOlToVaZG13xXtAG115OFFa1wtNji', 1, '2021-10-24 15:35:19', '2021-10-24 15:35:19'),
(97, 'nv060', NULL, '$2y$10$eR6RLTe8oaFpn00eGk/ywe6OwWQ1cALwPT81oMiFHcDyo4STm1xki', 1, '2021-10-24 15:35:29', '2021-10-24 15:35:29'),
(98, 'nv097', NULL, '$2y$10$6D08O3TfE8dWyDXUfYORl.i60BTGFIHcW63d9WyLn4QJ9S5T3RxIy', 1, '2021-10-24 15:35:43', '2021-10-24 15:35:43'),
(99, 'nv098', NULL, '$2y$10$i6IiPTvG92gkcHx74In0A.WDJc8t1rsaEZJ2ZuZgKU20zSFSHXeSu', 1, '2021-10-24 15:35:54', '2021-10-24 15:35:54'),
(100, 'nv148', NULL, '$2y$10$YCEUOImzSBi/BmiY3N8Zr.MtWGMsYq8sfMJ2aQ1xxVa2OQpwDtfEu', 1, '2021-10-24 15:36:08', '2021-10-24 15:36:08'),
(101, 'nv152', NULL, '$2y$10$8WQ42btyziek9FOofNykkeXCCSdDimZx7n1Ymbqjv0tYFbe0keZAu', 1, '2021-10-24 15:36:19', '2021-10-24 15:36:19'),
(102, 'nv153', NULL, '$2y$10$qhSxss5.MAEaPewn.zzMpu1jFWWC5HzfS.sDXOoFk5I0arD.WNjoK', 1, '2021-10-24 15:36:40', '2021-10-24 15:36:40'),
(103, 'nv155', NULL, '$2y$10$k3j1svIMkkIfjYcBYKOCKe5siWeSHkN3jqdeayGGUo.CT/AYeKbq6', 1, '2021-10-24 15:36:49', '2021-10-24 15:36:49'),
(104, 'nv172', NULL, '$2y$10$1c4zW05PNGsb0nx5kqxZUulf2s98uDKLiMAWQgnsWV9sLh7M6M6V2', 1, '2021-10-24 15:37:00', '2021-10-24 15:37:00'),
(105, 'nv085', NULL, '$2y$10$/72b2Cs1o42E.OYsuqzfj.C0DxwGw/nU4oJYv/TkI7e/5CzVX7DfK', 1, '2021-10-24 15:37:14', '2021-10-24 15:37:14'),
(106, 'nv177', NULL, '$2y$10$H4ZMnlwL3XFExpBXsZjv/ODsivuMKa7/6Jysq0sv/L2.FHLNIJ1Ee', 1, '2021-10-24 15:37:30', '2021-10-24 15:37:30'),
(107, 'nv181', NULL, '$2y$10$PvGaCNIXTCO6JYMV35H0UOvI8ar3qRxuOGA4u6UQhuzuC7Da1QWDu', 1, '2021-10-24 15:37:42', '2021-10-24 15:37:42'),
(108, 'nv182', NULL, '$2y$10$q2E2D6CDYaOpDFGwlr/2guQpBvCdrF7kOv.mLFzGIwmzXzJmnP8X.', 1, '2021-10-24 15:37:54', '2021-10-24 15:37:54'),
(109, 'nv183', NULL, '$2y$10$qvz/nRhqaCW96W630KxqbOMpwtESv54DmiZLYCFavKTATFeRIppI2', 1, '2021-10-24 15:38:05', '2021-10-24 15:38:05'),
(110, 'nv186', NULL, '$2y$10$dO9VNkXAMr/tENICxLVbcOLi4DtvXMKnewhtJ1eYCJk705lzBeRAa', 1, '2021-10-24 15:38:18', '2021-10-24 15:38:18'),
(111, 'nv191', NULL, '$2y$10$2gg5ygH4qpgBm7KWEJBKkevug27CLBt84am/Tl8rltKc3R/Z.ve8e', 1, '2021-10-24 15:38:44', '2021-10-24 15:38:44'),
(112, 'nv193', NULL, '$2y$10$jwnw4To4r1jRMd8y1X90sOK2IJPf3yWgXZZ2tg2xoWjziCs478Pti', 1, '2021-10-24 15:38:58', '2021-10-24 15:38:58'),
(113, 'Ngan', NULL, '$2y$10$5FIFEJXKshpKwlvnTk8NQuu/cmeSbHxXRRXfTRDwC.57uPccY8Pk2', 1, '2021-10-24 16:03:20', '2021-10-24 16:03:20'),
(114, 'Tai', NULL, '$2y$10$pK/mqUAFNBEHRsdd3.rEBOSJKoeZQodkKkUGHQoOif1cLUiq1CnHC', 1, '2021-10-24 16:03:32', '2021-10-24 16:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `users_detail`
--

CREATE TABLE `users_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_detail`
--

INSERT INTO `users_detail` (`id`, `id_user`, `surname`, `birthday`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn Admin', '01/01/2000', 'Long Xuyên, An Giang', '0000 111 222', NULL, NULL),
(2, 2, 'Demo', '1.1.2000', 'An Giang', '099090', '2021-10-17 13:42:58', '2021-10-21 14:52:17'),
(3, 3, 'Nguyễn Ngọc Thơ', '1.1.1', '111', '111', '2021-10-17 14:03:58', '2021-10-18 17:13:14'),
(4, 4, 'Võ Tài Nguyên', '1', '1', '1', '2021-10-17 14:26:17', '2021-10-21 09:54:15'),
(5, 5, 'Lê Thị Bông', '1.1.2000', '1', '1', '2021-10-18 02:25:39', '2021-10-18 17:12:55'),
(6, 6, 'Nguyễn Đỗ Mỹ Kim', '1', '1', '1', '2021-10-18 02:25:56', '2021-10-18 17:12:40'),
(7, 7, 'Trần Toàn Bảo', '1', '1', '1', '2021-10-18 02:27:16', '2021-10-18 02:27:16'),
(8, 8, 'Huỳnh Ngọc Phát', '03.04.1992', 'Long Xuyên, An Giang', '0869 505019', '2021-10-18 02:27:28', '2021-10-18 12:04:20'),
(9, 9, 'Nguyễn Chí Tây', '1', '1', '1', '2021-10-18 02:29:10', '2021-10-18 02:29:10'),
(11, 11, 'Ban Lãnh Đạo', '1', '1', '1', '2021-10-18 04:03:33', '2021-10-18 04:03:33'),
(12, 12, 'Nguyễn Thị Thu Cúc', '1', '1', '1', '2021-10-18 04:05:53', '2021-10-18 04:05:53'),
(13, 13, 'Nguyễn Quốc Đạt', '1', '1', '1', '2021-10-21 14:52:03', '2021-10-21 14:52:03'),
(14, 14, 'TRƯƠNG THANH HUY', '06/05/1996', 'Long Xuyên City', '0912216332', '2021-10-21 15:05:11', '2021-10-21 15:06:40'),
(15, 32, 'NGUYỄN THANH THÚY', '02/12/1987', '1', '0931774700', '2021-10-24 15:43:52', '2021-10-24 15:43:52'),
(16, 33, 'LÊ CHÍ PHƯỚC', '19/11/1988', '1', '0949222251', '2021-10-24 15:45:28', '2021-10-24 15:45:28'),
(17, 35, 'NGUYỄN THÀNH VŨ', '1', '1', '1', '2021-10-24 15:46:29', '2021-10-24 15:46:29'),
(18, 36, 'NGUYỄN THÁI SANG', '1', '1', '1', '2021-10-24 15:46:41', '2021-10-24 15:46:41'),
(19, 37, 'HUỲNH NGỌC KHÁNH LINH', '1', '1', '1', '2021-10-24 15:46:58', '2021-10-24 15:46:58'),
(20, 38, 'HUỲNH ĐÌNH THỤY', '1', '1', '1', '2021-10-24 15:47:14', '2021-10-24 15:47:14'),
(21, 39, 'LÊ BÁ PHƯỚC', '1', '1', '1', '2021-10-24 15:47:27', '2021-10-24 15:47:27'),
(22, 40, 'NGÔ THỊ THÙY LOAN', '1', '1', '1', '2021-10-24 15:47:48', '2021-10-24 15:47:48'),
(23, 41, 'NGUYỄN TRUNG KIỆT', '1', '1', '1', '2021-10-24 15:48:19', '2021-10-24 15:48:19'),
(24, 42, 'NGUYỄN THẾ NHƯ', '1', '1', '1', '2021-10-24 15:48:35', '2021-10-24 15:48:35'),
(25, 43, 'NGUYỄN ANH PHƯƠNG', '1', '1', '1', '2021-10-24 15:48:49', '2021-10-24 15:48:49'),
(26, 44, 'NGUYỄN QUỐC ĐẠT', '1', '1', '1', '2021-10-24 15:49:06', '2021-10-24 15:49:06'),
(27, 45, 'LƯU QUANG SƠN', '1', '1', '1', '2021-10-24 15:49:27', '2021-10-24 15:49:27'),
(28, 46, 'HÀ VĂN THỊNH', '1', '1', '1', '2021-10-24 15:49:48', '2021-10-24 15:49:48'),
(29, 47, 'NGUYỄN HÙNG', '1', '1', '1', '2021-10-24 15:50:06', '2021-10-24 15:50:06'),
(30, 48, 'LÝ MINH LUÂN', '1', '1', '1', '2021-10-24 15:50:18', '2021-10-24 15:50:18'),
(31, 49, 'NGUYỄN THỊ KIM NGỌC', '1', '1', '1', '2021-10-24 15:50:33', '2021-10-24 15:50:33'),
(32, 50, 'ĐỖ HOÀNG DUY', '1', '1', '1', '2021-10-24 15:50:48', '2021-10-24 15:50:48'),
(33, 51, 'NGUYỄN NGỌC TOÀN', '1', '1', '1', '2021-10-24 15:51:07', '2021-10-24 15:51:07'),
(34, 65, 'NGUYỄN Y PHƯƠNG', '1', '1', '1', '2021-10-24 15:51:33', '2021-10-24 15:51:33'),
(35, 66, 'TRẦN BẢO TOÀN', '1', '1', '1', '2021-10-24 15:51:46', '2021-10-24 15:51:46'),
(36, 67, 'VÕ TÀI NGUYÊN', '1', '1', '1', '2021-10-24 15:51:57', '2021-10-24 15:51:57'),
(37, 68, 'NGUYỄN VĂN MINH', '1', '1', '1', '2021-10-24 15:52:17', '2021-10-24 15:52:17'),
(38, 69, 'HUỲNH NGỌC PHÁT', '03/04/1992', 'Long Xuyen City', '0368887577', '2021-10-24 15:53:08', '2021-10-24 15:53:08'),
(39, 70, 'TRẦN MỸ HẢO', '1', '1', '1', '2021-10-24 15:53:22', '2021-10-24 15:53:22'),
(40, 71, 'NGUYỄN ĐỖ MỸ KIM', '1', '1', '1', '2021-10-24 15:53:33', '2021-10-24 15:53:33'),
(41, 72, 'LÊ THỊ BÔNG', '1', '1', '1', '2021-10-24 15:53:43', '2021-10-24 15:53:43'),
(42, 73, 'PHẠM NGUYỄN NGUYỆT HẰNG', '20/08/1996', 'Chợ Mới, An Giang', '0967320627', '2021-10-24 15:54:00', '2021-10-24 16:07:21'),
(43, 74, 'TRẦN THANH DUY', '1', '1', '1', '2021-10-24 15:54:13', '2021-10-24 15:54:13'),
(44, 75, 'NGÔ THỊ BÍCH LIÊN', '1', '1', '1', '2021-10-24 15:54:31', '2021-10-24 15:54:31'),
(45, 76, 'NGUYỄN CHÍ TÂY', '1', '1', '1', '2021-10-24 15:54:45', '2021-10-24 15:54:45'),
(46, 77, 'TRƯƠNG HOÀNG VĨ', '1', '1', '1', '2021-10-24 15:55:01', '2021-10-24 15:55:01'),
(47, 78, 'TRƯƠNG MINH THÙY', '1', '1', '1', '2021-10-24 15:55:10', '2021-10-24 15:55:10'),
(48, 79, 'TRƯƠNG ANH DŨNG', '1', '1', '1', '2021-10-24 15:55:22', '2021-10-24 15:55:22'),
(49, 80, 'VÕ THỊ ÚT', '1', '1', '1', '2021-10-24 15:55:32', '2021-10-24 15:55:32'),
(50, 81, 'BÙI THỊ KIM CHƯƠNG', '1', '1', '1', '2021-10-24 15:55:47', '2021-10-24 15:55:47'),
(51, 82, 'PHAN THỊ YẾN', '1', '1', '1', '2021-10-24 15:56:03', '2021-10-24 15:56:03'),
(52, 83, 'VÕ THỊ LIÊN', '1', '1', '1', '2021-10-24 15:56:15', '2021-10-24 15:56:15'),
(53, 84, 'NGUYỄN THỊ MỸ DUYÊN', '1', '1', '1', '2021-10-24 15:56:32', '2021-10-24 15:56:32'),
(54, 85, 'TRƯƠNG THỊ KIM CÚC', '1', '1', '1', '2021-10-24 15:56:44', '2021-10-24 15:56:44'),
(55, 86, 'NGUYỄN THỊ CẨM TIÊN', '1', '1', '1', '2021-10-24 15:56:57', '2021-10-24 15:56:57'),
(56, 87, 'NGUYỄN HỒ THẢO NHƯ', '1', '1', '1', '2021-10-24 15:57:12', '2021-10-24 15:57:12'),
(57, 88, 'TRIỆU QUANG TRUNG', '1', '1', '1', '2021-10-24 15:57:23', '2021-10-24 15:57:23'),
(58, 89, 'PHẠM MINH DUY', '1', '1', '1', '2021-10-24 15:57:38', '2021-10-24 15:57:38'),
(59, 90, 'KIẾN QUỐC NHÃ', '1', '1', '1', '2021-10-24 15:57:49', '2021-10-24 15:57:49'),
(60, 91, 'NGUYỄN PHƯỚC ĐỨC', '1', '1', '1', '2021-10-24 15:58:05', '2021-10-24 15:58:05'),
(61, 92, 'NGUYỄN PHÚC HIẾU', '1', '1', '1', '2021-10-24 15:58:16', '2021-10-24 15:58:16'),
(62, 93, 'NGUYỄN THÁI LỘC', '1', '1', '1', '2021-10-24 15:58:27', '2021-10-24 15:58:27'),
(63, 94, 'LÊ NGHĨA', '1', '1', '1', '2021-10-24 15:58:36', '2021-10-24 15:58:36'),
(64, 95, 'NGUYỄN HOÀNG MỚI', '1', '1', '1', '2021-10-24 15:58:50', '2021-10-24 15:58:50'),
(65, 96, 'TRƯƠNG THANH TÚ', '1', '1', '1', '2021-10-24 15:59:04', '2021-10-24 15:59:04'),
(66, 97, 'NGÔ VĂN SỰ', '1', '1', '1', '2021-10-24 15:59:15', '2021-10-24 15:59:15'),
(67, 98, 'NGUYỄN HOÀNG PHI HỢP', '1', '1', '1', '2021-10-24 15:59:28', '2021-10-24 15:59:28'),
(68, 99, 'TRƯƠNG NGUYỄN QUỐC THÁI', '1', '1', '1', '2021-10-24 15:59:46', '2021-10-24 15:59:46'),
(69, 100, 'NGUYỄN HỮU PHƯƠNG', '1', '1', '1', '2021-10-24 15:59:58', '2021-10-24 15:59:58'),
(70, 101, 'NGUYỄN TUẤN THANH', '1', '1', '1', '2021-10-24 16:00:16', '2021-10-24 16:00:16'),
(71, 102, 'NGUYỄN NGỌC THỚ', '1', '1', '1', '2021-10-24 16:00:29', '2021-10-24 16:00:29'),
(72, 103, 'ĐẶNG VĂN MẪN', '1', '1', '1', '2021-10-24 16:00:39', '2021-10-24 16:00:39'),
(73, 104, 'NGUYỄN THÀNH TÀI', '1', '1', '1', '2021-10-24 16:00:50', '2021-10-24 16:00:50'),
(74, 105, 'NGUYỄN NGỌC NGOAN', '1', '1', '1', '2021-10-24 16:01:02', '2021-10-24 16:01:02'),
(75, 106, 'LÊ THANH LINH', '1', '1', '1', '2021-10-24 16:01:18', '2021-10-24 16:01:18'),
(76, 107, 'NGUYỄN THANH QUY', '1', '1', '1', '2021-10-24 16:01:34', '2021-10-24 16:01:34'),
(77, 108, 'MAI THÀNH QUỐC', '1', '1', '1', '2021-10-24 16:01:43', '2021-10-24 16:01:43'),
(78, 109, 'NGUYỄN THANH HÀ', '1', '1', '1', '2021-10-24 16:01:52', '2021-10-24 16:01:52'),
(79, 110, 'NGUYỄN VĂN BÉ NĂM', '1', '1', '1', '2021-10-24 16:02:04', '2021-10-24 16:02:04'),
(80, 111, 'NGUYỄN TẤN KIỆT', '1', '1', '1', '2021-10-24 16:02:17', '2021-10-24 16:02:17'),
(81, 112, 'NGUYỄN CHÍ LINH', '1', '1', '1', '2021-10-24 16:02:30', '2021-10-24 16:02:30'),
(82, 113, 'NGUYỄN THỊ BÍCH NGÂN', '1', 'Long Xuyen City', '0333222333', '2021-10-24 16:04:14', '2021-10-24 16:04:14'),
(83, 114, 'HUỲNH TẤN TÀI', '1', 'Long Xuyen City', '0918188889', '2021-10-24 16:04:54', '2021-10-24 16:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `xe_lai_thu`
--

CREATE TABLE `xe_lai_thu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_car` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mau` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('DSC','DSD','T') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'T',
  `id_user_use` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `xe_lai_thu`
--

INSERT INTO `xe_lai_thu` (`id`, `name`, `number_car`, `mau`, `status`, `id_user_use`, `created_at`, `updated_at`) VALUES
(1, 'Accent', '67A-16411', 'Trắng', 'T', 4, '2021-10-18 03:01:01', '2021-10-28 07:36:31'),
(2, 'Kona', '67A-08090', 'Trắng', 'T', 4, '2021-10-18 03:01:15', '2021-10-27 08:06:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bh_pk_package`
--
ALTER TABLE `bh_pk_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bh_pk_package_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `cancel_hd`
--
ALTER TABLE `cancel_hd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cancel_hd_user_id_foreign` (`user_id`),
  ADD KEY `cancel_hd_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `car_sale`
--
ALTER TABLE `car_sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_sale_id_type_car_detail_foreign` (`id_type_car_detail`),
  ADD KEY `car_sale_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `cong`
--
ALTER TABLE `cong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cong_id_dv_foreign` (`id_dv`),
  ADD KEY `cong_id_loai_cong_foreign` (`id_loai_cong`);

--
-- Indexes for table `dang_ky_su_dung`
--
ALTER TABLE `dang_ky_su_dung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dang_ky_su_dung_id_user_reg_foreign` (`id_user_reg`),
  ADD KEY `dang_ky_su_dung_id_xe_lai_thu_foreign` (`id_xe_lai_thu`),
  ADD KEY `dang_ky_su_dung_id_user_check_foreign` (`id_user_check`);

--
-- Indexes for table `dv`
--
ALTER TABLE `dv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dv_id_guest_dv_foreign` (`id_guest_dv`),
  ADD KEY `dv_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id_type_guest_foreign` (`id_type_guest`),
  ADD KEY `guest_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `guest_dv`
--
ALTER TABLE `guest_dv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_dv_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `loai_cong`
--
ALTER TABLE `loai_cong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loai_phu_tung`
--
ALTER TABLE `loai_phu_tung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhom`
--
ALTER TABLE `nhom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhom_user`
--
ALTER TABLE `nhom_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhom_user_id_nhom_foreign` (`id_nhom`),
  ADD KEY `nhom_user_id_user_foreign` (`id_user`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `phu_tung`
--
ALTER TABLE `phu_tung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phu_tung_id_dv_foreign` (`id_dv`),
  ADD KEY `phu_tung_id_loai_phu_tung_foreign` (`id_loai_phu_tung`);

--
-- Indexes for table `quyen_xem`
--
ALTER TABLE `quyen_xem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quyen_xem_id_tai_lieu_foreign` (`id_tai_lieu`),
  ADD KEY `quyen_xem_id_nhom_foreign` (`id_nhom`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_user_report_foreign` (`user_report`);

--
-- Indexes for table `report_car`
--
ALTER TABLE `report_car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_car_id_report_foreign` (`id_report`),
  ADD KEY `report_car_dongxe_foreign` (`dongXe`);

--
-- Indexes for table `report_nhap`
--
ALTER TABLE `report_nhap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_nhap_id_report_foreign` (`id_report`);

--
-- Indexes for table `report_work`
--
ALTER TABLE `report_work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_work_id_report_foreign` (`id_report`);

--
-- Indexes for table `report_xuat`
--
ALTER TABLE `report_xuat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_xuat_id_report_foreign` (`id_report`);

--
-- Indexes for table `request_hd`
--
ALTER TABLE `request_hd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_hd_user_id_foreign` (`user_id`),
  ADD KEY `request_hd_car_detail_id_foreign` (`car_detail_id`),
  ADD KEY `request_hd_guest_id_foreign` (`guest_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD KEY `role_user_role_id_foreign` (`role_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id_guest_foreign` (`id_guest`),
  ADD KEY `sale_id_car_sale_foreign` (`id_car_sale`),
  ADD KEY `sale_id_user_create_foreign` (`id_user_create`);

--
-- Indexes for table `sale_off`
--
ALTER TABLE `sale_off`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_off_id_sale_foreign` (`id_sale`),
  ADD KEY `sale_off_id_bh_pk_package_foreign` (`id_bh_pk_package`);

--
-- Indexes for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tai_lieu_id_user_foreign` (`id_user`);

--
-- Indexes for table `type_car`
--
ALTER TABLE `type_car`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_car_name_unique` (`name`);

--
-- Indexes for table `type_car_detail`
--
ALTER TABLE `type_car_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_car_detail_id_type_car_foreign` (`id_type_car`);

--
-- Indexes for table `type_guest`
--
ALTER TABLE `type_guest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users_detail`
--
ALTER TABLE `users_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_detail_id_user_foreign` (`id_user`);

--
-- Indexes for table `xe_lai_thu`
--
ALTER TABLE `xe_lai_thu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xe_lai_thu_id_user_use_foreign` (`id_user_use`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bh_pk_package`
--
ALTER TABLE `bh_pk_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancel_hd`
--
ALTER TABLE `cancel_hd`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_sale`
--
ALTER TABLE `car_sale`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cong`
--
ALTER TABLE `cong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dang_ky_su_dung`
--
ALTER TABLE `dang_ky_su_dung`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dv`
--
ALTER TABLE `dv`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guest_dv`
--
ALTER TABLE `guest_dv`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_cong`
--
ALTER TABLE `loai_cong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_phu_tung`
--
ALTER TABLE `loai_phu_tung`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `nhom`
--
ALTER TABLE `nhom`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhom_user`
--
ALTER TABLE `nhom_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phu_tung`
--
ALTER TABLE `phu_tung`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quyen_xem`
--
ALTER TABLE `quyen_xem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `report_car`
--
ALTER TABLE `report_car`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `report_nhap`
--
ALTER TABLE `report_nhap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_work`
--
ALTER TABLE `report_work`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `report_xuat`
--
ALTER TABLE `report_xuat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `request_hd`
--
ALTER TABLE `request_hd`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_off`
--
ALTER TABLE `sale_off`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_car`
--
ALTER TABLE `type_car`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `type_car_detail`
--
ALTER TABLE `type_car_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_guest`
--
ALTER TABLE `type_guest`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `users_detail`
--
ALTER TABLE `users_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `xe_lai_thu`
--
ALTER TABLE `xe_lai_thu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bh_pk_package`
--
ALTER TABLE `bh_pk_package`
  ADD CONSTRAINT `bh_pk_package_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `cancel_hd`
--
ALTER TABLE `cancel_hd`
  ADD CONSTRAINT `cancel_hd_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`),
  ADD CONSTRAINT `cancel_hd_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `car_sale`
--
ALTER TABLE `car_sale`
  ADD CONSTRAINT `car_sale_id_type_car_detail_foreign` FOREIGN KEY (`id_type_car_detail`) REFERENCES `type_car_detail` (`id`),
  ADD CONSTRAINT `car_sale_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `cong`
--
ALTER TABLE `cong`
  ADD CONSTRAINT `cong_id_dv_foreign` FOREIGN KEY (`id_dv`) REFERENCES `dv` (`id`),
  ADD CONSTRAINT `cong_id_loai_cong_foreign` FOREIGN KEY (`id_loai_cong`) REFERENCES `loai_cong` (`id`);

--
-- Constraints for table `dang_ky_su_dung`
--
ALTER TABLE `dang_ky_su_dung`
  ADD CONSTRAINT `dang_ky_su_dung_id_user_check_foreign` FOREIGN KEY (`id_user_check`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dang_ky_su_dung_id_user_reg_foreign` FOREIGN KEY (`id_user_reg`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dang_ky_su_dung_id_xe_lai_thu_foreign` FOREIGN KEY (`id_xe_lai_thu`) REFERENCES `xe_lai_thu` (`id`);

--
-- Constraints for table `dv`
--
ALTER TABLE `dv`
  ADD CONSTRAINT `dv_id_guest_dv_foreign` FOREIGN KEY (`id_guest_dv`) REFERENCES `guest_dv` (`id`),
  ADD CONSTRAINT `dv_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guest_id_type_guest_foreign` FOREIGN KEY (`id_type_guest`) REFERENCES `type_guest` (`id`),
  ADD CONSTRAINT `guest_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `guest_dv`
--
ALTER TABLE `guest_dv`
  ADD CONSTRAINT `guest_dv_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `nhom_user`
--
ALTER TABLE `nhom_user`
  ADD CONSTRAINT `nhom_user_id_nhom_foreign` FOREIGN KEY (`id_nhom`) REFERENCES `nhom` (`id`),
  ADD CONSTRAINT `nhom_user_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `phu_tung`
--
ALTER TABLE `phu_tung`
  ADD CONSTRAINT `phu_tung_id_dv_foreign` FOREIGN KEY (`id_dv`) REFERENCES `dv` (`id`),
  ADD CONSTRAINT `phu_tung_id_loai_phu_tung_foreign` FOREIGN KEY (`id_loai_phu_tung`) REFERENCES `loai_phu_tung` (`id`);

--
-- Constraints for table `quyen_xem`
--
ALTER TABLE `quyen_xem`
  ADD CONSTRAINT `quyen_xem_id_nhom_foreign` FOREIGN KEY (`id_nhom`) REFERENCES `nhom` (`id`),
  ADD CONSTRAINT `quyen_xem_id_tai_lieu_foreign` FOREIGN KEY (`id_tai_lieu`) REFERENCES `tai_lieu` (`id`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_user_report_foreign` FOREIGN KEY (`user_report`) REFERENCES `users` (`id`);

--
-- Constraints for table `report_car`
--
ALTER TABLE `report_car`
  ADD CONSTRAINT `report_car_dongxe_foreign` FOREIGN KEY (`dongXe`) REFERENCES `type_car` (`id`),
  ADD CONSTRAINT `report_car_id_report_foreign` FOREIGN KEY (`id_report`) REFERENCES `report` (`id`);

--
-- Constraints for table `report_nhap`
--
ALTER TABLE `report_nhap`
  ADD CONSTRAINT `report_nhap_id_report_foreign` FOREIGN KEY (`id_report`) REFERENCES `report` (`id`);

--
-- Constraints for table `report_work`
--
ALTER TABLE `report_work`
  ADD CONSTRAINT `report_work_id_report_foreign` FOREIGN KEY (`id_report`) REFERENCES `report` (`id`);

--
-- Constraints for table `report_xuat`
--
ALTER TABLE `report_xuat`
  ADD CONSTRAINT `report_xuat_id_report_foreign` FOREIGN KEY (`id_report`) REFERENCES `report` (`id`);

--
-- Constraints for table `request_hd`
--
ALTER TABLE `request_hd`
  ADD CONSTRAINT `request_hd_car_detail_id_foreign` FOREIGN KEY (`car_detail_id`) REFERENCES `type_car_detail` (`id`),
  ADD CONSTRAINT `request_hd_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guest` (`id`),
  ADD CONSTRAINT `request_hd_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_id_car_sale_foreign` FOREIGN KEY (`id_car_sale`) REFERENCES `car_sale` (`id`),
  ADD CONSTRAINT `sale_id_guest_foreign` FOREIGN KEY (`id_guest`) REFERENCES `guest` (`id`),
  ADD CONSTRAINT `sale_id_user_create_foreign` FOREIGN KEY (`id_user_create`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_off`
--
ALTER TABLE `sale_off`
  ADD CONSTRAINT `sale_off_id_bh_pk_package_foreign` FOREIGN KEY (`id_bh_pk_package`) REFERENCES `bh_pk_package` (`id`),
  ADD CONSTRAINT `sale_off_id_sale_foreign` FOREIGN KEY (`id_sale`) REFERENCES `sale` (`id`);

--
-- Constraints for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  ADD CONSTRAINT `tai_lieu_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `type_car_detail`
--
ALTER TABLE `type_car_detail`
  ADD CONSTRAINT `type_car_detail_id_type_car_foreign` FOREIGN KEY (`id_type_car`) REFERENCES `type_car` (`id`);

--
-- Constraints for table `users_detail`
--
ALTER TABLE `users_detail`
  ADD CONSTRAINT `users_detail_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `xe_lai_thu`
--
ALTER TABLE `xe_lai_thu`
  ADD CONSTRAINT `xe_lai_thu_id_user_use_foreign` FOREIGN KEY (`id_user_use`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
