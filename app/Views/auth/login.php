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
      <div class="grid grid-cols-12 min-h-screen">
        <div class="col-span-12 md:col-span-6 px-12 py-8 md:py-12">
            <div class="flex flex-row items-stretch mb-6">
                <img src="<?= base_url('assets/img/logo.svg') ?>" alt="" class="me-2">
                <h3 class="self-center font-black" style="color: var(--primary-color)">INVW</h3>
            </div>
            <div class="greet mb-6">
                <h1 class="font-bold">Halo,</h1>
                <h1 class="font-bold mb-3">Selamat Datang di <span style="color: var(--primary-color)">INVW</span></h1>
                <p class="text-sm font-semibold" style="color: var(--secondary-text);">Silakan isi data anda dibawah ini.</p>
            </div>
            <?php if (session()->getFlashdata('error')) : ?>
                <div role="alert" class="alert alert-error alert-soft mb-6">
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>
            <form action="<?= base_url('/login/process') ?>" method="post">
                <input type="text" placeholder="Email" class="input w-full mb-4" name="email" required />
                <input type="password" placeholder="Password" name="password" class="input w-full mb-4" required />
                <div class="login-submit flex flex-col">
                    <label class="label text-xs font-semibold mb-5 w-50" style="color: var(--secondary-text);">
                    <input type="checkbox" checked="checked" class="checkbox checkbox-md checked:bg-[#5160FC] checked:text-[#ffffff]" />
                    Ingat saya
                    </label>
                    <button type="submit" class="btn bg-[#5160FC] text-[#ffffff] w-full md:w-50">Login</button>
                </div>
            </form>
        </div>
        <div class="col-span-12 md:col-span-6 p-3">
            <div class="bg-login h-96 md:min-h-screen flex items-center justify-center text-center rounded-xl" style="background-image: url(<?= base_url('assets/img/bg-login.png') ?>); background-repeat:repeat">
                <img src="<?= base_url('assets/img/vector-login.svg') ?>" alt="">
            </div>
        </div>
      </div>
</body>
</html>