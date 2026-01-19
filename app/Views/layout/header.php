<!-- Header -->
<div class="flex flex-row justify-between items-center">
    <h3 class="page-title" style="color: #333;"><?= $pageTitle ?></h3>
    <div class="end-header flex flex-row items-center md:gap-3">
        <!-- Notif -->
        <div class="dropdown dropdown-end hidden md:block">
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
                <div class="avatar">
                    <div class="w-14 rounded-full">
                        <img src="<?= base_url('uploads/'.session()->get('user_gambar')) ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-stretch">
            <p class="font-bold text-xs hidden md:block"><?= session()->get('user_nama') ?></p>
            <p class="text-xs font-semibold hidden md:block" style="color: var(--secondary-text);"><?= session()->get('user_role') ?></p>
        </div>  
        <!-- Avatar end -->
        </div>
</div>
<!-- Header end -->