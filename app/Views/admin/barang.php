<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
  <link href="<?= base_url('resources/css/custom.css') ?>" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="grid grid-cols-12 h-screen">
        <!-- Sidebar -->
        <div class="drawer lg:drawer-open row-span-4 col-span-2 h-full" style="background-color: var(--background-color);">
            <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col m-3">
                <!-- Page content here -->
                <label for="my-drawer-3" class="btn drawer-button lg:hidden" style="background-color: var(--secondary-color);">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#333333"><path d="M144-264v-72h672v72H144Zm0-180v-72h672v72H144Zm0-180v-72h672v72H144Z"/></svg>
                </label>
            </div>
            <div class="drawer-side">
                <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
                <ul class="menu p-0">
                    <!-- Sidebar -->
                    <div class="row-span-4 col-span-2 sidebar shadow-md font-bold h-full" style="padding: 24px 30px; background-color: var(--secondary-color);">
                        <div class="flex flex-row items-stretch">
                            <img src="<?= base_url('assets/img/logo.svg') ?>" alt="" class="me-2">
                            <h3 class="self-center" style="color: var(--primary-color)">INVWM</h3>
                        </div>
                        <hr class="my-5" style="color: var(--secondary-stroke)">
                        <p class="text-xs font-medium" style="color: var(--secondary-text); margin-bottom: 24px;">Menu Utama</p>
                        <ul>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <path d="M10.9951 4.68V8.56C10.9951 8.88127 10.9316 9.19938 10.8084 9.49606C10.6851 9.79275 10.5045 10.0622 10.2769 10.2889C10.0492 10.5156 9.77907 10.6951 9.48188 10.8171C9.18469 10.9392 8.86632 11.0013 8.54506 11H4.68506C4.36466 11.0019 4.04717 10.9392 3.75161 10.8155C3.45606 10.6917 3.18853 10.5096 2.96506 10.28C2.73817 10.055 2.5585 9.78697 2.43658 9.49161C2.31466 9.19625 2.25295 8.87952 2.25506 8.56V4.69C2.25505 4.0446 2.51075 3.42549 2.96618 2.96819C3.42161 2.51088 4.03966 2.25265 4.68506 2.25H8.55506C8.87512 2.25031 9.19195 2.3141 9.48719 2.43769C9.78243 2.56128 10.0502 2.74221 10.2751 2.97C10.5025 3.19298 10.6834 3.45902 10.807 3.75261C10.9306 4.04619 10.9945 4.36145 10.9951 4.68ZM21.7451 4.69V8.56C21.7399 9.2038 21.4825 9.81991 21.0282 10.2761C20.5739 10.7323 19.9588 10.9922 19.3151 11H15.4351C14.7883 10.996 14.1682 10.7416 13.7051 10.29C13.4788 10.0625 13.2996 9.79258 13.1777 9.49572C13.0559 9.19886 12.9938 8.88088 12.9951 8.56V4.69C12.9943 4.36977 13.0575 4.05261 13.1812 3.75722C13.3048 3.46182 13.4864 3.19416 13.7151 2.97C13.9399 2.74221 14.2077 2.56128 14.5029 2.43769C14.7982 2.3141 15.115 2.25031 15.4351 2.25H19.3051C19.9506 2.25523 20.5682 2.51398 21.0246 2.97044C21.4811 3.4269 21.7398 4.04449 21.7451 4.69ZM21.7451 15.44V19.31C21.7399 19.9538 21.4825 20.5699 21.0282 21.0261C20.5739 21.4823 19.9588 21.7422 19.3151 21.75H15.4351C14.7841 21.7566 14.1563 21.5091 13.6851 21.06C13.4579 20.8331 13.2781 20.5634 13.1562 20.2664C13.0343 19.9693 12.9727 19.651 12.9751 19.33V15.46C12.9743 15.1398 13.0375 14.8226 13.1612 14.5272C13.2848 14.2318 13.4664 13.9642 13.6951 13.74C13.9199 13.5122 14.1877 13.3313 14.4829 13.2077C14.7782 13.0841 15.095 13.0203 15.4151 13.02H19.2851C19.9306 13.0252 20.5482 13.284 21.0046 13.7404C21.4611 14.1969 21.7198 14.8145 21.7251 15.46L21.7451 15.44ZM10.9951 15.45V19.32C10.9872 19.9655 10.7259 20.582 10.2676 21.0366C9.80925 21.4912 9.19059 21.7474 8.54506 21.75H4.68506C4.36557 21.7513 4.04899 21.6894 3.75357 21.5677C3.45816 21.4461 3.18975 21.2671 2.96384 21.0412C2.73793 20.8153 2.55899 20.5469 2.43734 20.2515C2.31569 19.9561 2.25373 19.6395 2.25506 19.32V15.45C2.25763 14.8045 2.51385 14.1858 2.96844 13.7275C3.42303 13.2691 4.03957 13.0079 4.68506 13H8.55506C9.20326 13.0066 9.82355 13.2648 10.2851 13.72C10.7411 14.1801 10.9964 14.8021 10.9951 15.45Z"/>
                                        </svg>
                                        <p class="self-center">Dashboard</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu active">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <path d="M16.5 4.5H20.25V21.75H3.75V4.5H7.5V6H16.5V4.5ZM6.75 12H17.25V10.5H6.75V12ZM6.75 18H17.25V16.5H6.75V18ZM9 4.5V2.25H15V4.5H9Z"/>
                                        </svg>
                                        <p class="self-center">Data Barang</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="21" height="24" viewBox="0 0 21 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <g clip-path="url(#clip0_349_110)">
                                            <path d="M10.5 12C13.8141 12 16.5 9.31406 16.5 6C16.5 2.68594 13.8141 0 10.5 0C7.18594 0 4.5 2.68594 4.5 6C4.5 9.31406 7.18594 12 10.5 12ZM14.9906 13.5281L12.75 22.5L11.25 16.125L12.75 13.5H8.25L9.75 16.125L8.25 22.5L6.00937 13.5281C2.66719 13.6875 0 16.4203 0 19.8V21.75C0 22.9922 1.00781 24 2.25 24H18.75C19.9922 24 21 22.9922 21 21.75V19.8C21 16.4203 18.3328 13.6875 14.9906 13.5281Z"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_349_110">
                                            <rect width="21" height="24"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <p class="self-center">Data Supplier</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="19" viewBox="0 0 24 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <g clip-path="url(#clip0_349_114)">
                                            <path d="M22.8938 13.8529C22.9913 13.3297 22.9913 12.7953 22.8938 12.2721L23.8612 11.7191C23.9738 11.6561 24.0225 11.5262 23.985 11.4037C23.7337 10.6021 23.3025 9.8748 22.74 9.27363C22.6538 9.18086 22.515 9.15859 22.4025 9.22168L21.435 9.77461C21.0263 9.42949 20.5575 9.1623 20.0513 8.98418V7.87832C20.0513 7.75215 19.9613 7.64082 19.8375 7.61484C19.0013 7.4293 18.15 7.43672 17.355 7.61484C17.2313 7.64082 17.1413 7.75215 17.1413 7.87832V8.98418C16.635 9.1623 16.1663 9.42949 15.7575 9.77461L14.79 9.22168C14.6813 9.15859 14.5388 9.18086 14.4525 9.27363C13.89 9.8748 13.4588 10.6021 13.2075 11.4037C13.17 11.5262 13.2225 11.6561 13.3313 11.7191L14.2987 12.2721C14.2013 12.7953 14.2013 13.3297 14.2987 13.8529L13.3313 14.4059C13.2188 14.4689 13.17 14.5988 13.2075 14.7213C13.4588 15.5229 13.89 16.2465 14.4525 16.8514C14.5388 16.9441 14.6775 16.9664 14.79 16.9033L15.7575 16.3504C16.1663 16.6955 16.635 16.9627 17.1413 17.1408V18.2467C17.1413 18.3729 17.2313 18.4842 17.355 18.5102C18.1912 18.6957 19.0425 18.6883 19.8375 18.5102C19.9613 18.4842 20.0513 18.3729 20.0513 18.2467V17.1408C20.5575 16.9627 21.0263 16.6955 21.435 16.3504L22.4025 16.9033C22.5113 16.9664 22.6538 16.9441 22.74 16.8514C23.3025 16.2502 23.7337 15.5229 23.985 14.7213C24.0225 14.5988 23.97 14.4689 23.8612 14.4059L22.8938 13.8529ZM18.6 14.8623C17.595 14.8623 16.7812 14.0533 16.7812 13.0625C16.7812 12.0717 17.5988 11.2627 18.6 11.2627C19.6013 11.2627 20.4188 12.0717 20.4188 13.0625C20.4188 14.0533 19.605 14.8623 18.6 14.8623ZM8.4 9.5C11.0513 9.5 13.2 7.37363 13.2 4.75C13.2 2.12637 11.0513 0 8.4 0C5.74875 0 3.6 2.12637 3.6 4.75C3.6 7.37363 5.74875 9.5 8.4 9.5ZM15.945 17.9053C15.8588 17.8607 15.7725 17.8088 15.69 17.7605L15.3938 17.9312C15.1688 18.0574 14.9138 18.1279 14.6588 18.1279C14.25 18.1279 13.8563 17.9572 13.575 17.6604C12.8888 16.9256 12.3638 16.0312 12.0675 15.0775C11.8612 14.4207 12.1388 13.7268 12.7388 13.3816L13.035 13.2109C13.0313 13.1145 13.0313 13.018 13.035 12.9215L12.7388 12.7508C12.1388 12.4094 11.8612 11.7117 12.0675 11.0549C12.1012 10.9473 12.15 10.8396 12.1875 10.732C12.045 10.7209 11.9063 10.6875 11.76 10.6875H11.1337C10.3013 11.066 9.375 11.2812 8.4 11.2812C7.425 11.2812 6.5025 11.066 5.66625 10.6875H5.04C2.2575 10.6875 0 12.9215 0 15.675V17.2188C0 18.2021 0.80625 19 1.8 19H15C15.3788 19 15.7313 18.8813 16.02 18.6846C15.975 18.5436 15.945 18.3988 15.945 18.2467V17.9053Z"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_349_114">
                                            <rect width="24" height="19"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <p class="self-center">Data User</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                        <path d="M22 19V8.34996C22 7.52996 21.5 6.79996 20.74 6.48996L12.74 3.28996C12.26 3.09996 11.73 3.09996 11.25 3.28996L3.25 6.48996C2.5 6.79996 2 7.53996 2 8.34996V19C2 20.1 2.9 21 4 21H7V12H17V21H20C21.1 21 22 20.1 22 19ZM11 19H9V21H11V19ZM13 16H11V18H13V16ZM15 19H13V21H15V19Z"/>
                                        </svg>
                                        <p class="self-center">Data Gudang</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                            <g clip-path="url(#clip0_349_122)">
                                            <path d="M18 9V0H6V9H0V19.5H24V9H18ZM10.5 18H1.5V10.5H4.5V12H7.5V10.5H10.5V18ZM7.5 9V1.5H10.5V3H13.5V1.5H16.5V9H7.5ZM22.5 18H13.5V10.5H16.5V12H19.5V10.5H22.5V18ZM0 24H4.5V22.5H19.5V24H24V21H0V24Z"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_349_122">
                                            <rect width="24" height="24"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <p class="self-center">Data Rak</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                        <path d="M20 8L14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8ZM9 19H7V10H9V19ZM13 19H11V13H13V19ZM17 19H15V16H17V19ZM14 9H13V4L18 9H14Z"/>
                                        </svg>
                                        <p class="self-center">Laporan</p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="#">
                                    <div class="flex flex-row items-stretch">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C13.3132 1.99999 14.6136 2.25865 15.8268 2.76119C17.0401 3.26373 18.1425 4.00033 19.0711 4.92891C19.9997 5.8575 20.7363 6.95989 21.2388 8.17315C21.7414 9.3864 22 10.6868 22 12C22 17.5228 17.5228 22 12 22C6.47717 22 2 17.5228 2 12C2 6.47717 6.47717 2 12 2ZM13 13H11C8.5243 13 6.39884 14.4994 5.48211 16.6398C6.93261 18.6737 9.31142 20 12 20C14.6885 20 17.0674 18.6737 18.5179 16.6396C17.6012 14.4994 15.4757 13 13 13ZM12 5C10.3431 5 8.99998 6.34316 8.99998 8C8.99998 9.65684 10.3431 11 12 11C13.6568 11 15 9.65684 15 8C15 6.34316 13.6569 5 12 5Z"/>
                                        </svg>
                                        <p class="self-center">Profil</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <a href="">
                            <button class="btn btn-wide bg-white text-red-600 border-[#e5e5e5]">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15L15 12M15 12L12 9M15 12H4M9 7.249V7.2C9 6.08 9 5.52 9.218 5.092C9.41 4.715 9.715 4.41 10.092 4.218C10.52 4 11.08 4 12.2 4H16.8C17.92 4 18.48 4 18.907 4.218C19.284 4.41 19.59 4.715 19.782 5.092C20 5.519 20 6.079 20 7.197V16.804C20 17.922 20 18.481 19.782 18.908C19.59 19.2845 19.2837 19.5904 18.907 19.782C18.48 20 17.921 20 16.803 20H12.197C11.079 20 10.519 20 10.092 19.782C9.71569 19.5903 9.40974 19.2843 9.218 18.908C9 18.48 9 17.92 9 16.8V16.75" stroke="#E61919" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Logout
                            </button>
                        </a>
                    </div>
                    <!-- Sidebar end -->
                </ul>
            </div>
        </div>
        <!-- Sidebar end -->
        <!-- Kanan -->
        <div class="col-span-10 h-screen" style="padding: 30px 30px 30px 40px; background-color: var(--background-color);">
            <!-- Header -->
            <div class="flex flex-col md:flex-row items-center">
                <h3 class="page-title w-full" style="color: #333;">Data Barang</h3>
                <div class="end-header flex flex-row items-center">
                    <!-- Search -->
                    <label class="input mr-2">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                        stroke-linejoin="round"
                        stroke-linecap="round"
                        stroke-width="2.5"
                        fill="none"
                        stroke="currentColor"
                        >
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input type="search" required placeholder="Search" />
                    </label>
                    <!-- Search End -->
                    <!-- Notif -->
                    <div class="dropdown dropdown-end mr-2">
                        <div tabindex="0" role="button" class="btn m-1 bg-base-100">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.48998 12.6668C10.0667 12.6668 10.3713 13.3495 9.98665 13.7788C9.73655 14.0582 9.43034 14.2817 9.08802 14.4347C8.74569 14.5877 8.37495 14.6668 7.99999 14.6668C7.62502 14.6668 7.25428 14.5877 6.91195 14.4347C6.56963 14.2817 6.26342 14.0582 6.01332 13.7788C5.64532 13.3682 5.90799 12.7262 6.43599 12.6715L6.50932 12.6675L9.48998 12.6668ZM7.99999 1.3335C8.90532 1.3335 9.67065 1.9355 9.91665 2.76083L9.94732 2.87483L9.95265 2.9035C10.6877 3.31797 11.314 3.90074 11.7803 4.60403C12.2466 5.30732 12.5396 6.1111 12.6353 6.9495L12.654 7.14083L12.6667 7.3335V9.2875L12.6807 9.37816C12.7719 9.86938 13.0438 10.3086 13.4427 10.6095L13.554 10.6875L13.662 10.7535C14.2353 11.0782 14.0353 11.9308 13.4107 11.9962L13.3333 12.0002H2.66665C1.98132 12.0002 1.74199 11.0908 2.33799 10.7535C2.592 10.6097 2.81148 10.4121 2.98101 10.1745C3.15054 9.93695 3.26601 9.66513 3.31932 9.37816L3.33332 9.28283L3.33399 7.30283C3.37463 6.43208 3.62809 5.58457 4.07218 4.83447C4.51626 4.08438 5.13743 3.45457 5.88132 3.00016L6.04665 2.90283L6.05332 2.87416C6.14736 2.47538 6.36161 2.1151 6.66708 1.84206C6.97256 1.56901 7.35453 1.39638 7.76132 1.3475L7.88265 1.33616L7.99999 1.3335Z" fill="#333333"/>
                        </svg>
                        <div class="badge badge-sm text-neutral-50" style="background-color: var(--primary-color);">+17</div>
                        </div>
                        <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                            <li><a>Item 1</a></li>
                            <li><a>Item 2</a></li>
                        </ul>
                    </div>
                    <!-- Notif end -->
                    <!-- Avatar -->
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="my-1">
                            <div class="avatar mr-3">
                                <div class="w-14 rounded-full">
                                    <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                                </div>
                            </div>
                        </div>
                        <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                            <li><a>Item 1</a></li>
                            <li><a>Item 2</a></li>
                        </ul>
                    </div>
                    <div class="flex flex-col items-stretch w-50">
                        <p class="font-bold text-xs">Rayhan Dwi Putra</p>
                        <p class="text-xs font-semibold" style="color: var(--secondary-text);">Admin</p>
                    </div>  
                    <!-- Avatar end -->
                    </div>
            </div>
            <!-- Header end -->
            <hr class="my-4" style="color: var(--secondary-stroke);">
            <!-- Main Content -->
            <div class="data-barang font-semibold mt-8">
                <!-- Tombol -->
                <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="barangModal.showModal()">+ Tambah Barang</button>
                <!-- Tombol End -->
                <!-- Filter -->
                <div class="filter-table flex flex-row mb-3">
                    <div class="filter-table-kategori flex flex-row items-center mr-5">
                        <p class="text-sm mr-1">Kategori:</p>
                        <select class="select select-sm">
                            <option disabled selected>Small</option>
                            <option>Small Apple</option>
                            <option>Small Orange</option>
                            <option>Small Tomato</option>
                        </select>
                    </div>
                    <div class="filter-table-kategori flex flex-row items-center">
                        <p class="text-sm mr-1">Stok: </p>
                        <select class="select select-sm">
                            <option disabled selected>Small</option>
                            <option>Small Apple</option>
                            <option>Small Orange</option>
                            <option>Small Tomato</option>
                        </select>
                    </div>
                </div>
                <!-- Filter end -->
                <!-- Modal -->
                <dialog id="barangModal" class="modal modal-bottom sm:modal-middle">
                <div class="modal-box">
                    <h3 class="text-lg font-bold modal-title" id="barangModalLabel">Form Barang</h3>
                    <hr class="my-3" style="color: var(--secondary-stroke);">
                    <form id="barangForm">
                        <input type="hidden" name="id" id="id">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Nama Barang</legend>
                            <input type="text" class="input w-full" id="namaBarang" name="namaBarang" placeholder="Masukkan nama barang" required/>
                            <div class="invalid-feedback" id="namaBarang-error"></div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Harga Barang</legend>
                            <input
                            type="number"
                            class="input validator w-full"
                            required
                            placeholder="(Rp.) Masukkan harga barang"
                            min="1000"
                            max="100000000"
                            title="Minimal Rp. 1.000"
                            id="hargaBarang"
                            name="hargaBarang"
                            />
                            <p class="validator-hint">Minimal Rp. 1.000</p>
                            <div class="invalid-feedback" id="hargaBarang-error"></div>
                        </fieldset>
                    </form>
                    <div class="modal-action">
                        <form method="dialog">
                            <!-- if there is a button in form, it will close the modal -->
                             <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="barangForm">Simpan</button>
                            <button class="btn">Close</button>
                        </form>
                    </div>
                </div>
                </dialog>
                <!-- Modal end -->
                <!-- Log -->
                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-2">
                    <table id="tabelBarang" class="table responsive nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Log end -->
                <br><br>
            </div>
            <!-- Main Content end -->
        </div>
        <!-- Kanan end -->
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
    <!-- <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> -->
    <script>
        var csrfName = "<?= csrf_token() ?>";
        var csrfHash = "<?= csrf_hash() ?>";

        $(document).ready(function() {
            // Menampilkan data ke dalam dataTables
            var table = $('#tabelBarang').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('admin/ajaxlist') ?>",
                    "type": "GET",
                    "dataSrc": function (x) {
                        return x;
                    }
                },
                "columns": [
                    {"data": 0},
                    {"data": 1},
                    {"data": 2, "className": "text-end"},
                    {"data": 3}
                ],
                "columnDefs": [
                    {"targets": [3], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#barangForm')[0].reset();
                $('#barangModalLabel').text('Tambah Data Barang');
                $('#id').val('');
                $('.invalid-feedback').text('').hide();
                $('#barangModal').modal('show');
            });

            // 2. Simpan Data (Tambah dan Edit)
            $('#barangForm').on('submit', function(e) {
                // ... (Kode pencegahan default dan persiapan FormData)

                e.preventDefault();

                var formData = new FormData(this);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: "<?= site_url('/admin/save'); ?>",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Update CSRF Hash
                        csrfHash = response.token;

                        if (response.status) {
                            // Sukses
                            alert(response.msg);
                            $('#barangModal')[0].close();

                            // Memuat ulang data dari DataTables dari sumber AJAX
                            table.ajax.reload(null, false); // 'null' untuk callback, 'false' untuk tetap pada halaman saat ini
                        } else {
                            // Validasi gagal
                            $('.invalid-feedback').text('').hide();
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value).show().prev().addClass('is-invalid');
                            });
                        }
                        
                    },

                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                });

                // Fungsi untuk memperbarui CSRF Token dari respon AJAX
                function updateCsrfToken(response) {
                    // Cek jika ada token di respons (sesuai format CI4 standar)
                    var tokenName = Object.keys(response).filter(key => key.length === 32)[0];

                    if (tokenName) {
                        csrfName = tokenName;
                        csrfHash = response[tokenName];
                    } else if (response.token) {
                        // Jika controller hanya mengirim hash dengan key 'token' (Sesuai kode anda sebelumnya)
                        csrfHash = response.token;
                    }
                }
            });

            // 3. Edit Data (Mengisi form di modal)
            $('#tabelBarang').on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $('#barangForm')[0].reset();
                $('#barangModalLabel').text('Ubah Data Barang');
                $('.invalid-feedback').removeClass('d-block').hide();
                $('.form-control').removeClass('is-invalid');

                // Ambil data barang dari controller menggunakan AJAX
                $.ajax({
                    url: "<?= site_url('/admin/getBarang/'); ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // Isi form dengan data yang didapatkan
                        $('#id').val(data.id);
                        $('#namaBarang').val(data.namaBarang);
                        // Pastikan nama input sesuai dengan nama kolom di database: HargaBarang
                        $('#hargaBarang').val(data.hargaBarang);
                        
                        $('#barangModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            })


            // 4. Hapus data
            $('#tabelBarang').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('admin/deleteData'); ?>/" + id,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            // Kirim CSRF Token
                            [csrfName]: csrfHash
                        },
                        success: function(response) {
                            // updateCsrfToken(response); // Update CSRF Hash

                            if (response.status) {
                                alert(response.msg);
                                table.ajax.reload(null, false); // Reload DataTables
                            } else {
                                alert('Gagal: ' + response.msg);
                            }
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan saat menghapus data: ' + xhr.responseText);
                        }
                    });
                }
            });
        })
    </script>
</body>
</html>