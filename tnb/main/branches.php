<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สาขาและเครือข่ายจุดบริการ | TNB Logistics</title>
    <meta name="description" content="สาขาและเครือข่ายจุดบริการยุทธศาสตร์ของ TNB Logistics ครอบคลุมบางแสน แหลมฉบัง บางกะดี และลาดกระบัง" />

     <!-- Google SEO -->
     <meta name="robots" content="index, follow" />
     <link rel="canonical" href="https://tnb-logistics.com/branches.html" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://tnb-logistics.com/branches.html" />
    <meta property="og:title" content="สาขาและเครือข่ายจุดบริการ | TNB Logistics" />
    <meta property="og:description" content="สาขาและเครือข่ายจุดบริการยุทธศาสตร์ของ TNB Logistics ครอบคลุมบางแสน แหลมฉบัง บางกะดี และลาดกระบัง" />
    <meta property="og:image" content="https://tnb-logistics.com/scr/assets/homepage.webp" />
    <meta property="og:site_name" content="TNB Logistics" />
    <meta property="og:locale" content="th_TH" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../img/company_logo/tnb_logo.webp" />

    <!-- Custom CSS & JS -->
    <!-- CSS ของหน้านี้อยู่ใน: css/style.css หัวข้อ "Branches Page" -->
    
    <!-- Google Fonts: Inter (EN) + Sarabun (TH) + Noto Sans SC (ZH) + Noto Sans JP (JP) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS & JS -->
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    
    <!-- Load i18n first, then other scripts -->
    <script src="../js/i18n.js"></script>
    <script src="../js/script.js?v=<?php echo time(); ?>"></script>
</head>

<!-- page-branches: ใช้ scope CSS ให้เฉพาะหน้านี้ ป้องกันไม่ให้กระทบหน้าอื่น -->

<body class="page-branches">
    <?php include '../component/menubar.php'; ?>

    <!-- หัวข้อหลัก — Blue gradient header เหมือน technology.php -->
    <div class="card-ui-header layout_padding">
        <div class="container">
            <h1 class="card-ui-main-title" data-i18n="branches.title">สาขาของเรา</h1>
            <p class="card-ui-main-desc" data-i18n="branches.subtitle">เครือข่ายจุดบริการยุทธศาสตร์ครอบคลุมพื้นที่สำคัญทางอุตสาหกรรมเพื่อรองรับความต้องการของลูกค้าอย่างมีประสิทธิภาพ</p>
        </div>
    </div>

    <!-- เนื้อหาหลัก -->
    <div class="branches-page">
        <div class="branches-layout">

            <!-- ส่วนแผนที่ (Sticky) -->
            <div class="branches-image-section">
                <div class="branches-image-wrapper" id="branch-map-container">
                    <!-- Map will be loaded by JavaScript -->
                </div>
            </div>

            <!-- ส่วนการ์ดสาขา -->
            <div class="branches-cards-section">

                <div class="branch-card" data-branch="bangsaen" data-map-url="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1941.5823673197542!2d100.972431!3d13.277644!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3102b5007148f2cf%3A0xfe03520168a8b47c!2sTNB%20Logistics!5e0!3m2!1sth!2sth!4v1772427453783!5m2!1sth!2sth">
                    <h3 class="branch-card__name" data-i18n="branches.bangsaen_name">สาขาบางแสน (สำนักงานใหญ่)</h3>
                    <p class="branch-card__desc" data-i18n="branches.bangsaen_desc">ศูนย์กลางการบริหารจัดการการขนส่งภายในประเทศ เป็นสำนักงานใหญ่ที่รวมศูนย์บัญชาการและประสานงานทุกสาขา (กดเพื่อดูที่อยู่)</p>
                    <ul class="branch-card__services">
                        <li>Domestic Transport</li>
                        <li>Fleet Management</li>
                        <li>HQ Operations</li>
                    </ul>
                </div>

                <div class="branch-card" data-branch="laemchabang" data-map-url="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4433.973871895208!2d100.98898102323993!3d13.130790482165148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3102c9004d55530f%3A0x335bb4b530f87de8!2sTNB%20Logistics%20Co.%2C%20Ltd!5e0!3m2!1sth!2sth!4v1775619485568!5m2!1sth!2sth">
                    <h3 class="branch-card__name" data-i18n="branches.laemchabang_name">สาขาแหลมฉบัง</h3>
                    <p class="branch-card__desc" data-i18n="branches.laemchabang_desc">ให้บริการจัดจองตู้คอนเทนเนอร์และพื้นที่ฝากวางตู้ (Container Drop Yard) เชื่อมต่อท่าเรือแหลมฉบังโดยตรง <br>(กดเพื่อดูที่อยู่)</p>
                    <ul class="branch-card__services">
                        <li>Container Yard</li>
                        <li>Import/Export</li>
                        <li>Port Linkage</li>
                    </ul>
                </div>

                <!-- <div class="branch-card">
                    <h3 class="branch-card__name" data-i18n="branches.bangkadi_name">สาขาบางกะดี</h3>
                    <p class="branch-card__desc" data-i18n="branches.bangkadi_desc">เชี่ยวชาญการให้บริการรถ Shuttle รับ-ส่งสินค้าระหว่างคลังสินค้าและการจัดการตู้คอนเทนเนอร์ (กดเพื่อดูที่อยู่)</p>
                    <ul class="branch-card__services">
                        <li>Shuttle Service</li>
                        <li>WH to WH</li>
                        <li>Container Mgmt</li>
                    </ul>
                </div> -->

                <div class="branch-card" data-branch="latkrabang" data-map-url="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3875.8835142951084!2d100.79197927509041!3d13.757294986635225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTPCsDQ1JzI2LjMiTiAxMDDCsDQ3JzQwLjQiRQ!5e0!3m2!1sth!2sth!4v1775621030378!5m2!1sth!2sth"> 
                    <h3 class="branch-card__name" data-i18n="branches.latkrabang_name">สาขาลาดกระบัง</h3>
                    <p class="branch-card__desc" data-i18n="branches.latkrabang_desc">ศูนย์กระจายสินค้าและลานจอดรถขนาด 9,000 ตร.ม. ตั้งอยู่ใกล้กับ ICD เพื่อความรวดเร็วในการขนส่ง (กดเพื่อดูที่อยู่)</p>
                    <ul class="branch-card__services">
                        <li>Distribution Center</li>
                        <li>9,000 sqm Yard</li>
                        <li>Near ICD</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php include '../component/footer.php'; ?>
</body>

</html>