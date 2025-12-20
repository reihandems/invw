<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="barangModal.showModal()">+ Tambah Provinsi</button>
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
        </div>
        <!-- Modal -->
        <dialog id="barangModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="barangModalLabel">Form Barang</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form>
                <div class="mb-3">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Provinsi</legend>
                        <select class="select w-full" id="province_select" name="province_id">
                            <option disabled selected>Pilih Provinsi</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['id'] ?>"><?= $province['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </fieldset>
                </div>
                <div class="mb-3">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Kabupaten/Kota</legend>
                        <select class="select w-full" id="regency_select" name="regency_id" disabled>
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </fieldset>
                </div>
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
    </div>
    <!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>

        $(document).ready(function() {
            // URL dasar untuk AJAX (sesuaikan jika ada perubahan routing)
            const baseUrl = '<?= base_url() ?>';

            // Ketika dropdown Provinsi (province_select) berubah
            $('#province_select').change(function() {
                const provinceId = $(this).val();
                const $regencySelect = $('#regency_select');

                // Kosongkan dan nonaktifkan dropdown Kabupaten/Kota
                $regencySelect.html('<option value="">Memuat...</option>').prop('disabled', false);

                if (provinceId) {
                    // Lakukan panggilan AJAX ke Controller
                    $.ajax({
                        url: '<?= base_url("admin/supplier/getRegencies") ?>', // Endpoint di RegionController
                        method: 'POST',
                        data: {
                            province_id: provinceId
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Reset dropdown Kabupaten/Kota
                            $regencySelect.html('<option value="">Pilih Kabupaten/Kota</option>');

                            // Isi dropdown Kabupaten/Kota dengan data dari respons
                            if (response.length > 0) {
                                $.each(response, function(key, regency) {
                                    $regencySelect.append($('<option>', {
                                        value: regency.id,
                                        text: regency.name
                                    }));
                                });
                            } else {
                                $regencySelect.html('<option value="">Tidak ada Kabupaten/Kota</option>')
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching regencies: " + error);
                            $regencySelect.html('<option value="">Gagal memuat data</option>');
                        }
                    });
                } else {
                    // Jika provinsi tidak dipilih
                    $regencySelect.html('<option value="">Pilih provinsi terlebih dahulu</option>').prop('disabled', true);
                }
            });
        });
    </script>
<?= $this->endSection() ?>