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
        <div class="col-span-6 p-12 min-h-screen">
            <div class="flex flex-row items-stretch mb-6">
                <img src="<?= base_url('assets/img/logo.svg') ?>" alt="" class="me-2">
                <h3 class="self-center font-black" style="color: var(--primary-color)">INVW</h3>
            </div>
            <div class="greet mb-6">
                <h1 class="font-bold">Halo,</h1>
                <h1 class="font-bold mb-3">Selamat Datang di <span style="color: var(--primary-color)">INVW</span></h1>
                <p class="text-sm font-semibold" style="color: var(--secondary-text);">Silakan isi data anda dibawah ini.</p>
            </div>
            <form action="">
                <input type="text" placeholder="Email" class="input mb-3" />
                <input type="password" placeholder="Password" class="input" />
            </form>
        </div>
        <div class="col-span-6 relative min-h-screen p-3">
            <img src="<?= base_url('assets/img/login-img.svg') ?>" alt="" class="rounded-xl" style="height: 100vh;">
        </div>
      </div>
</body>
</html>